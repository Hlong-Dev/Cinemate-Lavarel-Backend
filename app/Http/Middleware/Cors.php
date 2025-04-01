<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Cors
{
    public function handle(Request $request, Closure $next)
    {
        $allowedOrigins = [
            'http://localhost:3000', 
            'https://cinemate-lavarel.vercel.app',
            'https://cinemate-watch.vercel.app',
            'https://www.cinemate.website',
            'https://cinemate.website', // Loại bỏ dấu '/' ở cuối
        ];

        // Xử lý preflight request
        if ($request->isMethod('OPTIONS')) {
            return response('', 200, [
                'Access-Control-Allow-Origin' => $request->header('Origin'),
                'Access-Control-Allow-Methods' => 'GET, POST, PUT, DELETE, OPTIONS',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Requested-With',
                'Access-Control-Allow-Credentials' => 'true'
            ]);
        }

        // Xử lý response
        $response = $next($request);

        // Kiểm tra origin từ request
        $origin = $request->header('Origin');
        
        // Nếu origin được phép
        if (in_array($origin, $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
        }

        $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        $response->headers->set('Access-Control-Allow-Credentials', 'true');

        return $response;
    }
}