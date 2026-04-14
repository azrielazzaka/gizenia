<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\DistributionController;
use App\Http\Controllers\Api\UserDashboardController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\UserMenuController;

// --- AUTHENTICATION ROUTES ---
Route::group(['prefix' => 'auth'], function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    

    // Endpoint yang butuh Token JWT
    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});

// --- GENERAL DATA ROUTES (Butuh Token JWT) ---
Route::middleware('auth:api')->group(function () {
    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']); 
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);
    Route::get('dashboard-stats', [AdminDashboardController::class, 'index']);
    // Rute Distribusi
    Route::get('distributions', [DistributionController::class, 'index']);
    Route::post('distributions', [DistributionController::class, 'store']);
    Route::put('distributions/{id}', [DistributionController::class, 'update']); 
    Route::delete('distributions/{id}', [DistributionController::class, 'destroy']);
    Route::get('reports', [ReportController::class, 'index']);
    
    // Rute CRUD Menu Makanan
    Route::get('menus', [MenuController::class, 'index']);
    Route::post('menus', [MenuController::class, 'store']);
    Route::post('menus/import', [MenuController::class, 'importCsv']);
    Route::put('menus/{id}', [MenuController::class, 'update']);
    Route::delete('menus/{id}', [MenuController::class, 'destroy']);

});

// --- ADMIN ROUTES ---
Route::middleware(['auth:api', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('dashboard', function () {
        return response()->json(['message' => 'Selamat datang di Dashboard Admin!']);
    });
});

// --- USER ROUTES ---
Route::middleware(['auth:api', 'role:user'])->prefix('user')->group(function () {
    
    // Rute untuk fitur Notifikasi dan Riwayat Distribusi Makanan
    Route::get('notification', [UserDashboardController::class, 'checkNotification']);
    Route::post('distributions/{id}/respond', [UserDashboardController::class, 'submitResponse']);
    Route::get('history', [UserDashboardController::class, 'history']);
    Route::get('menus', [UserMenuController::class, 'index']);
    
});