<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDestinationController;
use App\Http\Controllers\PemanduWisataController;
use App\Http\Controllers\ProfileController;

// ==== HALAMAN UTAMA ====
Route::redirect('/', '/beranda'); // langsung ke beranda

// ==== GUEST (belum login) ====
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ==== AUTH (sudah login) ====
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [BerandaController::class, 'wisatawan'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'showPasswordForm'])->name('profile.password.show');

});

// ==== BERANDA & DESTINASI & PEMANDU WISATA (ROUTE WISATAWAN) ====
Route::get('/beranda', [BerandaController::class, 'wisatawan'])->name('beranda.wisatawan');

Route::get('/category/{id}', [DestinationController::class, 'byCategory'])
    ->name('destinations.category');
    
Route::get('/destination/{id}', [DestinationController::class, 'show'])
    ->name('destinations.detail');
    
Route::get('/pemandu-wisata', [PemanduWisataController::class, 'index'])->name('pemandu-wisata.index');
Route::get('/pemandu-wisata/{agent}', [PemanduWisataController::class, 'show'])->name('pemandu-wisata.show');
Route::get('/pemandu-wisata/{agent}/paket', [PemanduWisataController::class, 'packages'])->name('pemandu-wisata.packages');
Route::get('/pemandu-wisata/{agent}/paket/{tourPackage}', [PemanduWisataController::class, 'packageDetail'])->name('pemandu-wisata.package-detail');


// ===== ROUTE ADMIN =====
Route::prefix('admin')->group(function() {
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