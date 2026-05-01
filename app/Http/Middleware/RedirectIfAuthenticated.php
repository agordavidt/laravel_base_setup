<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * Scenarios covered:
     * - Logged-in user hits /login or /register via browser back → redirected
     *   to their correct role dashboard, not the generic /dashboard.
     * - User logs out, a different user logs in, first user presses back →
     *   the new session has a different role, redirect goes to that role's
     *   dashboard. PreventBackHistory middleware on authenticated routes
     *   handles the cache layer.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect($this->roleBasedRedirect(Auth::guard($guard)->user()));
            }
        }

        return $next($request);
    }

    private function roleBasedRedirect($user): string
    {
        return match (true) {
            $user->hasRole('super-admin') => route('super-admin.dashboard'),
            $user->hasRole('admin')       => route('admin.dashboard'),
            $user->hasRole('agent')       => route('agent.dashboard'),
            default                       => route('dashboard'),
        };
    }
}