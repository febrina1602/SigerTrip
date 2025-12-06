@extends('layouts.app')

@section('title', 'Beranda Admin - SigerTrip')

@section('content')
<div class="min-vh-100 bg-white">

    {{-- HEADER --}}
    <header class="border-bottom bg-white shadow-sm">
        <div class="container py-2 d-flex align-items-center justify-content-between">
            
            {{-- Logo --}}
            <a href="{{ route('admin.beranda') }}" class="d-flex align-items-center text-decoration-none" style="min-width: 150px;">
                <img src="{{ asset('images/logo.png') }}" 
                    alt="SigerTrip Logo"
                    style="height:42px" 
                    loading="lazy" 
                    onerror="this.style.display='none'">
                <span class="ms-2 fw-bold text-dark d-none d-md-block">SigerTrip</span>
            </a>

            {{-- Profil dan Logout --}}
            <div class="d-flex align-items-center gap-4" style="min-width: 150px; justify-content: flex-end;">
                
                {{-- Profil Admin --}}
                <div class="text-center">
                    <i class="fas fa-user-circle text-dark" style="font-size: 1.8rem;"></i>
                    <div class="small fw-medium mt-1 text-dark">
                        {{ Auth::user()->full_name ?? Auth::user()->name ?? 'Admin' }}
                    </div>
                </div>

                {{-- Tombol Logout --}}
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger p-0" 
                            style="font-size: 1.6rem; line-height: 1;" 
                            title="Keluar">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>


    {{-- NAVIGATION --}}
    <nav class="nav-custom bg-light py-2 border-bottom">
        <div class="container d-flex gap-4">
            <a href="{{ route('admin.beranda') }}" class="nav-link-custom active">Beranda</a>
            <a href="#" class="nav-link-custom">Profil Agent</a>
            <a href="{{ route('admin.pasar') }}" class="nav-link-custom">Pasar Digital</a>
            <a href="#" class="nav-link-custom">Pemandu Wisata</a>
            <a href="{{ route('admin.users.index') }}" class="nav-link-custom">Kelola User</a>
        </div>
    </nav>

    {{-- STATISTIK --}}
    <section class="category-section">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row justify-content-center g-4">
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white">
                    <div class="mb-3">
                        <i class="fas fa-map-marked-alt text-primary" style="font-size: 2rem;"></i>
                    </div>
                    <h6 class="fw-semibold text-muted mb-3">Total Wisata</h6>
                    <h2 class="fw-bold text-primary mb-0" style="font-size: 3rem;">{{ $totalWisata ?? 0 }}</h2>
                </div>
            </div>
                
                {{-- CARD KATEGORI YANG BISA DIKLIK --}}
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.categories.index') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white card-hover">
                            <div class="mb-3">
                                <i class="fas fa-tags text-success" style="font-size: 2rem;"></i>
                            </div>
                            <h6 class="fw-semibold text-muted mb-3">Kategori</h6>
                            <h2 class="fw-bold text-success mb-0" style="font-size: 3rem;">{{ $totalKategori ?? 0 }}</h2>
                        </div>
                    </a>
                </div>
                
                <div class="col-md-3 col-sm-6">
                    <a href="{{ route('admin.users.index') }}" class="text-decoration-none">
                        <div class="card border-0 shadow-sm rounded-4 p-4 text-center h-100 bg-white card-hover">
                            <div class="mb-3">
                                <i class="fas fa-users text-info" style="font-size: 2rem;"></i>
                            </div>
                            <h6 class="fw-semibold text-muted mb-3">Pengguna</h6>
                            <h2 class="fw-bold text-info mb-0" style="font-size: 3rem;">{{ $totalUser ?? 0 }}</h2>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <style>
    .card-hover {
        transition: all 0.3s ease;
    }

    .card-hover:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.2) !important;
    }

    .card-hover:hover .text-success,
    .card-hover:hover .text-info {
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }
    </style>

   {{-- DAFTAR DATA TERBARU --}}
    <section class="py-5">
        <div class="container">
            {{-- Tombol tambah hanya muncul jika sudah ada destinasi --}}
            @if($destinations->count() > 0)
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Rekomendasi buat kamu!</h5>
                    <a href="{{ route('admin.wisata.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Tambah Destinasi
                    </a>
                </div>
            @endif

            <div class="row row-cols-1 row-cols-md-3 g-4">
                @forelse($destinations as $destination)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                            {{-- Gambar Destinasi --}}
                            <div class="position-relative">
                                <img src="{{ asset($destination->image_url) }}" 
                                    alt="{{ $destination->name }}" 
                                    class="card-img-top" 
                                    style="height:200px; object-fit:cover;"
                                    onerror="this.src='https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=400&q=80'">
                            </div>

                            {{-- Card Body --}}
                            <div class="card-body p-3">
                                {{-- Nama Destinasi & Rating --}}
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="fw-bold mb-0">{{ $destination->name }}</h6>
                                    <div class="d-flex align-items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($destination->rating))
                                                <i class="fas fa-star text-warning" style="font-size: 12px;"></i>
                                            @elseif($i - $destination->rating < 1)
                                                <i class="fas fa-star-half-alt text-warning" style="font-size: 12px;"></i>
                                            @else
                                                <i class="far fa-star text-warning" style="font-size: 12px;"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>

                                {{-- Alamat --}}
                                <p class="text-muted small mb-3" style="font-size: 11px;">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ Str::limit($destination->address, 50) }}
                                </p>

                                {{-- Divider --}}
                                <hr class="my-2">

                                {{-- Aktivitas Populer --}}
                                <div class="mb-3">
                                    <p class="fw-semibold mb-1 small">Aktivitas Populer:</p>
                                    <p class="text-muted small mb-0" style="font-size: 11px;">
                                        @php
                                            $activities = $destination->popular_activities ?? ['Menikmati pemandangan', 'Jalan santai di tepi pantai'];

                                            // Jika berupa array, gabungkan jadi string
                                            if (is_array($activities)) {
                                                $activities = implode(', ', $activities);
                                            }
                                        @endphp

                                        {{ Str::limit($activities, 80) }}
                                    </p>
                                </div>


                                {{-- Harga & Jumlah Orang --}}
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted small mb-0" style="font-size: 10px;">
                                            <i class="fas fa-users me-1"></i>
                                            3.000<br>Orang
                                        </p>
                                    </div>
                                    <div class="text-end">
                                        <p class="text-primary fw-bold mb-0" style="font-size: 13px;">
                                            Rp{{ number_format($destination->price_per_person, 0, ',', '.') }}
                                        </p>
                                        <p class="text-muted small mb-0" style="font-size: 10px;">/orang</p>
                                    </div>
                                </div>

                                {{-- Tombol Aksi Admin --}}
                                <div class="d-flex gap-2 mt-3 pt-2 border-top">
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
                    {{-- Tampilan jika belum ada destinasi --}}
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-map-marked-alt text-muted mb-4" style="font-size: 80px; opacity: 0.3;"></i>
                            <h5 class="text-muted mb-3">Belum Ada Destinasi</h5>
                            <p class="text-secondary mb-4">Mulai tambahkan destinasi wisata pertama Anda untuk mengelola sistem</p>
                            <a href="{{ route('admin.wisata.create') }}" class="btn btn-primary btn-lg rounded-pill px-2">
                                <i class="fas fa-plus me-2"></i> Tambah Destinasi Pertama
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

</div>
@endsection
