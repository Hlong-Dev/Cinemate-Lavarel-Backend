<?php

return [
    /*
     * Set a custom dashboard configuration
     */
    'dashboard' => [
        'port' => env('LARAVEL_WEBSOCKETS_PORT', 6001),
    ],

    /*
     * This package comes with multi tenancy out of the box. Here you can
     * configure the different apps that can use the webSockets server.
     *
     * Optionally you specify capacity so you can limit the maximum
     * concurrent connections for a specific app.
     *
     * Optionally you can disable client events so clients cannot send
     * messages to each other via the webSockets.
     */
    'apps' => [
        [
            'id' => env('PUSHER_APP_ID'),
            'name' => env('APP_NAME'),
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'path' => env('PUSHER_APP_PATH'),
            'capacity' => null,
            'enable_client_messages' => true,
            'enable_statistics' => true,
        ],
    ],

    /*
     * This class is responsible for finding the apps. The default provider
     * will use the apps defined in this config file.
     *
     * You can create a custom provider by implementing the
     * `AppProvider` interface.
     */
    'app_provider' => BeyondCode\LaravelWebSockets\Apps\ConfigAppProvider::class,

    /*
     * This array contains the hosts of which you want to allow incoming requests.
     * Leave this empty if you want to accept requests from all hosts.
     */
    'allowed_origins' => [
        'http://localhost:3000',
        'https://hlong-cinemate.vercel.app',
    ],

    /*
     * The maximum request size in kilobytes that is allowed for an incoming WebSocket request.
     */
    'max_request_size_in_kb' => 5000,

    /*
     * This path will be used to register the necessary routes for the package.
     */
    'path' => 'ws',
];