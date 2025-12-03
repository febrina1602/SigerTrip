@extends('layouts.app')

@section('title', 'Pemandu Wisata - SigerTrip')

@section('content')
    <div class="bg-white">
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
                    <a href="{{ route('pemandu-wisata.index') }}" class="nav-link-custom {{ request()->routeIs('pemandu-wisata.*') ? 'active' : '' }} ">
                        Pemandu Wisata
                    </a>
                </div>
            </div>
        </nav>

        <div class="py-5 bg-white min-vh-100">
            <div class="container">
                
                <h1 class="h3 fw-bold text-dark mb-4 text-start">Agen Tour Lokal</h1>
                
                <div class="row row-cols-1 row-cols-md-3 g-4">

                    {{-- Agent-mode: show LocalTourAgents owned by authenticated agent --}}
                    <div class="bg-white">
                        @if(auth()->check() && auth()->user()->role == 'agent')
                            @include('agent._header')
                        @else
                        {{-- HEADER --}}
                            <header>
                                <div class="container py-2 d-flex align-items-center justify-content-between">
                    
                                    <a href="{{ route('beranda.wisatawan') }}" class="d-flex align-items-center text-decoration-none" style="min-width: 150px;">
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

                            <nav class="nav-custom border-top bg-white">
                                <div class="container py-0">
                                    <div class="d-flex gap-4 justify-content-left">
                                        <a href="{{ route('beranda.wisatawan') }}"
                                        class="nav-link-custom {{ request()->routeIs('beranda.wisatawan') ? 'active' : '' }}">
                                            Beranda
                                        </a>
                                        <a href="{{ route('pasar-digital.index') }}" class="nav-link-custom {{ request()->routeIs('pasar-digital.*') ? 'active' : '' }}">
                                            Pasar Digital
                                        </a>
                                        <a href="{{ route('pemandu-wisata.index') }}" class="nav-link-custom {{ request()->routeIs('pemandu-wisata.*') ? 'active' : '' }} ">
                                            Pemandu Wisata
                                        </a>
                                    </div>
                                </div>
                            </nav>
                        @endif
                                <p class="fs-5 mb-2">Belum ada agen tour lokal tersedia</p>
                                <p class="small">Agen tour akan muncul di sini setelah terdaftar dan diverifikasi oleh admin</p>
                            </div>
                        @endif

                    @endif

                    
                </div>
            </div>
        </div>

    </div>


{{-- Modal for adding LocalTourAgent (used by agents) --}}
@auth
    @if(auth()->user()->role === 'agent')
        <div class="modal fade" id="addLocalTourAgentModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content border-0 rounded-4">
                    <div class="modal-header border-0 pb-0">
                        <h5 class="modal-title fw-bold">Tambah Agen Tour Lokal Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form id="addLocalTourAgentForm">
                        <div class="modal-body">
                            <div id="addLocalTourAgentAlert" class="alert d-none"></div>
                            <div class="mb-3">
                                <label class="form-label">Nama Agen</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <input type="text" name="address" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kontak Telepon</label>
                                <input type="text" name="contact_phone" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi singkat</label>
                                <textarea name="description" class="form-control" rows="4"></textarea>
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
                const form = document.getElementById('addLocalTourAgentForm');
                const alertBox = document.getElementById('addLocalTourAgentAlert');

                form.addEventListener('submit', async function (e) {
                    e.preventDefault();
                    alertBox.classList.add('d-none');

                    const data = new FormData(form);

                    try {
                        const res = await fetch("{{ route('agent.local_tour_agents.store') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: data
                        });

                        const json = await res.json();
                        if (!res.ok) throw json;

                        // Close modal
                        const modalEl = document.getElementById('addLocalTourAgentModal');
                        const modal = bootstrap.Modal.getInstance(modalEl);
                        modal.hide();

                        // Option: reload to show new item, but insert card dynamically for UX
                        location.reload();

                    } catch (err) {
                        alertBox.classList.remove('d-none');
                        alertBox.classList.add('alert-danger');
                        alertBox.innerText = (err && err.message) ? err.message : 'Gagal menyimpan. Periksa input Anda.';
                    }
                });
            });
        </script>
    @endif
@endauth

@endsection