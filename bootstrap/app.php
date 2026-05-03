<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
// use Throwable;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Spatie permission middleware aliases
        $middleware->alias([
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        // Append security headers to every web response
        $middleware->appendToGroup('web', \App\Http\Middleware\SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // A10: Global exception handler — the last safety net
        // report() is for logging. render() is for HTTP responses.
        // We use report() here so normal Laravel error pages still work.

        $exceptions->report(function (Throwable $e) {

            // Do not double-log HTTP exceptions (404, 419, etc.)
            // They are expected application states, not security events
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {

                // 403 specifically is an access control failure — log it
                if ($e->getStatusCode() === 403) {
                    app(\App\Services\SecurityLogger::class)->warning(
                        'access.denied',
                        ['status' => 403]
                    );
                }

                return; // Let Laravel handle all other HTTP exceptions normally
            }

            // Every other unhandled exception is a critical security event
            app(\App\Services\SecurityLogger::class)->critical(
                'error.unhandled_exception',
                [
                    // Safe subset only — no stack trace exposed (A10: CWE-209)
                    'exception' => get_class($e),
                    'message'   => $e->getMessage(),
                    'file'      => $e->getFile(),
                    'line'      => $e->getLine(),
                ]
            );
        });
    })->create();