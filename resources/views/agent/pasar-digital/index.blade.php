{{-- resources/views/agent/pasar-digital/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Pasar Digital Mitra - Kendaraan Anda')

@push('styles')
<style>
    /* Gaya mirip pasar-digital wisatawan */
    .filter-tabs .nav-link {
        border-radius: 16px;
        padding: 0.9rem 1.8rem;
        border: 1px solid #ffd4aa;
        color: #ff7a00;
        font-weight: 600;
        background-color: #fff7ec;
    }

    .filter-tabs .nav-link.active {
        background: #ffefe0;
        border-color: #ff7a00;
        color: #ff3d00;
        box-shadow: 0 8px 18px rgba(255, 143, 72, 0.25);
    }

    .vehicle-empty {
        text-align: center;
        padding: 60px 0 80px;
        color: #777;
    }

    .vehicle-empty i {
        font-size: 40px;
        margin-bottom: 16px;
        color: #c4c4c4;
    }

    .btn-daftar-kendaraan {
        background: #ffc400;
        border-radius: 999px;
        padding: 0.65rem 1.8rem;
        border: none;
        font-weight: 700;
        color: #5b4100;
        box-shadow: 0 6px 15px rgba(255, 196, 0, 0.35);
    }

    .btn-daftar-kendaraan:hover {
        filter: brightness(.95);
        color: #5b4100;
    }
</style>
@endpush

@section('content')
<div class="container py-5">

    {{-- Header + tombol daftar --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Pasar Digital – Kendaraan Anda</h2>
            <p class="text-muted mb-0">
                Kelola dan daftarkan kendaraan yang ingin Anda sewakan.
            </p>
        </div>

        <a href="{{ route('agent.pasar.create') }}"
           class="btn btn-daftar-kendaraan">
            Daftarkan kendaraan Anda
        </a>
    </div>

    {{-- FILTER TAB (sama konsepnya dengan wisatawan) --}}
    <div class="filter-tabs mb-4">
        @php
            $type = request('type'); // null, 'CAR', 'MOTORCYCLE'
        @endphp

        <ul class="nav gap-3">
            <li class="nav-item">
                <a href="{{ route('agent.pasar.index') }}"
                   class="nav-link {{ !$type ? 'active' : '' }}">
                    <i class="fa-solid fa-car-side me-2"></i> Semua Kendaraan
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('agent.pasar.index', ['type' => 'CAR']) }}"
                   class="nav-link {{ $type === 'CAR' ? 'active' : '' }}">
                    <i class="fa-solid fa-car me-2"></i> Mobil
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('agent.pasar.index', ['type' => 'MOTORCYCLE']) }}"
                   class="nav-link {{ $type === 'MOTORCYCLE' ? 'active' : '' }}">
                    <i class="fa-solid fa-motorcycle me-2"></i> Motor
                </a>
            </li>
        </ul>
    </div>

    {{-- LIST KENDARAAN MILIK AGENT --}}
    <h5 class="fw-bold mb-3">Kendaraan Anda</h5>

    @if($vehicles->isEmpty())
        <div class="vehicle-empty">
            <i class="fa-solid fa-car-side"></i>
            <p class="fw-semibold mb-1">Belum ada kendaraan yang didaftarkan.</p>
            <p class="mb-3">Mulai daftarkan kendaraan Anda untuk tampil di Pasar Digital wisatawan.</p>
            <a href="{{ route('agent.pasar.create') }}" class="btn btn-daftar-kendaraan">
                + Tambah kendaraan
            </a>
        </div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($vehicles as $vehicle)
                @php
                    $imageUrl = $vehicle->image_url
                        ? asset('storage/'.$vehicle->image_url)
                        : 'https://images.unsplash.com/photo-1553531889-a2b91d310614?w=600&q=80';
                @endphp

                <div class="col">
                    <div class="card h-100 shadow-sm border-0">

                        {{-- Gambar kendaraan --}}
                        <img src="{{ $imageUrl }}"
                             alt="{{ $vehicle->name }}"
                             class="card-img-top"
                             style="height: 200px; object-fit: cover; border-radius: 16px 16px 0 0;">

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold mb-1">{{ $vehicle->name }}</h5>

                            <p class="text-muted small mb-1">
                                <i class="fa-solid fa-location-dot me-1"></i>
                                {{ $vehicle->location ?? 'Lokasi tidak diisi' }}
                            </p>

                            <p class="fw-bold mb-2">
                                Rp {{ number_format($vehicle->price_per_day ?? 0, 0, ',', '.') }} / hari
                            </p>

                            <div class="mt-auto d-flex justify-content-between align-items-center">
                                <a href="{{ route('agent.pasar.edit', $vehicle->id) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fa-solid fa-pen me-1"></i> Ubah
                                </a>

                                <form action="{{ route('agent.pasar.destroy', $vehicle->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Hapus kendaraan ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid fa-trash-can me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
