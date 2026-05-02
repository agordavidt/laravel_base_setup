<?php

namespace App\Listeners\Auth;

use App\Services\SecurityLogger;
use Illuminate\Auth\Events\Failed;

class LogFailedLogin
{
    public function __construct(private readonly SecurityLogger $logger) {}

    public function handle(Failed $event): void
    {
        // High-value event: goes to DB and triggers threshold check
        $this->logger->warning('auth.login_failed', [
            // Log the attempted email for forensics — not a password (safe)
            'attempted_email' => $event->credentials['email'] ?? 'unknown',
            'guard'           => $event->guard,
        ]);
    }
}