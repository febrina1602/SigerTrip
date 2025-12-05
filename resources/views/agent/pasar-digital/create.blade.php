{{-- resources/views/agent/pasar-digital/create.blade.php --}}
@extends('layouts.app')

@section('title', 'Daftarkan Kendaraan – Pasar Digital Mitra')

@push('styles')
<style>
  .card-form {
      border-radius: 18px;
      border: none;
      box-shadow: 0 10px 30px rgba(0,0,0,0.06);
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
    <div class="row g-4">

        {{-- Tombol Kembali --}}
        <div class="col-12">
            <a href="{{ route('agent.pasar.index') }}" class="btn btn-back mb-3">
                <i class="fas fa-arrow-left me-2"></i> Kembali
            </a>
        </div>

        {{-- FORM DAFTAR KENDARAAN --}}
        <div class="col-lg-8">
            <div class="card card-form">
                <div class="card-body p-4 p-md-5">

                    <h3 class="fw-bold mb-1">Form Pendaftaran Kendaraan</h3>
                    <p class="text-muted mb-4">
                        Lengkapi informasi di bawah ini dengan data yang sebenarnya agar wisatawan merasa aman dan nyaman.
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

                    <form action="{{ route('agent.pasar.store') }}"
                          method="POST"
                          enctype="multipart/form-data"
                          class="row g-3">
                        @csrf

                        {{-- ===== INFORMASI UTAMA ===== --}}
                        <div class="col-12">
                            <div class="section-title">Informasi Utama</div>
                        </div>

                        <div class="col-md-8">
                            <label class="form-label required">Nama Kendaraan (Nama Paket)</label>
                            <input type="text" name="name"
                                   value="{{ old('name') }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Contoh: Avanza G 2019 – Include Driver">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label required">Jenis Kendaraan</label>
                            <select name="vehicle_type"
                                    class="form-select @error('vehicle_type') is-invalid @enderror">
                                <option value="">Pilih jenis</option>
                                <option value="CAR" {{ old('vehicle_type') === 'CAR' ? 'selected' : '' }}>Mobil</option>
                                <option value="MOTORCYCLE" {{ old('vehicle_type') === 'MOTORCYCLE' ? 'selected' : '' }}>Motor</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Harga Sewa per Hari (Rp)</label>
                            <input type="number" name="price_per_day" min="0" step="1000"
                                   value="{{ old('price_per_day') }}"
                                   class="form-control @error('price_per_day') is-invalid @enderror"
                                   placeholder="Contoh: 350000">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label required">Lokasi Penjemputan</label>
                            <input type="text" name="location"
                                   value="{{ old('location') }}"
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
                                   value="{{ old('brand') }}"
                                   class="form-control"
                                   placeholder="Contoh: Toyota, Honda">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Model / Tipe</label>
                            <input type="text" name="model"
                                   value="{{ old('model') }}"
                                   class="form-control"
                                   placeholder="Contoh: Avanza G, Vario 150">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Tahun</label>
                            <input type="number" name="year"
                                   value="{{ old('year') }}"
                                   class="form-control"
                                   min="1990" max="{{ date('Y') + 1 }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Transmisi</label>
                            <select name="transmission" class="form-select">
                                <option value="">Pilih</option>
                                <option value="Manual" {{ old('transmission') === 'Manual' ? 'selected' : '' }}>Manual</option>
                                <option value="Automatic" {{ old('transmission') === 'Automatic' ? 'selected' : '' }}>Automatic</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kapasitas Kursi</label>
                            <input type="number" name="seats"
                                   value="{{ old('seats') }}"
                                   class="form-control"
                                   min="1" max="20">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Jenis Bahan Bakar</label>
                            <input type="text" name="fuel_type"
                                   value="{{ old('fuel_type') }}"
                                   class="form-control"
                                   placeholder="Contoh: Bensin, Diesel">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nomor Polisi (Opsional)</label>
                            <input type="text" name="plate_number"
                                   value="{{ old('plate_number') }}"
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
                                   value="{{ old('min_rental_days', 1) }}"
                                   class="form-control"
                                   min="1" max="30">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label d-block">Layanan Termasuk</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox"
                                       id="include_driver" name="include_driver"
                                       value="1" {{ old('include_driver') ? 'checked' : '' }}>
                                <label class="form-check-label" for="include_driver">
                                    Include driver / sopir
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox"
                                       id="include_fuel" name="include_fuel"
                                       value="1" {{ old('include_fuel') ? 'checked' : '' }}>
                                <label class="form-check-label" for="include_fuel">
                                    Termasuk BBM
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox"
                                       id="include_pickup_drop" name="include_pickup_drop"
                                       value="1" {{ old('include_pickup_drop') ? 'checked' : '' }}>
                                <label class="form-check-label" for="include_pickup_drop">
                                    Layanan antar-jemput
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Deskripsi Kendaraan</label>
                            <textarea name="description" rows="3"
                                      class="form-control"
                                      placeholder="Ceritakan kondisi kendaraan, fasilitas, dan keunggulannya.">{{ old('description') }}</textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Syarat & Ketentuan Sewa (Opsional)</label>
                            <textarea name="terms_conditions" rows="4"
                                      class="form-control"
                                      placeholder="Contoh: 
- Penyewa wajib memiliki SIM A aktif
- Denda keterlambatan Rp 50.000 / jam
- Pembatalan H-1 dikenakan biaya 50%">{{ old('terms_conditions') }}</textarea>
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
                                Simpan &amp; Daftarkan Kendaraan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- PANEL SYARAT PENDAFTARAN --}}
        <div class="col-lg-4">
            <div class="card card-form mb-3">
                <div class="card-body">
                    <h5 class="fw-bold mb-2">Syarat Pendaftaran Kendaraan</h5>
                    <p class="text-muted small mb-2">Pastikan data yang Anda masukkan sudah sesuai:</p>
                    <ul class="small mb-0">
                        <li>Kendaraan dalam kondisi layak jalan &amp; terawat.</li>
                        <li>STNK &amp; pajak kendaraan masih berlaku.</li>
                        <li>Foto kendaraan jelas dan tidak blur.</li>
                        <li>Informasi harga sudah termasuk / belum termasuk layanan tertentu dijelaskan dengan jelas.</li>
                        <li>Nomor kontak pada profil mitra aktif &amp; dapat dihubungi.</li>
                    </ul>
                </div>
            </div>

            <div class="card card-form">
                <div class="card-body">
                    <h6 class="fw-semibold mb-1">Tips agar mudah dipilih wisatawan</h6>
                    <ul class="small mb-0">
                        <li>Gunakan foto kendaraan dengan pencahayaan yang baik.</li>
                        <li>Berikan deskripsi jujur tentang kondisi kendaraan.</li>
                        <li>Tambahkan fasilitas seperti WiFi, charger, atau free air mineral.</li>
                        <li>Respon cepat chat calon penyewa.</li>
                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
