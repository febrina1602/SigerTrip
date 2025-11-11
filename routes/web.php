<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDestinationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// ===== ROUTE WISATAWAN =====
Route::get('/beranda', [BerandaController::class, 'wisatawan'])->name('beranda.wisatawan');
Route::get('/category/{id}', [DestinationController::class, 'byCategory'])->name('destinations.category');
Route::get('/destination/{id}', [DestinationController::class, 'show'])->name('destinations.detail');

// ===== ROUTE ADMIN =====
Route::prefix('admin')->group(function() {
    // Gunakan AdminDestinationController untuk beranda admin
    Route::get('/beranda', [AdminDestinationController::class, 'index'])->name('admin.beranda');
    
    Route::get('/pemandu', [AdminController::class, 'pemandu'])->name('admin.pemandu');
    Route::get('/akun', [AdminController::class, 'akun'])->name('admin.akun');
    Route::post('/akun/{id}/verifikasi', [AdminController::class, 'verifikasi'])->name('admin.verifikasi');
    Route::get('/pasar', [AdminController::class, 'pasar'])->name('admin.pasar');
    // ===== ROUTE ADMIN DESTINATION =====
    Route::get('/wisata/create', [AdminDestinationController::class, 'create'])->name('admin.wisata.create');
    Route::post('/wisata/store', [AdminDestinationController::class, 'store'])->name('admin.wisata.store');
    Route::get('/wisata/{id}/edit', [AdminDestinationController::class, 'edit'])->name('admin.wisata.edit');
    Route::put('/wisata/{id}', [AdminDestinationController::class, 'update'])->name('admin.wisata.update');
    Route::delete('/wisata/{id}', [AdminDestinationController::class, 'destroy'])->name('admin.wisata.destroy');
});