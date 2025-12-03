@extends('layouts.app')

@section('title', 'Paket Perjalanan - ' . ($agent->name ?? 'Agen Tour') . ' - SigerTrip')

@section('content')
<div class="bg-white min-vh-100">
    {{-- HEADER --}}
    <header>
            <div class="container py-2 d-flex align-items-center justify-content-between">
            
            @php
                $homeRoute = auth()->check() && auth()->user()->role == 'agent' ? route('agent.dashboard') : route('beranda.wisatawan');
                $marketRoute = auth()->check() && auth()->user()->role == 'agent' ? route('agent.pasar-digital.index') : route('pasar-digital.index');
            @endphp

            <a href="{{ $homeRoute }}" class="d-flex align-items-center text-decoration-none" style="min-width: 150px;">
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
    </header>

    {{-- NAV --}}
    <nav class="nav-custom border-top bg-white">
        <div class="container py-0">
            <div class="d-flex gap-4 justify-content-left">
                <a href="{{ $homeRoute }}"
                   class="nav-link-custom {{ (auth()->check() && auth()->user()->role == 'agent') ? (request()->routeIs('agent.dashboard') ? 'active' : '') : (request()->routeIs('beranda.*') ? 'active' : '') }}">
                    Beranda
                </a>
                <a href="{{ $marketRoute }}" class="nav-link-custom {{ (auth()->check() && auth()->user()->role == 'agent') ? (request()->routeIs('agent.pasar-digital.*') ? 'active' : '') : (request()->routeIs('pasar-digital.*') ? 'active' : '') }}">
                    Pasar Digital
                </a>
                <a href="{{ route('pemandu-wisata.index') }}" class="nav-link-custom {{ request()->routeIs('pemandu-wisata.*')}} ">
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
                    <div class="bg-white min-vh-100">
                        @if(auth()->check() && auth()->user()->role == 'agent')
                            @include('agent._header')
                        @else
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
                            </header>

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
                                        <a href="{{ route('pemandu-wisata.index') }}" class="nav-link-custom {{ request()->routeIs('pemandu-wisata.*')}} ">
                                            Pemandu Wisata
                                        </a>
                                    </div>
                                </div>
                            </nav>
                        @endif