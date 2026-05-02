<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SecurityAlert;
use App\Services\SecurityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AcknowledgeAlertController extends Controller
{
    public function __construct(
        private readonly SecurityLogger $logger
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Mark a single alert as acknowledged
    |--------------------------------------------------------------------------
    */
    public function store(Request $request, SecurityAlert $alert): RedirectResponse
    {
        // Already acknowledged — do nothing, avoid duplicate updates
        if ($alert->isAcknowledged()) {
            return back()->with('info', 'This alert has already been acknowledged.');
        }

        $alert->acknowledged_at = now();
        $alert->acknowledged_by = $request->user()->id;
        $alert->save();

        // Audit: log who acknowledged what and when
        $this->logger->info('security.alert_acknowledged', [
            'alert_id'         => $alert->id,
            'alert_type'       => $alert->alert_type,
            'offending_ip'     => $alert->ip_address,
            'acknowledged_by'  => $request->user()->email,
        ]);

        return back()->with('success', 'Alert acknowledged.');
    }

    /*
    |--------------------------------------------------------------------------
    | Bulk acknowledge all unacknowledged alerts
    | Useful when an admin comes online after a period of inactivity
    |--------------------------------------------------------------------------
    */
    public function bulkAcknowledge(Request $request): RedirectResponse
    {
        $unacknowledged = SecurityAlert::unacknowledged()->get();

        if ($unacknowledged->isEmpty()) {
            return back()->with('info', 'No pending alerts to acknowledge.');
        }

        $now    = now();
        $userId = $request->user()->id;

        foreach ($unacknowledged as $alert) {
            $alert->acknowledged_at = $now;
            $alert->acknowledged_by = $userId;
            $alert->save();
        }

        $this->logger->warning('security.bulk_acknowledge', [
            'count'           => $unacknowledged->count(),
            'acknowledged_by' => $request->user()->email,
        ]);

        return back()->with('success', "{$unacknowledged->count()} alerts acknowledged.");
    }
}