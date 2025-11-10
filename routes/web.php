<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DestinationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini kamu bisa mendefinisikan semua route web aplikasi kamu.
| Termasuk untuk autentikasi, halaman utama, dan destinasi wisata.
|
*/

// ==== HALAMAN UTAMA ====
Route::get('/', function () {
    return view('welcome');
});

// ==== GUEST (belum login) ====
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// ==== AUTH (sudah login) ====
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard'); // pastikan view dashboard.blade.php ada
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ==== BERANDA & DESTINASI ====
Route::get('/beranda', [BerandaController::class, 'wisatawan'])->name('beranda.wisatawan');
Route::get('/category/{id}', [DestinationController::class, 'byCategory'])->name('destinations.category');
Route::get('/destination/{id}', [DestinationController::class, 'show'])->name('destinations.detail');
