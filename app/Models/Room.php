<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';

    protected $fillable = [
        'name',
        'current_video_url',
        'current_video_title',
        'thumbnail',
        'owner_username',
    ];

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'room_id');
    }
}
// Thay đổi cho commit #14: Set up user service and repository pattern
// Ngày: 2025-03-07
