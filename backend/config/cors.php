<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure CORS settings for your application. CORS allows
    | controlled access to resources from different origins.
    |
    | SECURITY: Only allow your frontend domain. NEVER use wildcard (*)
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        // Development
        'http://localhost:4200',
        'http://localhost:3000',
        'http://127.0.0.1:4200',
        
        // Production - Update these with your actual domain
        env('FRONTEND_URL', 'https://yourdomain.com'),
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [
        'X-Total-Count',      // Pagination total
        'X-Request-Id',       // Request tracking
        'X-RateLimit-Limit',
        'X-RateLimit-Remaining',
    ],

    'max_age' => 3600,

    'supports_credentials' => true,

];
