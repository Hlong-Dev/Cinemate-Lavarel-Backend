<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;
    
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    /**
     * Đăng ký người dùng mới
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        try {
            // Lấy dữ liệu từ request
            $userData = $request->only([
                'username', 'password', 'email', 'phone', 'address', 'avtUrl'
            ]);
            
            // Kiểm tra thông tin cần thiết
            if (!isset($userData['username']) || !isset($userData['password']) || !isset($userData['email'])) {
                return response()->json(['message' => 'Vui lòng nhập đầy đủ thông tin bắt buộc'], 400);
            }
            
            // Đăng ký người dùng
            $user = $this->userService->register($userData);
            
            // Gán quyền mặc định
            $this->userService->setDefaultRole($userData['username']);
            
            return response()->json(['message' => 'Đăng ký thành công'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
    
    /**
     * Lấy thông tin người dùng hiện tại
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentUser(Request $request)
    {
        try {
            // User ID được đính kèm bởi middleware xác thực
            if (!$request->has('userId')) {
                return response()->json([]);
            }
            
            $user = $this->userService->findByUsername($request->input('username'));
            
            if (!$user) {
                return response()->json(['message' => 'Người dùng không tồn tại'], 404);
            }
            
            // Loại bỏ mật khẩu
            $userArray = $user->toArray();
            unset($userArray['password']);
            
            return response()->json($userArray, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Khóa tài khoản người dùng
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function lockUserAccount(Request $request)
    {
        try {
            $username = $request->input('username');
            
            if (!$username) {
                return response()->json(['message' => 'Vui lòng cung cấp tên đăng nhập'], 400);
            }
            
            $this->userService->lockUserAccount($username);
            
            return response()->json(['message' => 'Khóa tài khoản thành công'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Mở khóa tài khoản người dùng
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlockUserAccount(Request $request)
    {
        try {
            $username = $request->input('username');
            
            if (!$username) {
                return response()->json(['message' => 'Vui lòng cung cấp tên đăng nhập'], 400);
            }
            
            $this->userService->unlockUserAccount($username);
            
            return response()->json(['message' => 'Mở khóa tài khoản thành công'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
    
    /**
     * Lấy danh sách tất cả người dùng
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUsers()
    {
        try {
            $users = $this->userService->getAllUsers();
            
            // Loại bỏ mật khẩu từ kết quả
            $users = $users->map(function ($user) {
                $userArray = $user->toArray();
                unset($userArray['password']);
                return $userArray;
            });
            
            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}