<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SecurityHeaders Middleware
 * 
 * Adds security headers to all HTTP responses.
 * 
 * Headers Added:
 * - Content-Security-Policy (XSS prevention)
 * - X-Frame-Options (Clickjacking prevention)
 * - X-Content-Type-Options (MIME sniffing prevention)
 * - Strict-Transport-Security (HTTPS enforcement)
 * - Referrer-Policy (Privacy)
 * - Permissions-Policy (Feature control)
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent XSS attacks
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', config('security.x_frame_options', 'DENY'));
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Enforce HTTPS
        if (config('security.hsts.enabled')) {
            $hstsValue = sprintf(
                'max-age=%d%s',
                config('security.hsts.max_age', 63072000),
                config('security.hsts.include_subdomains') ? '; includeSubDomains' : ''
            );

            if (config('security.hsts.preload')) {
                $hstsValue .= '; preload';
            }

            $response->headers->set('Strict-Transport-Security', $hstsValue);
        }

        // Referrer policy
        $response->headers->set('Referrer-Policy', config('security.referrer_policy', 'strict-origin-when-cross-origin'));

        // Content Security Policy
        if (config('security.csp.enabled')) {
            $csp = "default-src 'self'; " .
                "script-src 'self'; " .
                "style-src 'self' 'unsafe-inline'; " .
                "img-src 'self' data: https:; " .
                "font-src 'self'; " .
                "connect-src 'self' https://api.yourdomain.com; " .
                "frame-ancestors 'none'; " .
                "base-uri 'self'; " .
                "form-action 'self';";

            if (config('security.csp.report_uri')) {
                $csp .= '; report-uri ' . config('security.csp.report_uri');
            }

            $response->headers->set('Content-Security-Policy', $csp);
        }

        // Permissions Policy (formerly Feature-Policy)
        $permissionsPolicy = 'geolocation=(), ' .
            'microphone=(), ' .
            'camera=(), ' .
            'payment=(), ' .
            'usb=()';
        $response->headers->set('Permissions-Policy', $permissionsPolicy);

        return $response;
    }
}
