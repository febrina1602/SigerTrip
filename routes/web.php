<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDestinationController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\PasarDigitalController;
use App\Http\Controllers\PemanduWisataController;
use App\Http\Controllers\ProfileController;

// ==== HALAMAN UTAMA ====
// Tampilkan landing page (resources/views/welcome.blade.php)
Route::view('/', 'welcome')->name('landing');

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
Route::middleware(['auth', 'prevent-back-history'])->group(function () {

    // Dashboard wisatawan (umum)
    Route::get('/dashboard', [BerandaController::class, 'wisatawan'])->name('dashboard');

    // Dashboard untuk agent, tapi diarahkan ke halaman yang sama (dashboard wisatawan)
    Route::get('/agent/dashboard', [BerandaController::class, 'wisatawan'])->name('agent.dashboard'); // ⬅ tambah ini

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'showPasswordForm'])->name('profile.password.show');
});

// ==== ROUTE WISATAWAN / UMUM ====

// Beranda & destinasi
Route::get('/beranda', [BerandaController::class, 'wisatawan'])->name('beranda.wisatawan');
Route::get('/category/{id}', [DestinationController::class, 'byCategory'])->name('destinations.category');
Route::get('/destination/{id}', [DestinationController::class, 'show'])->name('destinations.detail');

// Pemandu wisata
Route::get('/pemandu-wisata', [PemanduWisataController::class, 'index'])->name('pemandu-wisata.index');
Route::get('/pemandu-wisata/{agent}', [PemanduWisataController::class, 'show'])->name('pemandu-wisata.show');
Route::get('/pemandu-wisata/{agent}/paket', [PemanduWisataController::class, 'packages'])->name('pemandu-wisata.packages');
Route::get(
    '/pemandu-wisata/{agent}/paket/{tourPackage}',
    [PemanduWisataController::class, 'packageDetail']
)->name('pemandu-wisata.package-detail');

// Pasar digital
Route::get('/pasar-digital', [PasarDigitalController::class, 'index'])->name('pasar-digital.index');
Route::get('/pasar-digital/{vehicle}', [PasarDigitalController::class, 'show'])->name('pasar-digital.detail');

// ===== ROUTE ADMIN (UMUM) =====
Route::prefix('admin')->group(function () {

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

// ===== ROUTE ADMIN (Proteksi is_admin) UNTUK MITRA/AGEN & USER MANAGEMENT =====
Route::prefix('admin')->middleware(['auth', 'is_admin'])->group(function () {

    // Daftar pengajuan mitra/agen yang pending verifikasi
    Route::get('/agents', [AdminController::class, 'pendingAgents'])->name('admin.agents.pending');

    // Verifikasi satu agen
    Route::post('/agents/{agent}/verifikasi', [AdminController::class, 'verifikasiAgen'])->name('admin.agents.verifikasi');

    // ===== ROUTE ADMIN USER MANAGEMENT =====
    Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [AdminUserController::class, 'create'])->name('admin.users.create');
    Route::post('/users/store', [AdminUserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [AdminUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [AdminUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/users/{id}/verify', [AdminUserController::class, 'verify'])->name('admin.users.verify');
    Route::post('/users/{id}/reject', [AdminUserController::class, 'reject'])->name('admin.users.reject');
    Route::post('/users/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('admin.users.toggleStatus');
});
