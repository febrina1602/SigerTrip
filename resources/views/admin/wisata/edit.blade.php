@extends('layouts.app')

@section('title', 'Edit Destinasi Wisata')

@section('content')
<div class="container py-5">
    {{-- HEADER --}}
   <header class="d-flex justify-content-between align-items-center py-3 px-4 border-bottom">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo" style="height:50px;">
        </div>
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
    </header>
    
    
    <h4 class="fw-bold mb-4">Edit Destinasi Wisata</h4>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form edit destinasi --}}
    <form action="{{ route('admin.wisata.update', $destination->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Nama --}}
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Nama Destinasi</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $destination->name) }}" required>
        </div>

        {{-- Alamat --}}
        <div class="mb-3">
            <label for="address" class="form-label fw-semibold">Alamat</label>
            <textarea class="form-control" id="address" name="address" rows="2" required>{{ old('address', $destination->address) }}</textarea>
        </div>

        {{-- Deskripsi --}}
        <div class="mb-3">
            <label for="description" class="form-label fw-semibold">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $destination->description) }}</textarea>
        </div>

        {{-- Fasilitas --}}
        <div class="mb-3">
            <label for="facilities" class="form-label fw-semibold">Fasilitas</label>
            <textarea class="form-control" id="facilities" name="facilities" rows="3" placeholder="Contoh: Toilet, Area Parkir, Wi-Fi">{{ old('facilities', $destination->facilities) }}</textarea>
        </div>

        {{-- Harga Tiket per Orang --}}
        <div class="mb-3">
            <label for="price_per_person" class="form-label fw-semibold">Harga per Orang (Rp)</label>
            <input type="number" class="form-control" id="price_per_person" name="price_per_person" min="0" value="{{ old('price_per_person', $destination->price_per_person) }}" required>
        </div>

        {{-- Harga Parkir --}}
        <div class="mb-3">
            <label for="parking_price" class="form-label fw-semibold">Harga Parkir (Rp)</label>
            <input type="number" class="form-control" id="parking_price" name="parking_price" min="0" value="{{ old('parking_price', $destination->parking_price) }}">
        </div>

        {{-- Kategori --}}
        <div class="mb-3">
            <label for="category_id" class="form-label fw-semibold">Kategori</label>
            <select name="category_id" id="category_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $destination->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Aktivitas Populer --}}
        <div class="mb-3">
            <label for="popular_activities" class="form-label fw-semibold">Aktivitas Populer</label>
            @php
                // Ambil data lama atau dari database
                $activities = old('popular_activities', $destination->popular_activities ?? []);

                // Jika datanya array (misalnya hasil casting JSON), ubah jadi string
                if (is_array($activities)) {
                    $activities = implode(', ', $activities);
                }
            @endphp

            <textarea class="form-control" id="popular_activities" name="popular_activities" rows="3" placeholder="Contoh: Snorkeling, Hiking, Menikmati Kuliner">{{ $activities }}</textarea>
        </div>


        {{-- Koordinat Lokasi --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="latitude" class="form-label fw-semibold">Latitude</label>
                <input type="text" class="form-control" id="latitude" name="latitude" placeholder="-5.4302" value="{{ old('latitude', $destination->latitude) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="longitude" class="form-label fw-semibold">Longitude</label>
                <input type="text" class="form-control" id="longitude" name="longitude" placeholder="105.2621" value="{{ old('longitude', $destination->longitude) }}" required>
            </div>
        </div>

        {{-- Rating --}}
        <div class="mb-3">
            <label for="rating" class="form-label fw-semibold">Rating Awal (0 - 5)</label>
            <input type="number" step="0.01" class="form-control" id="rating" name="rating" min="0" max="5" value="{{ old('rating', $destination->rating) }}">
        </div>

        {{-- Destinasi Unggulan --}}
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $destination->is_featured) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_featured">
                Tandai sebagai destinasi unggulan
            </label>
        </div>

        {{-- Preview Gambar Saat Ini --}}
        @if($destination->image_url)
        <div class="mb-3">
            <label class="form-label fw-semibold">Gambar Saat Ini</label>
            <div>
                <img src="{{ asset($destination->image_url) }}" alt="{{ $destination->name }}" class="img-thumbnail" style="max-width: 300px;">
            </div>
        </div>
        @endif

        {{-- Upload Foto Baru --}}
        <div class="mb-4">
            <label for="image" class="form-label fw-semibold">Upload Foto Baru (Opsional)</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <small class="text-muted">Format: JPG, PNG, atau JPEG (maksimal 2MB). Kosongkan jika tidak ingin mengubah gambar.</small>
        </div>

        {{-- Tombol --}}
        <button type="submit" class="btn btn-primary px-4">Update</button>
        <a href="{{ route('admin.beranda') }}" class="btn btn-secondary ms-2 px-4">Batal</a>
    </form>
</div>
@endsection