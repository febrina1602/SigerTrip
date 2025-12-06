@extends('layouts.app')

@section('title', 'Edit Kendaraan – Pasar Digital Mitra')

@push('styles')
<style>
  .card-form {
      border-radius: 18px;
      border: none;
      box-shadow: 0 10px 30px rgba(0,0,0,0.06);
      margin: 0 auto; /* Menjaga form tetap terpusat */
      max-width: 800px; /* Menetapkan lebar maksimal form */
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

  /* Tombol gradien seperti di login / register */
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

  /* Tombol Kembali dengan warna sama */
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
<div class="container py-5">
    <div class="row g-4 justify-content-center">

        {{-- Tombol Kembali --}}
        <div class="col-12">
            <a href="{{ route('admin.pasar') }}" class="btn btn-back mb-3">
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
                                   placeholder="Contoh: Avanza G 2019 – Include Driver">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">Jenis Kendaraan</label>
                            <select name="vehicle_type"
                                    class="form-select @error('vehicle_type') is-invalid @enderror">
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
                                   placeholder="Contoh: 350000">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Lokasi Penjemputan</label>
                            <input type="text" name="location"
                                   value="{{ old('location', $vehicle->location) }}"
                                   class="form-control @error('location') is-invalid @enderror"
                                   placeholder="Contoh: Bandara Radin Inten II, Kota Bandar Lampung">
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
                                   placeholder="Contoh: Toyota, Honda">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Model / Tipe</label>
                            <input type="text" name="model"
                                   value="{{ old('model', $vehicle->model) }}"
                                   class="form-control"
                                   placeholder="Contoh: Avanza G, Vario 150">
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
                                   placeholder="Contoh: Bensin, Diesel">
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
                                    Include driver / sopir
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
                                    Layanan antar-jemput
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi Kendaraan</label>
                            <textarea name="description" rows="3"
                                      class="form-control"
                                      placeholder="Ceritakan kondisi kendaraan, fasilitas, dan keunggulannya.">{{ old('description', $vehicle->description) }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Syarat & Ketentuan Sewa (Opsional)</label>
                            <textarea name="terms_conditions" rows="4"
                                      class="form-control"
                                      placeholder="Contoh: 
- Penyewa wajib memiliki SIM A aktif
- Denda keterlambatan Rp 50.000 / jam
- Pembatalan H-1 dikenakan biaya 50%">{{ old('terms_conditions', $vehicle->terms_conditions) }}</textarea>
                        </div>

                        {{-- GAMBAR --}}
                        <div class="col-12 pt-3">
                            <div class="section-title">Foto Kendaraan</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Upload Foto Kendaraan</label>
                            <input type="file" name="image" accept="image/*"
                                   class="form-control @error('image') is-invalid @enderror">
                            <div class="form-text">
                                Format JPG/PNG, maksimal 2 MB. Usahakan foto tampak depan / samping yang jelas.
                            </div>
                        </div>

                        {{-- SUBMIT --}}
                        <div class="col-12 mt-3">
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
@endsection
