<?php

use App\Http\Controllers\Admin\ADSController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CartController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DiscountController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SearchController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Vendor\ProductController as VendorProductController;
use App\Http\Controllers\Vendor\AuthController;
use App\Http\Controllers\Vendor\OrderController as VendorOrderController;
use App\Http\Controllers\Vendor\DiscountController as VendorDiscountController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'ar'])) {
        Session::put('locale', $locale);
    }
    return redirect()->back();
})->name('switchLang');

//  Apply localization to all routes using middleware
Route::middleware(['localization'])->group(function () {

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminAuthController::class, 'login']);
        Route::get('ads/getReferences', [ADSController::class, 'getReferences'])->name('ads.getReferences');

        //  Protected Admin Routes (Only Authenticated Admins)
        Route::middleware(['auth', 'check_type:admin'])->group(function () {
            Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
            Route::get('/', [HomeController::class, 'index'])->name('index');
            Route::get('/dashboard', [HomeController::class, 'index'])->name('export_admin');
            Route::resource('users', UserController::class);
            Route::resource('roles', RoleController::class);
            Route::resource('vendors', VendorController::class);
            Route::resource('categories', CategoryController::class);
            Route::resource('products', ProductController::class);
            Route::resource('customers', CustomerController::class);
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
            Route::patch('/ads/{id}/approve', [ADSController::class, 'approve'])->name('ads.approve');
            Route::patch('/ads/{id}/reject', [ADSController::class, 'reject'])->name('ads.reject');
            
            
            Route::resource('carts', CartController::class);
            Route::get('cart-settings', [SettingController::class, 'index'])->name('settings.cart');
            Route::post('cart-settings/update', [SettingController::class, 'update'])->name('settings.cart.update');

            Route::resource('orders', OrderController::class);
            Route::resource('discounts', DiscountController::class);
            Route::get('/cart-items', [CartController::class, 'index'])->name('cart.index');
            Route::post('/cart-items/read', [CartController::class, 'readdExpiredItems'])->name('cart.readd');

            Route::get('/searches', [SearchController::class, 'index'])->name('search.index');
            Route::get('/export', [HomeController::class, 'export_admin'])->name('export_admin');

            Route::get('/vendors/{id}/reviews', [VendorController::class, 'vendorReviews'])->name('vendors.reviews');

        });
    });
    Route::get('/get-regions', [CustomerController::class, 'getRegionsByCity'])->name('getRegionsByCity');
    // Route::get('/getZonesByRegion', [ZoneController::class, 'getZonesByRegion'])->name('getZonesByRegion');

    

    
    Route::prefix('vendor')->name('vendor.')->group(function () {
        //  Registration & Authentication
        Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [AuthController::class, 'register']);
        Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        // OTP Verification
        Route::get('otp-form', [AuthController::class, 'showOtpForm'])->name('otp.form');
        Route::post('otp-verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
        Route::get('/resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');

        
        Route::middleware(['auth', 'check_type:vendor'])->group(function () {
            Route::get('/dashboard', [HomeController::class, 'vendor_index'])->name('vendor_index');
            Route::get('/profile', [HomeController::class, 'showProfile'])->name('profile.show');
            Route::put('/profile/update', [HomeController::class, 'updateProfile'])->name('profile.update');
            Route::resource('products', VendorProductController::class);
            Route::post('/import-products', [VendorProductController::class, 'import'])->name('products.import');
            Route::get('products/export/sample', [VendorProductController::class, 'exportSample'])->name('products.export.sample');
            Route::resource('orders', VendorOrderController::class)->only(['index', 'show', 'edit', 'update']);
            Route::resource('discounts', VendorDiscountController::class);
            Route::get('/export', [HomeController::class, 'export'])->name('export');
        });
    });
});

require __DIR__.'/auth.php';
