<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\HomeController;
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
Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/', [HomeController::class, 'index'])->name('index');
    Route::resource('users', UserController::class);  
    Route::resource('roles', RoleController::class);  
});

// Route::middleware('auth')->prefix('dashboard')->name('dashboard.')->group(function () {
//     Route::get('/', [DashboardController::class, 'index'])->name('index'); 
//     Route::resource('roles', RoleController::class);                    
//     Route::resource('users', UserController::class);                      
// });
