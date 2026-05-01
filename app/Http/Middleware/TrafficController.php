<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrafficController
{
    /**
     * Role → route name map, in priority order.
     *
     * To add a new role tomorrow:
     *   1. Add one line here e.g. 'manager' => 'manager.dashboard'
     *   2. Create the route in web.php
     *   Done. Nothing else needs touching.
     */
    protected array $roleMap = [
        'super-admin' => 'super-admin.dashboard',
        'admin'       => 'admin.dashboard',
        'agent'       => 'agent.dashboard',
        'user'        => 'dashboard',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $user          = $request->user();
        $correctRoute  = $this->resolveCorrectRoute($user);
        $currentRoute  = $request->route()?->getName();

        // Self-Healing Navigation:
        // If the user is on any dashboard route that is NOT theirs,
        // silently correct their location. This covers:
        //   - Bookmarked wrong URL
        //   - Old link from a previous role
        //   - Browser back after role change
        //   - Another user logged in on the same browser
        if ($this->isDashboardRoute($currentRoute) && $currentRoute !== $correctRoute) {
            return redirect()->route($correctRoute);
        }

        // Pass through to the controller, then attach cache-control headers
        // on the way out. This replaces PreventBackHistory on every group —
        // one middleware, one place, covers all authenticated routes.
        return $next($request)->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'        => 'no-cache',
            'Expires'       => 'Sat, 01 Jan 2000 00:00:00 GMT',
        ]);
    }

    /**
     * Walk the role map in priority order and return the first match.
     * Falls back to the default user dashboard if no role matches.
     */
    private function resolveCorrectRoute($user): string
    {
        foreach ($this->roleMap as $role => $routeName) {
            if ($user->hasRole($role)) {
                return $routeName;
            }
        }

        return 'dashboard';
    }

    /**
     * True only if the current route is one of the known dashboard routes.
     * We scope self-healing to dashboards only — other pages (e.g. /profile,
     * /settings) should not trigger a redirect.
     */
    private function isDashboardRoute(?string $routeName): bool
    {
        return in_array($routeName, array_values($this->roleMap), strict: true);
    }
}