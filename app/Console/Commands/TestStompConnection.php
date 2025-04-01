<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Stomp\Client;
use Stomp\Network\Connection;
use Illuminate\Support\Facades\Log;

class TestRabbitMQ extends Command
{
    protected $signature = 'rabbitmq:test';
    protected $description = 'Kiểm tra kết nối RabbitMQ Web-STOMP';

    public function handle()
    {
        $this->info('Đang kiểm tra kết nối RabbitMQ Web-STOMP...');
        
        try {
            $host = env('RABBITMQ_HOST', 'localhost');
            $port = env('RABBITMQ_STOMP_PORT', '15674');
            $username = env('RABBITMQ_USERNAME', 'guest');
            $password = env('RABBITMQ_PASSWORD', 'guest');
            
            $url = "ws://{$host}:{$port}/ws";
            
            $connection = new Connection($url);
            $connection->setReadTimeout(10);
            
            $client = new Client($connection);
            $client->setLogin($username, $password);
            
            $this->info('Đang kết nối...');
            $client->connect();
            
            $this->info('Kết nối thành công!');
            
            // Đóng kết nối
            $client->disconnect();
            
        } catch (\Exception $e) {
            $this->error('Lỗi: ' . $e->getMessage());
            Log::error('STOMP Test Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        return 0;
    }
}