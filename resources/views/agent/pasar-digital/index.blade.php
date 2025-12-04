@extends('layouts.app')

@section('title', 'Pasar Digital - SigerTrip')

@push('styles')
<style>
    /* STYLE KOTAK KATEGORI */
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

    /* CARD IMG */
    .vehicle-card .card-img-top {
        height: 200px;
        object-fit: cover;
        border-radius: 12px 12px 0 0;
    }

    /* BUTTON DAFTARKAN KENDARAAN */
    .btn-daftar-kendaraan {
        background-color: #007bff;
        color: white;
        font-weight: 700;
        border-radius: 999px;
        padding: 0.65rem 1.8rem;
        box-shadow: 0 6px 15px rgba(0,123,255,0.35);
    }
</style>
@endpush

@section('content')

<div class="bg-white min-vh-100">

    {{-- HEADER --}}
    @include('components.layout.header')

    <main class="container py-4 py-md-5">

        {{-- TITLE + BUTTON --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Pasar Digital</h2>
                <p class="text-muted mb-0">Temukan kendaraan sewa terbaik untuk perjalananmu di Lampung.</p>
            </div>

            <a href="{{ route('agent.pasar.create') }}" class="btn btn-daftar-kendaraan">
                Daftarkan kendaraan Anda
            </a>
        </div>

        {{-- FILTER KATEGORI --}}
        @php $type = request('type'); @endphp

        <div class="d-flex gap-3 flex-wrap mb-4">

            <a href="{{ route('pasar-digital.index') }}"
               class="category-box {{ !$type ? 'active' : '' }}">
                <i class="fa-solid fa-car-side"></i> Semua Kendaraan
            </a>

            <a href="{{ route('pasar-digital.index', ['type' => 'CAR']) }}"
               class="category-box {{ $type === 'CAR' ? 'active' : '' }}">
                <i class="fa-solid fa-car"></i> Mobil
            </a>

            <a href="{{ route('pasar-digital.index', ['type' => 'MOTORCYCLE']) }}"
               class="category-box {{ $type === 'MOTORCYCLE' ? 'active' : '' }}">
                <i class="fa-solid fa-motorcycle"></i> Motor
            </a>
        </div>

        {{-- LIST KENDARAAN --}}
        <h5 class="fw-bold mb-3">Semua Pilihan Kendaraan</h5>

        @if($vehicles->isEmpty())

            {{-- EMPTY STATE --}}
            <div class="text-center py-5">
                <i class="fa-solid fa-car-side mb-3" style="font-size:40px; color:#C4C4C4;"></i>
                <p class="fw-semibold mb-1">Kendaraan tidak ditemukan</p>
                <p class="text-muted mb-0">Coba ubah filter atau kata kunci pencarian Anda.</p>
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
                        <div class="card vehicle-card h-100 border-0 shadow-sm">

                            <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $vehicle->name }}">

                            <div class="card-body d-flex flex-column">

                                <h5 class="fw-bold mb-1">{{ $vehicle->name }}</h5>

                                <p class="text-muted small mb-1">
                                    <i class="fa-solid fa-location-dot me-1"></i>
                                    {{ $vehicle->location ?? 'Lokasi tidak diisi' }}
                                </p>

                                <p class="fw-bold mb-2">
                                    Rp {{ number_format($vehicle->price_per_day ?? 0, 0, ',', '.') }} / hari
                                </p>

                                <span class="badge bg-light text-muted mb-3">
                                    {{ $vehicle->vehicle_type === 'CAR' ? 'Mobil' : 'Motor' }}
                                </span>

                                <div class="d-flex justify-content-between mt-auto">
                                    <a href="{{ route('agent.pasar.edit', $vehicle->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fa-solid fa-pen me-1"></i> Ubah
                                    </a>

                                    <form action="{{ route('agent.pasar.destroy', $vehicle->id) }}"
                                          method="POST" onsubmit="return confirm('Hapus kendaraan ini?');">
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

    </main>
</div>

@endsection
