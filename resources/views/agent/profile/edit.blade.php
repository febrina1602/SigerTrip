@extends('layouts.app')

@section('title', 'Lengkapi Profil Agensi - SigerTrip')

@section('content')
@include('components.layout.header')

<div class="min-vh-100 py-5" style="background: #f8f9fa;">
    <div class="container" style="max-width: 800px;">
        
        {{-- Session Messages --}}
        @if(session('warning'))
            <div class="alert alert-warning border-0 rounded-3 mb-4 alert-dismissible fade show shadow-sm">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 rounded-3 mb-4 alert-dismissible fade show shadow-sm">
                <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger border-0 rounded-3 mb-4 shadow-sm">
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Info Card --}}
        <div class="alert alert-info border-0 rounded-3 mb-4 shadow-sm d-flex align-items-center gap-3">
            <i class="fas fa-info-circle fa-lg text-info"></i>
            <div>
                <strong>Selamat datang!</strong> 
                <p class="mb-0 small">Harap lengkapi profil agensi Anda terlebih dahulu agar dapat mulai mengelola paket wisata dan kendaraan.</p>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <h3 class="fw-bold mb-1">Profil Agensi</h3>
                    <p class="text-muted">Lengkapi identitas usaha Anda</p>
                </div>

                <form action="{{ route('agent.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        {{-- Nama Agensi --}}
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">NAMA AGENSI / BRAND <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control form-control-lg" 
                                   value="{{ old('name', $agent->name) }}" required placeholder="Contoh: Pahawang Travel">
                        </div>

                        {{-- Kontak & Lokasi --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">NOMOR KONTAK (WA) <span class="text-danger">*</span></label>
                            <input type="text" name="contact_phone" class="form-control" 
                                   value="{{ old('contact_phone', $agent->contact_phone) }}" required placeholder="0812...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold small text-muted">KOTA / WILAYAH</label>
                            <input type="text" name="location" class="form-control" 
                                   value="{{ old('location', $agent->location) }}" placeholder="Contoh: Bandar Lampung">
                        </div>

                        {{-- Alamat Lengkap --}}
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">ALAMAT OPERASIONAL <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="2" required placeholder="Alamat lengkap kantor...">{{ old('address', $agent->address) }}</textarea>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">DESKRIPSI SINGKAT <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="4" required placeholder="Jelaskan secara singkat tentang layanan agensi Anda...">{{ old('description', $agent->description) }}</textarea>
                            <div class="form-text">Deskripsi ini akan muncul di halaman profil publik Anda.</div>
                        </div>

                        {{-- Banner Upload --}}
                        <div class="col-12">
                            <label class="form-label fw-bold small text-muted">BANNER PROFIL</label>
                            @if($agent->banner_image_url)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$agent->banner_image_url) }}" alt="Current Banner" class="img-thumbnail w-100" style="height: 150px; object-fit: cover;">
                                </div>
                            @endif
                            <input type="file" name="banner_image" class="form-control" accept="image/*">
                            <div class="form-text">Format: JPG, PNG. Maksimal 2MB.</div>
                        </div>
                    </div>

                    <hr class="my-4">

                    {{-- PERBAIKAN: type="submit" --}}
                    <div class="d-grid">
                        <button type="submit" class="btn btn-lg w-100 rounded-3 fw-bold" 
                                style="background: linear-gradient(90deg, #FFD15C 0%, #c84c3d 100%); color: white; padding: 0.9rem;">
                            <i class="fas fa-save me-2"></i> Simpan & Lanjutkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus, .form-select:focus {
        border-color: #FF9739 !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 151, 57, 0.25);
    }
</style>
@endsection