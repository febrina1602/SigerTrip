@extends('layouts.app')

@section('title', 'Beranda Admin - SigerTrip')

@section('content')
<div class="min-vh-100 bg-white">

    {{-- HEADER --}}
    <header class="d-flex justify-content-between align-items-center py-3 px-4 border-bottom">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo" style="height:50px;">
        </div>
        <div class="d-flex align-items-center gap-3">
            <span class="fw-semibold text-dark">Halo, {{ Auth::user()->full_name ?? Auth::user()->name ?? 'Admin' }}</span>

            {{-- logout via POST --}}
            <a href="#" class="btn btn-outline-danger btn-sm"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Keluar</a>
            <form id="logout-form" action="" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </header>

    {{-- NAVIGATION --}}
    <nav class="nav-custom bg-light py-2 border-bottom">
        <div class="container d-flex gap-4">
            <a href="{{ route('admin.beranda') }}" class="nav-link-custom active">Beranda</a>
            <a href="#" class="nav-link-custom">Pasar Digital</a>
            <a href="#" class="nav-link-custom">Pemandu Wisata</a>
        </div>
    </nav>

    {{-- STATISTIK --}}
    <section class="py-5">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <h5 class="fw-bold mb-4">Statistik Sistem</h5>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                        <h6 class="fw-semibold text-muted mb-2">Total Wisata</h6>
                        <h3 class="fw-bold text-primary">{{ $totalWisata ?? 0 }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                        <h6 class="fw-semibold text-muted mb-2">Kategori</h6>
                        <h3 class="fw-bold text-success">{{ $totalKategori ?? 0 }}</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-4 p-3 text-center">
                        <h6 class="fw-semibold text-muted mb-2">Pengguna</h6>
                        <h3 class="fw-bold text-info">{{ $totalUser ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

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
                                        {{ Str::limit($destination->popular_activities ?? 'Menikmati pemandangan, jalan santai di tepi pantai', 80) }}
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

    {{-- FOOTER --}}
    <footer class="footer position-relative mt-auto">
        <div class="container py-3">
            <div class="row align-items-start">
                <div class="col-md-3 d-flex align-items-center mb-3 mb-md-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SigerTrip" class="me-2" style="height:50px;">
                </div>

                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <h6 class="fw-bold mb-2">Ikuti Kami</h6>
                    <div class="d-flex justify-content-center align-items-center social-icons">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-x-twitter"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div class="col-md-3 mb-3 mb-md-0">
                    <h6 class="fw-bold mb-2">Dibuat Oleh:</h6>
                    <p class="mb-1">Febrina Aulia Azahra</p>
                    <p class="mb-1">Carissa Oktavia Sanjaya</p>
                    <p class="mb-1">Dilvi Yola</p>
                    <p class="mb-0">M. Hafiz Abyan</p>
                </div>

                <div class="col-md-3">
                    <h6 class="fw-bold mb-2">Informasi</h6>
                    <p class="mb-1"><a href="#" class="footer-link">Tentang</a></p>
                    <p class="mb-0"><a href="#" class="footer-link">FAQ</a></p>
                </div>
            </div>
        </div>
        <img src="{{ asset('images/siger-pattern.png') }}" alt="Siger Pattern" class="siger-pattern">
    </footer>

</div>
@endsection
