<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ShiftController;


Route::prefix('v1')->group(function() {
    // Departments
    Route::get('/departments', [DepartmentController::class, 'index']);
    Route::post('/departments', [DepartmentController::class, 'store']);

    // shifts
    Route::apiResource('shifts', ShiftController::class);

    
    // Positions
    Route::get('/positions', [PositionController::class, 'index']);
    Route::post('/positions', [PositionController::class, 'store']);
    
    // Staff
    Route::get('/staff', [StaffController::class, 'index']);
    Route::post('/staff', [StaffController::class, 'store']);
    Route::put('/staff/{staff}', [StaffController::class, 'update']);
    Route::delete('/staff/{staff}', [StaffController::class, 'destroy']);
    
    // Schedules
    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::post('/schedules', [ScheduleController::class, 'store']);
    Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy']);
    Route::put('/schedules/{schedule}', [ScheduleController::class, 'update']);

});