<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\BotController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\ContentConfigController;
use App\Http\Controllers\Api\Admin\UserController;

Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('profile', [AuthController::class, 'getProfile']);
    });
});
Route::middleware('auth:admin')->group(function () {
});
Route::post('/config', [ContentConfigController::class, 'store'])->name('config.store');
Route::get('/list', [ContentConfigController::class, 'list']);
Route::get('/users', [UserController::class, 'list']);
Route::post('/send', [BotController::class, 'send']);