@extends('layouts.app')

@section('title', 'Pasar Digital - SigerTrip')

@push('styles')
<style>
    .category-link {
        display: block;
        padding: 1.5rem 1rem;
        border-radius: 12px;
        background-color: #fff;
        border: 1px solid #eee;
        text-decoration: none;
        color: #333;
        font-weight: 600;
        text-align: center;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .category-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.08);
    }
    .category-link.active {
        border-color: #FF3D3D;
        background-color: #FFF5F5;
        color: #FF3D3D;
        box-shadow: 0 4px 10px rgba(255, 61, 61, 0.1);
    }
    .category-link i {
        font-size: 2rem;
        margin-bottom: 0.75rem;
        display: block;
        color: #FF9739;
    }
    .category-link.active i {
        color: #FF3D3D;
    }
</style>
@endpush

@section('content')
<div class="bg-white min-vh-100">
    
    @include('components.layout.header')

    {{-- Kategori Kendaraan --}}
    <div class="py-5" style="background: linear-gradient(to bottom, #FFF8E7, #FFFFFF);">
        <div class="container">
            <h1 class="h3 fw-bold text-dark mb-4 text-start">Pasar Digital</h1>
            <div class="row g-3">
                <div class="col-md-3 col-6">
                    <a href="{{ route('pasar-digital.index') }}" class="category-link {{ !$currentType ? 'active' : '' }}">
                        <i class="fas fa-car-alt"></i> Semua Kendaraan
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="{{ route('pasar-digital.index', ['type' => 'CAR']) }}" class="category-link {{ $currentType == 'CAR' ? 'active' : '' }}">
                        <i class="fas fa-car"></i> Mobil
                    </a>
                </div>
                <div class="col-md-3 col-6">
                    <a href="{{ route('pasar-digital.index', ['type' => 'MOTORCYCLE']) }}" class="category-link {{ $currentType == 'MOTORCYCLE' ? 'active' : '' }}">
                        <i class="fas fa-motorcycle"></i> Motor
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Kendaraan --}}
    <div class="py-5 bg-white">
        <div class="container">
            <h3 class="h4 fw-bold text-dark mb-4 text-start">
                @if($currentType == 'CAR')
                    Pilihan Mobil
                @elseif($currentType == 'MOTORCYCLE')
                    Pilihan Motor
                @else
                    Semua Pilihan Kendaraan
                @endif
            </h3>
            
            <div class="row row-cols-1 row-cols-md-3 g-4">
                
                @forelse($vehicles as $vehicle)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0">
                        <a href="{{ route('pasar-digital.detail', $vehicle->id) }}" class="text-decoration-none">
                            <img src="{{ asset('storage/'.$vehicle->image_url) ?? 'https://images.unsplash.com/photo-1553531889-a2b91d310614?w=600&q=80' }}" 
                                 alt="{{ $vehicle->name }}" 
                                 class="card-img-top" style="height: 200px; object-fit: cover;">
                        </a>
                        
                        <div class="card-body text-start d-flex flex-column">
                            <h5 class="card-title fw-bold text-dark mb-2">{{ $vehicle->name }}</h5>
                            
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="fas fa-user-check text-success small"></i>
                                <span class="text-muted small"><strong>{{ $vehicle->agent->name }}</strong></span>
                            </div>

                            <div class="mb-3">
                                <span class="text-dark h5 fw-bold">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}</span>
                                <span class="text-muted small">/ hari</span>
                            </div>

                            <a href="{{ route('pasar-digital.detail', $vehicle->id) }}"
                               class="btn btn-danger w-100 fw-semibold mt-auto">
                                Rincian
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                    <div class="col-12 text-center text-muted d-flex flex-column justify-content-center" 
                         style="min-height: 30vh;">
                        <i class="fas fa-car-side fa-3x mb-3 text-muted"></i>
                        <p class="fs-5 mb-2">Kendaraan tidak ditemukan</p>
                        <p class="small">Coba ubah filter atau kata kunci pencarian Anda.</p>
                    </div>
                @endforelse
                
            </div>

            <div class="mt-5">
                {{ $vehicles->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
