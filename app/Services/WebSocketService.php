<?php

namespace App\Services;

use App\Services\AMQPService;
use App\Services\ChatService;
use BeyondCode\LaravelWebSockets\WebSockets\WebSocketHandler;
use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Illuminate\Support\Facades\Log;

class WebSocketService
{
    protected $amqpService;
    protected $chatService;
    
    public function __construct(AMQPService $amqpService, ChatService $chatService) // Đổi tham số
    {
        $this->amqpService = $amqpService;
        $this->chatService = $chatService;
    }
    
    public function configureWebSocket()
    {
      
        
        return [
            'handlers' => [
                'chat' => $this->createChatHandler(),
            ]
        ];
    }
    
    protected function createChatHandler()
    {
        return new class($this->amqpService, $this->chatService) extends WebSocketHandler {
            protected $amqpService;
            protected $chatService;
            protected $connections = [];
            
            public function __construct($amqpService, $chatService)
            {
                $this->amqpService = $amqpService;
                $this->chatService = $chatService;
            }
            
            public function onOpen(ConnectionInterface $connection)
            {
                // Khởi tạo connection
                $this->connections[$connection->resourceId] = $connection;
                Log::info("Client đã kết nối qua WebSocket: {$connection->resourceId}");
            }
            
            public function onMessage(ConnectionInterface $connection, MessageInterface $message)
            {
                try {
                    $payload = json_decode($message->getPayload(), true);
                    
                    // Xử lý các sự kiện từ client
                    if (isset($payload['event'])) {
                        switch ($payload['event']) {
                            case 'joinRoom':
                                $this->handleJoinRoom($connection, $payload['data']);
                                break;
                                
                            case 'sendMessage':
                                $this->handleSendMessage($connection, $payload['data']);
                                break;
                                
                            case 'leaveRoom':
                                $this->handleLeaveRoom($connection, $payload['data']);
                                break;
                                
                            case 'videoControl':
                                $this->handleVideoControl($connection, $payload['data']);
                                break;
                                
                            case 'videoUpdate':
                                $this->handleVideoUpdate($connection, $payload['data']);
                                break;
                        }
                    }
                } catch (\Exception $e) {
                    Log::error('Lỗi khi xử lý tin nhắn WebSocket: ' . $e->getMessage());
                }
            }
            
            public function onClose(ConnectionInterface $connection)
            {
                // Xử lý người dùng ngắt kết nối
                if (isset($connection->username) && isset($connection->roomId)) {
                    $message = [
                        'type' => 'LEAVE',
                        'sender' => $connection->username,
                        'avtUrl' => $connection->avtUrl,
                        'content' => "{$connection->username} đã ngắt kết nối"
                    ];
                    
                    $this->broadcastToRoom($connection->roomId, $message);
                }
                
                // Xóa connection
                unset($this->connections[$connection->resourceId]);
                
                Log::info("Client {$connection->resourceId} đã ngắt kết nối");
            }
            
            public function onError(ConnectionInterface $connection, \Exception $e)
            {
                Log::error('Lỗi WebSocket: ' . $e->getMessage());
                $connection->close();
            }
            
            protected function handleJoinRoom($connection, $data)
            {
                try {
                    $roomId = $data['roomId'];
                    $username = $data['username'];
                    $avtUrl = $data['avtUrl'];
                    
                    // Lưu thông tin vào connection
                    $connection->roomId = $roomId;
                    $connection->username = $username;
                    $connection->avtUrl = $avtUrl;
                    
                    // Đăng ký queue cho room
                    $this->amqpService->registerRoomQueue($roomId);
                    
                    // Gửi thông báo cho tất cả người dùng trong phòng
                    $message = [
                        'type' => 'JOIN',
                        'sender' => $username,
                        'avtUrl' => $avtUrl,
                        'content' => "{$username} đã tham gia phòng"
                    ];
                    
                    $this->broadcastToRoom($roomId, $message);
                    
                    Log::info("{$username} đã tham gia phòng {$roomId}");
                } catch (\Exception $e) {
                    Log::error('Lỗi khi xử lý tham gia phòng: ' . $e->getMessage());
                }
            }
            
            protected function handleSendMessage($connection, $data)
            {
                try {
                    $roomId = $data['roomId'];
                    $message = $data['message'];
                    
                    // Broadcast tin nhắn
                    $this->broadcastToRoom($roomId, $message);
                    
                    // Gửi tin nhắn qua RabbitMQ để đồng bộ
                    $this->amqpService->publishMessage('chat.exchange', "room.{$roomId}", $message);
                    
                    // Lưu tin nhắn vào database
                    $this->chatService->saveMessage($message, $roomId);
                } catch (\Exception $e) {
                    Log::error('Lỗi khi xử lý tin nhắn: ' . $e->getMessage());
                }
            }
            
            protected function handleLeaveRoom($connection, $data)
            {
                try {
                    $roomId = $data['roomId'];
                    
                    if (isset($connection->username) && isset($connection->roomId)) {
                        // Gửi thông báo rời phòng
                        $message = [
                            'type' => 'LEAVE',
                            'sender' => $connection->username,
                            'avtUrl' => $connection->avtUrl,
                            'content' => "{$connection->username} đã rời phòng"
                        ];
                        
                        $this->broadcastToRoom($roomId, $message);
                    }
                } catch (\Exception $e) {
                    Log::error('Lỗi khi xử lý rời phòng: ' . $e->getMessage());
                }
            }
            
            protected function handleVideoControl($connection, $data)
            {
                try {
                    $roomId = $data['roomId'];
                    $action = $data['action'];
                    $time = $data['time'];
                    
                    $message = ['action' => $action, 'time' => $time];
                    
                    // Broadcast lệnh điều khiển video
                    $this->broadcastToRoom($roomId, $message, 'videoControl');
                    
                    // Gửi lệnh điều khiển qua RabbitMQ
                    $this->amqpService->publishMessage('video.exchange', "video.{$roomId}", $message);
                } catch (\Exception $e) {
                    Log::error('Lỗi khi xử lý điều khiển video: ' . $e->getMessage());
                }
            }
            
            protected function handleVideoUpdate($connection, $data)
            {
                try {
                    $roomId = $data['roomId'];
                    $videoUrl = $data['videoUrl'];
                    $currentTime = $data['currentTime'];
                    $type = $data['type'];
                    
                    $message = [
                        'videoUrl' => $videoUrl,
                        'currentTime' => $currentTime,
                        'type' => $type
                    ];
                    
                    // Broadcast thông tin cập nhật video
                    $this->broadcastToRoom($roomId, $message, 'videoUpdate');
                    
                    // Gửi thông tin cập nhật qua RabbitMQ
                    $this->amqpService->publishMessage('video.exchange', "video.{$roomId}", $message);
                } catch (\Exception $e) {
                    Log::error('Lỗi khi xử lý cập nhật video: ' . $e->getMessage());
                }
            }
            
            protected function broadcastToRoom($roomId, $message, $event = 'message')
            {
                foreach ($this->connections as $conn) {
                    if (isset($conn->roomId) && $conn->roomId === $roomId) {
                        $conn->send(json_encode([
                            'event' => $event,
                            'data' => $message
                        ]));
                    }
                }
            }
        };
    }
}