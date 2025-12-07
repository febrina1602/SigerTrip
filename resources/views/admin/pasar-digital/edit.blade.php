@extends('layouts.app')

@section('title', 'Edit Kendaraan – Pasar Digital')

@push('styles')
<style>
  .card-form {
      border-radius: 18px;
      border: none;
      box-shadow: 0 10px 30px rgba(0,0,0,0.06);
      margin: 0 auto;
      max-width: 800px;
  }
  .section-title {
      font-weight: 700;
      font-size: 1.05rem;
      margin-bottom: .75rem;
  }
  .required::after {
      content: '*';
      color: #e3342f;
      margin-left: 2px;
  }
  .btn-grad {
      background: linear-gradient(90deg, #FFD15C 0%, #FF9739 45%, #FF3D3D 100%);
      color: #fff;
      border: none;
      height: 46px;
      border-radius: 12px;
      font-weight: 700;
      padding: 0 32px;
  }
  .btn-grad:hover {
      filter: brightness(.96);
      color: #fff;
  }
  .btn-back {
      background: linear-gradient(90deg, #FFD15C 0%, #FF9739 45%, #FF3D3D 100%);
      color: #fff;
      border-radius: 12px;
      padding: 0.5rem 1.5rem;
      font-weight: 600;
      border: 1px solid #FF3D3D;
  }
  .btn-back:hover {
      filter: brightness(.96);
      color: #fff;
  }
</style>
@endpush

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
            {{-- PERBAIKAN: admin.pasar.index --}}
            <a href="{{ route('admin.pasar.index') }}" class="nav-link-custom active">Pasar Digital</a>
            <a href="#" class="nav-link-custom">Pemandu Wisata</a>
            <a href="{{ route('admin.users.index') }}" class="nav-link-custom">Kelola User</a>
        </div>
    </nav>

    {{-- CONTENT --}}
    <div class="container py-5">
        <div class="row g-4 justify-content-center">

            {{-- Tombol Kembali --}}
            <div class="col-12">
                <a href="{{ route('admin.pasar.index') }}" class="btn btn-back mb-3">
                    <i class="fas fa-arrow-left me-2"></i> Kembali
                </a>
            </div>

            {{-- FORM EDIT KENDARAAN --}}
            <div class="col-lg-8">
                <div class="card card-form">
                    <div class="card-body p-4 p-md-5">

                        <h3 class="fw-bold mb-1">Form Edit Kendaraan</h3>
                        <p class="text-muted mb-4">
                            Lengkapi informasi di bawah ini untuk memperbarui data kendaraan Anda.
                        </p>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.pasar.update', $vehicle->id) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="row g-3">
                            @csrf
                            @method('PUT')

                            {{-- ===== INFORMASI UTAMA ===== --}}
                            <div class="col-12">
                                <div class="section-title">Informasi Utama</div>
                            </div>

                            <div class="col-md-8">
                                <label class="form-label required">Nama Kendaraan (Nama Paket)</label>
                                <input type="text" name="name"
                                       value="{{ old('name', $vehicle->name) }}"
                                       class="form-control @error('name') is-invalid @enderror"
                                       placeholder="Contoh: Avanza G 2019 – Include Driver" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label required">Jenis Kendaraan</label>
                                <select name="vehicle_type"
                                        class="form-select @error('vehicle_type') is-invalid @enderror" required>
                                    <option value="">Pilih jenis</option>
                                    <option value="CAR" {{ old('vehicle_type', $vehicle->vehicle_type) === 'CAR' ? 'selected' : '' }}>Mobil</option>
                                    <option value="MOTORCYCLE" {{ old('vehicle_type', $vehicle->vehicle_type) === 'MOTORCYCLE' ? 'selected' : '' }}>Motor</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Harga Sewa per Hari (Rp)</label>
                                <input type="number" name="price_per_day" min="0" step="1000"
                                       value="{{ old('price_per_day', $vehicle->price_per_day) }}"
                                       class="form-control @error('price_per_day') is-invalid @enderror"
                                       placeholder="Contoh: 350000" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label required">Lokasi Penjemputan</label>
                                <input type="text" name="location"
                                       value="{{ old('location', $vehicle->location) }}"
                                       class="form-control @error('location') is-invalid @enderror"
                                       placeholder="Contoh: Bandara Radin Inten II" required>
                            </div>

                            {{-- ===== DETAIL KENDARAAN ===== --}}
                            <div class="col-12 pt-3">
                                <div class="section-title">Detail Kendaraan</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Merek</label>
                                <input type="text" name="brand"
                                       value="{{ old('brand', $vehicle->brand) }}"
                                       class="form-control"
                                       placeholder="Contoh: Toyota">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Model / Tipe</label>
                                <input type="text" name="model"
                                       value="{{ old('model', $vehicle->model) }}"
                                       class="form-control"
                                       placeholder="Contoh: Avanza G">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tahun</label>
                                <input type="number" name="year"
                                       value="{{ old('year', $vehicle->year) }}"
                                       class="form-control"
                                       min="1990" max="{{ date('Y') + 1 }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Transmisi</label>
                                <select name="transmission" class="form-select">
                                    <option value="">Pilih</option>
                                    <option value="Manual" {{ old('transmission', $vehicle->transmission) === 'Manual' ? 'selected' : '' }}>Manual</option>
                                    <option value="Automatic" {{ old('transmission', $vehicle->transmission) === 'Automatic' ? 'selected' : '' }}>Automatic</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Kapasitas Kursi</label>
                                <input type="number" name="seats"
                                       value="{{ old('seats', $vehicle->seats) }}"
                                       class="form-control"
                                       min="1" max="20">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Jenis Bahan Bakar</label>
                                <input type="text" name="fuel_type"
                                       value="{{ old('fuel_type', $vehicle->fuel_type) }}"
                                       class="form-control"
                                       placeholder="Contoh: Bensin">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Nomor Polisi (Opsional)</label>
                                <input type="text" name="plate_number"
                                       value="{{ old('plate_number', $vehicle->plate_number) }}"
                                       class="form-control"
                                       placeholder="Contoh: BE 1234 XX">
                            </div>

                            {{-- ===== OPSI LAYANAN & SYARAT SEWA ===== --}}
                            <div class="col-12 pt-3">
                                <div class="section-title">Opsi Layanan & Syarat Sewa</div>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Minimal Durasi Sewa (hari)</label>
                                <input type="number" name="min_rental_days"
                                       value="{{ old('min_rental_days', $vehicle->min_rental_days) }}"
                                       class="form-control"
                                       min="1" max="30">
                            </div>

                            <div class="col-md-8">
                                <label class="form-label d-block">Layanan Termasuk</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox"
                                           id="include_driver" name="include_driver"
                                           value="1" {{ old('include_driver', $vehicle->include_driver) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="include_driver">
                                        Include driver
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox"
                                           id="include_fuel" name="include_fuel"
                                           value="1" {{ old('include_fuel', $vehicle->include_fuel) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="include_fuel">
                                        Termasuk BBM
                                    </label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox"
                                           id="include_pickup_drop" name="include_pickup_drop"
                                           value="1" {{ old('include_pickup_drop', $vehicle->include_pickup_drop) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="include_pickup_drop">
                                        Antar-jemput
                                    </label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Deskripsi Kendaraan</label>
                                <textarea name="description" rows="3"
                                          class="form-control"
                                          placeholder="Ceritakan kondisi kendaraan...">{{ old('description', $vehicle->description) }}</textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Syarat & Ketentuan Sewa (Opsional)</label>
                                <textarea name="terms_conditions" rows="3"
                                          class="form-control"
                                          placeholder="Contoh: Wajib SIM A, denda keterlambatan...">{{ old('terms_conditions', $vehicle->terms_conditions) }}</textarea>
                            </div>

                            {{-- GAMBAR --}}
                            <div class="col-12 pt-3">
                                <div class="section-title">Foto Kendaraan</div>
                            </div>

                            @if($vehicle->image_url)
                            <div class="col-12 mb-2">
                                <img src="{{ asset('storage/'.$vehicle->image_url) }}" alt="Foto Lama" class="img-thumbnail" style="height: 150px;">
                                <div class="small text-muted mt-1">Foto saat ini</div>
                            </div>
                            @endif

                            <div class="col-md-6">
                                <label class="form-label">Upload Foto Baru (Opsional)</label>
                                <input type="file" name="image" accept="image/*"
                                       class="form-control @error('image') is-invalid @enderror">
                                <div class="form-text">
                                    Format JPG/PNG, maksimal 2 MB. Kosongkan jika tidak ingin mengubah.
                                </div>
                            </div>

                            {{-- SUBMIT --}}
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-grad w-100">
                                    Simpan &amp; Update Kendaraan
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection