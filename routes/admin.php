<?php
use App\Http\Controllers\Api\Admin\Customer\AuthController;
use App\Http\Controllers\Api\Admin\BotController;
use App\Http\Controllers\Api\Admin\CloneDataController;
use App\Http\Controllers\Api\Admin\ScheduleConfigController;
use App\Http\Controllers\Api\Admin\ScheduleGroupConfigController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\ContentConfigController;
use App\Http\Controllers\Api\Admin\GroupController;
use App\Http\Controllers\Api\Admin\PasswordController;
use App\Http\Controllers\Api\Admin\PhoneNumberController;
use App\Http\Controllers\Api\Admin\ScheduleDeleteMessageController;
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
    Route::prefix('schedule')->group(function () {
        Route::post('/', [ScheduleConfigController::class, 'store']);
        Route::post('/{id}', [ScheduleConfigController::class, 'update']);
    });
    Route::prefix('schedule-group')->group(function () {
        Route::post('/', [ScheduleGroupConfigController::class, 'store']);
        Route::post('/{id}', [ScheduleGroupConfigController::class, 'update']);
    });

    Route::prefix('schedule-delete')->group(function () {
        Route::post('/', [ScheduleDeleteMessageController::class, 'store']);
        Route::post('/{id}', [ScheduleDeleteMessageController::class, 'update']);
    });

    Route::post('/config', [ContentConfigController::class, 'store'])->name('config.store');
    Route::get('/list', [ContentConfigController::class, 'list']);
    Route::delete('/delete/{id}', [ContentConfigController::class, 'delete']);
    Route::post('/update/{id}', [ContentConfigController::class, 'update']);
    Route::get('/detail/{id}', [ContentConfigController::class, 'detail']);
    Route::post('/set-default/{id}', [ContentConfigController::class, 'setDefault']);

    Route::prefix('group')->group(function () {
        Route::get('/', [GroupController::class, 'list']);
        Route::get('/{id}', [GroupController::class, 'detail']);
        Route::post('/', [GroupController::class, 'create']);
        Route::put('/{id}', [GroupController::class, 'update']);
        Route::delete('/{id}', [GroupController::class, 'delete']);
    });

    Route::get('/users', [UserController::class, 'list']);
    Route::post('/send', [BotController::class, 'send']);

    Route::prefix('bot')->group(function () {
        Route::get('/', [BotController::class, 'list']);
        Route::get('/{id}', [BotController::class, 'detail']);
        Route::post('/{id}', [BotController::class, 'activeBot']);
        Route::post('/', [BotController::class, 'saveBot']);
        Route::delete('/{id}', [BotController::class, 'delete']);
    });

    Route::prefix('phone')->group(function () {
        Route::post('/', [PhoneNumberController::class, 'save']);
        Route::get('/', [PhoneNumberController::class, 'get']);
        Route::delete('/batch', [PhoneNumberController::class, 'deleteSelected']);
        Route::delete('/{id}', [PhoneNumberController::class, 'delete']);
    });

    Route::prefix('password')->group(function () {
        Route::get('/', [PasswordController::class, 'get']);
    });
    Route::prefix('clone')->group(function () {
        Route::post('/', [CloneDataController::class, 'clone']);
        Route::get('/', [CloneDataController::class, 'getData']);
    });
});
