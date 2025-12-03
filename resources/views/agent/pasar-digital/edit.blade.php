@extends('layouts.app')

@section('title', 'Edit Kendaraan - SigerTrip')

@section('content')
<div class="container py-4">

    <h2 class="fw-bold mb-3">Edit Kendaraan</h2>

    {{-- Error Validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Update --}}
    <form action="{{ route('agent.pasar.update', $vehicle->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Nama Kendaraan --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Nama Kendaraan</label>
            <input type="text" name="name" class="form-control" 
                   value="{{ old('name', $vehicle->name) }}" required>
        </div>

        {{-- Tipe Kendaraan --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Jenis Kendaraan</label>
            <select name="type" class="form-select" required>
                <option value="CAR" {{ $vehicle->type=='CAR' ? 'selected' : '' }}>Mobil</option>
                <option value="MOTOR" {{ $vehicle->type=='MOTOR' ? 'selected' : '' }}>Motor</option>
            </select>
        </div>

        {{-- Harga Sewa --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Harga Sewa / Hari</label>
            <input type="number" name="price" class="form-control"
                   value="{{ old('price', $vehicle->price) }}" required>
        </div>

        {{-- Deskripsi --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Deskripsi Kendaraan</label>
            <textarea name="description" rows="4" class="form-control" required>{{ old('description', $vehicle->description) }}</textarea>
        </div>

        {{-- Gambar --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">Foto Kendaraan</label>
            <div class="mb-2">
                @if($vehicle->image_url)
                    <img src="{{ asset('storage/' . $vehicle->image_url) }}" alt="Foto kendaraan"
                         style="width:180px; border-radius:10px;">
                @endif
            </div>

            <input type="file" name="image" class="form-control">
            <small class="text-muted">Biarkan kosong jika tidak ingin mengganti gambar.</small>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="{{ route('agent.pasar.index') }}" class="btn btn-secondary">Kembali</a>

            <button type="submit" class="btn btn-primary px-4">Update Kendaraan</button>
        </div>

    </form>

</div>
@endsection
