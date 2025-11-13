@extends('layouts.app')

@section('title', $tourPackage->name . ' - ' . $agent->name)

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
                    <li class="breadcrumb-item">
                        <a href="{{ route('pemandu-wisata.packages', $agent->id) }}" class="text-decoration-none">Paket Perjalanan</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $tourPackage->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div>
        <img src="{{ $tourPackage->cover_image_url ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80' }}" 
             alt="{{ $tourPackage->name }}" 
             class="w-100" style="height: 450px; object-fit: cover;">
    </div>
    
    <div class="container py-5">
        <div class="row g-5">
            
            <div class="col-lg-8">
                <h1 class="h3 fw-bold text-dark mb-4">{{ $tourPackage->name }}</h1>
                
                @php
                    $destinasiDikunjungi = $tourPackage->destinations();
                @endphp
                @if($destinasiDikunjungi->isNotEmpty())
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Destinasi yang Dikunjungi:</h5>
                    <div class="row row-cols-2 g-3">
                        @foreach($destinasiDikunjungi as $dest)
                        <div class="col">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-star text-warning"></i>
                                <span class="text-muted">{{ $dest->name }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @php
                    $fasilitas = $tourPackage->facilities_array;
                @endphp
                @if(!empty($fasilitas))
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Fasilitas:</h5>
                    <div class="row row-cols-2 g-3">
                        @foreach($fasilitas as $item)
                        <div class="col">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-star text-warning"></i>
                                <span class="text-muted">{{ $item }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($tourPackage->description)
                <div class="mt-5">
                    <h5 class="fw-bold mb-3">Deskripsi Paket</h5>
                    <p class="text-secondary" style="line-height: 1.7;">
                        {!! nl2br(e($tourPackage->description)) !!}
                    </p>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-light" style="position: sticky; top: 2rem;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Informasi Paket</h5>
                        
                        @if($tourPackage->duration)
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <i class="fas fa-clock text-muted fa-fw mt-1"></i>
                            <div>
                                <h6 class="fw-semibold text-dark mb-0">Durasi:</h6>
                                <span class="text-muted">{{ $tourPackage->duration }}</span>
                            </div>
                        </div>
                        @endif

                        @if($tourPackage->price_per_person > 0)
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <i class="fas fa-tag text-muted fa-fw mt-1"></i>
                            <div>
                                <h6 class="fw-semibold text-dark mb-0">Harga Mulai:</h6>
                                <span class="text-muted">
                                    Rp{{ number_format($tourPackage->price_per_person, 0, ',', '.') }}
                                    @if($tourPackage->minimum_participants)
                                        (min. {{ $tourPackage->minimum_participants }} orang)
                                    @endif
                                </span>
                            </div>
                        </div>
                        @endif
                        
                        <hr class="my-3">
                        
                        <p class="small text-muted text-center">
                            Klik tombol "Hubungi Kami" untuk melakukan pemesanan pada agensi kami.
                        </p>
                        
                        <div class="d-grid">
                            @php
                                $kontak = $agent->phone_number ?? $agent->contact_phone;
                                $waLink = $kontak ? 'https://api.whatsapp.com/send?phone=' . preg_replace('/[^0-9]/', '', $kontak) : '#';
                            @endphp
                            <a href="{{ $waLink }}" target="_blank" class="btn btn-lg fw-semibold text-dark" style="background-color: #FFD15C;">
                                <i class="fab fa-whatsapp me-2"></i> Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection