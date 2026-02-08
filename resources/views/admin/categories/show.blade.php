@extends('layouts.app')

@section('title', 'Kategori ' . $category->name . ' - SigerTrip')

@section('content')
<div class="min-vh-100 bg-white">

    {{-- HEADER --}}
    <header class="border-bottom bg-white shadow-sm">
        <div class="container py-2 d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.beranda') }}" class="d-flex align-items-center text-decoration-none" style="min-width: 150px;">
                <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo" style="height:42px" loading="lazy">
                <span class="ms-2 fw-bold text-dark d-none d-md-block">SigerTrip</span>
            </a>

            <div class="d-flex align-items-center gap-4" style="min-width: 150px; justify-content: flex-end;">
                <div class="text-center">
                    <i class="fas fa-user-circle text-dark" style="font-size: 1.8rem;"></i>
                    <div class="small fw-medium mt-1 text-dark">
                        {{ Auth::user()->full_name ?? 'Admin' }}
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger p-0" style="font-size: 1.6rem;" title="Keluar">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- NAVIGATION --}}
    <nav class="nav-custom bg-light py-2 border-bottom">
        <div class="container d-flex gap-4">
            <a href="{{ route('admin.beranda') }}" class="nav-link-custom">Beranda</a>
            <a href="{{route('admin.profil-agent.index')}}" class="nav-link-custom">Profil Agent</a>
            {{-- PERBAIKAN: admin.pasar.index --}}
            <a href="{{ route('admin.pasar.index') }}" class="nav-link-custom">Pasar Digital</a>
            <a href="{{ route('admin.tour-packages.index') }}" class="nav-link-custom">Pemandu Wisata</a>
            <a href="{{ route('admin.users.index') }}" class="nav-link-custom">Kelola User</a>
        </div>
    </nav>

    {{-- CONTENT --}}
    <section class="py-4">
        <div class="container">
            {{-- Breadcrumb & Header --}}
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Kategori</a></li>
                    <li class="breadcrumb-item active">{{ $category->name }}</li>
                </ol>
            </nav>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="fw-bold mb-1">
                        @if($category->icon_url)
                            <img src="{{ asset($category->icon_url) }}" alt="{{ $category->name }}" style="width: 40px; height: 40px; object-fit: contain;" class="me-2">
                        @else
                            <i class="fas fa-tags me-2"></i>
                        @endif
                        Kategori: {{ $category->name }}
                    </h4>
                    <p class="text-muted mb-0">Total {{ $destinations->total() }} destinasi</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.wisata.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Tambah Destinasi
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </div>

            {{-- Grid Destinasi --}}
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @forelse($destinations as $destination)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                            <img src="{{ asset($destination->image_url) }}" 
                                 alt="{{ $destination->name }}" 
                                 class="card-img-top" 
                                 style="height:200px; object-fit:cover;"
                                 onerror="this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400&q=80'">
                            
                            <div class="card-body p-3">
                                <h6 class="fw-bold mb-2">{{ $destination->name }}</h6>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ Str::limit($destination->address, 40) }}
                                </p>
                                <p class="text-primary fw-bold mb-3">
                                    Rp{{ number_format($destination->price_per_person, 0, ',', '.') }}/orang
                                </p>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.wisata.edit', $destination->id) }}" 
                                       class="btn btn-sm btn-outline-primary flex-fill">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.wisata.destroy', $destination->id) }}" 
                                          method="POST" 
                                          class="flex-fill"
                                          onsubmit="return confirm('Yakin hapus destinasi ini?')">
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
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-map-marked-alt text-muted mb-4" style="font-size: 80px; opacity: 0.3;"></i>
                            <h5 class="text-muted mb-3">Belum Ada Destinasi</h5>
                            <p class="text-secondary mb-4">Belum ada destinasi dalam kategori {{ $category->name }}</p>
                            <a href="{{ route('admin.wisata.create') }}" class="btn btn-primary btn-lg rounded-pill px-5">
                                <i class="fas fa-plus me-2"></i> Tambah Destinasi
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($destinations->hasPages())
                <div class="mt-4">
                    {{ $destinations->links() }}
                </div>
            @endif
        </div>
    </section>
</div>
@endsection