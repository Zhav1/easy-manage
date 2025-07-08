<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LogisticController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\PasswordChangeController;

Route::get('/', function () {
    return view('landing-page');
});
Route::put('/logistics/{id}/use', [LogisticController::class, 'useItem'])->name('logistics.use');
Route::middleware(['auth', 'web', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    Route::get('/notifikasi', function () {
        return view('notifikasi');
    });
// Get initial items for dropdown
Route::get('/logistics/get-items', [LogisticController::class, 'getItems'])->name('logistics.get-items');

// Save new item to dropdown options
Route::post('/logistics/store-item', [LogisticController::class, 'storeItem'])->name('logistics.store-item');
    // Hanya satu route untuk manajemen-logistik
    Route::get('/manajemen-logistik', [LogisticController::class, 'dashboard'])
        ->name('logistics.dashboard');

    // Resource Route untuk CRUD lengkap
    Route::resource('logistics', LogisticController::class)->except(['create']);

    // Route khusus untuk tampilan edit
    Route::get('/logout-other-browser-sessions-form', function () {
        return view('logout-other-browser-sessions-form.');
    });

    // Profile Routes
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

     Route::get('/tentang-kami', function () {
        return view('tentang-kami');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/password/change', [PasswordChangeController::class, 'edit'])
        ->name('password.change');
    Route::patch('/password/change', [PasswordChangeController::class, 'update'])
        ->name('password.update');
});