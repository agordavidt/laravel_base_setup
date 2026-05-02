<?php

namespace App\Providers;

use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

use App\Listeners\Auth\LogFailedLogin;
use App\Listeners\Auth\LogLockout;
use App\Listeners\Auth\LogLogout;
use App\Listeners\Auth\LogPasswordReset;
use App\Listeners\Auth\LogRegistered;
use App\Listeners\Auth\LogSuccessfulLogin;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Security event listeners
        // Each listener is auto-resolved by the container (DI works automatically)
        Event::listen(Login::class,         LogSuccessfulLogin::class);
        Event::listen(Failed::class,        LogFailedLogin::class);
        Event::listen(Logout::class,        LogLogout::class);
        Event::listen(Lockout::class,       LogLockout::class);
        Event::listen(PasswordReset::class, LogPasswordReset::class);
        Event::listen(Registered::class,    LogRegistered::class);
    }
}