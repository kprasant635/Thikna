<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default SMS Driver
    |--------------------------------------------------------------------------
    |
    | Supported drivers: "log", "null", "custom"
    |
    */
    'default' => env('SMS_DRIVER', 'log'),

    /*
    |--------------------------------------------------------------------------
    | OTP Configuration
    |--------------------------------------------------------------------------
    |
    | expiry_minutes: Time before an OTP expires
    | resend_cooldown_seconds: Cooldown period before requesting another OTP
    | max_attempts: Maximum invalid attempts allowed per OTP
    | max_per_hour: Maximum OTP requests permitted per phone/IP in an hour
    |
    */
    'otp' => [
        'expiry_minutes' => (int) env('OTP_EXPIRY_MINUTES', 5),
        'resend_cooldown_seconds' => (int) env('OTP_RESEND_COOLDOWN', 60),
        'max_attempts' => (int) env('OTP_MAX_ATTEMPTS', 5),
        'max_per_hour' => (int) env('OTP_MAX_PER_HOUR', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Provider Credentials (configured via .env)
    |--------------------------------------------------------------------------
    */
    'drivers' => [
        'log' => [
            'channel' => env('SMS_LOG_CHANNEL', null),
        ],
    ],
];
