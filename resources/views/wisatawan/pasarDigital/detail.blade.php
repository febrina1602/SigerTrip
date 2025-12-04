@extends('layouts.app')

@section('title', $vehicle->name . ' - SigerTrip')

@section('content')
<div class="bg-white min-vh-100">

    @include('components.layout.header')

    {{-- Breadcrumb --}}
    <div class="bg-light py-3 border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: '>';">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item">
                        <a href="{{ route('pasar-digital.index') }}" class="text-decoration-none">Pasar Digital</a>
                    </li>
                    @if($vehicle->vehicle_type)
                    <li class="breadcrumb-item">
                        <a href="{{ route('pasar-digital.index', ['type' => $vehicle->vehicle_type]) }}" class="text-decoration-none">
                            {{ $vehicle->vehicle_type == 'CAR' ? 'Mobil' : 'Motor' }}
                        </a>
                    </li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $vehicle->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div>
        <img src="{{ $vehicle->image_url ?? 'https://images.unsplash.com/photo-1553531889-a2b91d310614?w=1200&q=80' }}" 
             alt="{{ $vehicle->name }}" 
             class="w-100" style="height: 450px; object-fit: cover;">
    </div>
    
    <div class="container py-5">
        <div class="row g-5">
            
            <div class="col-lg-8">
                <h1 class="h3 fw-bold text-dark mb-3">{{ $vehicle->name }}</h1>

                @if($vehicle->location)
                <div class="d-flex align-items-start gap-2 mb-3 small text-muted">
                    <svg style="width: 1rem; height: 1rem;" class="mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>{{ $vehicle->location }}</span>
                </div>
                @endif
                
                {{-- Info Agen (Penyedia) --}}
                <div class="card shadow-sm border-light mb-4">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center gap-3">
                            <img src="{{ $vehicle->agent->user->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($vehicle->agent->name) . '&background=FFD15C&color=333&bold=true' }}" 
                                 alt="{{ $vehicle->agent->name }}"
                                 style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                            <div>
                                <h6 class="fw-bold mb-0">{{ $vehicle->agent->name }}</h6>
                                <span class="small text-success fw-medium">
                                    <i class="fas fa-check-circle"></i> Terverifikasi
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                @if($vehicle->description)
                <div class="mt-5">
                    <h5 class="fw-bold mb-3">Deskripsi Kendaraan</h5>
                    <p class="text-secondary" style="line-height: 1.7;">
                        {!! nl2br(e($vehicle->description)) !!}
                    </p>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-light" style="position: sticky; top: 2rem;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Sewa Kendaraan</h5>
                        
                        <div class="mb-3">
                            <span class="text-dark h4 fw-bolder">Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }}</span>
                            <span class="text-muted">/ hari</span>
                        </div>
                        
                        <hr class="my-3">
                        
                        <p class="small text-muted text-center">
                            Klik tombol "Hubungi Kami" untuk melakukan pemesanan pada agensi kami.
                        </p>
                        <div class="d-grid">
                            @php
                                $kontak = $vehicle->agent->phone_number ?? $vehicle->agent->contact_phone;
                                $pesan = urlencode("Halo, saya tertarik untuk menyewa " . $vehicle->name . " dari SigerTrip.");
                                $waLink = $kontak ? 'https://api.whatsapp.com/send?phone=' . preg_replace('/[^0-9]/', '', $kontak) . '&text=' . $pesan : '#';
                            @endphp
                            <a href="{{ $waLink }}" target="_blank" class="btn btn-lg fw-semibold text-dark" style="background-color: #FFD15C;">
                                <i class="fab fa-whatsapp me-2"></i> Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection