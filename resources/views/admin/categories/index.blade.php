@extends('layouts.app')

@section('title', 'Kelola Kategori - SigerTrip')

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
                        {{ Auth::user()->full_name ?? Auth::user()->name ?? 'Admin' }}
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
            <a href="#" class="nav-link-custom">Profil Agent</a>
            {{-- PERBAIKAN: admin.pasar.index --}}
            <a href="{{ route('admin.pasar.index') }}" class="nav-link-custom">Pasar Digital</a>
            <a href="#" class="nav-link-custom">Pemandu Wisata</a>
            <a href="{{ route('admin.users.index') }}" class="nav-link-custom">Kelola User</a>
        </div>
    </nav>

    {{-- CONTENT --}}
    <section class="py-4">
        <div class="container">
            {{-- Alert --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">
                    <i class="fas fa-tags me-2"></i>Kelola Kategori Destinasi
                </h4>

                {{-- Tombol Aksi --}}
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Tambah Kategori
                    </a>
                    <a href="{{ route('admin.beranda') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Kembali
                    </a>
                </div>
            </div>

            {{-- Grid Kategori --}}
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
                @forelse($categories as $category)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                            {{-- Icon Kategori --}}
                            <div class="card-body text-center p-4">
                                @if($category->icon_url)
                                    <img src="{{ asset($category->icon_url) }}" 
                                         alt="{{ $category->name }}" 
                                         class="mb-3"
                                         style="width: 80px; height: 80px; object-fit: contain;">
                                @else
                                    <div class="mb-3">
                                        <i class="fas fa-image text-muted" style="font-size: 4rem;"></i>
                                    </div>
                                @endif

                                {{-- Nama Kategori --}}
                                <h5 class="fw-bold mb-2">{{ $category->name }}</h5>
                                
                                {{-- Jumlah Destinasi --}}
                                <p class="text-muted small mb-3">
                                    <i class="fas fa-map-marked-alt me-1"></i>
                                    {{ $category->destinations_count }} Destinasi
                                </p>

                                {{-- Tombol Aksi --}}
                                <div class="d-flex gap-2 justify-content-center">
                                    {{-- Lihat Destinasi --}}
                                    <a href="{{ route('admin.categories.show', $category->id) }}" 
                                       class="btn btn-sm btn-info text-white" 
                                       title="Lihat Destinasi">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                       class="btn btn-sm btn-warning" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    {{-- Hapus --}}
                                    <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                                          method="POST" 
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin hapus kategori {{ $category->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-tags text-muted mb-4" style="font-size: 80px; opacity: 0.3;"></i>
                            <h5 class="text-muted mb-3">Belum Ada Kategori</h5>
                            <p class="text-secondary mb-4">Mulai tambahkan kategori untuk mengelompokkan destinasi wisata</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-lg rounded-pill px-5">
                                <i class="fas fa-plus me-2"></i> Tambah Kategori Pertama
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>
@endsection