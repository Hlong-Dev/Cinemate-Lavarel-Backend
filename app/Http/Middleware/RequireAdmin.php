<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequireAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Vui lòng đăng nhập để tiếp tục'], 401);
        }
        
        $user = Auth::user();
        
        // Thay đổi cách kiểm tra quyền admin
        if (!$user->roles || !$user->roles->contains('name', 'ROLE_ADMIN')) {
            return response()->json(['message' => 'Bạn không có quyền thực hiện hành động này'], 403);
        }
        
        return $next($request);
    }
}