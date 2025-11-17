@extends('layouts.app')

@section('title', $destination->name . ' - SigerTrip')

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
                <a href="#" class="nav-link-custom">Pasar Digital</a>
                <a href="#" class="nav-link-custom">Pemandu Wisata</a>
            </div>
        </div>
    </nav>

    <div>
        <img src="{{ $destination->image_url ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80' }}" 
             alt="{{ $destination->name }}" 
             class="w-100" style="height: 500px; object-fit: cover;">
    </div>

    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="mb-4">
                    @if($destination->is_featured)
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <span class="text-success fw-semibold small">Rekomendasi</span>
                        <svg style="width: 1.25rem; height: 1.25rem;" class="text-success" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    @endif
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="h2 fw-bold text-dark">{{ $destination->name }}</h1>
                        <div class="d-flex align-items-center">
                            @php
                                $rating = (int) $destination->rating;
                                $fullStars = min(5, max(0, $rating));
                            @endphp
                            @for($star = 0; $star < $fullStars; $star++)
                            <svg style="width: 1.5rem; height: 1.5rem;" class="text-warning" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                            @endfor
                            @for($star = $fullStars; $star < 5; $star++)
                            <svg style="width: 1.5rem; height: 1.5rem;" class="text-muted" fill="currentColor" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                            @endfor
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <h2 class="h4 fw-bold text-dark mb-3">Tentang {{ $destination->name }}</h2>
                    <div class="text-secondary" style="line-height: 1.7;">
                        @if($destination->description)
                            {!! nl2br(e($destination->description)) !!}
                        @else
                            <p>{{ $destination->name }} adalah destinasi wisata... (dst)</p>
                        @endif
                    </div>
                </div>

                @if($destination->facilities)
                <div class="mb-4">
                    <h2 class="h4 fw-bold text-dark mb-3">Fasilitas:</h2>
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        @php
                            $facilitiesList = [];
                            if (is_string($destination->facilities)) {
                                $decoded = json_decode($destination->facilities, true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                    $facilitiesList = $decoded;
                                } else {
                                    $facilitiesList = preg_split('/\r\n|\r|\n|,/', $destination->facilities);
                                    $facilitiesList = array_filter(array_map('trim', $facilitiesList));
                                }
                            } elseif (is_array($destination->facilities)) {
                                $facilitiesList = $destination->facilities;
                            }
                        @endphp
                        
                        @foreach($facilitiesList as $facility)
                            @if(!empty(trim($facility)))
                            <div class="col">
                                <div class="d-flex align-items-center">
                                    <svg style="width: 1.25rem; height: 1.25rem;" class="text-success me-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    <span class="text-secondary">{{ trim($facility) }}</span>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="bg-light rounded-3 p-4 mb-4">
                    <h3 class="h5 fw-bold text-dark mb-3">Informasi Harga</h3>
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        @if($destination->price_per_person > 0)
                        <div class="col">
                            <div class="d-flex align-items-center">
                                <svg style="width: 1.25rem; height: 1.25rem;" class="text-muted me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <div>
                                    <p class="small text-muted mb-0">Harga per Orang</p>
                                    <p class="fs-6 fw-semibold text-dark mb-0">Rp {{ number_format($destination->price_per_person, 0, ',', '.') }}</p>
                                </div>
                                </div>
                        </div>
                        @endif
                        @if($destination->parking_price > 0)
                        <div class="col">
                            <div class="d-flex align-items-center">
                                <svg style="width: 1.25rem; height: 1.25rem;" class="text-muted me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                                <div>
                                    <p class="small text-muted mb-0">Harga Parkir</p>
                                    <p class="fs-6 fw-semibold text-dark mb-0">Rp {{ number_format($destination->parking_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                @if(!empty($destination->popular_activities))
                <div class="mb-2 text-start">
                    <p class="small fw-semibold text-dark mb-1">Aktivitas Populer:</p>
                    
                    @php
                        $acts = $destination->popular_activities;
                        //  decode jika datanya ternyata string
                        if (is_string($acts)) {
                            $json = json_decode($acts, true);
                            if (json_last_error() === JSON_ERROR_NONE && is_array($json)) { 
                                $acts = $json; 
                            }
                        }
                    @endphp

                    <p class="small text-muted mb-0">
                        @if(is_array($acts))
                            {{ implode(', ', $acts) }}
                        @else
                            {{ $acts }}
                        @endif
                    </p>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="sticky-top" style="top: 2rem;">
                    <h3 class="h5 fw-bold text-dark mb-3">Lokasi</h3>
                    @if($destination->address)
                    <p class="text-muted mb-3 d-flex">
                        <svg style="width: 1.25rem; height: 1.25rem;" class="me-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span>{{ $destination->address }}</span>
                    </p>
                    @endif
                    
                    <div class="w-100 rounded-3 overflow-hidden shadow-lg" style="height: 400px;">
                        
                        {{-- Ambil Kunci API dari file .env --}}
                        @php
                            $apiKey = env('GOOGLE_MAPS_API_KEY'); 
                        @endphp

                        @if($destination->latitude && $destination->longitude)
                            <iframe
                                width="100%" height="100%" style="border:0"
                                loading="lazy" allowfullscreen
                                referrerpolicy="no-referrer-when-downgrade"
                                src="https://www.google.com/maps?q={{ $apiKey }}&q={{ $destination->latitude }},{{ $destination->longitude }}&hl=id&z=15&output=embed">
                            </iframe>
                        @elseif($destination->address)
                            <iframe
                                width="100%" height="100%" style="border:0"
                                loading="lazy" allowfullscreen
                                referrerpolicy="no-referrer-when-downgrade"
                                src="https://www.google.com/maps?q={{ $apiKey }}&q={{ urlencode($destination->address) }}&hl=id&z=15&output=embed">
                            </iframe>
                        @else
                            <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center">
                                <p class="text-muted">Peta tidak tersedia</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection