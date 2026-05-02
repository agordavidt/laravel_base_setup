<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Services\SecurityDashboardService;
use Illuminate\Http\Request;

class SecurityMonitoringController extends Controller
{
    public function __construct(
        private readonly SecurityDashboardService $dashboard
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Main security monitoring page
    | All heavy lifting is in SecurityDashboardService — controller stays thin
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        // Validate filter inputs before passing to service
        // Column names are never user-controlled (OWASP SQL injection)
        $filters = $request->validate([
            'event'     => ['nullable', 'string', 'max:100'],
            'ip'        => ['nullable', 'ip'],             // must be a valid IP
            'level'     => ['nullable', 'in:info,warning,critical'],
            'date_from' => ['nullable', 'date_format:Y-m-d'],
            'date_to'   => ['nullable', 'date_format:Y-m-d', 'after_or_equal:date_from'],
        ]);

        return view('super-admin.security.index', [
            'overview'        => $this->dashboard->getThreatOverview(),
            'recentEvents'    => $this->dashboard->getRecentEvents($filters),
            'topIps'          => $this->dashboard->getTopOffendingIps(),
            'authChart'       => $this->dashboard->getAuthHealthChart(),
            'activeAlerts'    => $this->dashboard->getActiveAlerts(),
            'accountActivity' => $this->dashboard->getRecentAccountActivity(),
            'eventTypes'      => $this->dashboard->getDistinctEventTypes(),
            'filters'         => $filters,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | IP Investigation detail — called when admin clicks an IP to investigate
    |--------------------------------------------------------------------------
    */
    public function investigateIp(Request $request, string $ip)
    {
        // Validate the IP is actually an IP before hitting the DB
        $validated = $request->validate([
            'ip' => ['required', 'ip'],
        ]);

        // Merge the route param into validation flow
        abort_unless(filter_var($ip, FILTER_VALIDATE_IP), 422, 'Invalid IP address.');

        return view('super-admin.security.ip-detail', [
            'summary' => $this->dashboard->getIpSummary($ip),
        ]);
    }
}