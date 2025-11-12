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
            <div class="d-flex gap-4 justify-content-center">
                <a href="{{ route('beranda.wisatawan') }}"
                   class="nav-link-custom {{ request()->routeIs('beranda.wisatawan') ? 'active' : '' }}">
                    Beranda
                </a>
                <a href="#" class="nav-link-custom">Pasar Digital</a>
                <a href="#" class="nav-link-custom active">Pemandu Wisata</a>
            </div>
        </div>
    </nav>

    <div class="py-5 bg-white min-vh-100">
        <div class="container">
            
            <h1 class="h3 fw-bold text-dark mb-4 text-start">Agen Tour Lokal</h1>
            
            <div class="row row-cols-1 row-cols-md-3 g-4">
                
                @if($agents->isNotEmpty())
                    
                    @foreach($agents as $agent)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="{{ $agent->banner_image_url ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600&q=80' }}" 
                                 alt="{{ $agent->name }}" 
                                 class="card-img-top" style="height: 200px; object-fit: cover;">
                            
                            <div class="card-body text-start">
                                @if($agent->is_verified)
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="text-success small fw-semibold">Terverifikasi</span>
                                    <svg style="width: 1rem; height: 1rem;" class="text-success" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                @endif
                                <h5 class="card-title fw-bold text-dark mb-3">{{ $agent->name }}</h5>
                                <a href="#" 
                                   class="btn btn-danger w-100 fw-semibold">
                                    Rincian
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                
                @else

                    <div class="col-12 text-center text-muted d-flex flex-column justify-content-center" 
                         style="min-height: 50vh;">
                        
                        <p class="fs-5 mb-2">Belum ada agen tour lokal tersedia</p>
                        <p class="small">Agen tour akan muncul di sini setelah terdaftar dan diverifikasi oleh admin</p>
                    </div>
                    
                @endif
                
            </div>
        </div>
    </div>

    <footer class="footer position-relative">
        <div class="container py-3">
            <div class="row align-items-start">
                <div class="col-md-3 d-flex align-items-center mb-3 mb-md-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SigerTrip" class="me-2" style="height:50px;" loading="lazy">
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
        <img src="{{ asset('images/siger-pattern.png') }}" alt="Siger Pattern" class="siger-pattern" loading="lazy">
    </footer>
</div>
@endsection