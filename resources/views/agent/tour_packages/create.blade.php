@extends('layouts.app')

@section('title', 'Buat Paket Baru')

@section('content')
@include('components.layout.header')

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Buat Paket Perjalanan Baru</h5>
                </div>
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('agent.tour-packages.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Pilih Cabang --}}
                        @php
                            $user = auth()->user();
                            $localAgents = $user->agent ? $user->agent->localTourAgents : collect([]);
                        @endphp

                        @if($localAgents->count() > 0)
                            <div class="mb-3">
                                <label class="form-label fw-bold small">Pilih Cabang (Opsional)</label>
                                <select name="local_tour_agent_id" class="form-select">
                                    <option value="">-- Pusat (Tanpa Cabang) --</option>
                                    @foreach($localAgents as $localAgent)
                                        <option value="{{ $localAgent->id }}" {{ old('local_tour_agent_id') == $localAgent->id ? 'selected' : '' }}>
                                            {{ $localAgent->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        {{-- Informasi Utama --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nama Paket <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required placeholder="Contoh: Eksplorasi Pahawang 2H1M" value="{{ old('name') }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Harga per Orang (Rp) <span class="text-danger">*</span></label>
                                <input type="number" name="price_per_person" class="form-control" required min="0" value="{{ old('price_per_person') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Durasi (Teks)</label>
                                <input type="text" name="duration" class="form-control" placeholder="Contoh: 3 Hari 2 Malam" value="{{ old('duration') }}">
                            </div>
                        </div>

                        {{-- Fasilitas & Destinasi --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Destinasi yang Dikunjungi</label>
                            <textarea name="destinations_visited" class="form-control" rows="2" placeholder="Pulau Kelagian, Pasir Timbul, ...">{{ old('destinations_visited') }}</textarea>
                            <div class="form-text">Pisahkan dengan koma.</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Fasilitas</label>
                            <textarea name="facilities" class="form-control" rows="3" placeholder="Makan siang, Tiket masuk, Transportasi...">{{ old('facilities') }}</textarea>
                            <div class="form-text">Pisahkan dengan koma atau baris baru.</div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Deskripsi Lengkap</label>
                            <textarea name="description" class="form-control" rows="5">{{ old('description') }}</textarea>
                        </div>

                        {{-- Foto & Status --}}
                        <div class="mb-4">
                            <label class="form-label fw-bold small">Foto Cover</label>
                            <input type="file" name="cover_image_file" class="form-control" accept="image/*">
                        </div>

                        <div class="mb-4 form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_published" name="is_published" value="1" checked>
                            <label class="form-check-label fw-bold small" for="is_published">Publikasikan Paket Ini?</label>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('agent.tour-packages.index') }}" class="btn btn-light">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">Simpan Paket</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection