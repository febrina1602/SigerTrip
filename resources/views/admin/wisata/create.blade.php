@extends('layouts.app')

@section('title', 'Tambah Destinasi Wisata')

@section('content')
<div class="min-vh-100 bg-white">

    {{-- HEADER STANDAR ADMIN --}}
    <header class="border-bottom bg-white shadow-sm">
        <div class="container py-2 d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.beranda') }}" class="d-flex align-items-center text-decoration-none" style="min-width: 150px;">
                <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo" style="height:42px" loading="lazy">
                <span class="ms-2 fw-bold text-dark d-none d-md-block">SigerTrip</span>
            </a>

            <div class="d-flex align-items-center gap-4" style="min-width: 150px; justify-content: flex-end;">
                <div class="text-center">
                    <i class="fas fa-user-circle text-dark" style="font-size: 1.8rem;"></i>
                    <div class="small fw-medium mt-1 text-dark">
                        {{ Auth::user()->full_name ?? Auth::user()->name ?? 'Admin' }}
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger p-0" style="font-size: 1.6rem; line-height: 1;" title="Keluar">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- NAVIGATION BAR (Sesuai dengan web.php baru) --}}
    <nav class="nav-custom bg-light py-2 border-bottom">
        <div class="container d-flex gap-4">
            <a href="{{ route('admin.beranda') }}" class="nav-link-custom">Beranda</a>
            <a href="{{route('admin.profil-agent.index')}}" class="nav-link-custom">Profil Agent</a>
            <a href="{{ route('admin.pasar.index') }}" class="nav-link-custom">Pasar Digital</a>
            <a href="#" class="nav-link-custom">Pemandu Wisata</a>
            <a href="{{ route('admin.users.index') }}" class="nav-link-custom">Kelola User</a>
        </div>
    </nav>

    {{-- KONTEN UTAMA --}}
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0">Tambah Destinasi Wisata</h4>
                    <a href="{{ route('admin.beranda') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                {{-- Alert Error Global --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <strong>Terjadi Kesalahan!</strong> Silakan periksa inputan Anda.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                {{-- Form Tambah Destinasi --}}
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        <form action="{{ route('admin.wisata.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            {{-- Nama --}}
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Nama Destinasi <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Lokasi dengan Autocomplete --}}
                            <div class="mb-3">
                                <label for="location_input" class="form-label fw-semibold">Cari Lokasi (Autocomplete)</label>
                                <div class="position-relative">
                                    <input type="text" class="form-control" id="location_input" 
                                           placeholder="Ketik nama tempat (misal: Pantai Sanur, Bali)" 
                                           autocomplete="off">
                                    <div id="autocomplete_list" class="position-absolute w-100 bg-white border rounded-2 mt-1 shadow" 
                                         style="display: none; max-height: 300px; overflow-y: auto; z-index: 1050;">
                                    </div>
                                </div>
                                <small class="text-muted">Ketik untuk mencari, lalu klik hasil untuk mengisi koordinat otomatis.</small>
                            </div>

                            {{-- Map --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Pilih Titik di Peta</label>
                                <div class="d-flex gap-2 mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="zoomIn"><i class="fas fa-plus"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="zoomOut"><i class="fas fa-minus"></i></button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="resetMap"><i class="fas fa-sync-alt"></i> Reset</button>
                                </div>
                                <div id="map" style="height: 400px; border-radius: 8px; border: 1px solid #dee2e6;"></div>
                                <small class="text-muted d-block mt-1">Klik pada peta untuk menyesuaikan titik lokasi secara manual.</small>
                            </div>

                            <div class="row">
                                {{-- Latitude --}}
                                <div class="col-md-6 mb-3">
                                    <label for="latitude" class="form-label fw-semibold">Latitude <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('latitude') is-invalid @enderror" 
                                           id="latitude" name="latitude" value="{{ old('latitude') }}" 
                                           placeholder="-5.xxxx" required readonly>
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- Longitude --}}
                                <div class="col-md-6 mb-3">
                                    <label for="longitude" class="form-label fw-semibold">Longitude <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('longitude') is-invalid @enderror" 
                                           id="longitude" name="longitude" value="{{ old('longitude') }}" 
                                           placeholder="105.xxxx" required readonly>
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Alamat --}}
                            <div class="mb-3">
                                <label for="address" class="form-label fw-semibold">Alamat Lengkap <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="2" required 
                                          placeholder="Alamat akan terisi otomatis atau ketik manual">{{ old('address') }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Kategori --}}
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div class="mb-3">
                                <label for="description" class="form-label fw-semibold">Deskripsi <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Fasilitas --}}
                            <div class="mb-3">
                                <label for="facilities" class="form-label fw-semibold">Fasilitas</label>
                                <textarea class="form-control @error('facilities') is-invalid @enderror" 
                                          id="facilities" name="facilities" rows="3" 
                                          placeholder="Contoh: Toilet, Parkir Luas, Musholla">{{ old('facilities') }}</textarea>
                                @error('facilities')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                {{-- Harga Tiket --}}
                                <div class="col-md-6 mb-3">
                                    <label for="price_per_person" class="form-label fw-semibold">Harga Tiket per Orang (Rp) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('price_per_person') is-invalid @enderror" 
                                           id="price_per_person" name="price_per_person" min="0" 
                                           value="{{ old('price_per_person') }}" required>
                                    @error('price_per_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                {{-- Harga Parkir --}}
                                <div class="col-md-6 mb-3">
                                    <label for="parking_price" class="form-label fw-semibold">Harga Parkir (Rp)</label>
                                    <input type="number" class="form-control @error('parking_price') is-invalid @enderror" 
                                           id="parking_price" name="parking_price" min="0" 
                                           value="{{ old('parking_price', 0) }}">
                                    @error('parking_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- Aktivitas Populer --}}
                            <div class="mb-3">
                                <label for="popular_activities" class="form-label fw-semibold">Aktivitas Populer</label>
                                <textarea class="form-control @error('popular_activities') is-invalid @enderror" 
                                          id="popular_activities" name="popular_activities" rows="2"
                                          placeholder="Contoh: Snorkeling, Foto Sunset">{{ old('popular_activities') }}</textarea>
                            </div>

                            <div class="row">
                                {{-- Rating Awal --}}
                                <div class="col-md-6 mb-3">
                                    <label for="rating" class="form-label fw-semibold">Rating Awal (0 - 5)</label>
                                    <input type="number" step="0.1" class="form-control @error('rating') is-invalid @enderror" 
                                           id="rating" name="rating" min="0" max="5" 
                                           value="{{ old('rating', 0) }}">
                                </div>
                                {{-- Featured Checkbox --}}
                                <div class="col-md-6 mb-3 d-flex align-items-center">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" 
                                            {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-semibold" for="is_featured">
                                            Tandai sebagai Destinasi Unggulan
                                        </label>
                                    </div>
                                </div>
                            </div>

                            {{-- Upload Foto --}}
                            <div class="mb-4">
                                <label for="image" class="form-label fw-semibold">Upload Foto Utama <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*" required>
                                <small class="text-muted">Format: JPG, PNG (Max 2MB)</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Tombol Aksi --}}
                            <div class="d-flex gap-2 pt-3 border-top">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save me-2"></i> Simpan Destinasi
                                </button>
                                <a href="{{ route('admin.beranda') }}" class="btn btn-secondary px-4">Batal</a>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

<script>
    const locationInput = document.getElementById('location_input');
    const autocompleteList = document.getElementById('autocomplete_list');
    const addressField = document.getElementById('address');
    const latitudeField = document.getElementById('latitude');
    const longitudeField = document.getElementById('longitude');
    
    let debounceTimer;
    let map;
    let marker;

    // Koordinat Default (Lampung) atau dari old input jika ada error
    const defaultLat = {{ old('latitude') ?? -4.68 }};
    const defaultLng = {{ old('longitude') ?? 104.55 }};
    const defaultZoom = {{ old('latitude') ? 15 : 9 }};

    function initMap() {
        map = L.map('map').setView([defaultLat, defaultLng], defaultZoom);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Jika ada old value, pasang marker
        @if(old('latitude') && old('longitude'))
            updateMapMarker(defaultLat, defaultLng);
        @endif

        // Zoom controls
        document.getElementById('zoomIn').onclick = () => map.zoomIn();
        document.getElementById('zoomOut').onclick = () => map.zoomOut();
        document.getElementById('resetMap').onclick = () => {
            map.setView([-4.68, 104.55], 9);
            if(marker) map.removeLayer(marker);
            latitudeField.value = '';
            longitudeField.value = '';
        };

        // Click event untuk set koordinat
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            latitudeField.value = lat.toFixed(8);
            longitudeField.value = lng.toFixed(8);
            
            updateMapMarker(lat, lng);
            
            // Reverse geocode optional, tapi form address sudah required manual/auto
            // reverseGeocode(lat, lng); 
        });
    }

    function updateMapMarker(lat, lng) {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker([lat, lng]).addTo(map);
        map.panTo([lat, lng]);
    }

    // Autocomplete Logic (Photon)
    locationInput.addEventListener('input', function(e) {
        clearTimeout(debounceTimer);
        const query = e.target.value.trim();

        if (query.length < 3) {
            autocompleteList.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(() => {
            // Bounding Box Lampung (approx)
            const lampungBBOX = "103.5,-6.0,106.0,-3.5"; 

            fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=5&bbox=${lampungBBOX}`)
                .then(res => res.json())
                .then(data => {
                    autocompleteList.innerHTML = "";
                    if (!data.features || data.features.length === 0) {
                        autocompleteList.style.display = "none";
                        return;
                    }

                    data.features.forEach(place => {
                        const p = place.properties;
                        const coord = place.geometry.coordinates;
                        
                        // Construct display name
                        let parts = [p.name, p.district, p.city, p.state].filter(Boolean);
                        let displayName = parts.join(', ');

                        const div = document.createElement('div');
                        div.className = 'p-2 border-bottom hover-bg-light';
                        div.style.cursor = 'pointer';
                        div.innerHTML = `<i class="fas fa-map-marker-alt text-danger me-2"></i> ${displayName}`;

                        div.onclick = () => {
                            locationInput.value = displayName;
                            autocompleteList.style.display = 'none';

                            const lng = coord[0];
                            const lat = coord[1];

                            latitudeField.value = lat.toFixed(8);
                            longitudeField.value = lng.toFixed(8);
                            
                            updateMapMarker(lat, lng);
                            map.setView([lat, lng], 16);

                            // Auto fill address if empty
                            if(!addressField.value) {
                                addressField.value = displayName;
                            }
                        };
                        autocompleteList.appendChild(div);
                    });
                    autocompleteList.style.display = "block";
                });
        }, 500);
    });

    // Close autocomplete when clicking outside
    document.addEventListener("click", function(e) {
        if (!autocompleteList.contains(e.target) && e.target !== locationInput) {
            autocompleteList.style.display = "none";
        }
    });

    window.addEventListener('load', initMap);
</script>

<style>
    .hover-bg-light:hover {
        background-color: #f8f9fa;
    }
</style>

@endsection