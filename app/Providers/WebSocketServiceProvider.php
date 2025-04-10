<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\WebSocketService;
use App\Services\AMQPService;
use App\Services\ChatService;

class WebSocketServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    /** */
    public function register()
    {
        $this->app->singleton(WebSocketService::class, function ($app) {
            return new WebSocketService(
                $app->make(AMQPService::class),
                $app->make(ChatService::class)
            );
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}