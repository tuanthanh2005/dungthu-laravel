<?php

return [
    'enabled' => env('CHATBOT_ENABLED', true),
    'max_context_messages' => env('CHATBOT_MAX_CONTEXT_MESSAGES', 10),
    'response_timeout' => env('CHATBOT_RESPONSE_TIMEOUT', 30),
    
    'groq' => [
        'api_key' => env('GROQ_API_KEY'),
        'model' => env('GROQ_MODEL', 'llama-3.1-8b-instant'),
    ],

    // Support info for fallback
    'support' => [
        'email' => env('SUPPORT_EMAIL', 'support@dungthu.com'),
        'phone' => env('SUPPORT_PHONE', '0772698113'),
        'zalo' => env('SUPPORT_ZALO', '0708910952'),
    ],
];
