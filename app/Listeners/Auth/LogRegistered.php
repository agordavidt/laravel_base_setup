<?php

namespace App\Listeners\Auth;

use App\Services\SecurityLogger;
use Illuminate\Auth\Events\Registered;

class LogRegistered
{
    public function __construct(private readonly SecurityLogger $logger) {}

    public function handle(Registered $event): void
    {
        // High-value: new account creation always goes to DB
        $this->logger->info('auth.registered', [
            'user_id' => $event->user->id,
            'email'   => $event->user->email,
        ]);
    }
}