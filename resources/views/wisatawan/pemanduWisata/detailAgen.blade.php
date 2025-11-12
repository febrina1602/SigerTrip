@extends('layouts.app')

@section('title', 'Pemandu Wisata - SigerTrip')

@section('content')
<div class="bg-white">
    <header>
        <div class="container py-2 d-flex align-items-center justify-content-between">
            
            <a href="{{ route('beranda.wisatawan') }}" class="d-flex align-items-center text-decoration-none" style="min-width: 150px;">
                <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo"
                     style="height:42px" loading="lazy" onerror="this.style.display='none'">
                <span class="ms-2 fw-bold text-dark d-none d-md-block">SigerTrip</span>
            </a>

            <form class="flex-grow-1 mx-3 mx-md-4" action="#" method="GET">
                <div class="position-relative" style="max-width: 600px; margin: 0 auto;">
                    <input type="text" class="form-control" name="search"
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

    <nav class="nav-custom border-top bg-white">
        <div class="container py-0">
            <div class="d-flex gap-4 justify-content-left">
                <a href="{{ route('beranda.wisatawan') }}"
                   class="nav-link-custom {{ request()->routeIs('beranda.wisatawan') ? 'active' : '' }}">
                    Beranda
                </a>
                <a href="#" class="nav-link-custom">Pasar Digital</a>
                <a href="#" class="nav-link-custom">Pemandu Wisata</a>
            </div>
        </div>
    </nav>

        @php
            $heroImage = $agent->banner_image_url ?? ($tourPackages->first()->cover_image_url ?? null);
        @endphp
        <div class="w-100">
            <img src="{{ $heroImage ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80' }}" 
                alt="{{ $agent->name }}" 
                class="w-100" style="height: 500px; object-fit: cover;">
        </div> 
        
        <div class="bg-white py-5">
            <div class="container">
                <div class="d-flex align-items-start justify-content-between mb-4">
                    
                    <div class="flex-grow-1">
                        <h1 class="h2 fw-bold text-dark mb-3">{{ $agent->name }}</h1>
                        
                        <div class="d-flex align-items-center gap-3 mb-4">
                            @if($agent->address)
                            <span class="badge bg-light text-dark rounded-pill d-inline-flex align-items-center gap-2 px-3 py-2">
                                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                {{ $agent->address }}
                            </span>
                            @endif
                            
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0">
                        <button class="btn btn-light rounded-circle p-2" title="Bagikan">
                            <svg style="width: 1.5rem; height: 1.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-4 mb-4 flex-wrap">
                    
                    <div class="flex-grow-1" style="min-width: 300px;">
                        <div class="card shadow-sm border-light">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;">
                                        <svg style="width: 2rem; height: 2rem;" class="text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    
                                    <div class="flex-grow-1">
                                        <h3 class="h5 fw-bold text-dark mb-1">{{ $agent->name }}</h3>
                                        @if($agent->is_verified)
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="text-muted small">Terverifikasi</span>
                                            <svg style="width: 1rem; height: 1rem;" class="text-success" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex-shrink-0">
                        <a href="#" 
                        class="btn btn-lg fw-semibold shadow-sm px-5 py-3 text-dark"
                        style="background: linear-gradient(to right, #FFB85C, #FF8C42);">
                            Pilih Paket Perjalanan Anda
                        </a>
                    </div>
                </div>

                <div class="mb-5">
                    <p class="text-muted small">
                        Klik tombol "Pilih Paket Perjalanan Anda" untuk memilih paket perjalanan yang anda inginkan.
                    </p>
                </div>

                @if($agent->description)
                <div class="mt-5" style="max-width: 48rem;">
                    <h2 class="h4 fw-bold text-dark mb-3">Tentang {{ $agent->name }}</h2>
                    <p class="text-secondary" style="line-height: 1.7;">{{ $agent->description }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Footer akan otomatis diambil dari layouts.app --}}
    </div>
@endsection