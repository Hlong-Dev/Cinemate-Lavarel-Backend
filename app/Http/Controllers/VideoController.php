<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\VideoService;

class VideoController extends Controller
{
    protected $videoService;
    
    public function __construct(VideoService $videoService)
    {
        $this->videoService = $videoService;
    }
    
    /**
     * Lấy danh sách video
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVideoList()
    {
        try {
            $videos = $this->videoService->getVideoList();
            
            return response()->json($videos, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Phát video với hỗ trợ streaming
     * @param string $fileName
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\JsonResponse
     */
    public function streamVideo($fileName, Request $request)
    {
        try {
            if (!$fileName) {
                return response()->json(['message' => 'Tên file không hợp lệ'], 400);
            }
            
            return $this->videoService->streamVideo($fileName, $request);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Lỗi khi phát video'], 500);
        }
    }
    public function getThumbnail($fileName)
{
    try {
        $thumbnailPath = $this->videoService->getThumbnailPath($fileName);
        
        if (!$thumbnailPath) {
            return response()->json(['message' => 'Thumbnail không tồn tại'], 404);
        }
        
        return response()->file($thumbnailPath);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Lỗi khi lấy thumbnail: ' . $e->getMessage()], 500);
    }
}
}
// Thay đổi cho commit #7: Create chat message model and controllers
// Ngày: 2025-03-24

// Thay đổi cho commit #10: Implement user authorization with roles
// Ngày: 2025-03-17

// Thay đổi cho commit #20: Implement WebRTC support
// Ngày: 2025-02-20
