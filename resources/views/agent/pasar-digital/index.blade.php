@extends('layouts.app')

@section('title', 'Pasar Digital - Agent')

@section('content')
@include('components.layout.header')

<div class="container my-5">
    
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Pasar Digital (Agent)</h3>
            <p class="text-muted mb-0">Kelola kendaraan yang Anda sewakan.</p>
        </div>
        <a href="{{ route('agent.pasar.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i> Tambah Kendaraan
        </a>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Content List --}}
    @if(isset($vehicles) && $vehicles->count() > 0)
        <div class="row g-4">
            @foreach($vehicles as $vehicle)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                        {{-- Image --}}
                        <div class="position-relative">
                            <img src="{{ $vehicle->image_url ? asset('storage/'.$vehicle->image_url) : 'https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?w=600&q=80' }}" 
                                 class="card-img-top" 
                                 alt="{{ $vehicle->name }}" 
                                 style="height: 200px; object-fit: cover;">
                            <span class="position-absolute top-0 end-0 m-3 badge bg-white text-dark shadow-sm">
                                {{ $vehicle->vehicle_type == 'CAR' ? 'Mobil' : 'Motor' }}
                            </span>
                        </div>

                        {{-- Body --}}
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-2">{{ $vehicle->name }}</h5>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i> {{ Str::limit($vehicle->location, 30) }}
                            </p>
                            <p class="text-primary fw-bold mb-3">
                                Rp {{ number_format($vehicle->price_per_day, 0, ',', '.') }} 
                                <span class="small text-muted fw-normal">/ hari</span>
                            </p>
                            
                            <hr class="my-3">

                            {{-- Actions --}}
                            <div class="d-flex gap-2">
                                <a href="{{ route('agent.pasar.edit', $vehicle->id) }}" class="btn btn-sm btn-outline-warning flex-fill">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('agent.pasar.destroy', $vehicle->id) }}" method="POST" class="flex-fill" onsubmit="return confirm('Yakin hapus kendaraan ini?');">
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

        {{-- Pagination --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $vehicles->links() }}
        </div>

    @else
        {{-- Empty State (Menggunakan style desain asli) --}}
        <div class="text-center py-5">
            <img src="{{ asset('images/empty_marketplace.svg') }}" alt="Pasar Digital Kosong" style="max-width: 320px; opacity: 0.8;" onerror="this.style.display='none'">
            <h3 class="mt-4">Belum Ada Kendaraan</h3>
            <p class="text-muted">Anda belum mendaftarkan kendaraan apapun di Pasar Digital.</p>
            <a href="{{ route('agent.pasar.create') }}" class="btn btn-primary mt-3 px-4 rounded-pill">
                <i class="fas fa-plus me-2"></i> Tambah Kendaraan Pertama
            </a>
        </div>
    @endif
</div>
@endsection