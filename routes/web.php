<?php

use App\Http\Controllers\Admin\ADSController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\HomeController;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Vendor\ProductController as VendorProductController;
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
        Route::get('ads/getReferences', [ADSController::class, 'getReferences'])->name('ads.getReferences');
       // Protected Routes (Only Authenticated Admins Can Access)
        Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/', [HomeController::class, 'index'])->name('index');
        Route::resource('users', UserController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('vendors', VendorController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::patch('/products/{id}/approve', [ProductController::class, 'approve'])->name('products.approve');
        Route::patch('/products/{id}/reject', [ProductController::class, 'reject'])->name('products.reject'); 
        Route::post('/import-products', [ProductController::class, 'import'])->name('products.import');
        Route::get('products/export/sample', [ProductController::class, 'exportSample'])->name('products.export.sample');

        Route::patch('/vendors/{id}/approve', [VendorController::class, 'approve'])->name('vendors.approve');
        Route::patch('/vendors/{id}/reject', [VendorController::class, 'reject'])->name('vendors.reject');
        Route::patch('/users/{id}/approve', [UserController::class, 'approve'])->name('users.approve');
        Route::patch('/users/{id}/reject', [UserController::class, 'reject'])->name('users.reject');

        Route::get('/profile', [UserController::class, 'showProfile'])->name('profile.show');
        Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');

        Route::resource('ads', ADSController::class);
        // Route::get('ads/getReferences', [ADSController::class, 'getReferences'])->name('ads.getReferences');

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
    Route::middleware(['auth', 'role:vendor'])->group(function () {
        Route::get('/vendor_dashboard', [HomeController::class, 'vendor_index'])->name('vendor_index');
        Route::get('/profile', [HomeController::class, 'showProfile'])->name('profile.show');
        Route::put('/profile/update', [HomeController::class, 'updateProfile'])->name('profile.update');
        Route::resource('products', VendorProductController::class);
        Route::post('/import-products', [VendorProductController::class, 'import'])->name('products.import');
        Route::get('products/export/sample', [VendorProductController::class, 'exportSample'])->name('products.export.sample');

    });
});

