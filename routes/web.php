<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DestinationController;

// ==== HALAMAN UTAMA ====
// Boleh pilih salah satu:
// Route::get('/', fn () => view('welcome'));
Route::redirect('/', '/beranda'); // langsung ke beranda

// ==== GUEST (belum login) ====
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ==== AUTH (sudah login) ====
// Arahkan dashboard ke beranda agar tidak perlu dashboard.blade.php
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [BerandaController::class, 'wisatawan'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ==== BERANDA & DESTINASI ====
Route::get('/beranda', [BerandaController::class, 'wisatawan'])->name('beranda.wisatawan');

Route::get('/category/{id}', [DestinationController::class, 'byCategory'])
    ->name('destinations.category');
Route::get('/destination/{id}', [DestinationController::class, 'show'])
    ->name('destinations.detail');
