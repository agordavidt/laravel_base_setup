<?php

namespace App\Listeners\Auth;

use App\Services\SecurityLogger;
use Illuminate\Auth\Events\PasswordReset;

class LogPasswordReset
{
    public function __construct(private readonly SecurityLogger $logger) {}

    public function handle(PasswordReset $event): void
    {
        // High-value: password changes are always worth recording in DB
        $this->logger->info('auth.password_reset_success', [
            'user_id' => $event->user->id,
            'email'   => $event->user->email,
        ]);
    }
}