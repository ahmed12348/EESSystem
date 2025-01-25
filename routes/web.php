<?php

use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Vendor\Auth\VendorLoginController;
use App\Http\Controllers\Vendor\Auth\VendorRegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Vendor\Auth\VendorOtpController;
use App\Http\Controllers\Vendor\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';


// Route::get('/', function () {
//     return view('welcome');
// });


Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login']);

       // Protected Routes (Only Authenticated Admins Can Access)
        Route::middleware('auth')->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', [HomeController::class, 'index'])->name('index');
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('vendors', VendorController::class);
    
        Route::patch('/vendors/{id}/approve', [VendorController::class, 'approve'])->name('vendors.approve');
        Route::patch('/vendors/{id}/reject', [VendorController::class, 'reject'])->name('vendors.reject');
        Route::patch('/users/{id}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::patch('/users/{id}/reject', [UserController::class, 'reject'])->name('users.reject');

        Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
        Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    });

});



Route::prefix('vendor')->name('vendor.')->group(function () {

    // Registration Routes
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

    // Login Routes
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // OTP Verification (✅ Move outside auth middleware)
    Route::get('otp-form', [AuthController::class, 'showOtpForm'])->name('otp.form');
    Route::post('otp-verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
    Route::get('/vendor/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');

    // ✅ Protect this part with auth middleware
    Route::middleware(['auth'])->group(function () {
        Route::get('/vendor_dashboard', [HomeController::class, 'vendor_index'])->name('vendor_index');
    });




    // Route::middleware('auth')->group(function () {
    //     Route::get('/dashboard', function () {
    //         return view('vendor.dashboard');
    //     })->name('dashboard');
    // });
});
