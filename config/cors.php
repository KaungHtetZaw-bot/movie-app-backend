<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'
        // 'http://localhost:5173',
        // 'http://127.0.0.1:5173',
        // 'http://192.168.110.102:5173',
        // 'http://192.168.110.109:5173',
        // 'http://192.168.110.129:5173',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false, // token-based auth
];
