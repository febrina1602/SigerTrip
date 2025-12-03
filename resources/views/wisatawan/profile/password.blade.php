@extends('layouts.app')

@section('title', 'Ubah Password - SigerTrip')

@section('content')
<div class="bg-white">
    @include('components.layout.header')
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
                    <a href="{{ route('login') }}" class="text-dark text-decoration-none d-flex flex-column align-items-center">
                        <i class="fas fa-user-circle" style="font-size: 1.75rem;"></i>
                        <span class="small fw-medium">Akun</span>
                    </a>
                @endguest
                
                @auth
                    @php
                        $profileRoute = auth()->user()->role == 'agent' 
                                      ? route('agent.dashboard') 
                                      : route('profile.show');
                    @endphp
                    <a href="{{ $profileRoute }}" class="text-dark text-decoration-none d-flex flex-column align-items-center me-3">
                        <img src="{{ auth()->user()->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->full_name) . '&background=FFD15C&color=333&bold=true' }}" 
                             alt="Foto Profil" 
                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #eee;">
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
    {{-- NAV --}}
    <nav class="nav-custom border-top bg-white">
        <div class="container py-0">
            <div class="d-flex gap-4 justify-content-left">
                <a href="{{ route('beranda.wisatawan') }}"
                class="nav-link-custom {{ request()->routeIs('beranda.wisatawan') ? 'active' : '' }}">
                    Beranda
                </a>
                <a href="{{ route('pasar-digital.index') }}" class="nav-link-custom {{ request()->routeIs('pasar-digital.*') ? 'active' : '' }}">
                    Pasar Digital
                </a>
                <a href="{{ route('pemandu-wisata.index') }}" class="nav-link-custom {{ request()->routeIs('pemandu-wisata.*') }} ">
                    Pemandu Wisata
                </a>
            </div>
        </div>
    </nav>

    <div class="py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card shadow-sm border-0 rounded-4">
                        <div class="card-body p-4 p-md-5">
                            <h3 class="h3 fw-bold text-dark mb-4 text-center">Ubah Password</h3>

                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-3">
                                    <label for="password" class="form-label fw-medium">Password Baru</label>
                                    <input type="password" class="form-control" id="password" name="password" 
                                           placeholder="Minimal 8 karakter" required>
                                </div>
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label fw-medium">Konfirmasi Password Baru</label>
                                    <input type="password" class="form-control" id="password_confirmation" 
                                           name="password_confirmation" placeholder="Ketik ulang password baru Anda" required>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-lg fw-semibold text-white" 
                                            style="background: linear-gradient(90deg, #FFD15C, #FF3D3D); border: none;">
                                        Ubah Password
                                    </button>
                                </div>
                            </form>
                            
                            <div class="text-center mt-3">
                                <a href="{{ route('profile.show') }}" class="small text-muted text-decoration-none">Kembali ke Profil</a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection