<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stateful Domains
    |--------------------------------------------------------------------------
    |
    | Requests from the following domains / hosts will receive stateful API
    | authentication cookies, as opposed to being rejected as providing an
    | invalid CSRF token. This is especially useful for developing an
    | SPA that is deployed to the same domain as the API.
    |
    | SECURITY: Only add your frontend domain here, never wildcard (*)
    |
    */

    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
        env('APP_URL') ? ',' . parse_url(env('APP_URL'), PHP_URL_HOST) : ''
    ))),

    /*
    |--------------------------------------------------------------------------
    | Sanctum Guards
    |--------------------------------------------------------------------------
    |
    | This array contains the authentication guards that will be checked when
    | Sanctum is trying to authenticate a request. If none of these guards
    | are able to authenticate the request, a 401 response is returned.
    |
    */

    'guard' => ['web', 'api'],

    /*
    |--------------------------------------------------------------------------
    | Expiration Minutes
    |--------------------------------------------------------------------------
    |
    | This value controls the number of minutes until an issued token will be
    | considered expired. If this value is null, personal access tokens do
    | not expire. This won't tweak the lifetime of first-party sessions.
    |
    | SECURITY: Set to 24 hours (1440 minutes) for API tokens
    |
    */

    'expiration' => env('SANCTUM_TOKEN_EXPIRATION', 1440),

    /*
    |--------------------------------------------------------------------------
    | Sanctum Middleware
    |--------------------------------------------------------------------------
    |
    | When authenticating your first-party SPA with Sanctum, you may need to
    | customize some of the middleware Sanctum uses while processing the
    | request. You may change the middleware listed below as required.
    |
    */

    'middleware' => [
        'verify_csrf_token' => \App\Http\Middleware\VerifyCsrfToken::class,
        'encrypt_cookies' => \App\Http\Middleware\EncryptCookies::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cookie Settings
    |--------------------------------------------------------------------------
    |
    | Configure cookie security for Sanctum authentication tokens.
    |
    | SECURITY BEST PRACTICES:
    | - HttpOnly: true (prevent JavaScript access)
    | - Secure: true (HTTPS only)
    | - SameSite: 'strict' (CSRF protection)
    |
    */

    'cookie' => [
        'name' => env('SANCTUM_COOKIE_NAME', 'XSRF-TOKEN'),
        'path' => env('SANCTUM_COOKIE_PATH', '/'),
        'domain' => env('SANCTUM_COOKIE_DOMAIN', null),
        'secure' => env('SANCTUM_COOKIE_SECURE', true),
        'http_only' => env('SANCTUM_COOKIE_HTTP_ONLY', true),
        'same_site' => env('SANCTUM_COOKIE_SAME_SITE', 'strict'),
    ],

];
