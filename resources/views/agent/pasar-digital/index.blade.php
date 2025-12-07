@extends('layouts.app')

@section('title', 'Pasar Digital - Agent')

@section('content')
@include('components.layout.header')

<div class="container my-5">
    
    {{-- Header Section --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Pasar Digital</h3>
            <p class="text-muted mb-0">Kelola kendaraan yang Anda sewakan.</p>
        </div>
        @if($agent->is_verified)
            <a href="{{ route('agent.pasar.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i> Tambah Kendaraan
            </a>
        @else
            <button class="btn btn-secondary" disabled title="Harap tunggu verifikasi admin">
                <i class="fas fa-lock me-2"></i> Tambah Kendaraan
            </button>
        @endif
    </div>

    {{-- Status Alert - Jika belum verified --}}
    @if(!$agent->is_verified)
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
                        Anda masih <strong>belum bisa menambah kendaraan baru</strong>. 
                        Profil Anda sedang dalam proses verifikasi oleh tim admin kami. 
                        Fitur ini akan aktif setelah verifikasi selesai.
                    </p>
                </div>
            </div>
        </div>
    @endif

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
                                @if($agent->is_verified)
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

        {{-- Pagination --}}
        <div class="mt-5 d-flex justify-content-center">
            {{ $vehicles->links() }}
        </div>

    @else
        {{-- Empty State --}}
        <div class="text-center py-5">
            <i class="fas fa-car fa-4x text-muted opacity-25 mb-3"></i>
            <h4>Belum Ada Kendaraan</h4>
            <p class="text-muted mb-4">Anda belum mendaftarkan kendaraan apapun di Pasar Digital.</p>
            
            @if($agent->is_verified)
                <a href="{{ route('agent.pasar.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i> Tambah Kendaraan Pertama
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
@endsection