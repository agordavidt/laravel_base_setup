<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=()'
        );

        $response->headers->set(
            'Content-Security-Policy',
            "default-src 'self'; " .

            // Scripts (Bootstrap + inline script)
            "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net; " .

            // Styles (Bootstrap + Google Fonts + inline styles)
            "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com https://cdnjs.cloudflare.com; " .

            // Fonts (Google Fonts + Font Awesome)
            "font-src 'self' https://fonts.gstatic.com https://cdnjs.cloudflare.com; " .

            // Images
            "img-src 'self' data: https:; " .

            // AJAX / API calls
            "connect-src 'self';"
        );

        // HTTPS enforcement (production only)
        if (config('app.env') === 'production') {
            $response->headers->set(
                'Strict-Transport-Security',
                'max-age=31536000; includeSubDomains; preload'
            );
        }

        return $response;
    }
}