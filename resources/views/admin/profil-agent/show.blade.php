@extends('layouts.app')

@section('title', 'Detail Profil Agent - Admin')

@push('styles')
<style>
    .info-card {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .info-row {
        display: flex;
        margin-bottom: 1rem;
        align-items: flex-start;
    }

    .info-label {
        font-weight: 600;
        min-width: 150px;
        color: #333;
    }

    .info-value {
        color: #666;
        flex: 1;
    }

    .status-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .status-verified {
        background-color: #d4edda;
        color: #155724;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .banner-container {
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1.5rem;
        background-color: #e9ecef;
        min-height: 250px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .banner-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #dee2e6;
    }
</style>
@endpush

@section('content')

<div class="min-vh-100 bg-white">

    {{-- HEADER --}}
    <header class="border-bottom bg-white shadow-sm">
        <div class="container py-2 d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.beranda') }}" class="d-flex align-items-center text-decoration-none" style="min-width: 150px;">
                <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo" style="height:42px" loading="lazy" onerror="this.style.display='none'">
                <span class="ms-2 fw-bold text-dark d-none d-md-block">SigerTrip</span>
            </a>

            <div class="d-flex align-items-center gap-4" style="min-width: 150px; justify-content: flex-end;">
                <div class="text-center">
                    <i class="fas fa-user-circle text-dark" style="font-size: 1.8rem;"></i>
                    <div class="small fw-medium mt-1 text-dark">
                        {{ Auth::user()->full_name ?? Auth::user()->name ?? 'Admin' }}
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger p-0" style="font-size: 1.6rem; line-height: 1;" title="Keluar">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- NAVIGATION --}}
    <nav class="nav-custom bg-light py-2 border-bottom">
        <div class="container d-flex gap-4">
            <a href="{{ route('admin.beranda') }}" class="nav-link-custom">Beranda</a>
            <a href="{{ route('admin.profil-agent.index') }}" class="nav-link-custom active">Profil Agent</a>
            <a href="{{ route('admin.pasar.index') }}" class="nav-link-custom">Pasar Digital</a>
            <a href="{{ route('admin.tour-packages.index') }}" class="nav-link-custom">Pemandu Wisata</a>
            <a href="{{ route('admin.users.index') }}" class="nav-link-custom">Kelola User</a>
        </div>
    </nav>

    <main class="container py-5">

        {{-- Pesan Sukses/Error --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Tombol Kembali --}}
        <a href="{{ route('admin.profil-agent.index') }}" class="btn btn-outline-secondary mb-4">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>

        <div class="row">
            <div class="col-lg-8">

                {{-- HEADER SECTION --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h2 class="fw-bold mb-1">{{ $agent->user->full_name ?? $agent->user->name }}</h2>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-envelope me-1"></i> {{ $agent->user->email }}
                                </p>
                            </div>
                            <span class="status-badge {{ $agent->is_verified ? 'status-verified' : 'status-pending' }}">
                                @if($agent->is_verified)
                                    <i class="fas fa-check-circle me-1"></i> Terverifikasi
                                @else
                                    <i class="fas fa-hourglass-half me-1"></i> Menunggu Verifikasi
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- BANNER --}}
                @if($agent->banner_image_url)
                    <div class="banner-container shadow-sm">
                        <img src="{{ asset('storage/' . $agent->banner_image_url) }}" alt="Banner {{ $agent->name }}">
                    </div>
                @else
                    <div class="banner-container shadow-sm">
                        <div class="text-center text-muted">
                            <i class="fas fa-image" style="font-size: 3rem; opacity: 0.3;"></i>
                            <p class="mt-2">Belum ada banner</p>
                        </div>
                    </div>
                @endif

                {{-- INFORMASI AGENSI --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="fas fa-building text-primary me-2"></i> Informasi Agensi
                        </h5>

                        <div class="info-card">
                            <div class="info-row">
                                <span class="info-label">Nama Agensi:</span>
                                <span class="info-value">{{ $agent->name ?? '-' }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Tipe Agent:</span>
                                <span class="info-value">
                                    @if($agent->agent_type === 'LOCAL_TOUR')
                                        <span class="badge bg-info">Local Tour</span>
                                    @else
                                        {{ $agent->agent_type }}
                                    @endif
                                </span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Lokasi:</span>
                                <span class="info-value">{{ $agent->location ?? '-' }}</span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Alamat Operasional:</span>
                                <span class="info-value">{{ $agent->address ?? '-' }}</span>
                            </div>
                        </div>

                        <h6 class="fw-bold mb-2">Deskripsi</h6>
                        <p class="text-muted">{{ $agent->description ?? '-' }}</p>
                    </div>
                </div>

                {{-- INFORMASI KONTAK --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4">
                            <i class="fas fa-phone text-success me-2"></i> Informasi Kontak
                        </h5>

                        <div class="info-card">
                            <div class="info-row">
                                <span class="info-label">Email:</span>
                                <span class="info-value">
                                    <a href="mailto:{{ $agent->user->email }}">{{ $agent->user->email }}</a>
                                </span>
                            </div>

                            <div class="info-row">
                                <span class="info-label">Nomor Kontak (WA):</span>
                                <span class="info-value">
                                    @if($agent->contact_phone)
                                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $agent->contact_phone) }}" target="_blank">
                                            {{ $agent->contact_phone }}
                                            <i class="fas fa-external-link-alt ms-1" style="font-size: 0.8rem;"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- AKSI VERIFIKASI --}}
                <div class="action-buttons">
                    @if(!$agent->is_verified && $agent->is_profile_complete)
                        <form action="{{ route('admin.profil-agent.verify', $agent->id) }}" method="POST" class="flex-grow-1">
                            @csrf
                            <button type="submit" class="btn btn-success w-100 py-2" onclick="return confirm('Verifikasi profil agent ini?')">
                                <i class="fas fa-check-circle me-2"></i> Verifikasi Profil
                            </button>
                        </form>
                    @elseif($agent->is_verified)
                        <form action="{{ route('admin.profil-agent.reset', $agent->id) }}" method="POST" class="flex-grow-1">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-warning w-100 py-2" onclick="return confirm('Reset verifikasi profil ini?')">
                                <i class="fas fa-redo me-2"></i> Reset Verifikasi
                            </button>
                        </form>
                    @elseif(!$agent->is_profile_complete)
                        <div class="alert alert-warning w-100 mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Profil belum lengkap. Agent perlu melengkapi data terlebih dahulu.
                        </div>
                    @endif
                </div>

            </div>

            {{-- SIDEBAR --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 1rem;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Status Verifikasi</h6>
                        
                        <div class="mb-3">
                            <p class="small text-muted mb-2">Profil Lengkap</p>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ $agent->is_profile_complete ? 'bg-success' : 'bg-warning' }}" 
                                     style="width: {{ $agent->is_profile_complete ? '100' : '50' }}%">
                                </div>
                            </div>
                            <small class="text-muted">
                                @if($agent->is_profile_complete)
                                    ✓ Semua data lengkap
                                @else
                                    ⚠ Data belum lengkap
                                @endif
                            </small>
                        </div>

                        <div class="mb-3">
                            <p class="small text-muted mb-2">Status Verifikasi</p>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar {{ $agent->is_verified ? 'bg-success' : 'bg-secondary' }}" 
                                     style="width: {{ $agent->is_verified ? '100' : '0' }}%">
                                </div>
                            </div>
                            <small class="text-muted">
                                @if($agent->is_verified)
                                    ✓ Sudah Terverifikasi
                                @else
                                    ⏳ Menunggu Verifikasi
                                @endif
                            </small>
                        </div>

                        <hr>

                        <p class="small text-muted mb-1"><strong>Bergabung:</strong></p>
                        <p class="small">{{ $agent->created_at->format('d M Y, H:i') }}</p>

                        <p class="small text-muted mb-1"><strong>Terakhir Update:</strong></p>
                        <p class="small">{{ $agent->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            </div>

        </div>

    </main>
</div>

@endsection