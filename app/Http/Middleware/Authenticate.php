<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');
    }
}

// Thay đổi cho commit #1: Initial commit
// Ngày: 2025-04-08

// Thay đổi cho commit #2: Set up basic Laravel project structure
// Ngày: 2025-04-06

// Thay đổi cho commit #5: Add Room model and controller
// Ngày: 2025-03-29

// Thay đổi cho commit #19: Add video recording functionality
// Ngày: 2025-02-22
