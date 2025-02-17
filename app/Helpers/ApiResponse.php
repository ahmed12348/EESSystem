<?php

namespace App\Helpers;

class ApiResponse
{

    public static function success($message, $data = [], $statusCode = 200)
    {
        return response()->json([
            'status' => true, // ✅ Changed from "success" to true
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }


    public static function error($message, $errors = [], $statusCode = 400)
    {
        return response()->json([
            'status' => false, // ✅ Changed from "error" to false
            'message' => $message,
            'data' => $errors
        ], $statusCode);
    }
}
