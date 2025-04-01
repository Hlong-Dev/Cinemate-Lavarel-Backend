<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoService
{
    protected $videoDirectory;
    protected $thumbnailDirectory;

    public function __construct()
    {
        // Sử dụng đường dẫn từ environment hoặc config
        $this->videoDirectory = env('VIDEO_DIRECTORY', storage_path('videos'));
        $this->thumbnailDirectory = env('THUMBNAIL_DIRECTORY', storage_path('thumbnails'));
    }

    public function getVideoList()
    {
        // Kiểm tra thư mục tồn tại
        if (!File::exists($this->videoDirectory)) {
            File::makeDirectory($this->videoDirectory, 0755, true);
            return [];
        }

        // Lấy danh sách video từ thư mục vật lý
        $files = File::files($this->videoDirectory);
        $videos = [];
        
        foreach ($files as $file) {
            if ($file->getExtension() === 'mp4') {
                $fileName = $file->getFilename();
                $thumbnailPath = $this->generateThumbnailPath($fileName);
                $videoDuration = $this->getVideoDuration($fileName);

                $videos[] = [
                    'title' => $fileName,
                    'thumbnail' => $thumbnailPath,
                    'duration' => $videoDuration,
                    'url' => url('video/stream/' . urlencode($fileName)),
                ];
            }
        }
        
        return $videos;
    }
    
    public function streamVideo($fileName, $request)
    {
        $filePath = $this->videoDirectory . '/' . $fileName;
        
        if (!File::exists($filePath)) {
            return response()->json(['message' => 'Video không tồn tại'], 404);
        }
        
        $fileSize = File::size($filePath);
        
        // Hỗ trợ streaming với Range header
        $range = $request->header('Range');
        
        if ($range) {
            // Xử lý range header
            preg_match('/bytes=(\d+)-(\d*)/', $range, $matches);
            $start = intval($matches[1]);
            $end = isset($matches[2]) && $matches[2] !== '' 
                ? intval($matches[2]) 
                : $fileSize - 1;
            
            $chunkSize = ($end - $start) + 1;
            
            $response = new StreamedResponse(function() use ($filePath, $start, $end) {
                $handle = fopen($filePath, 'rb');
                fseek($handle, $start);
                $remainingBytes = $end - $start + 1;
                
                while ($remainingBytes > 0) {
                    $readSize = min(1024 * 1024, $remainingBytes);
                    echo fread($handle, $readSize);
                    $remainingBytes -= $readSize;
                }
                
                fclose($handle);
            }, 206);
            
            // Sử dụng headers->set() thay vì header()
            $response->headers->set('Content-Type', 'video/mp4');
            $response->headers->set('Content-Range', "bytes {$start}-{$end}/{$fileSize}");
            $response->headers->set('Content-Length', $chunkSize);
            $response->headers->set('Accept-Ranges', 'bytes');
        } else {
            // Trường hợp không có range, gửi toàn bộ file
            $response = new StreamedResponse(function() use ($filePath) {
                readfile($filePath);
            });
            
            // Sử dụng headers->set() thay vì header()
            $response->headers->set('Content-Type', 'video/mp4');
            $response->headers->set('Content-Length', $fileSize);
            $response->headers->set('Accept-Ranges', 'bytes');
        }
        
        return $response;
    }
    public function getThumbnailPath($videoFileName)
    {
        // Sử dụng đường dẫn từ environment
        $thumbnailDirectory = env('THUMBNAIL_DIRECTORY', storage_path('thumbnails'));
        
        // Tạo tên file thumbnail
        $thumbnailFileName = str_replace('.mp4', '.jpg', $videoFileName);
        $thumbnailPath = $thumbnailDirectory . '/' . $thumbnailFileName;
        
        // Kiểm tra file tồn tại
        if (!File::exists($thumbnailPath)) {
            // Nếu không có, trả về thumbnail mặc định hoặc báo lỗi
            return null; // hoặc đường dẫn đến ảnh mặc định
        }
        
        return $thumbnailPath;
    }
    /**
     * Tạo đường dẫn thumbnail cho video
     */
    protected function generateThumbnailPath($videoFileName)
    {
        // Tạo thư mục thumbnail nếu chưa tồn tại
        if (!File::exists($this->thumbnailDirectory)) {
            File::makeDirectory($this->thumbnailDirectory, 0755, true);
        }

        $thumbnailFileName = str_replace('.mp4', '.jpg', $videoFileName);
        $thumbnailPath = $this->thumbnailDirectory . '/' . $thumbnailFileName;
        
        // Kiểm tra và tạo thumbnail nếu chưa tồn tại
        if (!File::exists($thumbnailPath)) {
            $this->generateThumbnail(
                $this->videoDirectory . '/' . $videoFileName, 
                $thumbnailPath
            );
        }
        
        return url('thumbnails/' . urlencode($thumbnailFileName));
    }

    /**
     * Tạo thumbnail bằng FFmpeg
     */
    protected function generateThumbnail($videoPath, $thumbnailPath)
    {
        $ffmpegPath = env('FFMPEG_PATH', 'ffmpeg');
        
        // Kiểm tra file video tồn tại
        if (!File::exists($videoPath)) {
            return false;
        }

        // Thực thi FFmpeg để tạo thumbnail
        $command = sprintf(
            '%s -i %s -ss 00:00:05 -vframes 1 %s',
            escapeshellarg($ffmpegPath),
            escapeshellarg($videoPath),
            escapeshellarg($thumbnailPath)
        );

        exec($command, $output, $returnVar);
        
        return $returnVar === 0;
    }

    /**
     * Lấy thời lượng video bằng FFmpeg
     */
    protected function getVideoDuration($videoFileName)
    {
        $ffmpegPath = env('FFMPEG_PATH', 'ffmpeg');
        $videoPath = $this->videoDirectory . '/' . $videoFileName;
        
        // Kiểm tra file video tồn tại
        if (!File::exists($videoPath)) {
            return '00:00';
        }

        // Sử dụng FFprobe để lấy thời lượng
        $command = sprintf(
            '%s -i %s -show_entries format=duration -v quiet -of csv=p=0',
            str_replace('ffmpeg', 'ffprobe', escapeshellarg($ffmpegPath)),
            escapeshellarg($videoPath)
        );

        exec($command, $output, $returnVar);
        
        if ($returnVar === 0 && isset($output[0])) {
            $durationSeconds = floatval($output[0]);
            $hours = floor($durationSeconds / 3600);
            $minutes = floor(($durationSeconds % 3600) / 60);
            $seconds = floor($durationSeconds % 60);
            
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }
        
        return '00:00';
    }
}