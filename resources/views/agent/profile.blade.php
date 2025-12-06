@extends('layouts.app')

@section('content')
<div class="container-fluid py-5" style="background: #f8f9fa;">
    <div class="container" style="max-width: 800px;">
        {{-- Session Messages --}}
        @if(session('warning'))
            <div class="alert alert-warning border-0 rounded-3 mb-4 alert-dismissible fade show" style="padding: 1rem 1.5rem;">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger border-0 rounded-3 mb-4 alert-dismissible fade show" style="padding: 1rem 1.5rem;">
                <i class="fas fa-times-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Alert Message --}}
        <div class="alert alert-info border-0 rounded-3 mb-4" style="background: #d1ecf1; padding: 1rem 1.5rem;">
            <i class="fas fa-info-circle me-2"></i> Selamat datang! Harap lengkapi profil agensi Anda terlebih dahulu.
        </div>

        {{-- Form Card --}}
        <div class="card border-0 rounded-4 shadow-sm">
            <div class="card-body p-5">
                <h3 class="fw-bold mb-2">Buat Profil Agensi Anda</h3>
                <p class="text-muted mb-5">Lengkapi data di bawah ini untuk mendaftarkan agensi Anda secara resmi.</p>

                <form id="agentProfileForm">
                    {{-- Deskripsi Singkat Agensi --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Deskripsi Singkat Agensi</label>
                        <textarea class="form-control rounded-2" rows="4" 
                                  style="border-color: #ddd; resize: vertical;">{{ $agent->description ?? '' }}</textarea>
                    </div>

                    {{-- Lokasi Agensi --}}
                    <div class="mb-4">
                        <label class="form-label fw-bold">Lokasi Agensi</label>
                        <input type="text" class="form-control rounded-2" 
                               value="{{ $agent->address ?? '' }}" 
                               style="border-color: #ddd;">
                    </div>

                    {{-- Upload Gambar Banner --}}
                    <div class="mb-5">
                        <label class="form-label fw-bold">Upload Gambar Banner</label>
                        <div class="input-group input-group-lg">
                            <input type="file" class="form-control rounded-2" accept="image/*" 
                                   style="border-color: #ddd;">
                        </div>
                        <small class="text-muted d-block mt-2">( JPG, PNG maks 2MB )</small>
                    </div>

                    {{-- Submit Button --}}
                    <button type="button" class="btn btn-lg w-100 rounded-3 fw-bold" 
                            style="background: linear-gradient(90deg, #FFD15C 0%, #c84c3d 100%); color: white; padding: 0.9rem;">
                        <i class="fas fa-lock me-2"></i> Kirim Pengajuan Profil
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control, .form-select {
        border: 1px solid #ddd !important;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #FF9739 !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 151, 57, 0.25);
    }
</style>
@endsection
