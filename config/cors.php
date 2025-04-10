<?php

return [
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:3000', 
        'https://cinemate-lavarel.vercel.app',
        'https://cinemate-watch.vercel.app',
        'https://www.cinemate.website',
        'https://cinemate.website',
        'http://localhost:5173'   // Loại bỏ dấu '/' ở cuối
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];