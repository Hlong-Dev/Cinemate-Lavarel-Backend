<?php

namespace App\Services;

use Stomp\Client;
use Stomp\StatefulStomp;
use Stomp\Transport\Frame;
use Stomp\Network\Connection;
use Stomp\Transport\Message;
use Illuminate\Support\Facades\Log;

class StompService
{
    protected $client;
    protected $stomp;
    
    public function __construct()
    {
        // Khởi tạo nhưng chưa kết nối
    }
    
    public function connectRabbitMQ()
{
    $host = env('RABBITMQ_HOST', 'localhost');
    $port = env('RABBITMQ_STOMP_PORT', '61613'); // Đổi lại cổng STOMP mặc định
    $username = env('RABBITMQ_USERNAME', 'guest');
    $password = env('RABBITMQ_PASSWORD', 'guest');
    
    try {
        // Kết nối STOMP trực tiếp, không qua WebSocket
        $this->client = new Client("tcp://{$host}:{$port}");
        $this->client->setLogin($username, $password);
        $this->client->connect();
        
        $this->stomp = new StatefulStomp($this->client);
        
        Log::info('Kết nối STOMP thành công!');
        
        return $this->client;
    } catch (\Exception $e) {
        Log::error('Không thể kết nối đến RabbitMQ qua STOMP: ' . $e->getMessage());
        throw $e;
    }
}
    
    
    public function registerRoomQueue($roomId)
    {
        // Cấu hình destination đúng với client
        $chatDestination = "/topic/{$roomId}";
        
        Log::info("Đã đăng ký STOMP destinations cho phòng {$roomId}");
        
        return ['chatDestination' => $chatDestination];
    }
    
    public function publishMessage($exchange, $routingKey, $message)
    {
        try {
            if (!$this->client->isConnected()) {
                Log::warning('STOMP client chưa kết nối, không thể gửi tin nhắn');
                return false;
            }
            
            // Chuyển đổi từ định dạng exchange/routingKey sang STOMP destination
            $destination = '';
            
            // Cả chat và video đều sử dụng cùng một destination
            if ($exchange === 'chat.exchange' || $exchange === 'video.exchange') {
                $roomId = explode('.', $routingKey)[1];
                $destination = "/topic/{$roomId}"; // Destination mà client đang subscribe
            } else {
                $destination = "/{$exchange}/{$routingKey}";
            }
            
            // Tạo message STOMP
            $headers = ['content-type' => 'application/json'];
            $stompMessage = new Message(json_encode($message), $headers);
            
            // Gửi tin nhắn qua STOMP
            $this->stomp->send($destination, $stompMessage);
            
            Log::info("Đã gửi tin nhắn đến {$destination}");
            
            return true;
        } catch (\Exception $e) {
            Log::error('Lỗi khi gửi tin nhắn qua STOMP: ' . $e->getMessage());
            return false;
        }
    }
    
    public function closeConnection()
    {
        if ($this->client && $this->client->isConnected()) {
            $this->client->disconnect();
            Log::info('Đã đóng kết nối STOMP');
        }
    }
}
// Thay đổi cho commit #11: Create middleware for authentication
// Ngày: 2025-03-14

// Thay đổi cho commit #17: Fix authentication bugs
// Ngày: 2025-02-27
