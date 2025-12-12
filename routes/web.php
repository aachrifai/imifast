<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImigrasiController;
use App\Http\Controllers\AuthController;

// USER
Route::get('/', [ImigrasiController::class, 'index'])->name('home');
Route::post('/book', [ImigrasiController::class, 'store'])->name('book.store');
Route::get('/check-quota', [ImigrasiController::class, 'checkQuota'])->name('check.quota');

// AUTH
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ADMIN
Route::middleware(['auth', 'prevent-back-history'])->prefix('admin')->group(function () {
    
    Route::get('/dashboard', [ImigrasiController::class, 'dashboard'])->name('admin.dashboard');
    
    Route::post('/quota', [ImigrasiController::class, 'setQuota'])->name('admin.quota');
    Route::post('/check-status', [ImigrasiController::class, 'checkQuotaStatus'])->name('admin.check_status');
    
    Route::delete('/delete/{id}', [ImigrasiController::class, 'destroy'])->name('admin.delete');
    Route::put('/update/{id}', [ImigrasiController::class, 'update'])->name('admin.update');
    
    // Route untuk Ceklis Status (Sudah Diambil)
    Route::patch('/toggle-status/{id}', [ImigrasiController::class, 'toggleStatus'])->name('admin.toggle_status');

    Route::post('/clean', [ImigrasiController::class, 'cleanData'])->name('admin.clean');
    Route::post('/reset-all', [ImigrasiController::class, 'resetAllData'])->name('admin.reset');

    // ... (Kode atas tetap sama) ...
    Route::post('/clean', [ImigrasiController::class, 'cleanData'])->name('admin.clean');
    Route::post('/reset-all', [ImigrasiController::class, 'resetAllData'])->name('admin.reset');
    
    // ROUTE BARU: CETAK LAPORAN PDF
    Route::get('/print-report', [ImigrasiController::class, 'printReport'])->name('admin.print');

    Route::post('/update-background', [ImigrasiController::class, 'updateBackground'])->name('admin.bg_update');

    Route::post('/clean', [ImigrasiController::class, 'cleanData'])->name('admin.clean');
    Route::post('/reset-all', [ImigrasiController::class, 'resetAllData'])->name('admin.reset');
    Route::get('/print-report', [ImigrasiController::class, 'printReport'])->name('admin.print');

    // ROUTE HALAMAN BANNER BARU
    Route::get('/banner-setting', [ImigrasiController::class, 'bannerPage'])->name('admin.banner');
    Route::post('/update-background', [ImigrasiController::class, 'updateBackground'])->name('admin.bg_update');
});