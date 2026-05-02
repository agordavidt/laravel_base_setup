<?php

namespace App\Services;

use App\Models\SecurityAlert;
use App\Models\SecurityLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SecurityDashboardService
{
    /*
    |--------------------------------------------------------------------------
    | Cache TTL
    | Dashboard data is cached for 60 seconds. This means the super admin
    | always sees data that is at most 1 minute old, but rapid refreshes
    | do not hammer the database.
    |--------------------------------------------------------------------------
    */
    private const CACHE_TTL = 60;

    /*
    |--------------------------------------------------------------------------
    | 1. Threat Overview — four stat cards
    |    Each card returns: value (today), delta (vs yesterday), direction
    |--------------------------------------------------------------------------
    */
    public function getThreatOverview(): array
    {
        return Cache::remember('security:dashboard:overview', self::CACHE_TTL, function () {
            $now       = now();
            $todayFrom = $now->copy()->subDay();
            $prevFrom  = $now->copy()->subDays(2);
            $prevTo    = $now->copy()->subDay();

            $metrics = [
                'failed_logins'         => 'auth.login_failed',
                'access_violations'     => 'access.denied',
                'unhandled_exceptions'  => 'error.unhandled_exception',
            ];

            $overview = [];

            foreach ($metrics as $key => $event) {
                $today     = SecurityLog::where('event', $event)
                                ->where('created_at', '>=', $todayFrom)
                                ->count();

                $yesterday = SecurityLog::where('event', $event)
                                ->whereBetween('created_at', [$prevFrom, $prevTo])
                                ->count();

                $overview[$key] = [
                    'value'     => $today,
                    'delta'     => $today - $yesterday,
                    'direction' => match (true) {
                        $today > $yesterday => 'up',
                        $today < $yesterday => 'down',
                        default             => 'same',
                    },
                ];
            }

            // Active alerts is a live count — intentionally not cached with
            // the rest so the badge stays accurate between cache refreshes
            $overview['active_alerts'] = [
                'value'     => SecurityAlert::unacknowledged()->count(),
                'delta'     => null,
                'direction' => 'none',
            ];

            return $overview;
        });
    }

    /*
    |--------------------------------------------------------------------------
    | 2. Recent Security Events — paginated, filterable
    |    Filters: event type, IP, severity level, date from/to
    |--------------------------------------------------------------------------
    */
    public function getRecentEvents(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = SecurityLog::query()->orderByDesc('created_at');

        if (!empty($filters['event'])) {
            $query->where('event', $filters['event']);
        }

        if (!empty($filters['ip'])) {
            // Strict equality — no wildcard to prevent injection
            $query->where('ip_address', $filters['ip']);
        }

        if (!empty($filters['level'])) {
            $query->where('level', $filters['level']);
        }

        if (!empty($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from'] . ' 00:00:00');
        }

        if (!empty($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to'] . ' 23:59:59');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /*
    |--------------------------------------------------------------------------
    | 3. Top Offending IPs — last 24 hours
    |    Ranked by total event count descending
    |--------------------------------------------------------------------------
    */
    public function getTopOffendingIps(int $limit = 10): Collection
    {
        return Cache::remember("security:dashboard:top_ips", self::CACHE_TTL, function () use ($limit) {
            return SecurityLog::query()
                ->select(
                    'ip_address',
                    DB::raw('COUNT(*) as total_events'),
                    DB::raw('SUM(CASE WHEN event = "auth.login_failed" THEN 1 ELSE 0 END) as failed_logins'),
                    DB::raw('SUM(CASE WHEN event = "access.denied" THEN 1 ELSE 0 END) as access_violations'),
                    DB::raw('MAX(event) as latest_event'),
                    DB::raw('MIN(created_at) as first_seen'),
                    DB::raw('MAX(created_at) as last_seen')
                )
                ->where('created_at', '>=', now()->subDay())
                ->groupBy('ip_address')
                ->orderByDesc('total_events')
                ->limit($limit)
                ->get();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Authentication Health Chart — last 7 days
    |    Returns data structured for direct Chart.js consumption
    |--------------------------------------------------------------------------
    */
    public function getAuthHealthChart(): array
    {
        return Cache::remember('security:dashboard:auth_chart', self::CACHE_TTL, function () {

            // Build the 7-day date range as labels (most recent last)
            $days = collect(range(6, 0))->map(
                fn ($daysAgo) => now()->subDays($daysAgo)->format('Y-m-d')
            );

            // Single query — fetch all three event types for the last 7 days
            $rows = SecurityLog::query()
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    'event',
                    DB::raw('COUNT(*) as count')
                )
                ->whereIn('event', [
                    'auth.login_success',
                    'auth.login_failed',
                    'access.denied',
                ])
                ->where('created_at', '>=', now()->subDays(7)->startOfDay())
                ->groupBy('date', 'event')
                ->get()
                ->groupBy('date');

            // Map into three parallel arrays aligned to the date labels
            $successful = [];
            $failed     = [];
            $violations = [];

            foreach ($days as $date) {
                $dayData = $rows->get($date, collect());

                $successful[] = (int) $dayData
                    ->firstWhere('event', 'auth.login_success')?->count ?? 0;

                $failed[] = (int) $dayData
                    ->firstWhere('event', 'auth.login_failed')?->count ?? 0;

                $violations[] = (int) $dayData
                    ->firstWhere('event', 'access.denied')?->count ?? 0;
            }

            return [
                // Human-readable labels for the X axis
                'labels'     => $days->map(
                    fn ($d) => \Carbon\Carbon::parse($d)->format('D d M')
                )->values()->toArray(),

                'successful' => $successful,
                'failed'     => $failed,
                'violations' => $violations,
            ];
        });
    }

    /*
    |--------------------------------------------------------------------------
    | 5. Active (Unacknowledged) Alerts
    |    Live — intentionally not cached so the panel reflects real state
    |--------------------------------------------------------------------------
    */
    public function getActiveAlerts(): Collection
    {
        return SecurityAlert::unacknowledged()
            ->with('acknowledgedBy:id,name')
            ->orderByDesc('triggered_at')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | 6. Recent Account Activity
    |    Registrations, password resets, role-relevant events
    |--------------------------------------------------------------------------
    */
    public function getRecentAccountActivity(int $limit = 15): Collection
    {
        return SecurityLog::query()
            ->whereIn('event', [
                'auth.registered',
                'auth.password_reset_success',
                'auth.password_reset_requested',
                'auth.lockout',
            ])
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | 7. Available event types for the filter dropdown
    |    Derived from distinct values actually present in the DB
    |--------------------------------------------------------------------------
    */
    public function getDistinctEventTypes(): Collection
    {
        return Cache::remember('security:dashboard:event_types', 300, function () {
            return SecurityLog::query()
                ->distinct()
                ->orderBy('event')
                ->pluck('event');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | 8. Summary for a specific IP — used by the detail / investigate view
    |--------------------------------------------------------------------------
    */
    public function getIpSummary(string $ip): array
    {
        // No cache here — called on demand for specific investigation
        $events = SecurityLog::where('ip_address', $ip)
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        $alerts = SecurityAlert::forIp($ip)
            ->orderByDesc('triggered_at')
            ->get();

        return [
            'ip'            => $ip,
            'total_events'  => $events->count(),
            'first_seen'    => $events->last()?->created_at,
            'last_seen'     => $events->first()?->created_at,
            'events'        => $events,
            'alerts'        => $alerts,
            'alert_count'   => $alerts->count(),
        ];
    }
}