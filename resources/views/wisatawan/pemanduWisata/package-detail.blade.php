@extends('layouts.app')

@section('title', $tourPackage->name . ' - ' . $agent->name)

@section('content')
<div class="bg-white min-vh-100">
            @if(auth()->check() && auth()->user()->role == 'agent')
                @include('agent._header')
            @else
    {{-- HEADER --}}
    <header>
            <div class="container py-2 d-flex align-items-center justify-content-between">
                @php
                    $homeRoute = auth()->check() && auth()->user()->role == 'agent' ? route('agent.dashboard') : route('beranda.wisatawan');
                    $marketRoute = auth()->check() && auth()->user()->role == 'agent' ? route('agent.pasar-digital.index') : route('pasar-digital.index');
                @endphp

                <a href="{{ $homeRoute }}" class="d-flex align-items-center text-decoration-none" style="min-width: 150px;">
                <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo"
                    style="height:42px" loading="lazy" onerror="this.style.display='none'">
                <span class="ms-2 fw-bold text-dark d-none d-md-block">SigerTrip</span>
            </a>

            <form class="flex-grow-1 mx-3 mx-md-4" action="#" method="GET">
                <div class="position-relative" style="max-width: 600px; margin: 0 auto;">
                    <input type="text" class="form-control" name="search"
                        placeholder="Wisata apa yang kamu cari?"
                        style="border-radius: 50px; padding-left: 2.5rem; height: 44px;">
                    <button type="submit" class="btn p-0" 
                    style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 1.1rem;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <div class="d-flex align-items-center" style="min-width: 150px; justify-content: flex-end;">
                
                @guest
                    <a href="{{ route('login') }}" class="text-dark text-decoration-none d-flex flex-column align-items-center">
                        <i class="fas fa-user-circle" style="font-size: 1.75rem;"></i>
                        <span class="small fw-medium">Akun</span>
                    </a>
                @endguest
                
                @auth
                    @php
                        $profileRoute = auth()->user()->role == 'agent' 
                                      ? route('agent.dashboard') 
                                      : route('profile.show');
                    @endphp
                    <a href="{{ $profileRoute }}" class="text-dark text-decoration-none d-flex flex-column align-items-center me-3">
                        <img src="{{ auth()->user()->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->full_name) . '&background=FFD15C&color=333&bold=true' }}" 
                             alt="Foto Profil" 
                             style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #eee;">
                        <span class="small fw-medium">
                            {{ \Illuminate\Support\Str::limit(auth()->user()->full_name ?? auth()->user()->name, 15) }}
                        </span>
                    </a>
                    
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-link text-danger p-0" title="Logout" 
                                style="font-size: 1.6rem; line-height: 1;">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                @endauth
            </div>
        </div>
    </header>

    {{-- NAV --}}
    <nav class="nav-custom border-top bg-white">
        <div class="container py-0">
            <div class="d-flex gap-4 justify-content-left">
                <a href="{{ $homeRoute }}"
                   class="nav-link-custom {{ (auth()->check() && auth()->user()->role == 'agent') ? (request()->routeIs('agent.dashboard') ? 'active' : '') : (request()->routeIs('beranda.*') ? 'active' : '') }}">
                    Beranda
                </a>
                <a href="{{ $marketRoute }}" class="nav-link-custom {{ (auth()->check() && auth()->user()->role == 'agent') ? (request()->routeIs('agent.pasar-digital.*') ? 'active' : '') : (request()->routeIs('pasar-digital.*') ? 'active' : '') }}">
                    Pasar Digital
                </a>
                <a href="{{ route('pemandu-wisata.index') }}" class="nav-link-custom {{ request()->routeIs('pemandu-wisata.*')}} ">
                    Pemandu Wisata
                </a>
            </div>
        </div>
    </nav>
    @endif

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