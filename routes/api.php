<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Middleware\JwtMiddleware;


Route::group(['prefix' => 'auth'], function () {
    // Register route
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    // OTP Verification Route (for step 3 of registration process)
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
   
    // Protected routes (requiring JWT token)
    Route::middleware('jwt.auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
  
        Route::get('/me', [AuthController::class, 'getUser']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/getUser', [AuthController::class, 'getUser']);
        Route::post('/profile', [AuthController::class, 'profile']);
        Route::get('/cities', [LocationController::class, 'getCities']);
        Route::get('/cities/{city_id}/regions', [LocationController::class, 'getRegionsByCity']);
    });
});

// Route::middleware('jwt.auth')->group(function () {
    
//     Route::get('/cities', [LocationController::class, 'getCities']);
//     Route::get('/cities/{city_id}/regions', [LocationController::class, 'getRegionsByCity']);

// });