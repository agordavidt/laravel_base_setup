<?php

namespace App\Listeners\Auth;

use App\Services\SecurityLogger;
use Illuminate\Auth\Events\Lockout;

class LogLockout
{
    public function __construct(private readonly SecurityLogger $logger) {}

    public function handle(Lockout $event): void
    {
        // High-value: goes to DB, triggers lockout threshold check
        $this->logger->warning('auth.lockout', [
            'attempted_email' => $event->request->input('email') ?? 'unknown',
        ]);
    }
}