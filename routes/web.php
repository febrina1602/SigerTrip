<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController; // PENTING: Import Controller baru Anda!

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// --- Route Halaman Utama ---
Route::get('/', function () {
    return view('welcome');
});

// |                       ROUTE AUTENTIKASI MANUAL                        |


// Route untuk Register (Menggunakan middleware 'guest' agar tidak bisa diakses jika sudah login)
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest');

// Route untuk Login (Menggunakan middleware 'guest')
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post')->middleware('guest');

// Route untuk Logout (Membutuhkan user terautentikasi untuk bisa logout)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route Dashboard (Contoh halaman yang dilindungi/membutuhkan sesi)
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware('auth');