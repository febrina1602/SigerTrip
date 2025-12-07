@extends('layouts.app')

@section('title', 'Edit Paket')

@section('content')
@include('components.layout.header')

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Edit Paket Perjalanan</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('agent.tour-packages.update', $package->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold small">Nama Paket</label>
                            <input type="text" name="name" class="form-control" value="{{ $package->name }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Harga per Orang (Rp)</label>
                                <input type="number" name="price_per_person" class="form-control" value="{{ $package->price_per_person }}" required min="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">Durasi</label>
                                <input type="text" name="duration" class="form-control" value="{{ $package->duration }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Fasilitas</label>
                            <textarea name="facilities" class="form-control" rows="3">{{ is_array($package->facilities) ? implode(', ', $package->facilities) : $package->facilities }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Deskripsi Lengkap</label>
                            <textarea name="description" class="form-control" rows="5">{{ $package->description }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small">Foto Cover</label>
                            @if($package->cover_image_url)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$package->cover_image_url) }}" alt="Current" class="img-thumbnail" style="height: 100px;">
                                </div>
                            @endif
                            <input type="file" name="cover_image_file" class="form-control" accept="image/*">
                            <div class="form-text">Biarkan kosong jika tidak ingin mengubah foto.</div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('agent.tour-packages.index') }}" class="btn btn-light">Batal</a>
                            <button type="submit" class="btn btn-primary px-4">Update Paket</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection