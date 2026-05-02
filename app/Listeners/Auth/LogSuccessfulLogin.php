<?php

namespace App\Listeners\Auth;

use App\Services\SecurityLogger;
use Illuminate\Auth\Events\Login;

class LogSuccessfulLogin
{
    public function __construct(private readonly SecurityLogger $logger) {}

    public function handle(Login $event): void
    {
        // Successful login is file-log only (not a high-value DB event)
        $this->logger->info('auth.login_success', [
            'user_id' => $event->user->id,
            'email'   => $event->user->email,
            'guard'   => $event->guard,
        ]);
    }
}