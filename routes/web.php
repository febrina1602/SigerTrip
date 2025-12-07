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
    // Registrasi wisatawan (user biasa)
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Registrasi mitra / agen
    Route::get('/register/agent', [AuthController::class, 'showAgentRegistrationForm'])->name('register.agent');
    Route::post('/register/agent', [AuthController::class, 'registerAgent'])->name('register.agent.post');

    // Login umum
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    // Login khusus agent
    Route::get('/agent/login', [AuthController::class, 'showAgentLoginForm'])->name('agent.login');
});

// ==== AUTH (SETELAH LOGIN, GLOBAL) ====
Route::middleware(['auth', 'prevent-back-history'])->group(function () {
    // Dashboard wisatawan (default)
    Route::get('/dashboard', [BerandaController::class, 'wisatawan'])->name('dashboard');

    // Agent actions: create local tour agent (AJAX/modal)
    Route::post('/agent/local-tour-agents', [AgentDashboardController::class, 'storeLocalTourAgent'])
        ->name('agent.local_tour_agents.store');

    // Agent: delete local tour agent
    Route::delete('/agent/local-tour-agents/{localTourAgent}', [AgentDashboardController::class, 'deleteLocalTourAgent'])
        ->name('agent.local_tour_agents.delete');

    // Agent manage tour package (delete)
    Route::post('/agent/tour-packages/{tourPackage}/delete', [AgentDashboardController::class, 'deleteTourPackage'])
        ->name('agent.tour_packages.delete');

    // Agent update tour package via modal (AJAX)
    Route::post('/agent/tour-packages/{tourPackage}/update', [AgentDashboardController::class, 'updateTourPackage'])
        ->name('agent.tour_packages.update');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/password', [ProfileController::class, 'showPasswordForm'])->name('profile.password.show');
});

// ==== ROUTE AGENT (KHUSUS AGENT, WAJIB LOGIN + MIDDLEWARE agent) ====
Route::prefix('agent')
    ->middleware(['auth', 'agent', 'prevent-back-history'])
    ->group(function () {
        Route::get('/profile/edit', [\App\Http\Controllers\AgentProfileController::class, 'edit'])->name('agent.profile.edit');
        
        Route::put('/profile/update', [\App\Http\Controllers\AgentProfileController::class, 'update'])->name('agent.profile.update');

        // Dashboard agent
        Route::get('/dashboard', [AgentDashboardController::class, 'index'])->name('agent.dashboard');

        // PASAR DIGITAL (CRUD Manual)
        Route::get('/pasar-digital', [AgentPasarDigitalController::class, 'index'])->name('agent.pasar.index');
        Route::get('/pasar-digital/create', [AgentPasarDigitalController::class, 'create'])->name('agent.pasar.create');
        Route::post('/pasar-digital', [AgentPasarDigitalController::class, 'store'])->name('agent.pasar.store');
        Route::get('/pasar-digital/{vehicle}/edit', [AgentPasarDigitalController::class, 'edit'])->name('agent.pasar.edit');
        Route::put('/pasar-digital/{vehicle}', [AgentPasarDigitalController::class, 'update'])->name('agent.pasar.update');
        Route::delete('/pasar-digital/{vehicle}', [AgentPasarDigitalController::class, 'destroy'])->name('agent.pasar.destroy');

        Route::resource('tour-packages', TourPackageController::class, ['as' => 'agent']);
        
        // Route Hapus Khusus (jika Anda masih pakai form manual di dashboard)
        Route::delete('/tour-packages/{tourPackage}/delete', [TourPackageController::class, 'destroy'])->name('agent.tour_packages.delete');
        
        // Route Agen Lokal (Lama/Legacy) - Tetap ada jika controller masih dipanggil
        Route::post('/local-tour-agents', [AgentDashboardController::class, 'storeLocalTourAgent'])->name('agent.local_tour_agents.store');
        Route::delete('/local-tour-agents/{localTourAgent}', [AgentDashboardController::class, 'deleteLocalTourAgent'])->name('agent.local_tour_agents.delete');
    });
    
