<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind custom LoginResponse — kept here, close to Fortify context
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // ---------------------------------------------------------------
        // REMOVED: Fortify::redirectUserForTwoFactorAuthenticationUsing()
        // This is already the Fortify default. Re-registering it causes a
        // container resolution failure during artisan CLI boot (the error
        // you saw at bootstrap/app.php line 18).
        // ---------------------------------------------------------------

        // Login rate limiter — throttle by email + IP (OWASP compliant)
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(
                Str::lower($request->input(Fortify::username())) . '|' . $request->ip()
            );
            return Limit::perMinute(5)->by($throttleKey);
        });

        // 2FA rate limiter
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // ---------------------------------------------------------------
        // REMOVED: passkeys rate limiter
        // $request->session() throws during artisan CLI boot when no
        // HTTP session is active. Only add this back if you implement
        // passkeys (WebAuthn) and guard it with a session-exists check.
        // ---------------------------------------------------------------

        // Views — all pass required data explicitly
        Fortify::loginView(fn () => view('auth.login'));

        Fortify::registerView(fn () => view('auth.register'));

        Fortify::verifyEmailView(fn () => view('auth.verify-email'));

        Fortify::requestPasswordResetLinkView(fn () => view('auth.forgot-password'));

        // FIX: pass $token and $email explicitly so the blade can use them
        // directly without depending on $request being available in the view.
        Fortify::resetPasswordView(function (Request $request) {
            return view('auth.reset-password', [
                'token' => $request->route('token'),
                'email' => $request->email,
            ]);
        });

        Fortify::confirmPasswordView(fn () => view('auth.confirm-password'));

        Fortify::twoFactorChallengeView(fn () => view('auth.two-factor-challenge'));
    }
}