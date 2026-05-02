<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SecurityAlertNotification extends Notification
{
    public function __construct(
        private readonly string $alertType,
        private readonly array  $data
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
        // To add Slack later, return ['mail', 'slack']
    }

    public function toMail($notifiable): MailMessage
    {
        $label = $this->humanReadableLabel();
        $ip    = $this->data['ip'] ?? 'unknown';
        $count = $this->data['count'] ?? 0;
        $mins  = $this->data['minutes'] ?? 0;
        $app   = config('app.name');
        $time  = now()->toDateTimeString();

        return (new MailMessage)
            ->subject("[{$app}] Security Alert: {$label}")
            ->greeting("Security Alert — {$app}")
            ->line("A suspicious activity threshold has been breached.")
            ->line("**Alert Type:** {$label}")
            ->line("**IP Address:** {$ip}")
            ->line("**Count:** {$count} occurrences in the last {$mins} minute(s)")
            ->line("**Detected at:** {$time}")
            ->line("**Recommended action:** Review your security logs and consider blocking this IP if the activity is malicious.")
            ->action('View Security Logs', url('/super-admin/dashboard'))
            ->line("This is an automated alert from {$app}. Do not reply to this email.");
    }

    private function humanReadableLabel(): string
    {
        return match ($this->alertType) {
            'failed_login_threshold'    => 'Repeated Failed Login Attempts',
            'lockout_threshold'         => 'Repeated Account Lockouts',
            'access_violation_threshold'=> 'Repeated Access Control Violations',
            default                     => ucwords(str_replace('_', ' ', $this->alertType)),
        };
    }
}