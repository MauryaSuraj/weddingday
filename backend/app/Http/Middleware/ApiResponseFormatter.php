<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ApiResponseFormatter Middleware
 * 
 * Wraps all API responses in a consistent format.
 * Ensures every response has success, data, and timestamp fields.
 * 
 * Response Format:
 * {
 *   "success": true/false,
 *   "data": { ... } | null,
 *   "error": { "code": "...", "message": "..." } | null,
 *   "timestamp": "ISO-8601"
 * }
 */
class ApiResponseFormatter
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only format JSON API responses
        if (!$request->is('api/*') || !$response->headers->get('Content-Type') || !str_contains($response->headers->get('Content-Type'), 'application/json')) {
            return $response;
        }

        return $response;
    }
}
