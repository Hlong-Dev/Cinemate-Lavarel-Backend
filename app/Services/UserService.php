<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function register($userData)
    {
        // Kiểm tra username và email đã tồn tại chưa
        $existingUser = User::where('username', $userData['username'])
            ->orWhere('email', $userData['email'])
            ->first();
            
        if ($existingUser) {
            if ($existingUser->username === $userData['username']) {
                throw new \Exception('Tên đăng nhập đã tồn tại');
            }
            if ($existingUser->email === $userData['email']) {
                throw new \Exception('Email đã tồn tại');
            }
        }
        
        // Mã hóa mật khẩu
        $userData['password'] = Hash::make($userData['password']);
        
        // Tạo user mới
        $user = User::create($userData);
        
        return $user;
    }
    
    public function setDefaultRole($username)
    {
        $user = User::where('username', $username)->first();
        $userRole = Role::where('name', 'ROLE_USER')->first();
        
        if (!$user || !$userRole) {
            throw new \Exception('Không thể gán quyền mặc định');
        }
        
        $user->roles()->attach($userRole->id);
        
        return true;
    }
    
    public function findByUsername($username)
    {
        return User::with('roles')->where('username', $username)->first();
    }
    
    public function lockUserAccount($username)
    {
        $user = User::where('username', $username)->first();
        
        if (!$user) {
            throw new \Exception('Người dùng không tồn tại');
        }
        
        $user->account_non_locked = false;
        $user->save();
        
        return true;
    }
    
    public function unlockUserAccount($username)
    {
        $user = User::where('username', $username)->first();
        
        if (!$user) {
            throw new \Exception('Người dùng không tồn tại');
        }
        
        $user->account_non_locked = true;
        $user->save();
        
        return true;
    }
    
    public function getAllUsers()
    {
        return User::with('roles')->get();
    }
}