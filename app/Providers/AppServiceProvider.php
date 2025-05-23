<?php

namespace App\Providers;
use App\Services\WebSocketService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $webSocketService = $this->app->make(WebSocketService::class);
        $webSocketService->configureWebSocket();
    }
}

// Thay đổi cho commit #11: Create middleware for authentication
// Ngày: 2025-03-14

// Thay đổi cho commit #23: Optimize WebSocket connection handling
// Ngày: 2025-02-12
