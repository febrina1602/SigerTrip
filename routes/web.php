<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BerandaController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDestinationController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminPasarDigitalController;
use App\Http\Controllers\AdminAgentProfileController;
use App\Http\Controllers\AdminTourPackageController;
use App\Http\Controllers\PasarDigitalController;
use App\Http\Controllers\PemanduWisataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AgentDashboardController;
use App\Http\Controllers\AgentPasarDigitalController;
use App\Http\Controllers\TourPackageController;

// ==== HALAMAN UTAMA ====
Route::redirect('/', '/beranda');

// ==== GUEST (BELUM LOGIN) ====
Route::middleware('guest')->group(function () {
    // Registrasi
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/register/agent', [AuthController::class, 'showAgentRegistrationForm'])->name('register.agent');
    Route::post('/register/agent', [AuthController::class, 'registerAgent'])->name('register.agent.post');

    // Login
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/agent/login', [AuthController::class, 'showAgentLoginForm'])->name('agent.login');
});

// ==== GLOBAL AUTH (Bisa diakses Semua Role: Admin, Agent, User) ====
Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// ==== ROUTE USER & PUBLIK (DIPROTEKSI DARI AGENT/ADMIN) ====
// Middleware 'is_user' harus dikonfigurasi untuk:
// 1. Mengizinkan Guest (belum login)
// 2. Mengizinkan User (role: user)
// 3. Melempar Agent/Admin ke dashboard masing-masing
Route::middleware(['is_user', 'prevent-back-history'])->group(function () {
    
    // -- Route Publik (Bisa Guest & User) --
    Route::get('/beranda', [BerandaController::class, 'wisatawan'])->name('beranda.wisatawan');
    
    // Destinasi
    Route::get('/category/{id}', [DestinationController::class, 'byCategory'])->name('destinations.category');
    Route::get('/destination/{id}', [DestinationController::class, 'show'])->name('destinations.detail');

    // Pemandu Wisata (Katalog)
    Route::get('/pemandu-wisata', [PemanduWisataController::class, 'index'])->name('pemandu-wisata.index');
    Route::get('/pemandu-wisata/{agent}', [PemanduWisataController::class, 'show'])->name('pemandu-wisata.show');
    Route::get('/pemandu-wisata/{agent}/paket', [PemanduWisataController::class, 'packages'])->name('pemandu-wisata.packages');
    Route::get('/pemandu-wisata/{agent}/paket/{tourPackage}', [PemanduWisataController::class, 'packageDetail'])->name('pemandu-wisata.package-detail');

    // Pasar Digital (Katalog)
    Route::get('/pasar-digital', [PasarDigitalController::class, 'index'])->name('pasar-digital.index');
    Route::get('/pasar-digital/{vehicle}', [PasarDigitalController::class, 'show'])->name('pasar-digital.detail');

    // -- Route Khusus User Login (Dashboard & Profile) --
    Route::middleware('auth')->group(function() {
        Route::get('/dashboard', [BerandaController::class, 'wisatawan'])->name('dashboard');
        
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/password', [ProfileController::class, 'showPasswordForm'])->name('profile.password.show');
    });
});

// ==== KHUSUS AGENT ====
Route::prefix('agent')
    ->middleware(['auth', 'agent', 'prevent-back-history'])
    ->group(function () {
        
        // Profil
        Route::get('/profile/edit', [\App\Http\Controllers\AgentProfileController::class, 'edit'])
            ->name('agent.profile.edit');
        Route::put('/profile/update', [\App\Http\Controllers\AgentProfileController::class, 'update'])
            ->name('agent.profile.update');
        
        // Dashboard
        Route::get('/dashboard', [AgentDashboardController::class, 'index'])
            ->name('agent.dashboard');
        
        // Pasar Digital (Manajemen) - Bisa diakses, tapi POST/STORE diproteksi di controller
        Route::get('/pasar-digital', [AgentPasarDigitalController::class, 'index'])
            ->name('agent.pasar.index');
        Route::get('/pasar-digital/create', [AgentPasarDigitalController::class, 'create'])
            ->name('agent.pasar.create');
        Route::post('/pasar-digital', [AgentPasarDigitalController::class, 'store'])
            ->name('agent.pasar.store');
        Route::get('/pasar-digital/{vehicle}/edit', [AgentPasarDigitalController::class, 'edit'])
            ->name('agent.pasar.edit');
        Route::put('/pasar-digital/{vehicle}', [AgentPasarDigitalController::class, 'update'])
            ->name('agent.pasar.update');
        Route::delete('/pasar-digital/{vehicle}', [AgentPasarDigitalController::class, 'destroy'])
            ->name('agent.pasar.destroy');

        // Paket Perjalanan (Manajemen) - Bisa diakses, tapi POST/STORE diproteksi di controller
        Route::resource('tour-packages', TourPackageController::class, ['as' => 'agent']);
        Route::delete('/tour-packages/{tourPackage}/delete', [TourPackageController::class, 'destroy'])
            ->name('agent.tour_packages.delete');
        
        // Legacy Local Agents (jika masih ada sisa)
        Route::post('/local-tour-agents', [AgentDashboardController::class, 'storeLocalTourAgent'])
            ->name('agent.local_tour_agents.store');
        Route::delete('/local-tour-agents/{localTourAgent}', [AgentDashboardController::class, 'deleteLocalTourAgent'])
            ->name('agent.local_tour_agents.delete');
    });

// ==== KHUSUS ADMIN ====
Route::prefix('admin')
    ->middleware(['auth', 'is_admin', 'prevent-back-history'])
    ->group(function () {
        // Dashboard
        Route::get('/beranda', [AdminDestinationController::class, 'index'])->name('admin.beranda');
        
        // Agent Management
        Route::get('/agents', [AdminController::class, 'pendingAgents'])->name('admin.agents.pending');
        Route::post('/agents/{agent}/verifikasi', [AdminController::class, 'verifikasiAgen'])->name('admin.agents.verifikasi');
        
        // Profil Agent - PENTING: Delete route HARUS sebelum resource
        Route::delete('/profil-agent/{agent}', [AdminAgentProfileController::class, 'destroy'])->name('admin.profil-agent.destroy');
        Route::post('/profil-agent/{agent}/verify', [AdminAgentProfileController::class, 'verify'])->name('admin.profil-agent.verify');
        Route::put('/profil-agent/{agent}/reset', [AdminAgentProfileController::class, 'reset'])->name('admin.profil-agent.reset');
        Route::post('/profil-agent/{agent}/reject', [AdminAgentProfileController::class, 'reject'])->name('admin.profil-agent.reject');
        Route::resource('profil-agent', AdminAgentProfileController::class, ['as' => 'admin']);
        
        // Pasar Digital (Admin)
        Route::resource('pasar', AdminPasarDigitalController::class, ['as' => 'admin', 'parameters' => ['pasar' => 'vehicle']]);

        // Paket Perjalanan (Admin)
        Route::get('/tour-packages', [AdminTourPackageController::class, 'index'])->name('admin.tour-packages.index');
        Route::get('/tour-packages/{id}/edit', [AdminTourPackageController::class, 'edit'])->name('admin.tour-packages.edit');
        Route::put('/tour-packages/{id}', [AdminTourPackageController::class, 'update'])->name('admin.tour-packages.update');
        Route::delete('/tour-packages/{id}', [AdminTourPackageController::class, 'destroy'])->name('admin.tour-packages.destroy');

        // Destinasi (CRUD)
        Route::get('/wisata/create', [AdminDestinationController::class, 'create'])->name('admin.wisata.create');
        Route::post('/wisata/store', [AdminDestinationController::class, 'store'])->name('admin.wisata.store');
        Route::get('/wisata/{id}/edit', [AdminDestinationController::class, 'edit'])->name('admin.wisata.edit');
        Route::put('/wisata/{id}', [AdminDestinationController::class, 'update'])->name('admin.wisata.update');
        Route::delete('/wisata/{id}', [AdminDestinationController::class, 'destroy'])->name('admin.wisata.destroy');

        // Master Data
        Route::resource('categories', AdminCategoryController::class, ['as' => 'admin']);
        Route::resource('users', AdminUserController::class, ['as' => 'admin']);
        
        // User Actions
        Route::post('/users/{id}/verify', [AdminUserController::class, 'verify'])->name('admin.users.verify');
        Route::post('/users/{id}/reject', [AdminUserController::class, 'reject'])->name('admin.users.reject');
        Route::post('/users/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('admin.users.toggleStatus');
    });