// ==== ROUTE WISATAWAN / UMUM ====

// Beranda
Route::get('/beranda', [BerandaController::class, 'wisatawan'])->name('beranda.wisatawan');

// Destinasi
Route::get('/category/{id}', [DestinationController::class, 'byCategory'])->name('destinations.category');
Route::get('/destination/{id}', [DestinationController::class, 'show'])->name('destinations.detail');

// Pemandu wisata (catalog LocalTourAgent)
Route::get('/pemandu-wisata', [PemanduWisataController::class, 'index'])->name('pemandu-wisata.index');
Route::get('/pemandu-wisata/{localTourAgent}', [PemanduWisataController::class, 'show'])->name('pemandu-wisata.show');
Route::get('/pemandu-wisata/{localTourAgent}/paket', [PemanduWisataController::class, 'packages'])->name('pemandu-wisata.packages');
Route::get(
    '/pemandu-wisata/{localTourAgent}/paket/{tourPackage}',
    [PemanduWisataController::class, 'packageDetail']
)->name('pemandu-wisata.package-detail');

// Pasar digital (umum, sisi wisatawan)
Route::get('/pasar-digital', [PasarDigitalController::class, 'index'])->name('pasar-digital.index');
Route::get('/pasar-digital/{vehicle}', [PasarDigitalController::class, 'show'])->name('pasar-digital.detail');

// ==== ROUTE ADMIN (WAJIB LOGIN + ADMIN) ====
Route::prefix('admin')
    ->middleware(['auth', 'is_admin', 'prevent-back-history'])
    ->group(function () {

        // Dashboard admin
        Route::get('/beranda', [AdminDestinationController::class, 'index'])->name('admin.beranda');

        // Pemandu / Agent Verification
        Route::get('/agents', [AdminController::class, 'pendingAgents'])->name('admin.agents.pending');
        Route::post('/agents/{agent}/verifikasi', [AdminController::class, 'verifikasiAgen'])->name('admin.agents.verifikasi');

        // PASAR DIGITAL (Admin) - Resource Controller
        // 'parameters' digunakan agar variabel di controller adalah $vehicle (bukan $pasar)
        Route::resource('pasar', AdminPasarDigitalController::class, [
            'as' => 'admin', 
            'parameters' => ['pasar' => 'vehicle']
        ]);

        // DESTINASI (CRUD Manual)
        Route::get('/wisata/create', [AdminDestinationController::class, 'create'])->name('admin.wisata.create');
        Route::post('/wisata/store', [AdminDestinationController::class, 'store'])->name('admin.wisata.store');
        Route::get('/wisata/{id}/edit', [AdminDestinationController::class, 'edit'])->name('admin.wisata.edit');
        Route::put('/wisata/{id}', [AdminDestinationController::class, 'update'])->name('admin.wisata.update');
        Route::delete('/wisata/{id}', [AdminDestinationController::class, 'destroy'])->name('admin.wisata.destroy');

        // CATEGORY (CRUD Manual)
        Route::get('/categories', [AdminCategoryController::class, 'index'])->name('admin.categories.index');
        Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('admin.categories.create');
        Route::post('/categories/store', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/categories/{id}', [AdminCategoryController::class, 'show'])->name('admin.categories.show');
        Route::get('/categories/{id}/edit', [AdminCategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/categories/{id}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');

        // USER MANAGEMENT - Resource Controller
        Route::resource('users', AdminUserController::class, ['as' => 'admin']);
        
        // Custom Routes User Management (tidak tercover oleh resource)
        Route::post('/users/{id}/verify', [AdminUserController::class, 'verify'])->name('admin.users.verify');
        Route::post('/users/{id}/reject', [AdminUserController::class, 'reject'])->name('admin.users.reject');
        Route::post('/users/{id}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('admin.users.toggleStatus');

    });