@extends('layouts.app')

@section('title', $category->name . ' - SigerTrip')

@section('content')
<div class="min-vh-100 bg-white">
    <header>
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo">
        </div>
        <div>
            <button class="btn-custom me-2">Masuk</button>
            <button class="btn-custom">Daftar</button>
        </div>
    </header>

    <nav class="nav-custom">
        <div class="container py-0">
            <div class="d-flex gap-4">
                <a href="{{ route('beranda.wisatawan') }}" class="nav-link-custom active">
                    Beranda
                </a>
                <a href="#" class="nav-link-custom">
                    Pasar Digital
                </a>
                <a href="#" class="nav-link-custom">
                    Pemandu Wisata
                </a>
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

    <!-- FOOTER -->
    <footer class="footer position-relative">
        <div class="container py-3">
            <div class="row align-items-start">
                <div class="col-md-3 d-flex align-items-center mb-3 mb-md-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SigerTrip" class="me-2" style="height:50px;">
                </div>

                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <h6 class="fw-bold mb-2">Ikuti Kami</h6>
                    <div class="d-flex justify-content-center align-items-center social-icons">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-x-twitter"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div class="col-md-3 mb-3 mb-md-0">
                    <h6 class="fw-bold mb-2">Dibuat Oleh:</h6>
                    <p class="mb-1">Febrina Aulia Azahra</p>
                    <p class="mb-1">Carissa Oktavia Sanjaya</p>
                    <p class="mb-1">Dilvi Yola</p>
                    <p class="mb-0">M. Hafiz Abyan</p>
                </div>

                <div class="col-md-3">
                    <h6 class="fw-bold mb-2">Informasi</h6>
                    <p class="mb-1"><a href="#" class="footer-link">Tentang</a></p>
                    <p class="mb-0"><a href="#" class="footer-link">FAQ</a></p>
                </div>
            </div>
        </div>
        <img src="{{ asset('images/siger-pattern.png') }}" alt="Siger Pattern" class="siger-pattern">
    </footer>
</div>
@endsection