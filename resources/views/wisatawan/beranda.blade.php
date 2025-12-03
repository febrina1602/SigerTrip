@extends('layouts.app')

@section('title', 'Beranda - SigerTrip')

@section('content')
<div class="min-vh-100 bg-white">
    
    @include('components.layout.header')

    {{-- KATEGORI --}}
    <section class="category-section">
        <div class="container">
            <div class="row g-4">
                @forelse($categories as $category)
                <div class="col-6 col-md-2">
                    <a href="{{ route('destinations.category', $category->id) }}" class="text-decoration-none">
                        <div class="category-card">
                            <div class="mb-2">
                                @if(!empty($category->icon_url))
                                    <img src="{{ $category->icon_url }}" alt="{{ $category->name }}" class="category-icon" loading="lazy">
                                @else
                                    {{-- Fallback ikon sederhana --}}
                                    <svg class="category-icon" viewBox="0 0 100 100" fill="none" width="64" height="64" aria-hidden="true">
                                        <circle cx="50" cy="50" r="30" fill="#FFB85C"/>
                                    </svg>
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

    {{-- REKOMENDASI --}}
    <section class="bg-white py-5">
        <div class="container">
            <h5 class="fw-bold mb-3 text-start">Rekomendasi buat kamu!</h5>
            
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @forelse($recommendations as $destination)
                <div class="col">
                    <a href="{{ route('destinations.detail', $destination->id) }}" class="card h-100 shadow-sm text-decoration-none text-dark border-0">
                        <img src="{{ $destination->image_url ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400&q=80' }}" 
                            alt="{{ $destination->name }}" 
                            class="card-img-top" style="height: 200px; object-fit: cover;">
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between text-start mb-2">
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
                            
                            @if(!empty($destination->popular_activities))
                            <div class="mb-2 text-start">
                                <p class="small fw-semibold text-dark mb-1">Aktivitas Populer:</p>
                                
                                @php
                                    $acts = $destination->popular_activities;
                                    // decode jika datanya ternyata string
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
                    </a>
                </div>
                @empty
                <div class="col-12 text-center text-secondary py-5">
                    <p class="fs-5 mb-2">Belum ada rekomendasi tersedia</p>
                    <p class="small">Destinasi akan muncul di sini setelah ditambahkan oleh admin</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

</div>
@endsection
