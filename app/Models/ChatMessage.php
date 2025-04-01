<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'chat_messages';

    protected $fillable = [
        'content',
        'sender',
        'room_id',
        'image',
        'type',
        'avtUrl',
        'reply_to_id',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function replyTo()
    {
        return $this->belongsTo(ChatMessage::class, 'reply_to_id');
    }

    public function replies()
    {
        return $this->hasMany(ChatMessage::class, 'reply_to_id');
    }
}