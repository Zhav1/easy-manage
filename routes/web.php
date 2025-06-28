<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'web', 'verified',])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
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
