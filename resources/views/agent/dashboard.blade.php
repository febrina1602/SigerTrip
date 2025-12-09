@extends('layouts.app')

@section('title', 'Dashboard Agent - SigerTrip')

@section('content')

<div class="min-vh-100 bg-light">
    
    @include('components.layout.header')

    {{-- MAIN CONTENT --}}
    <div class="container mt-4 mb-5">
        
        {{-- STATUS ALERT - IMPROVED UI --}}
        @if (!$agent->is_verified)
            <div class="mb-4">
                <div class="alert alert-warning border-0 rounded-4 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-warning" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="alert-heading mb-1 fw-bold">
                                Menunggu Verifikasi Admin
                            </h5>
                            <p class="mb-0 text-muted">
                                Profil agensi Anda sedang dalam proses verifikasi oleh tim admin kami. 
                                <strong>Fitur Pasar Digital dan Paket Perjalanan akan aktif setelah verifikasi selesai.</strong>
                            </p>

                            <div class="mt-2">
                                @if($agent->is_profile_complete)
                                    {{-- Jika profil lengkap --}}
                                    <small class="text-muted d-block mb-2">
                                        <i class="fas fa-check-circle me-1" style="color: #10b981;"></i>
                                        Status: <strong>Profil Lengkap</strong>
                                    </small>

                                    <a href="{{ route('agent.profile.edit') }}" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-edit me-1"></i>Edit Profil
                                    </a>

                                @else
                                    {{-- Jika profil belum lengkap --}}
                                    <small class="text-danger d-block mb-2">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        Status: <strong>Profil Belum Lengkap</strong>
                                    </small>

                                    <a href="{{ route('agent.profile.edit') }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit me-1"></i>Lengkapi Profil Sekarang
                                    </a>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-4">
                <div class="alert alert-success border-0 rounded-4 shadow-sm" role="alert">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-success" style="font-size: 2rem;"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="alert-heading mb-1 fw-bold">
                                Profil Terverifikasi!
                            </h5>
                            <p class="mb-0 text-muted">
                                Selamat! Profil Anda sudah diverifikasi. Semua fitur sekarang dapat Anda akses.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif


        {{-- WELCOME SECTION --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="bg-gradient-primary rounded-4 p-4 p-md-5 text-white shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-2">Selamat Datang, {{ $user->full_name }}! 👋</h2>
                            <p class="mb-0 text-white">
                                Kelola agen tour lokal Anda, buat paket perjalanan menarik, dan tingkatkan rating untuk menjadi partner terpercaya SigerTrip.
                            </p>
                        </div>
                        <div class="col-md-4 text-center d-none d-md-block">
                            <i class="fas fa-briefcase" style="font-size: 5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STATISTICS SECTION --}}
        <div class="row mb-5">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 rounded-4 shadow-sm h-100 transition" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small mb-1">Agen Tour Lokal</p>
                                <h3 class="fw-bold mb-0" style="color: #667eea;">{{ $totalLocalTourAgents }}</h3>
                            </div>
                            <div class="rounded-circle p-3" style="background-color: #e8eef7;">
                                <i class="fas fa-map-location-dot" style="font-size: 1.5rem; color: #667eea;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 rounded-4 shadow-sm h-100 transition" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small mb-1">Paket Perjalanan</p>
                                <h3 class="fw-bold mb-0" style="color: #f59e0b;">{{ $totalTourPackages }}</h3>
                            </div>
                            <div class="rounded-circle p-3" style="background-color: #fef3c7;">
                                <i class="fas fa-suitcase" style="font-size: 1.5rem; color: #f59e0b;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 rounded-4 shadow-sm h-100 transition" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small mb-1">Rating Rata-Rata</p>
                                <div>
                                    <h3 class="fw-bold mb-0 d-inline" style="color: #ec4899;">
                                        {{ number_format($averageRating, 1) }}
                                    </h3>
                                    <span class="text-warning ms-2">
                                        <i class="fas fa-star" style="font-size: 0.8rem;"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="rounded-circle p-3" style="background-color: #fce7f3;">
                                <i class="fas fa-star" style="font-size: 1.5rem; color: #ec4899;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 rounded-4 shadow-sm h-100 transition" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small mb-1">Kendaraan (Pasar Digital)</p>
                                <h3 class="fw-bold mb-0" style="color: #10b981;">{{ $totalVehicles }}</h3>
                            </div>
                            <div class="rounded-circle p-3" style="background-color: #d1fae5;">
                                <i class="fas fa-car-side" style="font-size: 1.5rem; color: #10b981;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="row mb-5">
            <div class="col-12">
                <h5 class="fw-bold mb-3">Aksi Cepat</h5>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        {{-- Pasar Digital --}}
                        <a href="{{ route('agent.pasar.index') }}" class="btn btn-outline-warning w-100 rounded-3 py-3 position-relative">
                            <i class="fas fa-car me-2"></i> Pasar Digital
                            @if(!$agent->is_verified)
                                <span class="position-absolute top-0 start-50 translate-middle badge bg-warning rounded-pill">
                                    <i class="fas fa-lock" style="font-size: 0.6rem;"></i>
                                </span>
                            @endif
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        {{-- Paket Perjalanan --}}
                        <a href="{{ route('agent.tour-packages.index') }}" class="btn btn-outline-success w-100 rounded-3 py-3 position-relative">
                            <i class="fas fa-plus me-2"></i> Paket Perjalanan
                            @if(!$agent->is_verified)
                                <span class="position-absolute top-0 start-50 translate-middle badge bg-warning rounded-pill">
                                    <i class="fas fa-lock" style="font-size: 0.6rem;"></i>
                                </span>
                            @endif
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('agent.profile.edit') }}" class="btn btn-outline-secondary w-100 rounded-3 py-3">
                            <i class="fas fa-gear me-2"></i> Edit Profil
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-info w-100 rounded-3 py-3">
                            <i class="fas fa-user me-2"></i> Profil Akun
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- PAKET PERJALANAN TABLE --}}
        <div class="row mb-5">
            <div class="col-12">
                <h5 class="fw-bold mb-3">Paket Perjalanan Terbaru</h5>

                @if($recentTourPackages->count() > 0)
                    <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4">Nama Paket</th>
                                        <th>Harga</th>
                                        <th>Durasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTourPackages as $package)
                                        <tr>
                                            <td class="px-4 fw-medium">{{ Str::limit($package->name, 40) }}</td>
                                            <td class="fw-bold text-warning">
                                                Rp {{ number_format($package->price_per_person, 0, ',', '.') }}
                                            </td>
                                            <td>{{ $package->duration ?? '-' }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('agent.tour-packages.edit', $package->id) }}" class="btn btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('agent.tour-packages.destroy', $package->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus paket ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info border-0 rounded-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada paket perjalanan yang dibuat.
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

@endsection