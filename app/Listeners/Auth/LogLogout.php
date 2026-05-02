<?php

namespace App\Listeners\Auth;

use App\Services\SecurityLogger;
use Illuminate\Auth\Events\Logout;

class LogLogout
{
    public function __construct(private readonly SecurityLogger $logger) {}

    public function handle(Logout $event): void
    {
        // File-log only — routine event
        $this->logger->info('auth.logout', [
            'user_id' => $event->user?->id,
            'email'   => $event->user?->email,
            'guard'   => $event->guard,
        ]);
    }
}