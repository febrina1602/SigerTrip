@extends('layouts.app')

@section('title', 'Edit Kategori - SigerTrip')

@section('content')
<div class="min-vh-100 bg-white">

    {{-- HEADER --}}
    <header class="border-bottom bg-white shadow-sm">
        <div class="container py-2 d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.beranda') }}" class="d-flex align-items-center text-decoration-none" style="min-width: 150px;">
                <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo" style="height:42px" loading="lazy">
                <span class="ms-2 fw-bold text-dark d-none d-md-block">SigerTrip</span>
            </a>

            <div class="d-flex align-items-center gap-4" style="min-width: 150px; justify-content: flex-end;">
                <div class="text-center">
                    <i class="fas fa-user-circle text-dark" style="font-size: 1.8rem;"></i>
                    <div class="small fw-medium mt-1 text-dark">
                        {{ Auth::user()->full_name ?? 'Admin' }}
                    </div>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger p-0" style="font-size: 1.6rem;" title="Keluar">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- NAVIGATION --}}
    <nav class="nav-custom bg-light py-2 border-bottom">
        <div class="container d-flex gap-4">
            <a href="{{ route('admin.beranda') }}" class="nav-link-custom active">Beranda</a>
            <a href="#" class="nav-link-custom">Profil Agent</a>
            <a href="{{ route('admin.pasar') }}" class="nav-link-custom">Pasar Digital</a>
            <a href="#" class="nav-link-custom">Pemandu Wisata</a>
            <a href="{{ route('admin.users.index') }}" class="nav-link-custom">Kelola User</a>
        </div>
    </nav>

    {{-- CONTENT --}}
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-warning text-dark py-3 rounded-top-4">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-edit me-2"></i>Edit Kategori: {{ $category->name }}
                            </h5>
                        </div>

                        <div class="card-body p-4">
                            {{-- Alert Error --}}
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <strong>Terjadi Kesalahan!</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Icon Saat Ini --}}
                                @if($category->icon_url)
                                    <div class="mb-3 text-center">
                                        <label class="form-label fw-semibold">Icon Saat Ini</label>
                                        <div>
                                            <img src="{{ asset($category->icon_url) }}" 
                                                 alt="{{ $category->name }}" 
                                                 class="img-thumbnail"
                                                 style="max-width: 120px;">
                                        </div>
                                    </div>
                                @endif

                                {{-- Nama Kategori --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        Nama Kategori <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $category->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Upload Icon Baru --}}
                                <div class="mb-4">
                                    <label for="icon" class="form-label fw-semibold">Upload Icon Baru</label>
                                    <input type="file" 
                                           class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" 
                                           name="icon" 
                                           accept="image/*"
                                           onchange="previewIcon(event)">
                                    <small class="text-muted">Format: JPG, PNG, SVG (Maksimal 2MB). Kosongkan jika tidak ingin mengubah icon.</small>
                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    {{-- Preview Icon Baru --}}
                                    <div id="iconPreview" class="mt-3" style="display: none;">
                                        <label class="form-label fw-semibold">Preview Icon Baru:</label>
                                        <img id="preview" src="" alt="Preview" class="img-thumbnail d-block" style="max-width: 200px;">
                                    </div>
                                </div>

                                {{-- Tombol --}}
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-warning px-4">
                                        <i class="fas fa-save me-2"></i>Update
                                    </button>
                                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary px-4">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
function previewIcon(event) {
    const preview = document.getElementById('preview');
    const previewDiv = document.getElementById('iconPreview');
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            previewDiv.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        previewDiv.style.display = 'none';
    }
}
</script>
@endsection