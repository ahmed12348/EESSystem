<?php

namespace App\Helpers;

class ApiResponse
{
    
    public static function success($message, $data = [], $statusCode = 200)
    {
        return response()->json([
            'statusCode' => $statusCode,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

 
    public static function error($message, $statusCode = 400, $errors = [])
    {
        return response()->json([
            'statusCode' => $statusCode,
            'message' => $message,
            'errors' => $errors,
        ], $statusCode);
    }
}
