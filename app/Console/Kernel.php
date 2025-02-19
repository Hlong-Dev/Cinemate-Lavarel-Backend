<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
   

    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }
    protected $commands = [
        \App\Console\Commands\StartWebSocketServer::class,
        \App\Console\Commands\TestRabbitMQ::class,
        \App\Console\Commands\StartWebSocketServer::class,
    ];
    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

// Thay đổi cho commit #5: Add Room model and controller
// Ngày: 2025-03-29
