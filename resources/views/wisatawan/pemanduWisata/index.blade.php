@extends('layouts.app')

@section('title', 'Pemandu Wisata - SigerTrip')

@section('content')
<div class="bg-white">
    @include('components.layout.header')

    <div class="py-5 bg-white min-vh-100">
        <div class="container">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold text-dark mb-1">Agen Tour & Travel</h1>
                    <p class="text-muted mb-0 small">Temukan pemandu wisata profesional untuk perjalanan Anda.</p>
                </div>
                
                {{-- Tombol Kelola Paket (Khusus Agent Login) --}}
                @auth
                    @if(auth()->user()->role === 'agent')
                        <a href="{{ route('agent.tour-packages.index') }}" class="btn btn-primary rounded-3 shadow-sm">
                            <i class="fas fa-suitcase me-2"></i> Kelola Paket Saya
                        </a>
                    @endif
                @endauth
            </div>


            <div class="row g-4">
                @forelse($agents as $agent)
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 rounded-4 shadow-sm h-100 transition hover-lift cursor-pointer" onclick="window.location.href='{{ route('pemandu-wisata.show', $agent->id) }}';">
                            
                            {{-- Banner Image --}}
                            <div style="height: 180px; background-color: #eee; position: relative; overflow: hidden; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                                @if($agent->banner_image_url)
                                    <img src="{{ asset('storage/'.$agent->banner_image_url) }}" class="w-100 h-100 object-fit-cover" alt="{{ $agent->name }}">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-secondary text-white">
                                        <i class="fas fa-image fa-3x opacity-25"></i>
                                    </div>
                                @endif
                                
                                @if($agent->is_verified)
                                    <span class="position-absolute top-0 end-0 m-3 badge bg-success rounded-pill shadow-sm">
                                        <i class="fas fa-check-circle me-1"></i> Verified
                                    </span>
                                @endif
                            </div>

                            <div class="card-body pt-4 position-relative">
                                {{-- Avatar Overlay --}}
                                <div class="position-absolute start-0 ms-3" style="top: -40px;">
                                    <img src="{{ $agent->profile_picture_url ?? 'https://ui-avatars.com/api/?name='.urlencode($agent->name).'&background=random' }}" 
                                         class="rounded-circle border border-4 border-white shadow-sm bg-white" 
                                         width="80" height="80" style="object-fit: cover;">
                                </div>

                                <div class="mt-4">
                                    <h5 class="card-title fw-bold mb-1">{{ $agent->name }}</h5>
                                    
                                    <div class="d-flex align-items-center gap-3 mb-3 text-muted small">
                                        <span><i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $agent->location ?? $agent->address ?? 'Lampung' }}</span>
                                    </div>

                                    <p class="card-text text-muted small line-clamp-2 mb-3">
                                        {{ Str::limit($agent->description, 100) ?? 'Agen profesional dan terpercaya di Lampung.' }}
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                        <span class="text-primary fw-bold small">Lihat Profil <i class="fas fa-arrow-right ms-1"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-5 text-center">
                        <div class="mb-3">
                            <i class="fas fa-search fa-3x text-muted opacity-25"></i>
                        </div>
                        <h5 class="text-muted">Belum ada agen tour yang tersedia.</h5>
                        <p class="text-muted small">Mungkin belum ada agen yang mendaftar atau diverifikasi.</p>
                    </div>
                @endforelse
            </div>
                
        </div>
    </div>
</div>

<style>
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    }
    .transition { transition: all 0.3s ease; }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .cursor-pointer { cursor: pointer; }
</style>
@endsection