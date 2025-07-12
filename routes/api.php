    <?php

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\DepartmentController;
    use App\Http\Controllers\DashboardController;
    use App\Http\Controllers\PositionController;
    use App\Http\Controllers\StaffController;
    use App\Http\Controllers\ScheduleController;
    use App\Http\Controllers\ShiftController;
    use App\Http\Controllers\UserDataController;
    use App\Http\Controllers\PrivateScheduleController;
    use App\Http\Controllers\ReportController;
    use App\Http\Controllers\QualityInspectionController;
    use App\Http\Controllers\PerformanceEvaluationController;
    use App\Http\Controllers\TrainingNeedController;
    use App\Http\Controllers\CvcMonitoringController;
    use App\Http\Controllers\NotificationController;
    use App\Http\Controllers\LogisticController;


    Route::middleware('auth:sanctum')->post('/token', function (Request $request) {
        $token = $request->user()->createToken('api-token')->plainTextToken;
        return ['token' => $token];
    });

    Route::get('/logistics/items', function (Request $request) {
            $category = $request->query('category');
            
            if (!in_array($category, ['Alat Kesehatan', 'Barang Habis Pakai'])) {
                return response()->json([], 400);
            }

            $items = \App\Models\Logistic::where('category', $category)
                ->where('department_id', auth()->user()->department_id)
                ->select('id', 'item_name')
                ->distinct('item_name')
                ->get();

            return response()->json($items);
    })->middleware('auth:sanctum');
Route::prefix('v1/logistics')->group(function () {
    Route::post('/process-transaction', [LogisticController::class, 'processTransaction']);
});

    Route::middleware(['auth:sanctum'])->prefix('v1')->group(function() {
        // Departments
        Route::get('/departments', [DepartmentController::class, 'index']) ;
        Route::post('/departments', [DepartmentController::class, 'store']);

        // Shifts
        Route::apiResource('shifts', ShiftController::class);

        // Positions
        Route::get('/positions', [PositionController::class, 'index']);
        Route::post('/positions', [PositionController::class, 'store']);

        // User data
        Route::get('/user/info', [UserDataController::class, 'index']);

        // Quality Inspection / Indikator Mutu
        Route::get('/quality-inspection/{formType}/current', [QualityInspectionController::class, 'getCurrentWeekForm']);
        Route::get('/quality-inspection/{formType}/all', [QualityInspectionController::class, 'getAllFormData']);
        Route::post('/quality-inspection/{formType}', [QualityInspectionController::class, 'submitForm']);
        Route::get('/quality-inspection/{formType}/history', [QualityInspectionController::class, 'getFormHistory']);

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

        // Private Schedules
        Route::get('/private-schedules', [PrivateScheduleController::class, 'indexPrivateSchedules']);
        Route::post('/private-schedules', [PrivateScheduleController::class, 'storePrivateSchedule']);
        Route::get('/private-schedules/{id}', [PrivateScheduleController::class, 'showPrivateSchedule']);
        Route::put('/private-schedules/{id}', [PrivateScheduleController::class, 'updatePrivateSchedule']);
        Route::delete('/private-schedules/{id}', [PrivateScheduleController::class, 'destroyPrivateSchedule']);

        // Special Cases Routes
        Route::get('special-cases', [PrivateScheduleController::class, 'indexSpecialCases']);
        Route::post('special-cases', [PrivateScheduleController::class, 'storeSpecialCase']);
        Route::get('special-cases/{id}', [PrivateScheduleController::class, 'showSpecialCase']);
        Route::put('special-cases/{id}', [PrivateScheduleController::class, 'updateSpecialCase']);
        Route::delete('special-cases/{id}', [PrivateScheduleController::class, 'destroySpecialCase']);

        // Performance Evaluations
        Route::apiResource('performance-evaluations', PerformanceEvaluationController::class);

        // Training Needs
        Route::apiResource('training-needs', TrainingNeedController::class);

        // CVC Insertion Forms
        Route::prefix('cvc-insertions')->group(function () {
            Route::get('/', [CvcMonitoringController::class, 'getInsertionForms']);
            Route::post('/', [CvcMonitoringController::class, 'storeInsertionForm']);
            Route::put('/{form}', [CvcMonitoringController::class, 'updateInsertionForm']);
            Route::delete('/{form}', [CvcMonitoringController::class, 'deleteInsertionForm']);
            Route::get('/{form}', [CvcMonitoringController::class, 'showInsertionForm']);
        });

        // CVC Maintenance Forms
        Route::prefix('cvc-maintenances')->group(function () {
            Route::get('/', [CvcMonitoringController::class, 'getMaintenanceForms']);
            Route::post('/', [CvcMonitoringController::class, 'storeMaintenanceForm']);
            Route::put('/{form}', [CvcMonitoringController::class, 'updateMaintenanceForm']);
            Route::delete('/{form}', [CvcMonitoringController::class, 'deleteMaintenanceForm']);
            Route::get('/{form}', [CvcMonitoringController::class, 'showMaintenanceForm']);
        });

        // CVC Infection Reports
        Route::prefix('cvc-infections')->group(function () {
            Route::get('/analytics', [CvcMonitoringController::class, 'getOverallStats']); // KEEP AT TOP
            Route::get('/', [CvcMonitoringController::class, 'getInfectionReports']);
            Route::post('/', [CvcMonitoringController::class, 'storeInfectionReport']);
            Route::put('/{report}', [CvcMonitoringController::class, 'updateInfectionReport']);
            Route::delete('/{report}', [CvcMonitoringController::class, 'deleteInfectionReport']);
            Route::get('/{report}', [CvcMonitoringController::class, 'showInfectionReport']);
        });

        // Needlestick Reports
        Route::prefix('needlestick-reports')->group(function () {
            Route::get('/', [CvcMonitoringController::class, 'getNeedlestickReports']);
            Route::post('/', [CvcMonitoringController::class, 'storeNeedlestickReport']);
            Route::put('/{report}', [CvcMonitoringController::class, 'updateNeedlestickReport']);
            Route::delete('/{report}', [CvcMonitoringController::class, 'deleteNeedlestickReport']);
            Route::get('/{report}', [CvcMonitoringController::class, 'showNeedlestickReport']);
        });
        
        //Reports
        Route::get('/reports/header-stats', [ReportController::class, 'getHeaderStats']);
        Route::get('/reports/daily-logs', [ReportController::class, 'getDailyLogs']);
        Route::get('/reports/staff-schedules', [ReportController::class, 'getStaffSchedules']);
        Route::get('/reports/logistics-summary', [ReportController::class, 'getLogisticsSummary']);
        Route::get('/reports/ppi-data', [ReportController::class, 'getPpiData']);
        Route::get('/reports/staff-performance', [ReportController::class, 'getStaffPerformance']);
        Route::get('/reports/tna-data', [ReportController::class, 'getTnaData']);
        Route::get('/reports/quality-indicators', [ReportController::class, 'getQualityIndicators']);

        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead']);
        Route::patch('/notifications/{notification}/dismiss', [NotificationController::class, 'dismiss']);
        Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy']);

        //Dashboard
        Route::get('/dashboard-data', [DashboardController::class, 'index']);
    });
    Route::delete('/logistics/delete-all', [LogisticController::class, 'hapusSemua'])
    ->name('logistics.deleteAll')
    ->middleware('auth');