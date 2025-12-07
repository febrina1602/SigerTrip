@extends('layouts.app')

@section('title', 'Pemandu Wisata - SigerTrip')

@section('content')
<div class="bg-white">
    @include('components.layout.header')

    <div class="py-5 bg-white min-vh-100">
        <div class="container">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold text-dark mb-1">Agen Tour Lokal</h1>
                    <p class="text-muted mb-0 small">Temukan pemandu lokal terbaik untuk perjalanan Anda.</p>
                </div>
                
                {{-- Action buttons for agent --}}
                @auth
                    @if(auth()->user()->role === 'agent')
                        <div>
                            {{-- HANYA Tombol ke Halaman Paket Perjalanan --}}
                            {{-- Fitur tambah agen lokal dihapus karena menggunakan Profile Agent utama --}}
                            <a href="{{ route('agent.tour-packages.index') }}" class="btn btn-primary rounded-3 shadow-sm">
                                <i class="fas fa-suitcase me-2"></i> Kelola Paket Perjalanan
                            </a>
                        </div>
                    @endif
                @endauth
            </div>

            {{-- Content for agent --}}
            @if(auth()->check() && auth()->user()->role == 'agent')
                <div class="row">
                    {{-- 
                       NOTE: Karena fitur "Tambah Agen Lokal" dihapus, area ini akan menampilkan 
                       daftar agen lokal yang SUDAH ADA saja (jika ada). 
                       Jika konsepnya 1 Agent = 1 Profil, maka list ini mungkin tidak diperlukan 
                       atau hanya menampilkan profil diri sendiri.
                    --}}
                    @if(isset($localTourAgents) && $localTourAgents->count() > 0)
                        @foreach($localTourAgents as $localAgent)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="card border-0 rounded-4 shadow-sm h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h5 class="card-title fw-bold mb-0">{{ $localAgent->name }}</h5>
                                            @if($localAgent->is_verified)
                                                <span class="badge bg-success-subtle text-success rounded-pill small">Verified</span>
                                            @endif
                                        </div>
                                        
                                        <p class="card-text small text-muted mb-2">
                                            <i class="fas fa-map-pin me-1"></i>
                                            {{ $localAgent->address ?? 'Alamat tidak ditentukan' }}
                                        </p>
                                        <p class="card-text small mb-3">
                                            <i class="fas fa-phone me-1"></i>
                                            {{ $localAgent->contact_phone ?? 'Telepon tidak ditentukan' }}
                                        </p>
                                        <p class="card-text small text-muted mb-3 line-clamp-2">
                                            {{ $localAgent->description ?? 'Tidak ada deskripsi' }}
                                        </p>
                                        
                                        <div class="d-flex gap-2 mt-auto">
                                            {{-- Tombol Hapus tetap ada untuk memanage data lama --}}
                                            <form action="{{ route('agent.local_tour_agents.delete', $localAgent->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus cabang agen ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger w-100" title="Hapus">
                                                    <i class="fas fa-trash me-1"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        {{-- Tampilan jika tidak ada cabang agen lokal --}}
                        <div class="col-12">
                            <div class="alert alert-light border rounded-4 text-center py-5" role="alert">
                                <i class="fas fa-user-tie fa-3x text-muted mb-3 opacity-50"></i>
                                <h5 class="fw-bold text-dark">Profil Agen Anda Aktif</h5>
                                <p class="text-muted mb-4">
                                    Anda menggunakan Profil Utama Agen sebagai identitas pemandu wisata.<br>
                                    Silakan langsung kelola paket perjalanan Anda.
                                </p>
                                <a href="{{ route('agent.tour-packages.index') }}" class="btn btn-outline-primary rounded-pill px-4">
                                    <i class="fas fa-plus me-2"></i> Buat Paket Baru
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

            {{-- Content for user/guest (Browse catalog) --}}
            @else
                {{-- Search Bar (Hanya untuk user/guest) --}}
                <div class="mb-4">
                    <form action="{{ route('pemandu-wisata.index') }}" method="GET">
                        <div class="input-group shadow-sm rounded-3 overflow-hidden border-0">
                            <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                            <input type="text" name="q" class="form-control border-0 py-3" placeholder="Cari nama agen atau lokasi..." value="{{ request('q') }}">
                            <button class="btn btn-primary px-4 fw-bold" type="submit">Cari</button>
                        </div>
                    </form>
                </div>

                <div class="row g-4">
                    @if(isset($localTourAgents) && $localTourAgents->count() > 0)
                        @foreach($localTourAgents as $localAgent)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-0 rounded-4 shadow-sm h-100 transition hover-lift cursor-pointer" onclick="window.location.href='{{ route('pemandu-wisata.show', $localAgent->id) }}';">
                                    
                                    {{-- Banner Image --}}
                                    <div style="height: 180px; background-color: #eee; position: relative; overflow: hidden; border-top-left-radius: 1rem; border-top-right-radius: 1rem;">
                                        @if($localAgent->banner_image_url)
                                            <img src="{{ $localAgent->banner_image_url }}" class="w-100 h-100 object-fit-cover" alt="{{ $localAgent->name }}">
                                        @else
                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-secondary text-white">
                                                <i class="fas fa-image fa-3x opacity-25"></i>
                                            </div>
                                        @endif
                                        
                                        @if($localAgent->is_verified)
                                            <span class="position-absolute top-0 end-0 m-3 badge bg-success rounded-pill shadow-sm">
                                                <i class="fas fa-check-circle me-1"></i> Verified
                                            </span>
                                        @endif
                                    </div>

                                    <div class="card-body pt-4 position-relative">
                                        {{-- Avatar Overlay --}}
                                        <div class="position-absolute start-0 ms-3" style="top: -40px;">
                                            <img src="{{ $localAgent->profile_picture_url ?? 'https://ui-avatars.com/api/?name='.urlencode($localAgent->name).'&background=random' }}" 
                                                 class="rounded-circle border border-4 border-white shadow-sm bg-white" 
                                                 width="80" height="80" style="object-fit: cover;">
                                        </div>

                                        <div class="mt-4">
                                            <h5 class="card-title fw-bold mb-1">{{ $localAgent->name }}</h5>
                                            
                                            <div class="d-flex align-items-center gap-3 mb-3 text-muted small">
                                                <span><i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $localAgent->location ?? 'Lampung' }}</span>
                                                <span><i class="fas fa-star text-warning me-1"></i> {{ number_format($localAgent->rating ?? 0, 1) }}</span>
                                            </div>

                                            <p class="card-text text-muted small line-clamp-2 mb-3">
                                                {{ $localAgent->description ?? 'Agen profesional dan terpercaya di Lampung.' }}
                                            </p>

                                            <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                                <span class="badge bg-light text-dark border">
                                                    <i class="fas fa-suitcase me-1"></i> {{ $localAgent->tourPackages->count() ?? 0 }} Paket
                                                </span>
                                                <span class="text-primary fw-bold small">Lihat Profil <i class="fas fa-arrow-right ms-1"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12 py-5 text-center">
                            <div class="mb-3">
                                <i class="fas fa-search fa-3x text-muted opacity-25"></i>
                            </div>
                            <h5 class="text-muted">Tidak ada agen tour ditemukan.</h5>
                        </div>
                    @endif
                </div>
            @endif
                
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