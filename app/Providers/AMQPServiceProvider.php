<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AMQPService;
/** */
class AMQPServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AMQPService::class, function ($app) {
            return new AMQPService();
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