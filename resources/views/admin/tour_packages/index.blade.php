@extends('layouts.app')

@section('title', 'Pemandu Wisata - Admin')

@push('styles')
<style>
    .package-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
    }

    .package-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15) !important;
    }

    .package-card .card-img-top {
        height: 180px;
        object-fit: cover;
    }

    .agent-section {
        margin-bottom: 2rem;
        background-color: #f8f9fa;
        border-radius: 12px;
        border-left: 4px solid #667eea;
        overflow: hidden;
    }

    .agent-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1.5rem;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .agent-header:hover {
        background-color: #e9ecef;
    }

    .agent-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .agent-info h6 {
        margin: 0;
        font-weight: 700;
        color: #333;
    }

    .agent-info p {
        margin: 0;
        font-size: 0.85rem;
        color: #666;
    }

    .package-count-badge {
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .collapse-icon {
        transition: transform 0.2s;
        font-size: 1.2rem;
        color: #667eea;
    }

    .agent-section.collapsed .collapse-icon {
        transform: rotate(-90deg);
    }

    .agent-packages {
        padding: 1.5rem;
        border-top: 1px solid #dee2e6;
        display: none;
    }

    .agent-packages.show {
        display: block;
    }

    .view-all-link {
        display: inline-block;
        color: #667eea;
        font-weight: 600;
        text-decoration: none;
        margin-top: 1rem;
    }

    .view-all-link:hover {
        color: #764ba2;
        text-decoration: underline;
    }

    .price-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .pagination .page-link {
        color: #667eea;
    }

    .pagination .page-item.active .page-link {
        background-color: #667eea;
        border-color: #667eea;
    }

    .pagination .page-link:hover {
        color: #764ba2;
        background-color: #f0f1ff;
    }

    /* Back button styling */
    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        color: #667eea;
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .back-button:hover {
        color: #764ba2;
    }
</style>
@endpush

@section('content')

<div class="min-vh-100 bg-white">

    {{-- HEADER --}}
    <header class="border-bottom bg-white shadow-sm">
        <div class="container py-2 d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.beranda') }}" class="d-flex align-items-center text-decoration-none" style="min-width: 150px;">
                <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo" style="height:42px" loading="lazy" onerror="this.style.display='none'">
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
                    <button type="submit" class="btn btn-link text-danger p-0" style="font-size: 1.6rem; line-height: 1;" title="Keluar">
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
            <a href="{{ route('admin.profil-agent.index') }}" class="nav-link-custom">Profil Agent</a>
            <a href="{{ route('admin.pasar.index') }}" class="nav-link-custom">Pasar Digital</a>
            <a href="{{ route('admin.tour-packages.index') }}" class="nav-link-custom {{ request()->routeIs('admin.tour-packages*') ? 'active' : '' }}">Pemandu Wisata</a>
            <a href="{{ route('admin.users.index') }}" class="nav-link-custom">Kelola User</a>
        </div>
    </nav>

    <main class="container py-4 py-md-5">

        {{-- BACK BUTTON (Jika view detail agent) --}}
        @if(isset($agentId))
            <a href="{{ route('admin.tour-packages.index') }}" class="back-button">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar Agent
            </a>
        @endif

        {{-- TITLE --}}
        <div class="mb-4">
            <h2 class="fw-bold mb-0" style="font-size: 1.5rem;">
                @if(isset($agentId))
                    Paket Perjalanan dari {{ $packages->first()->agent->user->full_name ?? $packages->first()->agent->user->name }}
                @else
                    Pemandu Wisata (Admin)
                @endif
            </h2>
            <p class="text-muted mb-0">
                @if(isset($agentId))
                    Kelola semua paket perjalanan dari agent ini
                @else
                    Kelola semua paket perjalanan yang dibuat oleh agent/mitra
                @endif
            </p>
        </div>

        {{-- PESAN SUKSES --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- STATISTIK SINGKAT (Hanya tampil jika tidak ada filter agent) --}}
        @if(!isset($agentId))
            <div class="row g-3 mb-4">
                <div class="col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm text-center p-3">
                        <h6 class="text-muted mb-1">Total Paket</h6>
                        <h3 class="fw-bold text-primary mb-0">{{ $allPackages->count() }}</h3>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm text-center p-3">
                        <h6 class="text-muted mb-1">Total Agent</h6>
                        <h3 class="fw-bold text-success mb-0">{{ $allPackages->groupBy('agent_id')->count() }}</h3>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm text-center p-3">
                        <h6 class="text-muted mb-1">Harga Rata-rata</h6>
                        <h3 class="fw-bold text-info mb-0">
                            Rp {{ number_format($allPackages->avg('price_per_person'), 0, ',', '.') }}
                        </h3>
                    </div>
                </div>
            </div>
        @endif

        {{-- LIST PAKET --}}
        @if(!isset($agentId))
            <h5 class="fw-bold mb-4">Paket Perjalanan per Agent</h5>
        @else
            <h5 class="fw-bold mb-4">Semua Paket Perjalanan dari Agent ({{ $packages->total() }} total)</h5>
        @endif

        @if($packages->isEmpty())

            {{-- EMPTY STATE --}}
            <div class="text-center py-5">
                <i class="fas fa-suitcase-rolling mb-3" style="font-size:40px; color:#C4C4C4;"></i>
                <p class="fw-semibold mb-1">Tidak ada paket</p>
                <p class="text-muted mb-0">
                    @if(isset($agentId))
                        Agent ini belum membuat paket perjalanan.
                    @else
                        Belum ada agent yang membuat paket perjalanan.
                    @endif
                </p>
            </div>

        @elseif(isset($agentId))
            {{-- DISPLAY MODE: Detail Agent (Grid semua paket) --}}
            <div class="row g-4">
                @foreach($packages as $package)
                    @php
                        $imageUrl = $package->cover_image_url
                            ? asset('storage/'.$package->cover_image_url)
                            : 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=600&q=80';
                    @endphp

                    <div class="col-md-6 col-lg-4">
                        <div class="card package-card h-100 border-0 shadow-sm overflow-hidden">
                            <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $package->name }}">

                            <div class="card-body d-flex flex-column">
                                <h5 class="fw-bold mb-2" style="font-size: 0.95rem;">{{ Str::limit($package->name, 30) }}</h5>

                                <p class="text-muted small mb-2">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $package->duration ?? 'Durasi tidak diisi' }}
                                </p>

                                <div class="mb-3">
                                    <span class="price-badge">
                                        Rp {{ number_format($package->price_per_person, 0, ',', '.') }}
                                    </span>
                                </div>

                                <p class="card-text text-secondary small mb-3 flex-grow-1" style="max-height: 60px; overflow: hidden;">
                                    {{ Str::limit($package->description, 60) }}
                                </p>

                                <div class="d-flex justify-content-between gap-2 pt-2 border-top">
                                    <a href="{{ route('admin.tour-packages.edit', $package->id) }}"
                                       class="btn btn-sm btn-outline-primary flex-grow-1">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>

                                    <form action="{{ route('admin.tour-packages.destroy', $package->id) }}"
                                          method="POST" onsubmit="return confirm('Hapus paket ini?');" class="flex-grow-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                            <i class="fas fa-trash-alt me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @else
            {{-- DISPLAY MODE: Grouped by Agent --}}
            @php
                // Group packages by agent
                $groupedPackages = $packages->groupBy(function($package) {
                    return $package->agent_id;
                });
            @endphp

            @foreach($groupedPackages as $agentGroupId => $agentPackages)

                @php
                    $agent = $agentPackages->first()->agent;
                    $agentUser = $agent->user;
                    $initials = strtoupper(substr($agentUser->full_name ?? $agentUser->name, 0, 1));
                    $totalPackages = $allPackages->where('agent_id', $agentGroupId)->count();
                    $displayPackages = $agentPackages->take(3);
                @endphp

                <div class="agent-section collapsed" data-agent-id="{{ $agentGroupId }}">
                    
                    {{-- Agent Header (Clickable) --}}
                    <div class="agent-header" onclick="toggleAgent(this)">
                        <div class="agent-avatar">{{ $initials }}</div>
                        <div class="agent-info">
                            <h6>{{ $agentUser->full_name ?? $agentUser->name }}</h6>
                            <p>{{ $agentUser->email ?? 'Email tidak tersedia' }}</p>
                        </div>
                        <div class="ms-auto d-flex align-items-center gap-3">
                            <span class="package-count-badge">
                                <i class="fas fa-suitcase me-1"></i> {{ $totalPackages }} Paket
                            </span>
                            <i class="fas fa-chevron-down collapse-icon"></i>
                        </div>
                    </div>

                    {{-- Packages Grid (Hidden by default) --}}
                    <div class="agent-packages">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">

                            @foreach($displayPackages as $package)

                                @php
                                    $imageUrl = $package->cover_image_url
                                        ? asset('storage/'.$package->cover_image_url)
                                        : 'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?w=600&q=80';
                                @endphp

                                <div class="col">
                                    <div class="card package-card h-100 border-0 shadow-sm overflow-hidden">

                                        <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $package->name }}">

                                        <div class="card-body d-flex flex-column">

                                            <h5 class="fw-bold mb-2" style="font-size: 0.95rem;">{{ Str::limit($package->name, 30) }}</h5>

                                            <p class="text-muted small mb-2">
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $package->duration ?? 'Durasi tidak diisi' }}
                                            </p>

                                            <div class="mb-3">
                                                <span class="price-badge">
                                                    Rp {{ number_format($package->price_per_person, 0, ',', '.') }}
                                                </span>
                                            </div>

                                            <p class="card-text text-secondary small mb-3 flex-grow-1" style="max-height: 60px; overflow: hidden;">
                                                {{ Str::limit($package->description, 60) }}
                                            </p>

                                            <div class="d-flex justify-content-between gap-2 pt-2 border-top">
                                                <a href="{{ route('admin.tour-packages.edit', $package->id) }}"
                                                   class="btn btn-sm btn-outline-primary flex-grow-1">
                                                    <i class="fas fa-edit me-1"></i> Edit
                                                </a>

                                                <form action="{{ route('admin.tour-packages.destroy', $package->id) }}"
                                                      method="POST" onsubmit="return confirm('Hapus paket ini?');" class="flex-grow-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                                        <i class="fas fa-trash-alt me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            @endforeach

                        </div>

                        {{-- View All Link --}}
                        @if($totalPackages > 3)
                            <a href="{{ route('admin.tour-packages.index', ['agent' => $agentGroupId]) }}" class="view-all-link">
                                <i class="fas fa-arrow-right me-1"></i> Lihat semua {{ $totalPackages }} paket dari agent ini
                            </a>
                        @endif
                    </div>
                </div>

            @endforeach

        @endif

        {{-- PAGINATION --}}
        @if($packages->hasPages())
            <div class="d-flex justify-content-center">
                {{ $packages->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </main>
</div>

<script>
    function toggleAgent(header) {
        const section = header.closest('.agent-section');
        const packages = section.querySelector('.agent-packages');
        
        section.classList.toggle('collapsed');
        packages.classList.toggle('show');
    }
</script>

@endsection