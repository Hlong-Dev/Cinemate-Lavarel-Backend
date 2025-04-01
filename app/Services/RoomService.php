<?php

namespace App\Services;

use App\Models\Room;

class RoomService
{
    public function getAllRooms()
    {
        return Room::all();
    }
    
    public function getRoomById($id)
    {
        $room = Room::find($id);
        
        if (!$room) {
            throw new \Exception('Phòng không tồn tại');
        }
        
        return $room;
    }
    
    public function createRoom($username, $roomData)
    {
        $roomData['owner_username'] = $username;
        
        return Room::create($roomData);
    }
    
    public function isRoomOwner($roomId, $username)
    {
        $room = Room::find($roomId);
        
        if (!$room) {
            return false;
        }
        
        return $room->owner_username === $username;
    }
    
    /**
     * Xóa phòng - chỉ khi người dùng là chủ phòng
     * @param int $id
     * @param string $username
     * @return bool
     */
    public function deleteRoom($id, $username)
    {
        $room = Room::find($id);
        
        if (!$room) {
            throw new \Exception('Phòng không tồn tại');
        }
        
        if ($room->owner_username !== $username) {
            throw new \Exception('Bạn không có quyền xóa phòng này');
        }
        
        return $room->delete();
    }
    
    public function updateRoomVideo($roomId, $videoData)
    {
        $room = Room::find($roomId);
        
        if (!$room) {
            throw new \Exception('Phòng không tồn tại');
        }
        
        $room->current_video_url = $videoData['current_video_url'];
        $room->current_video_title = $videoData['current_video_title'];
        $room->save();
        
        return $room;
    }
}