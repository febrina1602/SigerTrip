@extends('layouts.app')

@section('title', 'Edit Pemandu Wisata - Admin')

@section('content')

  {{-- HEADER --}}
    <header class="border-bottom bg-white shadow-sm">
        <div class="container py-2 d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.beranda') }}" class="d-flex align-items-center text-decoration-none" style="min-width: 150px;">
                <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo" style="height:42px" loading="lazy" onerror="this.style.display='none'">
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
                    <button type="submit" class="btn btn-link text-danger p-0" style="font-size: 1.6rem; line-height: 1;" title="Keluar">
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
        <a href="{{ route('admin.profil-agent.index') }}" class="nav-link-custom">Profil Agent</a>
        <a href="{{ route('admin.pasar.index') }}" class="nav-link-custom">Pasar Digital</a>
        <a href="{{ route('admin.tour-packages.index') }}" class="nav-link-custom {{ request()->routeIs('admin.tour-packages*') ? 'active' : '' }}">Pemandu Wisata</a>
        <a href="{{ route('admin.users.index') }}" class="nav-link-custom">Kelola User</a>
    </div>
</nav>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            {{-- BREADCRUMB --}}
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.tour-packages.index') }}" class="text-decoration-none">Pemandu Wisata</a></li>
                    <li class="breadcrumb-item active">Edit Paket</li>
                </ol>
            </nav>

            <div class="card border-0 shadow-sm rounded-4">
                
                <div class="card-header bg-white py-3 border-bottom">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h5 class="fw-bold mb-1">Edit Paket Perjalanan</h5>
                            <p class="text-muted small mb-0">
                                Agent: <strong>{{ $package->agent->user->full_name ?? $package->agent->user->name }}</strong>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    
                    <form action="{{ route('admin.tour-packages.update', $package->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        {{-- Nama Paket --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small">Nama Paket</label>
                            <input type="text" 
                                   name="name" 
                                   class="form-control rounded-3 @error('name') is-invalid @enderror" 
                                   value="{{ old('name', $package->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Row: Harga & Durasi --}}
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold small">Harga per Orang (Rp)</label>
                                <input type="number" 
                                       name="price_per_person" 
                                       class="form-control rounded-3 @error('price_per_person') is-invalid @enderror" 
                                       value="{{ old('price_per_person', $package->price_per_person) }}" 
                                       required 
                                       min="0"
                                       step="1000">
                                @error('price_per_person')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold small">Durasi</label>
                                <input type="text" 
                                       name="duration" 
                                       class="form-control rounded-3 @error('duration') is-invalid @enderror" 
                                       value="{{ old('duration', $package->duration) }}"
                                       placeholder="Contoh: 2 Hari 1 Malam">
                                @error('duration')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Fasilitas --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small">Fasilitas</label>
                            <textarea name="facilities" 
                                      class="form-control rounded-3 @error('facilities') is-invalid @enderror" 
                                      rows="3"
                                      placeholder="Contoh: Makan siang, Tiket masuk, Transportasi">{{ old('facilities', is_array($package->facilities) ? implode(', ', $package->facilities) : $package->facilities) }}</textarea>
                            <small class="text-muted">Pisahkan dengan koma jika ada banyak fasilitas</small>
                            @error('facilities')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small">Deskripsi Lengkap</label>
                            <textarea name="description" 
                                      class="form-control rounded-3 @error('description') is-invalid @enderror" 
                                      rows="5"
                                      placeholder="Jelaskan detail paket perjalanan ini...">{{ old('description', $package->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Foto Cover --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small">Foto Cover</label>
                            
                            @if($package->cover_image_url)
                                <div class="mb-3">
                                    <p class="text-muted small mb-2">Foto saat ini:</p>
                                    <img src="{{ asset('storage/'.$package->cover_image_url) }}" 
                                         alt="Current" 
                                         class="img-thumbnail rounded-3" 
                                         style="height: 150px; object-fit: cover;">
                                </div>
                            @endif

                            <input type="file" 
                                   name="cover_image_file" 
                                   class="form-control rounded-3 @error('cover_image_file') is-invalid @enderror" 
                                   accept="image/jpeg,image/png,image/jpg,image/webp">
                            <small class="text-muted d-block mt-2">
                                Format: JPG, PNG, WebP (Max: 2MB). Biarkan kosong jika tidak ingin mengubah foto.
                            </small>
                            @error('cover_image_file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                            <a href="{{ route('admin.tour-packages.index') }}" class="btn btn-light rounded-3 px-4">
                                <i class="fas fa-times me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary rounded-3 px-4">
                                <i class="fas fa-save me-1"></i> Update Paket
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Danger Zone --}}
            <div class="card card-danger border-danger mt-4">
                <div class="card-header bg-danger bg-opacity-10 border-danger py-3">
                    <h6 class="fw-bold text-danger mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i> Zona Berbahaya
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">Tindakan di bawah tidak dapat dibatalkan.</p>
                    <form action="{{ route('admin.tour-packages.destroy', $package->id) }}" method="POST" onsubmit="return confirm('Yakin hapus paket ini secara permanen?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger rounded-3">
                            <i class="fas fa-trash-alt me-1"></i> Hapus Paket Ini
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
