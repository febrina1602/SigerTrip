{{-- Agent header + nav partial --}}
<header>
    <div class="container py-2 d-flex align-items-center justify-content-between">
        <a href="{{ route('agent.dashboard') }}" class="d-flex align-items-center text-decoration-none" style="min-width: 150px;">
            <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo"
                style="height:42px" loading="lazy" onerror="this.style.display='none'">
            <span class="ms-2 fw-bold text-dark d-none d-md-block">SigerTrip</span>
        </a>

        <div class="flex-grow-1 mx-3 mx-md-4">
            <h5 class="mb-0 text-dark">Dashboard Agent</h5>
        </div>

        <div class="d-flex align-items-center" style="min-width: 150px; justify-content: flex-end; gap: 1rem;">
            <a href="#" class="text-dark text-decoration-none" title="Notifikasi">
                <i class="fas fa-bell" style="font-size: 1.4rem; position: relative;">
                    <span class="badge bg-danger rounded-pill" style="position: absolute; top: -5px; right: -8px; font-size: 0.6rem; padding: 0.25rem 0.4rem;">3</span>
                </i>
            </a>

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
        </div>
    </div>
</header>

{{-- NAV TABS (same menus as user: Beranda, Pasar Digital, Pemandu Wisata) --}}
<nav class="nav-custom border-top bg-white sticky-top" style="z-index: 99;">
    <div class="container py-0">
        <div class="d-flex gap-4 justify-content-left">
            <a href="{{ route('agent.dashboard') }}"
               class="nav-link-custom {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home me-1"></i> Beranda
            </a>

            <a href="{{ route('agent.pasar-digital.index') }}"
               class="nav-link-custom {{ request()->routeIs('agent.pasar-digital.*') ? 'active' : '' }}">
                <i class="fas fa-store me-1"></i> Pasar Digital
            </a>

            <a href="{{ route('pemandu-wisata.index') }}"
               class="nav-link-custom {{ request()->routeIs('pemandu-wisata.*') ? 'active' : '' }}">
                <i class="fas fa-map-location-dot me-1"></i> Pemandu Wisata
            </a>
        </div>
    </div>
</nav>

