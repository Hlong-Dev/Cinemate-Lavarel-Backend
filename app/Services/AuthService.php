<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService
{
    public function login($username, $password)
    {
        $user = User::where('username', $username)->first();
        
        if (!$user || !Hash::check($password, $user->password)) {
            throw new \Exception('Tên đăng nhập hoặc mật khẩu không đúng');
        }
        
        if (!$user->account_non_locked) {
            throw new \Exception('Tài khoản đã bị khóa');
        }
        
        // Tạo token
        $token = $this->createTokenForUser($user);
        
        // Định dạng dữ liệu user trả về
        $userData = $user->toArray();
        unset($userData['password']);
        
        // Thêm roles vào user data
        $userData['roles'] = $user->roles->pluck('name')->toArray();
        
        return [
            'token' => $token
        ];
    }
    
    public function createTokenForUser($user)
    {
        // Tạo payload với các thông tin cần thiết
        $payload = [
            'sub' => $user->username,
            'id' => $user->id,
            'avt_url' => $user->avt_url,
            'iat' => time(),
            'exp' => time() + (60 * 60 * 24), // Token hết hạn sau 24 giờ
        ];
        
        // Sử dụng cú pháp mới của Firebase JWT
        return JWT::encode($payload, env('JWT_SECRET'), 'HS256');
    }
    
    public function verifyToken($token)
    {
        try {
            // Sử dụng cú pháp mới của Firebase JWT
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
            
            $user = User::where('username', $decoded->sub)->first();
            
            if (!$user) {
                throw new \Exception('Người dùng không tồn tại');
            }
            
            if (!$user->account_non_locked) {
                throw new \Exception('Tài khoản đã bị khóa');
            }
            
            return $decoded;
        } catch (\Exception $e) {
            throw new \Exception('Token không hợp lệ: ' . $e->getMessage());
        }
    }
}
// Thay đổi cho commit #16: Create chat service implementation
// Ngày: 2025-03-02
