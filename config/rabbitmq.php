<?php

return [
    'host' => env('RABBITMQ_HOST', 'localhost'),
    'port' => env('RABBITMQ_PORT', 5672),
    'user' => env('RABBITMQ_USERNAME', 'guest'),
    'password' => env('RABBITMQ_PASSWORD', 'guest'),
    'vhost' => env('RABBITMQ_VHOST', '/'),
    
    'exchange_type' => 'topic',
    'durable' => true,
    'persistent' => true,
    
    'chat_exchange' => 'chat.exchange',
    'video_exchange' => 'video.exchange',
];