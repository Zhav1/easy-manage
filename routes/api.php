<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\UserDataController;
use App\Http\Controllers\PrivateScheduleController;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;



Route::middleware('auth:sanctum')->post('/token', function (Request $request) {
    $token = $request->user()->createToken('api-token')->plainTextToken;
    return ['token' => $token];
});

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function() {
    // Departments
    Route::get('/departments', [DepartmentController::class, 'index']) ;
    Route::post('/departments', [DepartmentController::class, 'store']);

    // shifts
    Route::apiResource('shifts', ShiftController::class);
    
    // Positions
    Route::get('/positions', [PositionController::class, 'index']);
    Route::post('/positions', [PositionController::class, 'store']);

    //user data
    Route::get('/user/info', [UserDataController::class, 'index']);

    
    // Staff
    Route::get('/staff', [StaffController::class, 'index']);
    Route::post('/staff', [StaffController::class, 'store']);
    Route::put('/staff/{staff}', [StaffController::class, 'update']);
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy']);
    
    // Staff Schedules
    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::post('/schedules', [ScheduleController::class, 'store']);
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy']);
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update']);

    //Private Schedules
    Route::get('/private-schedules', [PrivateScheduleController::class, 'index']);
    Route::post('/private-schedules', [PrivateScheduleController::class, 'store']);
    Route::get('/private-schedules/{id}', [PrivateScheduleController::class, 'show']);
    Route::put('/private-schedules/{id}', [PrivateScheduleController::class, 'update']);
    Route::delete('/private-schedules/{id}', [PrivateScheduleController::class, 'destroy']);

});