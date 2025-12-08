@extends('layouts.app')

@section('title', 'Edit User - SigerTrip')

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
                        {{ Auth::user()->full_name ?? Auth::user()->name ?? 'Admin' }}
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
            <a href="{{ route('admin.beranda') }}" class="nav-link-custom">Beranda</a>
            <a href="{{route('admin.profil-agent.index')}}" class="nav-link-custom">Profil Agent</a>
            <a href="{{ route('admin.pasar.index') }}" class="nav-link-custom">Pasar Digital</a>
            <a href="{{ route('admin.tour-packages.index') }}" class="nav-link-custom">Pemandu Wisata</a>
            <a href="{{ route('admin.users.index') }}" class="nav-link-custom active">Kelola User</a>
        </div>
    </nav>

    {{-- CONTENT --}}
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    {{-- Card Header --}}
                    <div class="card border-0 shadow-sm rounded-4">
                        <div class="card-header bg-warning text-dark py-3 rounded-top-4">
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-user-edit me-2"></i>Edit User: {{ $user->full_name }}
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

                            {{-- Info Status User --}}
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Status Saat Ini:</strong>
                                @if($user->status === 'aktif')
                                    <span class="badge bg-success ms-2">Aktif</span>
                                @elseif($user->status === 'pending')
                                    <span class="badge bg-warning text-dark ms-2">Pending</span>
                                @else
                                    <span class="badge bg-danger ms-2">Nonaktif</span>
                                @endif
                                | <strong>Role:</strong> 
                                @if($user->role === 'admin')
                                    <span class="badge bg-info ms-2">Admin</span>
                                @elseif($user->role === 'agent')
                                    <span class="badge bg-success ms-2">Agen</span>
                                @else
                                    <span class="badge bg-warning ms-2">Wisatawan</span>
                                @endif
                            </div>

                            <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                {{-- Foto Profil Saat Ini --}}
                                @if($user->profile_picture_url)
                                    <div class="mb-3 text-center">
                                        <label class="form-label fw-semibold">Foto Profil Saat Ini</label>
                                        <div>
                                            <img src="{{ asset($user->profile_picture_url) }}" 
                                                 alt="{{ $user->full_name }}" 
                                                 class="img-thumbnail rounded-circle"
                                                 style="width: 120px; height: 120px; object-fit: cover;">
                                        </div>
                                    </div>
                                @endif

                                {{-- Nama Lengkap --}}
                                <div class="mb-3">
                                    <label for="full_name" class="form-label fw-semibold">
                                        Nama Lengkap <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('full_name') is-invalid @enderror" 
                                           id="full_name" 
                                           name="full_name" 
                                           value="{{ old('full_name', $user->full_name) }}" 
                                           required>
                                    @error('full_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">
                                        Email <span class="text-danger">*</span>
                                    </label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- No Telepon --}}
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label fw-semibold">No. Telepon</label>
                                    <input type="text" 
                                           class="form-control @error('phone_number') is-invalid @enderror" 
                                           id="phone_number" 
                                           name="phone_number" 
                                           value="{{ old('phone_number', $user->phone_number) }}">
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Gender --}}
                                <div class="mb-3">
                                    <label for="gender" class="form-label fw-semibold">Jenis Kelamin</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" 
                                            id="gender" 
                                            name="gender">
                                        <option value="">-- Pilih Jenis Kelamin --</option>
                                        <option value="Laki-laki" {{ old('gender', $user->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('gender', $user->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Role --}}
                                <div class="mb-3">
                                    <label for="role" class="form-label fw-semibold">
                                        Role <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('role') is-invalid @enderror" 
                                            id="role" 
                                            name="role" 
                                            required>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                        <option value="agent" {{ old('role', $user->role) == 'agent' ? 'selected' : '' }}>Agen</option>
                                        <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Wisatawan</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- STATUS --}}
                                <div class="mb-3">
                                    <label for="status" class="form-label fw-semibold">
                                        Status <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('status') is-invalid @enderror" 
                                            id="status" 
                                            name="status" 
                                            required>
                                        <option value="aktif" {{ old('status', $user->status) == 'aktif' ? 'selected' : '' }}>
                                            Aktif
                                        </option>
                                        <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="nonaktif" {{ old('status', $user->status) == 'nonaktif' ? 'selected' : '' }}>
                                            Nonaktif
                                        </option>
                                    </select>
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Ubah status sesuai kebutuhan. Status "Aktif" akan otomatis update waktu verifikasi.
                                    </small>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Password Baru --}}
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">
                                        Password Baru
                                    </label>
                                    <input type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           id="password" 
                                           name="password" 
                                           placeholder="Kosongkan jika tidak ingin mengubah password">
                                    <small class="text-muted">Minimal 8 karakter. Kosongkan jika tidak ingin mengubah.</small>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Konfirmasi Password --}}
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label fw-semibold">
                                        Konfirmasi Password Baru
                                    </label>
                                    <input type="password" 
                                           class="form-control" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           placeholder="Ulangi password baru">
                                </div>

                                {{-- Upload Foto Profil Baru --}}
                                <div class="mb-4">
                                    <label for="profile_picture" class="form-label fw-semibold">Upload Foto Profil Baru</label>
                                    <input type="file" 
                                           class="form-control @error('profile_picture') is-invalid @enderror" 
                                           id="profile_picture" 
                                           name="profile_picture" 
                                           accept="image/*"
                                           onchange="previewImage(event)">
                                    <small class="text-muted">Format: JPG, PNG, JPEG (Maksimal 2MB). Kosongkan jika tidak ingin mengubah foto.</small>
                                    @error('profile_picture')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    {{-- Preview Image --}}
                                    <div id="imagePreview" class="mt-3" style="display: none;">
                                        <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                </div>

                                {{-- Tombol --}}
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-warning px-4">
                                        <i class="fas fa-save me-2"></i>Update
                                    </button>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary px-4">
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
function previewImage(event) {
    const preview = document.getElementById('preview');
    const previewDiv = document.getElementById('imagePreview');
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