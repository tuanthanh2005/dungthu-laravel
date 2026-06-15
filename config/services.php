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

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_CLIENT_CALLBACK'),
        'project_id' => env('GOOGLE_PROJECT_ID'),
        'auth_uri' => env('GOOGLE_AUTH_URI'),
        'token_uri' => env('GOOGLE_TOKEN_URI'),
        'auth_provider_cert_url' => env('GOOGLE_AUTH_PROVIDER_CERT_URL'),
    ],

    'groq' => [
        'key' => env('GROQ_API_KEY'),
        'model' => env('GROQ_MODEL', 'llama-3.1-8b-instant'),
    ],

    'google_indexing' => [
        'enabled' => env('GOOGLE_INDEXING_ENABLED', true),
        'key_file' => env('GOOGLE_INDEXING_KEY_FILE', storage_path('app/google/indexing-service-account.json')),
        'site_url' => rtrim(env('GOOGLE_INDEXING_SITE_URL', env('APP_URL')), '/'),
    ],

    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN', '8187679739:AAEbsH_miAXOOepBwsB9p7oraCqQdD4jIXI'),
        'chat_id' => env('TELEGRAM_CHAT_ID', '8199725778'),
    ],

    'vietqr' => [
        'bank_code' => env('VIETQR_BANK_CODE', '970428'),
        'bank_name' => env('VIETQR_BANK_NAME', 'Nam A Bank'),
        'account_number' => env('VIETQR_ACCOUNT_NUMBER', 'YOUR_NAM_A_BANK_ACCOUNT_NUMBER'),
        'account_name' => env('VIETQR_ACCOUNT_NAME', 'TRAN THANH TUAN'),
        'add_info' => env('VIETQR_ADD_INFO', 'AI GIA RE THUDUNG'),
    ],

];

