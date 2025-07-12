<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogisticController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\PasswordChangeController;

Route::get('/', function () {
    return view('landing-page');
});
Route::get('/tentang-kami', function () {
        return view('tentang-kami');
    });
Route::put('/logistics/{id}/use', [LogisticController::class, 'useItem'])->name('logistics.use');


Route::middleware(['auth', 'web', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    Route::get('/notifikasi', function () {
        return view('notifikasi');
    });

    Route::get('/logistics/get-items', [LogisticController::class, 'getItems'])->name('logistics.get-items');

    Route::post('/logistics/store-item', [LogisticController::class, 'storeItem'])->name('logistics.store-item');

    Route::get('/manajemen-logistik', [LogisticController::class, 'dashboard'])
        ->name('logistics.dashboard');

    Route::resource('logistics', LogisticController::class)->except(['create']);

    Route::get('/logout-other-browser-sessions-form', function () {
        return view('logout-other-browser-sessions-form.');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.delete');
    
    Route::get('/dinas', function () {
        return view('jadwal-dinas');
    });
    
    Route::get('/settings', function () {
        return view('settings');
    });
    
    Route::get('/pengendalian-dan-pencegahan-infeksi', function () {
        return view('ppi');
    });

    Route::get('/bundle-insersi', function () {
        return view('bundle-insersi');
    });
    
    Route::get('/bundle-maintenance', function () {
        return view('bundle-maintenance');
    });
    
    Route::get('/schedule', function () {
        return view('schedule');
    });
    
    Route::get('/laporan', function () {
        return view('laporan');
    });
    
    Route::get('/kinerja-staff', function () {
        return view('kinerja-staff');
    });
    
    Route::get('/tna', function () {
        return view('tna');
    });
    
    Route::get('/indikator-mutu', function () {
        return view('indikator-mutu');
    });

    // Excel Export Routes
    Route::get('/reports/export/daily-logs/excel', [ReportController::class, 'exportDailyLogsExcel'])->name('reports.daily_logs.excel');
    Route::get('/reports/export/staff-schedules/excel', [ReportController::class, 'exportStaffSchedulesExcel'])->name('reports.staff_schedules.excel');
    Route::get('/reports/export/logistics/excel', [ReportController::class, 'exportLogisticsExcel'])->name('reports.logistics.excel');
    Route::get('/reports/export/ppi/excel', [ReportController::class, 'exportPpiReportsExcel'])->name('reports.ppi.excel');
    Route::get('/reports/export/staff-performance/excel', [ReportController::class, 'exportStaffPerformanceExcel'])->name('reports.staff_performance.excel');
    Route::get('/reports/export/tna/excel', [ReportController::class, 'exportTnaRecordsExcel'])->name('reports.tna.excel');
    Route::get('/reports/export/quality-indicators/excel', [ReportController::class, 'exportQualityIndicatorsExcel'])->name('reports.quality_indicators.excel');

    // New PDF Export Routes
    Route::get('/reports/export/daily-logs/pdf', [ReportController::class, 'exportDailyLogsPdf'])->name('reports.daily_logs.pdf');
    Route::get('/reports/export/staff-schedules/pdf', [ReportController::class, 'exportStaffSchedulesPdf'])->name('reports.staff_schedules.pdf');
    Route::get('/reports/export/logistics/pdf', [ReportController::class, 'exportLogisticsPdf'])->name('reports.logistics.pdf');
    Route::get('/reports/export/ppi/pdf', [ReportController::class, 'exportPpiReportsPdf'])->name('reports.ppi.pdf');
    Route::get('/reports/export/staff-performance/pdf', [ReportController::class, 'exportStaffPerformancePdf'])->name('reports.staff_performance.pdf');
    Route::get('/reports/export/tna/pdf', [ReportController::class, 'exportTnaRecordsPdf'])->name('reports.tna.pdf');
    Route::get('/reports/export/quality-indicators/pdf', [ReportController::class, 'exportQualityIndicatorsPdf'])->name('reports.quality_indicators.pdf');

    Route::get('/password/change', [PasswordChangeController::class, 'edit'])
        ->name('password.change');
    Route::patch('/password/change', [PasswordChangeController::class, 'update'])
        ->name('password.update');
});

