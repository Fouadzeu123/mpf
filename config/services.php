<?php

return [

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
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

    'whatsapp' => [
        'driver' => env('WHATSAPP_DRIVER', 'log'),
        'twilio_sid' => env('TWILIO_SID'),
        'twilio_token' => env('TWILIO_TOKEN'),
        'twilio_from' => env('TWILIO_WHATSAPP_FROM'),
        'business_token' => env('WHATSAPP_BUSINESS_TOKEN'),
        'business_phone_id' => env('WHATSAPP_BUSINESS_PHONE_ID'),
    ],

    'notchpay' => [
        'public_key' => env('NOTCHPAY_PUBLIC_KEY'),
        'private_key' => env('NOTCHPAY_PRIVATE_KEY'),
        'sandbox' => env('NOTCHPAY_SANDBOX', true),
        'callback_url' => env('NOTCHPAY_CALLBACK_URL'),
    ],

    'bible' => [
        'api_key' => env('BIBLE_API_KEY'),
        'base_url' => env('BIBLE_API_URL', 'https://api.scripture.api.bible/v1'),
        'bible_id' => env('BIBLE_ID', 'de4e12af7f28f599-02'),
    ],

];
