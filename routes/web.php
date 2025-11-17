<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDestinationController;

// ==== HALAMAN UTAMA ====
// Tampilkan landing page (misal resources/views/welcome.blade.php)
Route::view('/', 'welcome')->name('landing');
// Kalau landing page-mu pakai nama lain, ganti 'welcome' dengan nama view-nya,
// misal: Route::view('/', 'landing')->name('landing');

// ==== GUEST (belum login) ====
Route::middleware('guest')->group(function () {
    // Registrasi wisatawan biasa
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Registrasi mitra / agen
    Route::get('/register/agent', [AuthController::class, 'showAgentRegistrationForm'])->name('register.agent');
    Route::post('/register/agent', [AuthController::class, 'registerAgent'])->name('register.agent.post');

    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ==== AUTH (sudah login) ====
// Proteksi halaman setelah login + cegah halaman tersimpan di browser cache
Route::middleware(['auth','prevent-back-history'])->group(function () {
    Route::get('/dashboard', [BerandaController::class, 'wisatawan'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ===== ROUTE WISATAWAN / UMUM =====
Route::get('/beranda', [BerandaController::class, 'wisatawan'])->name('beranda.wisatawan');
Route::get('/category/{id}', [DestinationController::class, 'byCategory'])->name('destinations.category');
Route::get('/destination/{id}', [DestinationController::class, 'show'])->name('destinations.detail');

// ===== ROUTE ADMIN =====
Route::prefix('admin')->group(function() {
    // Beranda admin
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

// ===== TAMBAHAN: ROUTE ADMIN (Proteksi is_admin) UNTUK MITRA/AGEN =====
Route::prefix('admin')->middleware(['auth','is_admin'])->group(function () {
    // Daftar pengajuan mitra/agen yang pending verifikasi
    Route::get('/agents', [AdminController::class, 'pendingAgents'])->name('admin.agents.pending');

    // Verifikasi satu agen
    Route::post('/agents/{agent}/verifikasi', [AdminController::class, 'verifikasiAgen'])->name('admin.agents.verifikasi');
});
