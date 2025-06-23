<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
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


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
