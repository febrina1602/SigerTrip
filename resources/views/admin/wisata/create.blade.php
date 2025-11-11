@extends('layouts.app')

@section('title', 'Tambah Destinasi Wisata')

@section('content')
<div class="container py-5">
    <h4 class="fw-bold mb-4">Tambah Destinasi Wisata</h4>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form tambah destinasi --}}
    <form action="{{ route('admin.wisata.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Nama --}}
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Nama Destinasi</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>

        {{-- Alamat --}}
        <div class="mb-3">
            <label for="address" class="form-label fw-semibold">Alamat</label>
            <textarea class="form-control" id="address" name="address" rows="2" required></textarea>
        </div>

        {{-- Deskripsi --}}
        <div class="mb-3">
            <label for="description" class="form-label fw-semibold">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
        </div>

        {{-- Fasilitas --}}
        <div class="mb-3">
            <label for="facilities" class="form-label fw-semibold">Fasilitas</label>
            <textarea class="form-control" id="facilities" name="facilities" rows="3" placeholder="Contoh: Toilet, Area Parkir, Wi-Fi"></textarea>
        </div>

        {{-- Harga Tiket per Orang --}}
        <div class="mb-3">
            <label for="price_per_person" class="form-label fw-semibold">Harga per Orang (Rp)</label>
            <input type="number" class="form-control" id="price_per_person" name="price_per_person" min="0" required>
        </div>

        {{-- Harga Parkir --}}
        <div class="mb-3">
            <label for="parking_price" class="form-label fw-semibold">Harga Parkir (Rp)</label>
            <input type="number" class="form-control" id="parking_price" name="parking_price" min="0" value="0">
        </div>

        {{-- Kategori --}}
        <div class="mb-3">
            <label for="category_id" class="form-label fw-semibold">Kategori</label>
            <select name="category_id" id="category_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Aktivitas Populer --}}
        <div class="mb-3">
            <label for="popular_activities" class="form-label fw-semibold">Aktivitas Populer</label>
            <textarea class="form-control" id="popular_activities" name="popular_activities" rows="3" placeholder="Contoh: Snorkeling, Hiking, Menikmati Kuliner"></textarea>
        </div>

        {{-- Koordinat Lokasi --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="latitude" class="form-label fw-semibold">Latitude</label>
                <input type="text" class="form-control" id="latitude" name="latitude" placeholder="-5.4302" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="longitude" class="form-label fw-semibold">Longitude</label>
                <input type="text" class="form-control" id="longitude" name="longitude" placeholder="105.2621" required>
            </div>
        </div>

        {{-- Rating --}}
        <div class="mb-3">
            <label for="rating" class="form-label fw-semibold">Rating Awal (0 - 5)</label>
            <input type="number" step="0.01" class="form-control" id="rating" name="rating" min="0" max="5" value="0">
        </div>

        {{-- Destinasi Unggulan --}}
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1">
            <label class="form-check-label" for="is_featured">
                Tandai sebagai destinasi unggulan
            </label>
        </div>

        {{-- Upload Foto --}}
        <div class="mb-4">
            <label for="image" class="form-label fw-semibold">Upload Foto Utama</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            <small class="text-muted">Format: JPG, PNG, atau JPEG (maksimal 2MB).</small>
        </div>

        {{-- Tombol --}}
        <button type="submit" class="btn btn-primary px-4">Simpan</button>
        <a href="{{ route('admin.beranda') }}" class="btn btn-secondary ms-2 px-4">Batal</a>
    </form>
</div>
@endsection
