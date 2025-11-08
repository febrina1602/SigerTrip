@extends('layouts.app')

@section('title', 'Beranda - SigerTrip')

@section('content')
<div class="min-vh-100 bg-white">
    
    <!-- HEADER -->
    <header>
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo">
        </div>
        <div>
            <button class="btn-custom me-2">Masuk</button>
            <button class="btn-custom">Daftar</button>
        </div>
    </header>

    <!-- Navigation -->
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

    <!-- Category Section-->
    <section class="category-section">
        <div class="container">
            <div class="row g-4">
                @forelse($categories as $category)
                <div class="col-6 col-md-2">
                    <a href="{{ route('destinations.category', $category->id) }}" class="text-decoration-none">
                        <div class="category-card">
                            <div class="mb-2">
                                @if($category->icon_url)
                                    <img src="{{ $category->icon_url }}" alt="{{ $category->name }}" class="category-icon">
                                @else
                                    @if(strtolower($category->name) == 'pantai')
                                        <svg class="category-icon" viewBox="0 0 100 100" fill="none">
                                            <path d="M20 60L30 40L50 30L70 40L80 60L70 70L50 75L30 70L20 60Z" fill="#FF6B6B"/>
                                            <rect x="45" y="70" width="10" height="20" fill="#8B4513"/>
                                            <circle cx="35" cy="35" r="15" fill="#FFE66D" stroke="#FF8C42" stroke-width="2"/>
                                            <path d="M10 60L90 60L85 90L15 90L10 60Z" fill="#4ECDC4"/>
                                        </svg>
                                    @elseif(strtolower($category->name) == 'gunung')
                                        <svg class="category-icon" viewBox="0 0 100 100" fill="none">
                                            <path d="M50 20L35 50L20 80L50 90L80 80L65 50L50 20Z" fill="#4CAF50"/>
                                            <path d="M50 20L45 40L40 60L50 90L60 60L55 40L50 20Z" fill="#81C784"/>
                                            <rect x="47" y="85" width="6" height="15" fill="#8B4513"/>
                                            <circle cx="65" cy="35" r="8" fill="#87CEEB"/>
                                        </svg>
                                    @elseif(strtolower($category->name) == 'kuliner')
                                        <svg class="category-icon" viewBox="0 0 100 100" fill="none">
                                            <circle cx="50" cy="50" r="25" fill="#8B4513"/>
                                            <path d="M50 25L60 35L70 25L65 45L85 50L65 55L70 75L60 65L50 75L40 65L30 75L35 55L15 50L35 45L30 25L40 35L50 25Z" fill="#FFD700"/>
                                            <path d="M45 45L50 55L55 45" stroke="#FF6B6B" stroke-width="2" fill="none"/>
                                        </svg>
                                    @elseif(strtolower($category->name) == 'budaya')
                                        <svg class="category-icon" viewBox="0 0 100 100" fill="none">
                                            <rect x="30" y="20" width="40" height="60" fill="#FFD700"/>
                                            <path d="M30 20L50 10L70 20L70 50L50 80L30 50L30 20Z" fill="#FFA500"/>
                                            <rect x="45" y="40" width="10" height="15" fill="#8B4513"/>
                                            <rect x="35" y="55" width="30" height="5" fill="#8B4513"/>
                                            <circle cx="40" cy="30" r="3" fill="#FF6B6B"/>
                                            <circle cx="60" cy="30" r="3" fill="#FF6B6B"/>
                                        </svg>
                                    @elseif(strtolower($category->name) == 'air terjun')
                                        <svg class="category-icon" viewBox="0 0 100 100" fill="none">
                                            <path d="M40 10L45 40L50 70L55 40L60 10L50 20L40 10Z" fill="#4ECDC4"/>
                                            <path d="M45 40L50 70L55 40" stroke="#1E88E5" stroke-width="3"/>
                                            <path d="M30 70L70 70L65 90L35 90L30 70Z" fill="#4CAF50"/>
                                            <circle cx="50" cy="75" r="8" fill="#81C784"/>
                                        </svg>
                                    @else
                                        <svg class="category-icon" viewBox="0 0 100 100" fill="none">
                                            <circle cx="50" cy="50" r="30" fill="#FFB85C"/>
                                        </svg>
                                    @endif
                                @endif
                            </div>
                            <span class="small fw-medium text-dark text-center d-block">{{ $category->name }}</span>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12 text-center text-secondary py-5">
                    Belum ada kategori tersedia
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Recommendations Section -->
    <section class="bg-white py-5">
        <div class="container">
            <h5 class="fw-bold mb-3">Rekomendasi buat kamu!</h5>
            
        </div>
    </section>

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