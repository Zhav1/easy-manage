<?php

use App\Http\Controllers\LogisticController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'web', 'verified',])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });
    Route::get('/notifikasi', function () {
        return view('notifikasi');
    });

    // Resource Route untuk CRUD lengkap
    Route::resource('logistics', LogisticController::class)->except(['create']);

    // Route khusus untuk tampilan edit (jika ingin menggunakan editml.blade.php)

    Route::get('/logout-other-browser-sessions-form', function () {
        return view('logout-other-browser-sessions-form.');
    });
    Route::get('/profile', function () {
        return view('profile');
    });
    Route::get('/dinas', function () {
        return view('jadwal-dinas');
    });
    Route::get('/settings', function () {
        return view('settings');
    });
    Route::get('/pengendalian-dan-pencegahan-infeksi', function () {
        return view('ppi');
    });

    Route::get('/mltable', [LogisticController::class, 'index'])->name('logistics.index');
    Route::resource('logistics', LogisticController::class);
    Route::get('/bundle-insersi', function () {
        return view('bundle-insersi');
    });
    Route::get('/bundle-maintenance', function () {
        return view('bundle-maintenance');
    });
    Route::get('/manajemen-logistik', function () {
        return view('manajemenlogistik');
    });

    Route::get('/', function () {
        return view('dashboard');
    });
    Route::get('/notifikasi', function () {
        return view('notifikasi');
    });
    Route::get('/logout-other-browser-sessions-form', function () {
        return view('logout-other-browser-sessions-form.');
    });
    Route::get('/profile', function () {
        return view('profile');
    });
    Route::get('/dinas', function () {
        return view('jadwal-dinas');
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
    Route::get('/manajemen-logistik', function () {
        return view('manajemenlogistik');
    });
    Route::get('/landing', function () {
        return view('landing-page');
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
 
});
