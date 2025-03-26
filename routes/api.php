<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\VideoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/google', [AuthController::class, 'redirectToGoogle']);
    Route::get('/google/callback', [AuthController::class, 'googleCallback']);
    Route::get('/verify', [AuthController::class, 'verifyToken']);
});

// User routes
Route::post('/register', [UserController::class, 'register']);
Route::get('/user', [UserController::class, 'getCurrentUser']);

// Admin routes (removed auth requirement)
Route::post('/user/lock', [UserController::class, 'lockUserAccount']);
Route::post('/user/unlock', [UserController::class, 'unlockUserAccount']);
Route::get('/users', [UserController::class, 'getAllUsers']);

// Room routes (all public now)
Route::prefix('rooms')->group(function () {
    Route::get('/', [RoomController::class, 'getAllRooms']);
    Route::get('/{id}', [RoomController::class, 'getRoomById']);
    Route::post('/', [RoomController::class, 'createRoom']);
    Route::delete('/{id}', [RoomController::class, 'deleteRoom']);
    Route::put('/{roomId}/video', [RoomController::class, 'updateRoomVideo']);
});

// Video routes
Route::prefix('video')->group(function () {
    Route::get('/list', [VideoController::class, 'getVideoList']);
    Route::get('/play/{fileName}', [VideoController::class, 'streamVideo']);
    Route::get('/thumbnail/{fileName}', [VideoController::class, 'getThumbnail']); // Thêm route này // Thay đổi thành /video/play
});
// Thay đổi cho commit #19: Add video recording functionality
// Ngày: 2025-02-22
