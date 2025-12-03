@php
    $isAgent = auth()->check() && auth()->user()->role == 'agent';
@endphp

<div>
    {{-- HEADER (Universal - sama untuk semua role) --}}
    <div class="app-header" style="background: linear-gradient(90deg, #D19878, #FFE75D); padding: 15px 40px; display: flex; justify-content: space-between; align-items: center;">
        <div class="container py-2 d-flex align-items-center justify-content-between" style="width: 100%; padding: 0;">
            {{-- Logo --}}
            <a href="{{ route('beranda.wisatawan') }}" class="d-flex align-items-center text-decoration-none" style="min-width: 150px;">
                <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo"
                    style="height:42px" loading="lazy" onerror="this.style.display='none'">
                <span class="ms-2 fw-bold text-dark d-none d-md-block">SigerTrip</span>
            </a>

            {{-- Searchbar (universal) --}}
            <form class="flex-grow-1 mx-3 mx-md-4" action="{{ route('pemandu-wisata.index') }}" method="GET">
                <div class="position-relative" style="max-width: 600px; margin: 0 auto;">
                    <input type="text" class="form-control" name="q"
                        placeholder="Cari agen..."
                        value="{{ request('q', '') }}"
                        style="border-radius: 50px; padding-left: 2.5rem; height: 44px;">
                    <button type="submit" class="btn p-0" 
                    style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 1.1rem;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            {{-- Right side: Bell + Dropdown Profile (universal) --}}
            <div class="d-flex align-items-center" style="min-width: 150px; justify-content: flex-end; gap: 1rem;">
                @guest
                    {{-- Guest: Login link --}}
                    <a href="{{ route('login') }}" class="text-dark text-decoration-none d-flex flex-column align-items-center">
                        <i class="fas fa-user-circle" style="font-size: 1.75rem;"></i>
                        <span class="small fw-medium">Akun</span>
                    </a>
                @endguest

                @auth
                    {{-- Notification Bell --}}
                    <a href="#" class="text-dark text-decoration-none" title="Notifikasi">
                        <i class="fas fa-bell" style="font-size: 1.4rem; position: relative;">
                            <span class="badge bg-danger rounded-pill" style="position: absolute; top: -5px; right: -8px; font-size: 0.6rem; padding: 0.25rem 0.4rem;">3</span>
                        </i>
                    </a>

                    {{-- Profile Dropdown --}}
                    <div class="dropdown">
                        <button class="btn btn-link text-dark text-decoration-none p-0 dropdown-toggle" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ auth()->user()->profile_picture_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->full_name) . '&background=FFD15C&color=333&bold=true' }}" 
                                 alt="Foto Profil" 
                                 style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #ddd;">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="fas fa-user me-2"></i> Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    {{-- NAVIGATION BAR (Conditional per role) --}}
    <nav class="app-navbar border-top bg-white sticky-top" style="z-index: 99;">
        <div class="container py-0">
            <div class="d-flex gap-4 justify-content-left">
                @if($isAgent)
                    {{-- Agent Navigation --}}
                    <a href="{{ route('agent.dashboard') }}"
                       class="app-nav-link {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home me-1"></i> Beranda
                    </a>

                    <a href="{{ route('agent.pasar-digital.index') }}"
                       class="app-nav-link {{ request()->routeIs('agent.pasar-digital.*') ? 'active' : '' }}">
                        <i class="fas fa-store me-1"></i> Pasar Digital
                    </a>

                    <a href="{{ route('pemandu-wisata.index') }}"
                       class="app-nav-link {{ request()->routeIs('pemandu-wisata.*') ? 'active' : '' }}">
                        <i class="fas fa-map-location-dot me-1"></i> Pemandu Wisata
                    </a>
                @else
                    {{-- User/Guest Navigation --}}
                    <a href="{{ route('beranda.wisatawan') }}"
                       class="app-nav-link {{ request()->routeIs('beranda.*') ? 'active' : '' }}">
                        <i class="fas fa-home me-1"></i> Beranda
                    </a>

                    <a href="{{ route('pasar-digital.index') }}" 
                       class="app-nav-link {{ request()->routeIs('pasar-digital.*') ? 'active' : '' }}">
                        <i class="fas fa-store me-1"></i> Pasar Digital
                    </a>

                    <a href="{{ route('pemandu-wisata.index') }}" 
                       class="app-nav-link {{ request()->routeIs('pemandu-wisata.*') ? 'active' : '' }}">
                        <i class="fas fa-map-location-dot me-1"></i> Pemandu Wisata
                    </a>
                @endif
            </div>
        </div>
    </nav>
</div>
