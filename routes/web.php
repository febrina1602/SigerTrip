<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DestinationController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/beranda', [BerandaController::class, 'wisatawan'])->name('beranda.wisatawan');
Route::get('/category/{id}', [DestinationController::class, 'byCategory'])
     ->name('destinations.category'); // <-- Ini nama yang dicari oleh view Anda
Route::get('/destination/{id}', [DestinationController::class, 'show'])
     ->name('destinations.detail');
