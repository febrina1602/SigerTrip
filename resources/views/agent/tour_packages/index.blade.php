@extends('layouts.app')

@section('title', 'Kelola Paket Perjalanan')

@section('content')
@include('components.layout.header')

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Paket Perjalanan</h3>
            <p class="text-muted mb-0">Kelola paket wisata yang Anda tawarkan.</p>
        </div>
        <a href="{{ route('agent.tour-packages.create') }}" class="btn btn-primary rounded-3 shadow-sm">
            <i class="fas fa-plus me-2"></i> Buat Paket Baru
        </a>
    </div>

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
            <p class="text-muted">Anda belum membuat paket perjalanan apapun.</p>
            <a href="{{ route('agent.tour-packages.create') }}" class="btn btn-primary mt-2">
                Buat Paket Pertama
            </a>
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