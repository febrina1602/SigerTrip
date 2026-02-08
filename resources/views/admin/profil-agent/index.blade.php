@extends('layouts.app')

@section('title', 'Profil Agent - Admin')

@push('styles')
<style>
    .status-badge {
        display: inline-block;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-verified {
        background-color: #d4edda;
        color: #155724;
    }

    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-incomplete {
        background-color: #f8d7da;
        color: #721c24;
    }

    .agent-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .agent-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }

    .filter-btn {
        padding: 0.5rem 1.2rem;
        border-radius: 20px;
        font-weight: 600;
        transition: all 0.2s;
        border: 2px solid #ddd;
        background: white;
        color: #333;
    }

    .filter-btn.active {
        background: linear-gradient(00deg, #FFD15C 0%, #FF9739 45%);
        color: white;
        border-color: transparent;
    }

    .filter-btn:hover {
        transform: translateY(-2px);
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
            <a href="{{ route('admin.beranda') }}" class="nav-link-custom {{ request()->routeIs('admin.beranda') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('admin.profil-agent.index') }}" class="nav-link-custom {{ request()->routeIs('admin.profil-agent.*') ? 'active' : '' }}">Profil Agent</a>
            <a href="{{ route('admin.pasar.index') }}" class="nav-link-custom {{ request()->routeIs('admin.pasar.*') ? 'active' : '' }}">Pasar Digital</a>
            <a href="{{ route('admin.tour-packages.index') }}" class="nav-link-custom">Pemandu Wisata</a>
            <a href="{{ route('admin.users.index') }}" class="nav-link-custom {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Kelola User</a>
        </div>
    </nav>

    <main class="container py-4 py-md-5">

        {{-- TITLE --}}
        <div class="mb-4">
            <h2 class="fw-bold mb-0" style="font-size: 1.5rem;">Profil Agent</h2>
            <p class="text-muted mb-0">Validasi dan kelola profil agent sebelum mereka dapat menambah paket wisata.</p>
        </div>

        {{-- PESAN SUKSES/ERROR --}}
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

        {{-- STATISTIK --}}
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6 class="text-muted mb-1">Total Agent</h6>
                    <h3 class="fw-bold text-primary mb-0">{{ $totalAgents }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6 class="text-muted mb-1">Terverifikasi</h6>
                    <h3 class="fw-bold text-success mb-0">{{ $verifiedAgents }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6 class="text-muted mb-1">Menunggu</h6>
                    <h3 class="fw-bold text-warning mb-0">{{ $incompleteAgents }}</h3>
                </div>
            </div>
        </div>

        {{-- FILTER BUTTONS --}}
        @php $filter = request('filter', 'semua'); @endphp
        <div class="d-flex gap-2 mb-4 flex-wrap">
            <a href="{{ route('admin.profil-agent.index', ['filter' => 'semua']) }}" 
               class="filter-btn {{ $filter === 'semua' ? 'active' : '' }}">
                <i class="fas fa-list me-1"></i> Semua Agent
            </a>
            <a href="{{ route('admin.profil-agent.index', ['filter' => 'terverifikasi']) }}" 
               class="filter-btn {{ $filter === 'terverifikasi' ? 'active' : '' }}">
                <i class="fas fa-check-circle me-1"></i> Terverifikasi
            </a>
            <a href="{{ route('admin.profil-agent.index', ['filter' => 'menunggu']) }}" 
               class="filter-btn {{ $filter === 'menunggu' ? 'active' : '' }}">
                <i class="fas fa-hourglass-half me-1"></i> Menunggu Verifikasi
            </a>
            <a href="{{ route('admin.profil-agent.index', ['filter' => 'belum']) }}" 
               class="filter-btn {{ $filter === 'belum' ? 'active' : '' }}">
                <i class="fas fa-exclamation-circle me-1"></i> Belum Lengkap
            </a>
        </div>

        {{-- DAFTAR AGENT --}}
        <h5 class="fw-bold mb-3">Daftar Agent ({{ $agents->count() }})</h5>

        @if($agents->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-users mb-3" style="font-size:40px; color:#C4C4C4;"></i>
                <p class="fw-semibold mb-1">Tidak ada agent</p>
                <p class="text-muted">Tidak ada agent dengan status yang dipilih.</p>
            </div>
        @else

            <div class="row g-3">
                @foreach($agents as $agent)
                    <div class="col-lg-6">
                        <div class="card agent-card border-0 shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ $agent->user->full_name ?? $agent->user->name }}</h6>
                                        <small class="text-muted">{{ $agent->user->email }}</small>
                                    </div>
                                    <span class="status-badge {{ $agent->is_verified ? 'status-verified' : ($agent->is_profile_complete ? 'status-pending' : 'status-incomplete') }}">
                                        @if($agent->is_verified)
                                            <i class="fas fa-check-circle me-1"></i> Terverifikasi
                                        @elseif($agent->is_profile_complete)
                                            <i class="fas fa-hourglass-half me-1"></i> Menunggu
                                        @else
                                            <i class="fas fa-exclamation-circle me-1"></i> Belum Lengkap
                                        @endif
                                    </span>
                                </div>

                                {{-- Info Profil --}}
                                <div class="small mb-3">
                                    <p class="mb-1"><strong>Agensi:</strong> {{ $agent->name ?? '-' }}</p>
                                    <p class="mb-1"><strong>Kontak:</strong> {{ $agent->contact_phone ?? '-' }}</p>
                                    <p class="mb-1"><strong>Lokasi:</strong> {{ $agent->location ?? '-' }}</p>
                                    <p class="mb-0"><strong>Deskripsi:</strong> {{ Str::limit($agent->description, 60) ?? '-' }}</p>
                                </div>

                                {{-- Aksi --}}
                                <div class="d-flex gap-2 pt-2 border-top">
                                    <a href="{{ route('admin.profil-agent.show', $agent->id) }}" class="btn btn-sm btn-primary flex-grow-1">
                                        <i class="fas fa-eye me-1"></i> Lihat Detail
                                    </a>
                                    
                                    @if(!$agent->is_verified && $agent->is_profile_complete)
                                        <form action="{{ route('admin.profil-agent.verify', $agent->id) }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success w-100" onclick="return confirm('Verifikasi profil agent ini?')">
                                                <i class="fas fa-check me-1"></i> Verifikasi
                                            </button>
                                        </form>
                                    @elseif($agent->is_verified)
                                        <form action="{{ route('admin.profil-agent.reset', $agent->id) }}" method="POST" class="flex-grow-1">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-warning w-100" onclick="return confirm('Reset status verifikasi?')">
                                                <i class="fas fa-redo me-1"></i> Reset
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Delete --}}
                                    <form action="{{ route('admin.profil-agent.destroy', $agent->id) }}" method="POST" onsubmit="return confirm('Yakin hapus agent ini? Tindakan ini tidak bisa dibatalkan.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @endif

    </main>
</div>

@endsection