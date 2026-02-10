<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Security Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file controls custom security settings for the
    | application including headers, encryption, and protection mechanisms.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Content Security Policy
    |--------------------------------------------------------------------------
    |
    | CSP Headers to prevent XSS, clickjacking, and other attacks.
    |
    */

    'csp' => [
        'enabled' => env('CSP_ENABLED', true),
        'report_uri' => env('CSP_REPORT_URI', '/api/v1/security/csp-report'),
        'upgrade_insecure' => env('CSP_UPGRADE_INSECURE', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | HSTS (HTTP Strict Transport Security)
    |--------------------------------------------------------------------------
    |
    | Forces HTTPS connections. Set max_age in seconds.
    | 63072000 = 2 years
    |
    */

    'hsts' => [
        'enabled' => env('HSTS_ENABLED', true),
        'max_age' => env('HSTS_MAX_AGE', 63072000),
        'include_subdomains' => env('HSTS_INCLUDE_SUBDOMAINS', true),
        'preload' => env('HSTS_PRELOAD', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | X-Frame-Options
    |--------------------------------------------------------------------------
    |
    | Prevent clickjacking attacks by controlling frame embedding.
    | Options: DENY, SAMEORIGIN, ALLOW-FROM
    |
    */

    'x_frame_options' => env('X_FRAME_OPTIONS', 'DENY'),

    /*
    |--------------------------------------------------------------------------
    | X-Content-Type-Options
    |--------------------------------------------------------------------------
    |
    | Prevent browsers from MIME-sniffing responses.
    | Should always be 'nosniff'
    |
    */

    'x_content_type_options' => env('X_CONTENT_TYPE_OPTIONS', 'nosniff'),

    /*
    |--------------------------------------------------------------------------
    | Referrer-Policy
    |--------------------------------------------------------------------------
    |
    | Control how much referrer information is shared with third parties.
    |
    */

    'referrer_policy' => env('REFERRER_POLICY', 'strict-origin-when-cross-origin'),

    /*
    |--------------------------------------------------------------------------
    | Permissions-Policy (formerly Feature-Policy)
    |--------------------------------------------------------------------------
    |
    | Control which browser features can be used.
    |
    */

    'permissions_policy' => [
        'geolocation' => [],
        'microphone' => [],
        'camera' => [],
        'payment' => [],
        'usb' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Rate Limiting
    |--------------------------------------------------------------------------
    |
    | Configure rate limiting thresholds.
    |
    */

    'rate_limit' => [
        'authenticated' => env('RATE_LIMIT_AUTHENTICATED', '60,1'),      // 60 per minute
        'unauthenticated' => env('RATE_LIMIT_UNAUTHENTICATED', '15,1'),  // 15 per minute
        'login_attempts' => env('RATE_LIMIT_LOGIN', '5,1'),              // 5 attempts per minute
        'api_endpoints' => env('RATE_LIMIT_API', '100,1'),               // 100 per minute
    ],

    /*
    |--------------------------------------------------------------------------
    | API Versioning
    |--------------------------------------------------------------------------
    |
    | Configure API version management.
    |
    */

    'api_version' => [
        'current' => env('API_VERSION', 'v1'),
        'supported' => ['v1'],  // Add old versions if maintaining backward compatibility
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Logging
    |--------------------------------------------------------------------------
    |
    | Enable audit logs for security-critical actions.
    |
    */

    'audit_logging' => [
        'enabled' => env('AUDIT_LOGGING_ENABLED', true),
        'log_login' => true,
        'log_logout' => true,
        'log_password_change' => true,
        'log_permission_change' => true,
        'log_data_access' => true,
        'retention_days' => env('AUDIT_LOG_RETENTION', 90),
    ],

    /*
    |--------------------------------------------------------------------------
    | File Upload Security
    |--------------------------------------------------------------------------
    |
    | Configure file upload restrictions.
    |
    */

    'file_upload' => [
        'max_size_mb' => env('FILE_UPLOAD_MAX_SIZE', 10),
        'blocked_extensions' => ['exe', 'bat', 'sh', 'com', 'pif', 'php', 'php3', 'php4', 'php5', 'phps'],
        'allowed_mime_types' => [
            'image/jpeg',
            'image/png',
            'image/gif',
            'image/webp',
            'application/pdf',
            'text/plain',
        ],
        'upload_directory' => 'uploads',
        'public_access' => false,  // Store outside public directory
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Policy
    |--------------------------------------------------------------------------
    |
    | Configure password requirements.
    |
    */

    'password' => [
        'min_length' => env('PASSWORD_MIN_LENGTH', 12),
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_special_chars' => true,
        'special_chars' => '!@#$%^&*()_+-=[]{}|;:,.<>?',
        'expiration_days' => env('PASSWORD_EXPIRATION_DAYS', 0),  // 0 = no expiration
        'history_count' => env('PASSWORD_HISTORY_COUNT', 5),  // Prevent reuse
    ],

    /*
    |--------------------------------------------------------------------------
    | Token Configuration
    |--------------------------------------------------------------------------
    |
    | Configure security token settings.
    |
    */

    'token' => [
        'expiration_hours' => env('TOKEN_EXPIRATION_HOURS', 24),
        'refresh_before_minutes' => env('TOKEN_REFRESH_BEFORE_MINUTES', 60),
        'allow_refresh' => true,
        'rotation_enabled' => true,  // Rotate tokens on each refresh
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Management
    |--------------------------------------------------------------------------
    |
    | Configure session security.
    |
    */

    'session' => [
        'timeout_minutes' => env('SESSION_TIMEOUT_MINUTES', 60),
        'disable_navigation_warnings' => false,
        'secure_cookies' => true,
        'http_only' => true,
        'same_site' => 'strict',
    ],

    /*
    |--------------------------------------------------------------------------
    | IP Whitelisting (Optional)
    |--------------------------------------------------------------------------
    |
    | Allow access to admin routes from specific IPs only.
    |
    */

    'ip_whitelist' => [
        'enabled' => env('IP_WHITELIST_ENABLED', false),
        'ips' => explode(',', env('WHITELISTED_IPS', '')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication
    |--------------------------------------------------------------------------
    |
    | If implementing 2FA, configure here.
    |
    */

    'two_factor' => [
        'enabled' => env('TWO_FACTOR_ENABLED', false),
        'provider' => env('TWO_FACTOR_PROVIDER', 'totp'),  // totp, sms, email
        'window' => 1,  // Number of windows to verify
    ],

];
