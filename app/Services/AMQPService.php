<?php

namespace App\Services;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Illuminate\Support\Facades\Log;
/** */
class AMQPService
{
    protected $connection;
    protected $channel;
    
    public function __construct()
    {
       
    }
    
    public function connectRabbitMQ()
    {
        $host = env('RABBITMQ_HOST', 'localhost');
        $port = env('RABBITMQ_PORT', 5672);
        $username = env('RABBITMQ_USERNAME', 'guest');
        $password = env('RABBITMQ_PASSWORD', 'guest');
        $vhost = env('RABBITMQ_VHOST', '/');
        
        try {
            // Khởi tạo kết nối AMQP
            $this->connection = new AMQPStreamConnection(
                $host,
                $port,
                $username,
                $password,
                $vhost
            );
            
            $this->channel = $this->connection->channel();
            
            // Khai báo các exchange
            $this->declareExchanges();
            
            Log::info('Kết nối AMQP thành công!');
            
            return $this->connection;
        } catch (\Exception $e) {
            Log::error('Không thể kết nối đến RabbitMQ qua AMQP: ' . $e->getMessage());
            throw $e;
        }
    }
    
    protected function declareExchanges()
    {
        // Khai báo exchange cho chat
        $this->channel->exchange_declare(
            'chat.exchange',  // exchange name
            'topic',          // type
            false,            // passive
            true,             // durable
            false             // auto_delete
        );
        
        // Khai báo exchange cho video
        $this->channel->exchange_declare(
            'video.exchange', // exchange name
            'topic',          // type
            false,            // passive
            true,             // durable
            false             // auto_delete
        );
    }
    
    public function registerRoomQueue($roomId)
    {
        // Tạo queue cho phòng chat
        $queueName = "room.{$roomId}";
        
        // Khai báo queue
        $this->channel->queue_declare(
            $queueName,      // queue name
            false,           // passive
            true,            // durable
            false,           // exclusive
            false            // auto_delete
        );
        
        // Bind queue với chat exchange
        $this->channel->queue_bind(
            $queueName,
            'chat.exchange',
            "room.{$roomId}"
        );
        
        // Bind queue với video exchange
        $this->channel->queue_bind(
            $queueName,
            'video.exchange',
            "video.{$roomId}"
        );
        
        Log::info("Đã đăng ký queue cho phòng {$roomId}");
        
        return ['queueName' => $queueName];
    }
    
    public function publishMessage($exchange, $routingKey, $message)
    {
        try {
            if (!$this->channel || !$this->connection->isConnected()) {
                Log::warning('AMQP client chưa kết nối, không thể gửi tin nhắn');
                return false;
            }
            
            // Tạo message AMQP
            $msg = new AMQPMessage(
                json_encode($message),
                [
                    'content_type' => 'application/json',
                    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
                ]
            );
            
            // Gửi tin nhắn
            $this->channel->basic_publish($msg, $exchange, $routingKey);
            
            Log::info("Đã gửi tin nhắn đến {$exchange} với routing key {$routingKey}");
            
            return true;
        } catch (\Exception $e) {
            Log::error('Lỗi khi gửi tin nhắn qua AMQP: ' . $e->getMessage());
            return false;
        }
    }
    
    public function consume($queueName, $callback)
    {
        $this->channel->basic_consume(
            $queueName,       // queue
            '',               // consumer tag
            false,            // no local
            false,            // no ack
            false,            // exclusive
            false,            // no wait
            $callback         // callback
        );
        
        Log::info("Bắt đầu consume từ queue {$queueName}");
        
        // Loop để nhận tin nhắn
        while ($this->channel && $this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }
    
    public function closeConnection()
    {
        if ($this->channel) {
            $this->channel->close();
        }
        
        if ($this->connection && $this->connection->isConnected()) {
            $this->connection->close();
            Log::info('Đã đóng kết nối AMQP');
        }
    }
}
// Thay đổi cho commit #20: Implement WebRTC support
// Ngày: 2025-02-20
