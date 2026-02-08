@extends('layouts.app')

@section('title', 'Paket Perjalanan - ' . ($agent->name ?? 'Agen Tour') . ' - SigerTrip')

@section('content')
<div class="bg-white min-vh-100">
    {{-- HEADER --}}
    <header>
        <div class="container py-2 d-flex align-items-center justify-content-between">
            <a href="{{ route('beranda.wisatawan') }}" class="d-flex align-items-center text-decoration-none" style="min-width: 150px;">
                <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo"
                     style="height:42px" loading="lazy" onerror="this.style.display='none'">
                <span class="ms-2 fw-bold text-dark d-none d-md-block">SigerTrip</span>
            </a>

            <form class="flex-grow-1 mx-3 mx-md-4" action="{{ route('pemandu-wisata.index') }}" method="GET">
                <div class="position-relative" style="max-width: 600px; margin: 0 auto;">
                    <input type="text" class="form-control" name="q"
                           placeholder="Agen tour apa yang kamu butuhkan?"
                           style="border-radius: 50px; padding-left: 2.5rem; height: 44px;">
                    <button type="submit" class="btn p-0" 
                       style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 1.1rem;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <div class="d-flex align-items-center" style="min-width: 150px; justify-content: flex-end;">
                @guest
                    <a href="{{ route('login') }}" class="text-dark text-decoration-none d-flex flex-column align-items-center">
                        <i class="fas fa-user-circle" style="font-size: 1.75rem;"></i>
                        <span class="small fw-medium">Akun</span>
                    </a>
                @endguest
                @auth
                    <a href="{{ route('dashboard') }}" class="text-dark text-decoration-none d-flex flex-column align-items-center me-3">
                        <i class="fas fa-user-circle" style="font-size: 1.75rem;"></i>
                        <span class="small fw-medium">
                            {{ \Illuminate\Support\Str::limit(auth()->user()->full_name ?? auth()->user()->name, 15) }}
                        </span>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-link text-danger p-0" title="Logout" 
                                style="font-size: 1.6rem; line-height: 1;">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    {{-- NAV --}}
    <nav class="nav-custom border-top bg-white">
        <div class="container py-0">
            <div class="d-flex gap-4 justify-content-left">
                <a href="{{ route('beranda.wisatawan') }}"
                   class="nav-link-custom {{ request()->routeIs('beranda.wisatawan') ? 'active' : '' }}">
                    Beranda
                </a>
                <a href="#" class="nav-link-custom">Pasar Digital</a>
                <a href="{{ route('pemandu-wisata.index') }}" 
                   class="nav-link-custom {{ request()->routeIs('pemandu-wisata.*') }}">
                   Pemandu Wisata
                </a>
            </div>
        </div>
    </nav>

    <div class="bg-light py-3 border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: '>';">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item">
                        <a href="{{ route('pemandu-wisata.index') }}" class="text-decoration-none">Pemandu Wisata</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('pemandu-wisata.show', $agent->id) }}" class="text-decoration-none">{{ $agent->name }}</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Paket Perjalanan</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="bg-light py-5">
        <div class="container">
            <div class="mb-4 text-start">
                <h1 class="h3 fw-bold text-dark mb-2">Paket Perjalanan dari {{ $agent->name }}</h1>
                <p class="text-muted">Pilih paket perjalanan yang sesuai dengan kebutuhan Anda</p>
            </div>
            
            @if($tourPackages->isNotEmpty())
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($tourPackages as $package)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        {{-- Image --}}
                        <div class="position-relative">
                            <img src="{{ $package->cover_image_url ? asset('storage/'.$package->cover_image_url) : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600&q=80' }}" 
                                 alt="{{ $package->name }}" 
                                 class="card-img-top" 
                                 style="height: 200px; object-fit: cover;">
                            
                            @if($package->price_per_person > 0)
                            <div class="position-absolute bottom-0 end-0 bg-dark p-2" style="border-top-left-radius: 0.5rem; opacity: 0.85;">
                                <p class="small mb-0 text-white opacity-75" style="font-size: 0.7rem; line-height: 1.2;">MULAI DARI</p>
                                <p class="h5 fw-bold mb-0 text-white" style="line-height: 1.2;">Rp {{ number_format($package->price_per_person, 0, ',', '.') }}</p>
                                <p class="small mb-0 text-white opacity-75" style="font-size: 0.7rem; line-height: 1.2;">/ ORANG</p>
                            </div>
                            @endif
                        </div>
                        
                        <div class="card-body d-flex flex-column text-start">
                            <h5 class="card-title fw-bold text-dark mb-2">{{ $package->name }}</h5>
                            
                            @if($package->duration)
                            <div class="d-flex align-items-center gap-2 small text-muted mb-2">
                                <i class="fas fa-clock fa-fw"></i>
                                <span>{{ $package->duration }}</span>
                            </div>
                            @endif
                            
                            @if($package->description)
                            <p class="small text-muted mb-3">
                                {{ Str::limit($package->description, 100) }}
                            </p>
                            @endif
                            
                            <a href="{{ route('pemandu-wisata.package-detail', [$agent->id, $package->id]) }}" 
                               class="btn btn-danger w-100 fw-semibold mt-auto">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center text-muted py-5 bg-white rounded shadow-sm d-flex flex-column justify-content-center" style="min-height: 40vh;">
                <p class="fs-5 mb-2">Belum ada paket perjalanan tersedia</p>
                <p class="small">Paket perjalanan akan muncul di sini setelah ditambahkan oleh agen</p>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection