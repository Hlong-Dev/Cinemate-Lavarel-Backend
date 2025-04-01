<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AMQPService;

class StartWebSocketServer extends Command
{
    protected $signature = 'websocket:start';
    protected $description = 'Khởi động WebSocket và AMQP server';

    protected $amqpService; 

    public function __construct(AMQPService $amqpService)
    {
        parent::__construct();
        $this->amqpService = $amqpService; 
    }

    public function handle()
    {
    
        $this->info('Khởi động AMQP service...');
        
        try {
            $this->amqpService->connectRabbitMQ();
            $this->info('Kết nối RabbitMQ AMQP thành công!');
        } catch (\Exception $e) {
            $this->error('Không thể kết nối RabbitMQ AMQP: ' . $e->getMessage());
        }
        
    
        $this->info('Khởi động WebSocket server...');
        $this->call('websockets:serve');
        
        return 0;
    }
}