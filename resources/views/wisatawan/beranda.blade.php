@extends('layouts.app')

@section('title', 'Beranda - SigerTrip')

@section('content')
<div class="min-vh-100 bg-white">
    
    {{-- Header Component --}}
    @include('components.layout.header')

    {{-- KATEGORI --}}
    <section class="category-section py-4" style="background: linear-gradient(to bottom, #FFF8E7, #FFFFFF);">
        <div class="container">
            <div class="row g-3 justify-content-center">
                @forelse($categories as $category)
                <div class="col-4 col-md-2">
                    <a href="{{ route('destinations.category', $category->id) }}" class="text-decoration-none">
                        <div class="category-card text-center p-3 h-100 rounded-4 bg-white shadow-sm hover-shadow transition">
                            <div class="mb-2">
                                @if(!empty($category->icon_url))
                                    <img src="{{ asset($category->icon_url) }}" alt="{{ $category->name }}" class="category-icon" style="width: 50px; height: 50px; object-fit: contain;">
                                @else
                                    <i class="fas fa-map-marked-alt text-warning fa-2x"></i>
                                @endif
                            </div>
                            <span class="small fw-bold text-dark d-block">{{ $category->name }}</span>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12 text-center text-secondary py-3">
                    Belum ada kategori tersedia
                </div>
                @endforelse
            </div>
        </div>
    </section>

    {{-- REKOMENDASI --}}
    <section class="bg-white py-5">
        <div class="container">
            <h4 class="fw-bold mb-4 text-start border-start border-4 border-warning ps-3">Rekomendasi buat kamu!</h4>
            
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @forelse($recommendations as $destination)
                <div class="col">
                    <a href="{{ route('destinations.detail', $destination->id) }}" class="card h-100 shadow-sm text-decoration-none text-dark border-0 rounded-4 overflow-hidden">
                        <img src="{{ $destination->image_url ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400&q=80' }}" 
                            alt="{{ $destination->name }}" 
                            class="card-img-top" style="height: 200px; object-fit: cover;">
                        
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title fw-bold text-dark mb-0 text-truncate">{{ $destination->name }}</h5>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-star text-warning small me-1"></i>
                                    <span class="small fw-bold">{{ number_format($destination->rating, 1) }}</span>
                                </div>
                            </div>
                            
                            @if($destination->address)
                            <div class="d-flex align-items-center text-muted small mb-3">
                                <i class="fas fa-map-marker-alt me-2 text-danger"></i>
                                <span class="text-truncate">{{ $destination->address }}</span>
                            </div>
                            @endif
                            
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top">
                                <div>
                                    <p class="mb-0 small text-muted">Harga Tiket</p>
                                    <p class="mb-0 fw-bold text-primary">Rp {{ number_format($destination->price_per_person, 0, ',', '.') }}</p>
                                </div>
                                <span class="btn btn-sm btn-outline-primary rounded-pill">Detail <i class="fas fa-arrow-right ms-1"></i></span>
                            </div>
                        </div>
                    </a>
                </div>
                @empty
                <div class="col-12 text-center text-secondary py-5">
                    <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-state-2130362-1800926.png" alt="Empty" style="width: 200px; opacity: 0.5;">
                    <p class="mt-3">Belum ada rekomendasi saat ini.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
    .transition {
        transition: all 0.3s ease;
    }
</style>
@endsection