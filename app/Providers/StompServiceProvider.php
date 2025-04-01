<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\StompService;

class StompServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(StompService::class, function ($app) {
            return new StompService();
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