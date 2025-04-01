<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            if (!$user->account_non_locked) {
                return response()->json(['message' => 'Tài khoản đã bị khóa'], 401);
            }
            
            // Thêm thông tin người dùng vào request
            $request->merge([
                'userId' => $user->id,
                'username' => $user->username,
                'avtUrl' => $user->avt_url,
                'roles' => $user->roles->pluck('name')->toArray(),
            ]);
            
            return $next($request);
        }

        // Cho phép tiếp tục mà không có thông tin người dùng
        return $next($request);
    }
}