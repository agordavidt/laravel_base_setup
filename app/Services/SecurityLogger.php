<?php

namespace App\Services;

use App\Models\SecurityLog;
use App\Notifications\SecurityAlertNotification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Request;
use Throwable;

class SecurityLogger
{
    /*
    |--------------------------------------------------------------------------
    | Keys that must NEVER appear in any log entry (CWE-532)
    |--------------------------------------------------------------------------
    */
    private const REDACTED_KEYS = [
        'password', 'password_confirmation', 'current_password',
        'token', 'api_key', 'secret', 'authorization',
        'card_number', 'cvv', 'ssn',
    ];

    /*
    |--------------------------------------------------------------------------
    | Public API
    |--------------------------------------------------------------------------
    */

    public function info(string $event, array $context = []): void
    {
        $this->log($event, $context, 'info');
    }

    public function warning(string $event, array $context = []): void
    {
        $this->log($event, $context, 'warning');
    }

    public function critical(string $event, array $context = []): void
    {
        $this->log($event, $context, 'critical');
    }

    /*
    |--------------------------------------------------------------------------
    | Core
    |--------------------------------------------------------------------------
    */

    private function log(string $event, array $context, string $level): void
    {
        $entry = $this->buildEntry($event, $context);

        // 1. Every event goes to the security log file
        $this->writeToFile($level, $event, $entry);

        // 2. High-value events also go to the database
        if ($this->isHighValueEvent($event)) {
            $this->persistToDatabase($event, $entry, $level);
        }

        // 3. Check whether this event breaches a threshold and needs an alert
        $this->checkThresholds($event, $entry);
    }

    /*
    |--------------------------------------------------------------------------
    | Entry Builder
    |--------------------------------------------------------------------------
    */

    private function buildEntry(string $event, array $context): array
    {
        $user = Request::user();

        return [
            'event'      => $event,
            'ip'         => Request::ip() ?? 'unknown',
            'user_agent' => $this->sanitise(Request::userAgent() ?? 'unknown'),
            'path'       => $this->sanitise(Request::path()),
            'method'     => Request::method(),
            'user_id'    => $user?->id,
            'user_email' => $user?->email,
            'user_role'  => $user?->getRoleNames()->first(),
            'context'    => $this->sanitiseContext($context),
            'timestamp'  => now()->toIso8601String(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Log Injection Prevention (CWE-117)
    | Strip newlines and ASCII control characters so an attacker cannot
    | forge additional log entries by injecting newlines into input fields.
    |--------------------------------------------------------------------------
    */

    private function sanitise(string $value): string
    {
        // Remove all ASCII control characters (0x00–0x1F) and DEL (0x7F)
        return preg_replace('/[\x00-\x1F\x7F]/', '', $value);
    }

    private function sanitiseContext(array $context): array
    {
        $clean = [];

        foreach ($context as $key => $value) {
            // Silently drop sensitive keys entirely (CWE-532)
            if (in_array(strtolower($key), self::REDACTED_KEYS, strict: true)) {
                continue;
            }

            $clean[$key] = is_string($value) ? $this->sanitise($value) : $value;
        }

        return $clean;
    }

    /*
    |--------------------------------------------------------------------------
    | File Writer
    |--------------------------------------------------------------------------
    */

    private function writeToFile(string $level, string $event, array $entry): void
    {
        try {
            Log::channel('security')->{$level}($event, $entry);
        } catch (Throwable $e) {
            // Fall back to the default log so the failure is never silent
            Log::error('SecurityLogger: failed to write to security channel', [
                'original_event' => $event,
                'error'          => $e->getMessage(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Database Persistence
    |--------------------------------------------------------------------------
    */

    private function isHighValueEvent(string $event): bool
    {
        return in_array($event, config('security.db_events'), strict: true);
    }

    private function persistToDatabase(string $event, array $entry, string $level): void
    {
        try {
            SecurityLog::create([
                'event'      => $event,
                'level'      => $level,
                'user_id'    => $entry['user_id'],
                'user_email' => $entry['user_email'],
                'ip_address' => $entry['ip'],
                'user_agent' => substr($entry['user_agent'] ?? '', 0, 500),
                'path'       => substr($entry['path'] ?? '', 0, 500),
                'method'     => $entry['method'],
                'context'    => $entry['context'],
            ]);
        } catch (Throwable $e) {
            // Logging must NEVER crash the application (A10: fail safely)
            Log::error('SecurityLogger: failed to persist to database', [
                'event' => $event,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Threshold Checks & Alerting
    |--------------------------------------------------------------------------
    */

    private function checkThresholds(string $event, array $entry): void
    {
        $ip = $entry['ip'];

        match ($event) {
            'auth.login_failed'        => $this->checkCounter(
                                            "security:failed_logins:{$ip}",
                                            config('security.thresholds.failed_logins'),
                                            'failed_login_threshold',
                                            $ip
                                        ),
            'auth.lockout'             => $this->checkCounter(
                                            "security:lockouts:{$ip}",
                                            config('security.thresholds.lockouts'),
                                            'lockout_threshold',
                                            $ip
                                        ),
            'access.denied',
            'access.control_violation' => $this->checkCounter(
                                            "security:access_violations:{$ip}",
                                            config('security.thresholds.access_violations'),
                                            'access_violation_threshold',
                                            $ip
                                        ),
            default                    => null,
        };
    }

    private function checkCounter(
        string $cacheKey,
        array  $threshold,
        string $alertType,
        string $ip
    ): void {
        // Cache::add is atomic: only sets the key if it does not already exist
        // This handles the first-hit TTL assignment safely
        Cache::add($cacheKey, 0, now()->addMinutes($threshold['minutes']));
        $count = Cache::increment($cacheKey);

        if ($count >= $threshold['count']) {
            $this->triggerAlert($alertType, [
                'ip'      => $ip,
                'count'   => $count,
                'minutes' => $threshold['minutes'],
            ]);
        }
    }

    private function triggerAlert(string $alertType, array $data): void
    {
        // Prevent alert flooding: one alert per IP per alert type per window
        $dedupeKey = "security:alert_sent:{$alertType}:{$data['ip']}";

        if (Cache::has($dedupeKey)) {
            return;
        }

        // Lock the deduplication key for the same window as the threshold
        Cache::put($dedupeKey, true, now()->addMinutes($data['minutes']));

        // Log the alert itself as a high-value event
        $this->log('security.alert_triggered', array_merge($data, [
            'alert_type' => $alertType,
        ]), 'warning');

        // Send the email notification
        $alertEmail = config('security.alert_email');

        if ($alertEmail) {
            try {
                Notification::route('mail', $alertEmail)
                    ->notify(new SecurityAlertNotification($alertType, $data));
            } catch (Throwable $e) {
                Log::error('SecurityLogger: failed to send alert notification', [
                    'alert_type' => $alertType,
                    'error'      => $e->getMessage(),
                ]);
            }
        }
    }
}