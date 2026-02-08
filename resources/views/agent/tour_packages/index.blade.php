@extends('layouts.app')

@section('title', 'Paket Perjalanan - Agent')

@section('content')
@include('components.layout.header')

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Paket Perjalanan</h3>
            <p class="text-muted mb-0">Kelola paket wisata yang Anda tawarkan.</p>
        </div>
        @if($user->agent->is_verified)
            <a href="{{ route('agent.tour-packages.create') }}" class="btn btn-primary rounded-3 shadow-sm">
                <i class="fas fa-plus me-2"></i> Buat Paket Baru
            </a>
        @else
            <button class="btn btn-secondary rounded-3 shadow-sm" disabled title="Harap tunggu verifikasi admin">
                <i class="fas fa-lock me-2"></i> Buat Paket Baru
            </button>
        @endif
    </div>

    {{-- Status Alert - Jika belum verified --}}
    @if(!$user->agent->is_verified)
        <div class="alert alert-warning border-0 rounded-4 shadow-sm mb-4" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-clock text-warning" style="font-size: 1.5rem;"></i>
                </div>
                <div class="flex-grow-1 ms-3">
                    <h5 class="alert-heading mb-1 fw-bold">
                        Fitur Terbatas - Menunggu Verifikasi Admin
                    </h5>
                    <p class="mb-0 text-muted">
                        Anda masih <strong>belum bisa membuat paket baru</strong>. 
                        Profil Anda sedang dalam proses verifikasi oleh tim admin kami. 
                        Fitur ini akan aktif setelah verifikasi selesai.
                    </p>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($packages->count() > 0)
        <div class="row g-4">
            @foreach($packages as $package)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="position-relative">
                            <img src="{{ $package->cover_image_url ? asset('storage/'.$package->cover_image_url) : 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=600&q=80' }}" 
                                 class="card-img-top" 
                                 alt="{{ $package->name }}" 
                                 style="height: 200px; object-fit: cover;">
                            
                            <div class="position-absolute top-0 end-0 p-3">
                                <span class="badge bg-white text-dark shadow-sm">
                                    Rp {{ number_format($package->price_per_person, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-2">{{ $package->name }}</h5>
                            <div class="text-muted small mb-3">
                                <i class="fas fa-clock me-1"></i> {{ $package->duration ?? '-' }}  
                            </div>
                            <p class="card-text text-secondary small line-clamp-2">
                                {{ Str::limit($package->description, 80) }}
                            </p>
                            
                            <hr class="my-3">
                            
                            <div class="d-flex gap-2">
                                @if($user->agent->is_verified)
                                    <a href="{{ route('agent.tour-packages.edit', $package->id) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('agent.tour-packages.destroy', $package->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Yakin hapus paket ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-sm btn-secondary flex-fill" disabled>
                                        <i class="fas fa-lock me-1"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-secondary flex-fill" disabled>
                                        <i class="fas fa-lock me-1"></i> Hapus
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="mt-5 d-flex justify-content-center">
            {{ $packages->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-suitcase-rolling fa-4x text-muted opacity-25 mb-3"></i>
            <h4>Belum Ada Paket</h4>
            <p class="text-muted mb-4">Anda belum membuat paket perjalanan apapun.</p>
            
            @if($user->agent->is_verified)
                <a href="{{ route('agent.tour-packages.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Buat Paket Pertama
                </a>
            @else
                <div class="alert alert-warning d-inline-block rounded-4 mt-3" style="max-width: 400px;">
                    <p class="mb-0 text-muted">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Fitur akan aktif</strong> setelah profil Anda diverifikasi oleh admin
                    </p>
                </div>
            @endif
        </div>
    @endif
</div>

<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection