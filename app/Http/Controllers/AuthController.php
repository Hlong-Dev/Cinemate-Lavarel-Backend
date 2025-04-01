<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
class AuthController extends Controller
{
    protected $authService;
    protected $userService;
    
    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }
    
    /**
     * Xử lý đăng nhập
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        try {
            $username = $request->input('username');
            $password = $request->input('password');
            
            if (!$username || !$password) {
                return response()->json(['message' => 'Vui lòng nhập tên đăng nhập và mật khẩu'], 400);
            }
            
            $result = $this->authService->login($username, $password);
            
            return response()->json($result, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 401);
        }
    }
    
    /**
     * Chuyển hướng đến trang đăng nhập Google
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    
    /**
     * Xử lý callback từ Google OAuth
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function googleCallback(Request $request)
    {
        try {
            // Socialite không hỗ trợ stateless trong một số phiên bản, nên bỏ phương thức này
            $googleUser = Socialite::driver('google')->user();
            
            // Xử lý đăng nhập/đăng ký bằng Google
            $user = $this->handleGoogleUser($googleUser);
            
            if (!$user) {
                return redirect(env('CLIENT_URL') . '/login?error=auth_failed');
            }
            
            // Tạo token
            $token = $this->authService->createTokenForUser($user);
            
            // Chuyển hướng về trang chủ với token
            return redirect(env('CLIENT_URL') . "/auth/callback?token={$token}");
        } catch (\Exception $e) {
            return redirect(env('CLIENT_URL') . '/login?error=auth_failed');
        }
    }
    
    /**
     * Xử lý thông tin người dùng Google
     * @param $googleUser
     * @return User
     */
    private function handleGoogleUser($googleUser)
    {
        // Triển khai logic xử lý thông tin từ Google OAuth
        $email = $googleUser->getEmail();
        $providerId = $googleUser->getId();
        
        // Tìm user theo providerId
        $user = User::where('provider', 'google')
                    ->where('provider_id', $providerId)
                    ->first();
                    
        if (!$user) {
            // Kiểm tra email đã đăng ký chưa
            $existingUser = User::where('email', $email)->first();
            
            if ($existingUser) {
                // Liên kết tài khoản hiện có với Google
                $existingUser->provider = 'google';
                $existingUser->provider_id = $providerId;
                $existingUser->avt_url = $googleUser->getAvatar() ?? 'https://i.imgur.com/Tr9qnkI.jpeg';
                $existingUser->save();
                
                return $existingUser;
            } else {
                // Tạo tài khoản mới - sử dụng Str::random() thay vì str_random()
                $user = User::create([
                    'username' => 'google_' . $providerId,
                    'password' => bcrypt(Str::random(16)),
                    'email' => $email,
                    'provider' => 'google',
                    'provider_id' => $providerId,
                    'avt_url' => $googleUser->getAvatar() ?? 'https://i.imgur.com/Tr9qnkI.jpeg'
                ]);
                
                // Gán role USER cho tài khoản mới
                $this->userService->setDefaultRole($user->username);
                
                return $user;
            }
        }
        
        // Kiểm tra tài khoản bị khóa
        if (!$user->account_non_locked) {
            return null;
        }
        
        return $user;
    }
}