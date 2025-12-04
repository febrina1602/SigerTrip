@extends('layouts.app')

@section('title', $tourPackage->name . ' - ' . $agent->name)

@section('content')
<div class="bg-white min-vh-100">
    @include('components.layout.header')

    <div class="bg-light py-3 border-bottom">
        <div class="container">
            <nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: '>';">
                <ol class="breadcrumb mb-0 small">
                    <li class="breadcrumb-item">
                        <a href="{{ route('pemandu-wisata.index') }}" class="text-decoration-none">Pemandu Wisata</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('pemandu-wisata.show', $agent->id) }}" class="text-decoration-none">{{ $agent->name }}</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('pemandu-wisata.packages', $agent->id) }}" class="text-decoration-none">Paket Perjalanan</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $tourPackage->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div>
        <img src="{{ $tourPackage->cover_image_url ?? 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=1200&q=80' }}" 
             alt="{{ $tourPackage->name }}" 
             class="w-100" style="height: 450px; object-fit: cover;">
    </div>
    
    <div class="container py-5">
        <div class="row g-5">
            
            <div class="col-lg-8">
                <h1 class="h3 fw-bold text-dark mb-4">{{ $tourPackage->name }}</h1>
                
                @php
                    $destinasiDikunjungi = $tourPackage->destinations();
                @endphp
                @if(isset($isOwner) && $isOwner)
                    <div class="mb-3 text-end">
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editPackageModal">
                            <i class="fas fa-pen"></i> Edit Paket
                        </button>
                    </div>
                @endif
                @if($destinasiDikunjungi->isNotEmpty())
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Destinasi yang Dikunjungi:</h5>
                    @if(isset($isOwner) && $isOwner)
                        <a href="#" class="ms-2 small text-primary"> <i class="fas fa-pen"></i> Edit</a>
                    @endif
                    <div class="row row-cols-2 g-3">
                        @foreach($destinasiDikunjungi as $dest)
                        <div class="col">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-star text-warning"></i>
                                <span class="text-muted">{{ $dest->name }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @php
                    $fasilitas = $tourPackage->facilities_array;
                @endphp
                @if(!empty($fasilitas))
                <div class="mb-4">
                    <h5 class="fw-bold mb-3">Fasilitas:</h5>
                    @if(isset($isOwner) && $isOwner)
                        <a href="#" class="ms-2 small text-primary"> <i class="fas fa-pen"></i> Edit</a>
                    @endif
                    <div class="row row-cols-2 g-3">
                        @foreach($fasilitas as $item)
                        <div class="col">
                            <div class="d-flex align-items-center gap-2">
                                <i class="fas fa-star text-warning"></i>
                                <span class="text-muted">{{ $item }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if($tourPackage->description)
                <div class="mt-5">
                    <h5 class="fw-bold mb-3">Deskripsi Paket</h5>
                    @if(isset($isOwner) && $isOwner)
                        <a href="#" class="ms-2 small text-primary"> <i class="fas fa-pen"></i> Edit</a>
                    @endif
                    <p class="text-secondary" style="line-height: 1.7;">
                        {!! nl2br(e($tourPackage->description)) !!}
                    </p>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-light" style="position: sticky; top: 2rem;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3">Informasi Paket</h5>
                        
                        @if($tourPackage->duration)
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <i class="fas fa-clock text-muted fa-fw mt-1"></i>
                            <div>
                                <h6 class="fw-semibold text-dark mb-0">Durasi:</h6>
                                <span class="text-muted">{{ $tourPackage->duration }}</span>
                            </div>
                        </div>
                        @endif

                        @if($tourPackage->price_per_person > 0)
                        <div class="d-flex align-items-start gap-3 mb-3">
                            <i class="fas fa-tag text-muted fa-fw mt-1"></i>
                            <div>
                                <h6 class="fw-semibold text-dark mb-0">Harga Mulai:</h6>
                                <span class="text-muted">
                                    Rp{{ number_format($tourPackage->price_per_person, 0, ',', '.') }}
                                    @if($tourPackage->minimum_participants)
                                        (min. {{ $tourPackage->minimum_participants }} orang)
                                    @endif
                                </span>
                            </div>
                        </div>
                        @endif
                        
                        <hr class="my-3">
                        
                        <p class="small text-muted text-center">
                            Klik tombol "Hubungi Kami" untuk melakukan pemesanan pada agensi kami.
                        </p>
                        
                        <div class="d-grid">
                            @php
                                $kontak = $agent->phone_number ?? $agent->contact_phone;
                                $pesan = urlencode("Halo, saya tertarik untuk memesan paket tur " . $tourPackage->name . " dari SigerTrip.");
                                $waLink = $kontak ? 'https://api.whatsapp.com/send?phone=' . preg_replace('/[^0-9]/', '', $kontak) . '&text=' . $pesan : '#';
                            @endphp
                            <a href="{{ $waLink }}" target="_blank" class="btn btn-lg fw-semibold text-dark" style="background-color: #FFD15C;">
                                <i class="fab fa-whatsapp me-2"></i> Hubungi Kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
    @if(isset($isOwner) && $isOwner)
        <!-- Edit Package Modal -->
        <div class="modal fade" id="editPackageModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0 rounded-4">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Edit Paket: {{ $tourPackage->name }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="editPackageForm">
                        <div class="modal-body">
                            <div id="editPackageAlert" class="alert d-none"></div>
                            <div class="mb-3">
                                <label class="form-label">Harga per orang</label>
                                <input type="number" step="0.01" name="price_per_person" class="form-control" value="{{ $tourPackage->price_per_person }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Durasi</label>
                                <input type="text" name="duration" class="form-control" value="{{ $tourPackage->duration }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Fasilitas (koma dipisah)</label>
                                <input type="text" name="facilities" class="form-control" value="{{ is_array($tourPackage->facilities) ? implode(',', $tourPackage->facilities) : $tourPackage->facilities }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="6">{{ $tourPackage->description }}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary rounded-3">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.getElementById('editPackageForm');
                const alertBox = document.getElementById('editPackageAlert');
                form.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    alertBox.classList.add('d-none');

                    const data = new FormData(form);
                    try {
                        const res = await fetch("{{ route('agent.tour_packages.update', $tourPackage->id) }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: data
                        });
                        const json = await res.json();
                        if (!res.ok) throw json;
                        location.reload();
                    } catch (err) {
                        alertBox.classList.remove('d-none');
                        alertBox.classList.add('alert-danger');
                        alertBox.innerText = (err && err.message) ? err.message : 'Gagal menyimpan perubahan.';
                    }
                });
            });
        </script>
    @endif
</div>
@endsection