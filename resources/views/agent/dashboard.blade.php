@extends('layouts.app')

@section('title', 'Dashboard Agent - SigerTrip')

@section('content')
<div class="min-vh-100 bg-light">
    
    @include('components.layout.header')

    {{-- MAIN CONTENT --}}
    <div class="container mt-4 mb-5">
        
        {{-- WELCOME SECTION --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="bg-gradient-primary rounded-4 p-4 p-md-5 text-white shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-2">Selamat Datang, {{ $user->full_name }}! 👋</h2>
                            <p class="mb-0 opacity-90">
                                Kelola agen tour lokal Anda, buat paket perjalanan menarik, dan tingkatkan rating untuk menjadi partner terpercaya SigerTrip.
                            </p>
                        </div>
                        <div class="col-md-4 text-center d-none d-md-block">
                            <i class="fas fa-briefcase" style="font-size: 5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STATISTICS SECTION --}}
        <div class="row mb-5">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 rounded-4 shadow-sm h-100 transition" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small mb-1">Agen Tour Lokal</p>
                                <h3 class="fw-bold mb-0" style="color: #667eea;">{{ $totalLocalTourAgents }}</h3>
                            </div>
                            <div class="rounded-circle p-3" style="background-color: #e8eef7;">
                                <i class="fas fa-map-location-dot" style="font-size: 1.5rem; color: #667eea;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 rounded-4 shadow-sm h-100 transition" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small mb-1">Paket Perjalanan</p>
                                <h3 class="fw-bold mb-0" style="color: #f59e0b;">{{ $totalTourPackages }}</h3>
                            </div>
                            <div class="rounded-circle p-3" style="background-color: #fef3c7;">
                                <i class="fas fa-suitcase" style="font-size: 1.5rem; color: #f59e0b;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 rounded-4 shadow-sm h-100 transition" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small mb-1">Rating Rata-Rata</p>
                                <div>
                                    <h3 class="fw-bold mb-0 d-inline" style="color: #ec4899;">
                                        {{ number_format($averageRating, 1) }}
                                    </h3>
                                    <span class="text-warning ms-2">
                                        @for ($i = 0; $i < 5; $i++)
                                            @if ($i < floor($averageRating))
                                                <i class="fas fa-star" style="font-size: 0.8rem;"></i>
                                            @else
                                                <i class="far fa-star" style="font-size: 0.8rem;"></i>
                                            @endif
                                        @endfor
                                    </span>
                                </div>
                            </div>
                            <div class="rounded-circle p-3" style="background-color: #fce7f3;">
                                <i class="fas fa-star" style="font-size: 1.5rem; color: #ec4899;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 rounded-4 shadow-sm h-100 transition" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small mb-1">Destinasi Unik</p>
                                <h3 class="fw-bold mb-0" style="color: #10b981;">{{ $totalDestinations }}</h3>
                            </div>
                            <div class="rounded-circle p-3" style="background-color: #d1fae5;">
                                <i class="fas fa-globe" style="font-size: 1.5rem; color: #10b981;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- INFO CARD --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="alert alert-info border-0 rounded-4 d-flex align-items-center gap-3" role="alert" style="background-color: #e0f2fe;">
                    <i class="fas fa-circle-info" style="font-size: 1.5rem; color: #0284c7; flex-shrink: 0;"></i>
                    <div>
                        <strong style="color: #0284c7;">Informasi Penting:</strong>
                        <p class="mb-0 mt-1" style="color: #0c4a6e;">
                            Akun agent Anda telah <span class="badge bg-success">Terverifikasi</span>. 
                            Anda sekarang dapat menambahkan agen tour lokal dan membuat paket perjalanan untuk wisatawan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="row mb-5">
            <div class="col-12">
                <h5 class="fw-bold mb-3">Aksi Cepat</h5>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <button class="btn btn-outline-primary w-100 rounded-3 py-3" data-bs-toggle="modal" data-bs-target="#addLocalTourAgentModal">
                            <i class="fas fa-plus me-2"></i> Tambah Agen Tour Lokal
                        </button>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <button class="btn btn-outline-success w-100 rounded-3 py-3" data-bs-toggle="modal" data-bs-target="#addTourPackageModal">
                            <i class="fas fa-plus me-2"></i> Buat Paket Perjalanan
                        </button>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="#" class="btn btn-outline-warning w-100 rounded-3 py-3">
                            <i class="fas fa-chart-line me-2"></i> Lihat Laporan
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary w-100 rounded-3 py-3">
                            <i class="fas fa-gear me-2"></i> Pengaturan Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- AGEN TOUR LOKAL SECTION --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Agen Tour Lokal Terbaru</h5>
                    <a href="#" class="btn btn-sm btn-link text-primary text-decoration-none">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                @if($recentLocalTourAgents->count() > 0)
                    <div class="row g-3">
                        @foreach($recentLocalTourAgents as $localAgent)
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-0 rounded-4 shadow-sm overflow-hidden h-100 transition" style="transition: transform 0.3s ease;">
                                    @if($localAgent->banner_image_url)
                                        <img src="{{ $localAgent->banner_image_url }}" class="card-img-top" alt="{{ $localAgent->name }}" style="height: 180px; object-fit: cover;">
                                    @else
                                        <div style="height: 180px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-image" style="font-size: 3rem; color: rgba(255,255,255,0.3);"></i>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold mb-2">{{ $localAgent->name }}</h6>
                                        <p class="card-text small text-muted mb-2">
                                            <i class="fas fa-map-pin me-1" style="color: #667eea;"></i>
                                            {{ $localAgent->location ?? 'Lokasi tidak ditentukan' }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <span class="badge bg-light text-dark">
                                                    <i class="fas fa-star text-warning"></i> {{ number_format($localAgent->rating, 1) }}
                                                </span>
                                                @if($localAgent->is_verified)
                                                    <span class="badge bg-success ms-1">
                                                        <i class="fas fa-check-circle"></i> Verified
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="alert alert-warning border-0 rounded-4">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Belum ada agen tour lokal. <a href="#" class="alert-link">Tambahkan sekarang</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- PAKET PERJALANAN SECTION --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Paket Perjalanan Terbaru</h5>
                    <a href="#" class="btn btn-sm btn-link text-primary text-decoration-none">
                        Lihat Semua <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>

                @if($recentTourPackages->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Paket</th>
                                    <th>Agen Tour</th>
                                    <th>Harga</th>
                                    <th>Durasi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentTourPackages as $package)
                                    <tr>
                                        <td class="fw-medium">{{ Illuminate\Support\Str::limit($package->name, 30) }}</td>
                                        <td>{{ $package->localTourAgent->name ?? 'N/A' }}</td>
                                        <td class="fw-bold" style="color: #f59e0b;">
                                            Rp {{ number_format($package->price_per_person, 0, ',', '.') }}
                                        </td>
                                        <td>
                                            @if($package->duration_days && $package->duration_nights)
                                                {{ $package->duration_days }}H {{ $package->duration_nights }}M
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-success">Aktif</span>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning border-0 rounded-4">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        Belum ada paket perjalanan. <a href="#" class="alert-link">Buat paket sekarang</a>
                    </div>
                @endif
            </div>
        </div>

        {{-- PERFORMANCE SECTION --}}
        <div class="row">
            <div class="col-12">
                <h5 class="fw-bold mb-3">Performa Bulan Ini</h5>
                <div class="card border-0 rounded-4 shadow-sm">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4 border-end">
                                <h6 class="text-muted mb-2">Paket Dibuat</h6>
                                <h3 class="fw-bold" style="color: #667eea;">{{ $packagesThisMonth }}</h3>
                            </div>
                            <div class="col-md-4 border-end">
                                <h6 class="text-muted mb-2">Rating Terakhir</h6>
                                <h3 class="fw-bold" style="color: #ec4899;">
                                    {{ number_format($averageRating, 1) }}
                                    <i class="fas fa-star text-warning" style="font-size: 1rem;"></i>
                                </h3>
                            </div>
                            <div class="col-md-4">
                                <h6 class="text-muted mb-2">Total Pengunjung</h6>
                                <h3 class="fw-bold" style="color: #10b981;">2,340</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- MODAL: Add Local Tour Agent --}}
<div class="modal fade" id="addLocalTourAgentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Agen Tour Lokal Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info border-0 rounded-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Fitur Coming Soon!</strong> Halaman untuk menambah agen tour lokal sedang dalam pengembangan.
                </div>
                <p class="text-muted">Anda akan dapat menambahkan agen tour lokal dengan informasi seperti nama, alamat, nomor kontak, dan foto.</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: Add Tour Package --}}
<div class="modal fade" id="addTourPackageModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Buat Paket Perjalanan Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info border-0 rounded-3">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Fitur Coming Soon!</strong> Halaman untuk membuat paket perjalanan sedang dalam pengembangan.
                </div>
                <p class="text-muted">Anda akan dapat membuat paket perjalanan dengan detail harga, durasi, destinasi, dan fasilitas.</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .nav-link-custom {
        color: #6c757d;
        text-decoration: none;
        padding: 1rem 0;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        display: inline-block;
        white-space: nowrap;
    }

    .nav-link-custom:hover {
        color: #667eea;
        border-bottom-color: #667eea;
    }

    .nav-link-custom.active {
        color: #667eea;
        border-bottom-color: #667eea;
        font-weight: 500;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12) !important;
    }

    .btn-group-sm .btn {
        padding: 0.35rem 0.6rem;
        font-size: 0.8rem;
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>

@endsection
