<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RoomService;

class RoomController extends Controller
{
    protected $roomService;
    
    public function __construct(RoomService $roomService)
    {
        $this->roomService = $roomService;
    }
    
    /**
     * Lấy tất cả các phòng
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllRooms()
    {
        try {
            $rooms = $this->roomService->getAllRooms();
            
            return response()->json($rooms, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Lấy phòng theo ID
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRoomById($id)
    {
        try {
            if (!$id) {
                return response()->json(['message' => 'ID phòng không hợp lệ'], 400);
            }
            
            $room = $this->roomService->getRoomById($id);
            
            return response()->json($room, 200);
        } catch (\Exception $e) {
            if ($e->getMessage() === 'Phòng không tồn tại') {
                return response()->json(['message' => $e->getMessage()], 404);
            }
            
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Tạo phòng mới
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createRoom(Request $request)
    {
        try {
            $username = $request->input('username');
            
            if (!$username) {
                return response()->json(['message' => 'Thiếu thông tin username'], 400);
            }
            
            $roomData = $request->only([
                'name', 'thumbnail', 'current_video_url', 'current_video_title'
            ]);
            
            $room = $this->roomService->createRoom($username, $roomData);
            
            return response()->json($room, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Xóa phòng
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteRoom($id, Request $request)
    {
        try {
            $username = $request->input('username');
            
            if (!$id) {
                return response()->json(['message' => 'ID phòng không hợp lệ'], 400);
            }
            
            if (!$username) {
                return response()->json(['message' => 'Thiếu thông tin username'], 400);
            }
            
            // Kiểm tra xem người dùng có phải là chủ phòng không
            $isOwner = $this->roomService->isRoomOwner($id, $username);
            
            if (!$isOwner) {
                // Nếu không phải chủ phòng, không xóa phòng, chỉ trả về thành công
                return response()->json(['message' => 'Đã rời phòng thành công'], 200);
            }
            
            // Nếu là chủ phòng, thực hiện xóa phòng
            $this->roomService->deleteRoom($id, $username);
            
            return response()->json(['message' => 'Xóa phòng thành công'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Cập nhật video trong phòng
     * @param int $roomId
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRoomVideo($roomId, Request $request)
    {
        try {
            $videoData = $request->only([
                'current_video_url', 'current_video_title'
            ]);
            
            // Đặt tiêu đề mặc định nếu không có
            if (!isset($videoData['current_video_title'])) {
                $videoData['current_video_title'] = 'Video không tiêu đề';
            }
            
            $this->roomService->updateRoomVideo($roomId, $videoData);
            
            return response()->json(['message' => 'Cập nhật video thành công'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
// Thay đổi cho commit #14: Set up user service and repository pattern
// Ngày: 2025-03-07
