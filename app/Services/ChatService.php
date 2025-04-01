<?php

namespace App\Services;

use App\Models\ChatMessage;
use App\Models\Room;

class ChatService
{
    public function saveMessage($message, $roomId)
    {
        $room = Room::find($roomId);
        
        if (!$room) {
            throw new \Exception('Phòng không tồn tại');
        }
        
        $chatMessage = new ChatMessage([
            'content' => $message['content'] ?? null,
            'sender' => $message['sender'],
            'room_id' => $roomId,
            'image' => $message['image'] ?? null,
            'type' => $message['type'] ?? 'CHAT',
            'avtUrl' => $message['avtUrl'] ?? null,
            'reply_to_id' => $message['reply_to_id'] ?? null,
        ]);
        
        $chatMessage->save();
        
        return $chatMessage;
    }
    
    public function getMessagesForRoom($roomId, $limit = 50)
    {
        return ChatMessage::where('room_id', $roomId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->reverse();
    }
}