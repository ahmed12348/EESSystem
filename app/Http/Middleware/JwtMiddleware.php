<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        
        try {
            return   $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return response()->json([
                'statusCode' => 401,
                'error' => 'انتهت صلاحية الرمز المميز، الرجاء تسجيل الدخول مرة أخرى.'
            ], 401);
        }

        return $next($request);
    }
}