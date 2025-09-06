<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'toss' => [
        'api_url' => env('TOSS_API_URL', 'https://api.tosspayments.com'),
        'client_key' => env('test_ck_ADpexMgkW36plo0DgLErGbR5ozO0'),
        'secret_key' => env('test_sk_O6BYq7GWPVvAO42yX253NE5vbo1d'),
        'security_key' => env('892f0ac3795a2a53fe40f92926e2b8a1f0bb2b05fda255cd9dcb55c68b08be21'),
        'success_url' => env('TOSS_SUCCESS_URL', env('APP_URL') . '/billing/payment/success'),
        'fail_url' => env('TOSS_FAIL_URL', env('APP_URL') . '/billing/payment/fail'),
        'webhook_url' => env('TOSS_WEBHOOK_URL', env('APP_URL') . '/api/webhooks/toss'),
    ],

];
