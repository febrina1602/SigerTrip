@extends('layouts.app')

@section('title', 'Pasar Digital - Admin')

@push('styles')
<style>
    .vehicle-card .card-img-top {
        height: 200px;
        object-fit: cover;
        border-radius: 12px 12px 0 0;
    }

    .category-box {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 1rem 2rem;
        border-radius: 14px;
        border: 1px solid #ffd4aa;
        background-color: #fff7ec;
        color: #ff7a00;
        font-weight: 600;
        transition: 0.2s;
        text-decoration: none;
    }

    .category-box.active {
        background-color: #ffefe0;
        border-color: #ff7a00;
        color: #ff3d00;
        box-shadow: 0 8px 18px rgba(255,143,72,0.25);
    }

    .category-box i {
        font-size: 20px;
    }

    .agent-section {
        margin-bottom: 2rem;
        background-color: #f8f9fa;
        border-radius: 12px;
        border-left: 4px solid #ff7a00;
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
        background: linear-gradient(135deg, #FFD15C, #FF9739);
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

    .vehicle-count-badge {
        background: linear-gradient(90deg, #FFD15C 0%, #FF9739 45%, #FF3D3D 100%);
        color: white;
        padding: 0.35rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .collapse-icon {
        transition: transform 0.2s;
        font-size: 1.2rem;
        color: #ff7a00;
    }

    .agent-section.collapsed .collapse-icon {
        transform: rotate(-90deg);
    }

    .agent-vehicles {
        padding: 1.5rem;
        border-top: 1px solid #dee2e6;
        display: none;
    }

    .agent-vehicles.show {
        display: block;
    }

    .view-all-link {
        display: inline-block;
        color: #ff7a00;
        font-weight: 600;
        text-decoration: none;
        margin-top: 1rem;
    }

    .view-all-link:hover {
        color: #ff3d00;
        text-decoration: underline;
    }

    /* Pagination styling */
    .pagination {
        margin-top: 2rem;
    }

    .pagination .page-link {
        color: #ff7a00;
    }

    .pagination .page-item.active .page-link {
        background-color: #ff7a00;
        border-color: #ff7a00;
    }

    .pagination .page-link:hover {
        color: #ff3d00;
        background-color: #fff7ec;
    }
</style>
@endpush

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
            <a href="{{ route('admin.beranda') }}" class="nav-link-custom">Beranda</a>
            <a href="#" class="nav-link-custom">Profil Agent</a>
            
            {{-- PERBAIKAN: admin.pasar.index --}}
            <a href="{{ route('admin.pasar.index') }}" class="nav-link-custom {{ request()->routeIs('admin.pasar*') ? 'active' : '' }}">Pasar Digital</a>
            
            <a href="#" class="nav-link-custom">Pemandu Wisata</a>
            <a href="{{ route('admin.users.index') }}" class="nav-link-custom">Kelola User</a>
        </div>
    </nav>

    <main class="container py-4 py-md-5">

        {{-- TITLE --}}
        <div class="mb-4">
            <h2 class="fw-bold mb-0" style="font-size: 1.5rem;">Pasar Digital (Admin)</h2>
            <p class="text-muted mb-0">Kelola semua kendaraan yang didaftarkan oleh agent/mitra.</p>
        </div>

        {{-- PESAN SUKSES --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- FILTER KATEGORI --}}
        @php $type = request('type'); @endphp

        <div class="d-flex gap-3 flex-wrap mb-4">

            {{-- PERBAIKAN: admin.pasar.index --}}
            <a href="{{ route('admin.pasar.index') }}"
               class="category-box {{ !$type ? 'active' : '' }}">
                <i class="fa-solid fa-car-side"></i> Semua Kendaraan
            </a>

            <a href="{{ route('admin.pasar.index', ['type' => 'CAR']) }}"
               class="category-box {{ $type === 'CAR' ? 'active' : '' }}">
                <i class="fa-solid fa-car"></i> Mobil
            </a>

            <a href="{{ route('admin.pasar.index', ['type' => 'MOTORCYCLE']) }}"
               class="category-box {{ $type === 'MOTORCYCLE' ? 'active' : '' }}">
                <i class="fa-solid fa-motorcycle"></i> Motor
            </a>
        </div>

        {{-- STATISTIK SINGKAT --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6 class="text-muted mb-1">Total Kendaraan</h6>
                    <h3 class="fw-bold text-primary mb-0">{{ $allVehicles->count() }}</h3>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6 class="text-muted mb-1">Total Agent</h6>
                    <h3 class="fw-bold text-success mb-0">{{ $allVehicles->groupBy('agent_id')->count() }}</h3>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6 class="text-muted mb-1">Mobil</h6>
                    <h3 class="fw-bold text-info mb-0">{{ $allVehicles->where('vehicle_type', 'CAR')->count() }}</h3>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm text-center p-3">
                    <h6 class="text-muted mb-1">Motor</h6>
                    <h3 class="fw-bold text-warning mb-0">{{ $allVehicles->where('vehicle_type', 'MOTORCYCLE')->count() }}</h3>
                </div>
            </div>
        </div>

        {{-- LIST KENDARAAN GROUPED BY AGENT --}}
        <h5 class="fw-bold mb-4">Kendaraan per Agent (Menampilkan 6 per halaman)</h5>

        @if($allVehicles->isEmpty())

            {{-- EMPTY STATE --}}
            <div class="text-center py-5">
                <i class="fa-solid fa-car-side mb-3" style="font-size:40px; color:#C4C4C4;"></i>
                <p class="fw-semibold mb-1">Tidak ada kendaraan</p>
                <p class="text-muted mb-0">Belum ada agent yang mendaftarkan kendaraan.</p>
            </div>

        @else

            @foreach($vehicles as $agentVehicles)

                @php
                    $agent = $agentVehicles->first()->agent;
                    $agentUser = $agent->user;
                    $agentId = $agent->id;
                    $initials = strtoupper(substr($agentUser->full_name ?? $agentUser->name, 0, 1));
                    $totalVehicles = $allVehicles->where('agent_id', $agentId)->count();
                    // Get first 3 vehicles to display
                    $displayVehicles = $agentVehicles->take(3);
                @endphp

                <div class="agent-section collapsed" data-agent-id="{{ $agentId }}">
                    {{-- Agent Header (Clickable) --}}
                    <div class="agent-header" onclick="toggleAgent(this)">
                        <div class="agent-avatar">{{ $initials }}</div>
                        <div class="agent-info">
                            <h6>{{ $agentUser->full_name ?? $agentUser->name }}</h6>
                            <p>{{ $agentUser->email ?? 'Email tidak tersedia' }}</p>
                        </div>
                        <div class="ms-auto d-flex align-items-center gap-3">
                            <span class="vehicle-count-badge">
                                <i class="fa-solid fa-car me-1"></i> {{ $totalVehicles }} Kendaraan
                            </span>
                            <i class="fas fa-chevron-down collapse-icon"></i>
                        </div>
                    </div>

                    {{-- Vehicles Grid (Hidden by default) --}}
                    <div class="agent-vehicles">
                        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">

                            @foreach($displayVehicles as $vehicle)

                                @php
                                    $imageUrl = $vehicle->image_url
                                        ? asset('storage/'.$vehicle->image_url)
                                        : 'https://images.unsplash.com/photo-1553531889-a2b91d310614?w=600&q=80';
                                @endphp

                                <div class="col">
                                    <div class="card vehicle-card h-100 border-0 shadow-sm">

                                        <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $vehicle->name }}">

                                        <div class="card-body d-flex flex-column">

                                            <h5 class="fw-bold mb-1" style="font-size: 1rem;">{{ $vehicle->name }}</h5>

                                            <p class="text-muted small mb-1">
                                                <i class="fa-solid fa-location-dot me-1"></i>
                                                {{ Str::limit($vehicle->location ?? 'Lokasi tidak diisi', 30) }}
                                            </p>

                                            <p class="fw-bold mb-2" style="font-size: 0.95rem;">
                                                Rp {{ number_format($vehicle->price_per_day ?? 0, 0, ',', '.') }} / hari
                                            </p>

                                            <span class="badge bg-light text-muted mb-3">
                                                {{ $vehicle->vehicle_type === 'CAR' ? 'Mobil' : 'Motor' }}
                                            </span>

                                            <div class="d-flex justify-content-between mt-auto gap-2">
                                                <a href="{{ route('admin.pasar.edit', $vehicle->id) }}"
                                                   class="btn btn-sm btn-outline-primary flex-grow-1">
                                                    <i class="fa-solid fa-pen me-1"></i> Edit
                                                </a>

                                                <form action="{{ route('admin.pasar.destroy', $vehicle->id) }}"
                                                      method="POST" onsubmit="return confirm('Hapus kendaraan ini?');" class="flex-grow-1">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger w-100">
                                                        <i class="fa-solid fa-trash-can me-1"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                            @endforeach

                        </div>

                        {{-- View All Link --}}
                        @if($totalVehicles > 3)
                            {{-- PERBAIKAN: admin.pasar.index --}}
                            <a href="{{ route('admin.pasar.index', ['agent' => $agentId]) }}" class="view-all-link">
                                <i class="fa-solid fa-arrow-right me-1"></i> Lihat semua {{ $totalVehicles }} kendaraan dari agent ini
                            </a>
                        @endif
                    </div>
                </div>

            @endforeach

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-center">
                {{ $vehicles->links('pagination::bootstrap-5') }}
            </div>

        @endif

    </main>
</div>

<script>
    function toggleAgent(header) {
        const section = header.closest('.agent-section');
        const vehicles = section.querySelector('.agent-vehicles');
        
        section.classList.toggle('collapsed');
        vehicles.classList.toggle('show');
    }
</script>

@endsection