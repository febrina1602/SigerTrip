@extends('layouts.app')

@section('content')
<div class="container mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h4 fw-bold">Edit Paket Perjalanan</h1>
        <a href="{{ route('tour-packages.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger border-0 rounded-3 mb-4">
            <h5 class="alert-heading"><i class="fas fa-exclamation-circle me-2"></i>Validasi Gagal</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 rounded-3 alert-dismissible fade show mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('tour-packages.update', $package->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card rounded-4 shadow-sm mb-4">
            <div class="card-header bg-light rounded-top-4 py-3 border-0">
                <h5 class="mb-0 fw-bold">Informasi Dasar</h5>
            </div>
            <div class="card-body">
                {{-- Judul Paket --}}
                <div class="mb-3">
                    <label for="title" class="form-label fw-bold">Judul Paket <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" 
                           value="{{ old('title', $package->name) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="mb-3">
                    <label for="description" class="form-label fw-bold">Deskripsi Paket</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" 
                              name="description" rows="4">{{ old('description', $package->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Cover Image --}}
                <div class="mb-3">
                    <label for="cover_image_file" class="form-label fw-bold">Gambar Sampul</label>
                    @if ($package->cover_image_url)
                        <div class="mb-2">
                            <img src="{{ $package->cover_image_url }}" alt="Cover" class="img-thumbnail" style="max-width: 200px; max-height: 150px;">
                            <p class="text-muted small">Upload gambar baru untuk mengganti</p>
                        </div>
                    @endif
                    <input type="file" class="form-control @error('cover_image_file') is-invalid @enderror" 
                           id="cover_image_file" name="cover_image_file" accept="image/*">
                    <small class="form-text text-muted">Format: JPG, PNG. Ukuran maksimal: 2MB</small>
                    @error('cover_image_file')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card rounded-4 shadow-sm mb-4">
            <div class="card-header bg-light rounded-top-4 py-3 border-0">
                <h5 class="mb-0 fw-bold">Harga & Durasi</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    {{-- Harga Per Orang --}}
                    <div class="col-md-6 mb-3">
                        <label for="price_per_person" class="form-label fw-bold">Harga Per Orang (Rp) <span class="text-danger">*</span></label>
                        <input type="number" class="form-control @error('price_per_person') is-invalid @enderror" 
                               id="price_per_person" name="price_per_person" 
                               value="{{ old('price_per_person', $package->price_per_person) }}" required step="1000">
                        @error('price_per_person')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Durasi --}}
                    <div class="col-md-6 mb-3">
                        <label for="duration" class="form-label fw-bold">Durasi</label>
                        <input type="text" class="form-control @error('duration') is-invalid @enderror" 
                               id="duration" name="duration"
                               value="{{ old('duration', $package->duration) }}">
                        @error('duration')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Hari --}}
                    <div class="col-md-6 mb-3">
                        <label for="duration_days" class="form-label fw-bold">Jumlah Hari</label>
                        <input type="number" class="form-control @error('duration_days') is-invalid @enderror" 
                               id="duration_days" name="duration_days"
                               value="{{ old('duration_days', $package->duration_days) }}" min="0">
                        @error('duration_days')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Malam --}}
                    <div class="col-md-6 mb-3">
                        <label for="duration_nights" class="form-label fw-bold">Jumlah Malam</label>
                        <input type="number" class="form-control @error('duration_nights') is-invalid @enderror" 
                               id="duration_nights" name="duration_nights"
                               value="{{ old('duration_nights', $package->duration_nights) }}" min="0">
                        @error('duration_nights')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card rounded-4 shadow-sm mb-4">
            <div class="card-header bg-light rounded-top-4 py-3 border-0">
                <h5 class="mb-0 fw-bold">Fasilitas & Persyaratan</h5>
            </div>
            <div class="card-body">
                {{-- Fasilitas --}}
                <div class="mb-3">
                    <label for="facilities" class="form-label fw-bold">Fasilitas Paket</label>
                    <textarea class="form-control @error('facilities') is-invalid @enderror" id="facilities" 
                              name="facilities" rows="4">{{ old('facilities', implode("\n", $package->facilities_array ?? [])) }}</textarea>
                    <small class="form-text text-muted">Tulis satu fasilitas per baris atau pisahkan dengan koma</small>
                    @error('facilities')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Peserta Minimum --}}
                <div class="mb-3">
                    <label for="minimum_participants" class="form-label fw-bold">Peserta Minimum</label>
                    <input type="number" class="form-control @error('minimum_participants') is-invalid @enderror" 
                           id="minimum_participants" name="minimum_participants"
                           value="{{ old('minimum_participants', $package->minimum_participants ?? 0) }}" min="0">
                    @error('minimum_participants')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card rounded-4 shadow-sm mb-4">
            <div class="card-header bg-light rounded-top-4 py-3 border-0">
                <h5 class="mb-0 fw-bold">Periode & Status</h5>
            </div>
            <div class="card-body">
                {{-- Periode Ketersediaan --}}
                <div class="mb-3">
                    <label for="availability_period" class="form-label fw-bold">Periode Ketersediaan</label>
                    <input type="text" class="form-control @error('availability_period') is-invalid @enderror" 
                           id="availability_period" name="availability_period"
                           value="{{ old('availability_period', $package->availability_period ?? '') }}">
                    @error('availability_period')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="d-flex gap-2 mb-5">
            <button type="submit" class="btn btn-primary btn-lg rounded-3 flex-grow-1">
                <i class="fas fa-save me-2"></i> Perbarui Paket
            </button>
            <a href="{{ route('tour-packages.index') }}" class="btn btn-outline-secondary btn-lg rounded-3">
                <i class="fas fa-times me-2"></i> Batal
            </a>
        </div>
    </form>
</div>
@endsection