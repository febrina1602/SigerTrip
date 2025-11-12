@extends('layouts.app')

@section('title', $category->name . ' - SigerTrip')

@section('content')
<div class="min-vh-100 bg-white">
    {{-- HEADER --}}
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
                        placeholder="Wisata apa yang kamu cari?"
                        style="border-radius: 50px; padding-left: 2.5rem; height: 44px;">
                    <button type="submit" class="btn p-0" 
                    style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 1.1rem;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <div class="d-flex align-items-center" style="min-width: 150px; justify-content: flex-end;">
                
                @guest
                    {{-- TAMPILAN SAAT BELUM LOGIN (SESUAI DESAIN) --}}
                    <a href="{{ route('login') }}" class="text-dark text-decoration-none d-flex flex-column align-items-center">
                        <i class="fas fa-user-circle" style="font-size: 1.75rem;"></i>
                        <span class="small fw-medium">Akun</span>
                    </a>
                @endguest
                
                @auth
                    {{-- TAMPILAN SAAT SUDAH LOGIN (SESUAI PERMINTAAN ANDA) --}}
                    
                    <a href="{{ route('dashboard') }}" class="text-dark text-decoration-none d-flex flex-column align-items-center me-3">
                        <i class="fas fa-user-circle" style="font-size: 1.75rem;"></i>
                        <span class="small fw-medium">
                            {{-- Mengganti 'Akun' dengan nama user --}}
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
                <a href="#" class="nav-link-custom">Pemandu Wisata</a>
            </div>
        </div>
    </nav>

    <div style="background: linear-gradient(to bottom, #FFF8E7, #FFFFFF);" class="py-5">
        <div class="container">
            <h1 class="h3 fw-bold text-dark mb-4">{{ $category->name }} Pilihan SigerTrip</h1>
            
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @forelse($destinations as $destination)
                <div class="col">
                    <a href="{{ route('destinations.detail', $destination->id) }}" class="card h-100 shadow-sm text-decoration-none text-dark border-0">
                        <img src="{{ $destination->image_url ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400&q=80' }}" 
                             alt="{{ $destination->name }}" 
                             class="card-img-top" style="height: 200px; object-fit: cover;">
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title fw-bold text-dark mb-0">{{ $destination->name }}</h5>
                                <div class="d-flex align-items-center">
                                    @php
                                        $rating = (int) $destination->rating;
                                        $fullStars = min(5, max(0, $rating));
                                    @endphp
                                    @for($star = 0; $star < $fullStars; $star++)
                                    <svg style="width: 1.25rem; height: 1.25rem;" class="text-warning" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                    @endfor
                                    @for($star = $fullStars; $star < 5; $star++)
                                    <svg style="width: 1.25rem; height: 1.25rem;" class="text-muted" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                    @endfor
                                </div>
                            </div>
                            
                            @if($destination->address)
                            <div class="d-flex align-items-center text-muted small mb-3">
                                <svg style="width: 1rem; height: 1rem;" class="me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <span>{{ $destination->address }}</span>
                            </div>
                            @endif
                            
                            <div class="d-flex align-items-center gap-4 mb-3">
                                @if($destination->price_per_person > 0)
                                <div class="d-flex align-items-center text-muted small">
                                    <svg style="width: 1rem; height: 1rem;" class="me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span>{{ number_format($destination->price_per_person, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                @if($destination->parking_price > 0)
                                <div class="d-flex align-items-center text-muted small">
                                    <svg style="width: 1rem; height: 1rem;" class="me-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                    <span>{{ number_format($destination->parking_price, 0, ',', '.') }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12 text-center text-muted py-5">
                    <p class="fs-5 mb-2">Belum ada destinasi {{ strtolower($category->name) }} tersedia</p>
                    <p class="small">Destinasi akan muncul di sini setelah ditambahkan oleh admin</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection