<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\OrderController;
use App\Http\Middleware\JwtMiddleware;


Route::group(['prefix' => 'auth'], function () {
    // Register route
    Route::post('/send-notification', [NotificationController::class, 'sendNotification']);
    
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    // OTP Verification Route (for step 3 of registration process)
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::get('/cities', [LocationController::class, 'getCities']);
    Route::get('/cities/{city_id}/regions', [LocationController::class, 'getRegionsByCity']);
    // Protected routes (requiring JWT token)
    Route::middleware('jwt.auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
  
        Route::get('/me', [AuthController::class, 'getUser']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/getUser', [AuthController::class, 'getUser']);
        Route::post('/profile', [AuthController::class, 'profile']);
        // orders 
        Route::post('/cart', [CartController::class, 'createCart']);
        Route::post('/cart/add/item', [CartController::class, 'addItemToCart']);
        Route::get('/cart', [CartController::class, 'viewCart']);
        Route::post('/order', [OrderController::class, 'createOrder']);
    });
});

Route::get('categories-with-products', [CategoryController::class, 'getCategoriesWithProducts']);
