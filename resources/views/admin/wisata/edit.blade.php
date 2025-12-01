@extends('layouts.app')

@section('title', 'Edit Destinasi Wisata')

@section('content')
<div class="container py-5">
    {{-- HEADER --}}
    <header class="d-flex justify-content-between align-items-center py-3 px-4 border-bottom">
        <div class="d-flex align-items-center gap-2">
            <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo" style="height:50px;">
        </div>
        {{-- Profil dan Logout --}}
        <div class="d-flex align-items-center gap-4" style="min-width: 150px; justify-content: flex-end;">
            {{-- Profil Admin --}}
            <div class="text-center">
                <i class="fas fa-user-circle text-dark" style="font-size: 1.8rem;"></i>
                <div class="small fw-medium mt-1 text-dark">
                    {{ Auth::user()->full_name ?? Auth::user()->name ?? 'Admin' }}
                </div>
            </div>
            {{-- Tombol Logout --}}
            <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                @csrf
                <button type="submit" class="btn btn-link text-danger p-0" 
                        style="font-size: 1.6rem; line-height: 1;" 
                        title="Keluar">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </header>

    <h4 class="fw-bold mb-4">Edit Destinasi Wisata</h4>

    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Form edit destinasi --}}
    <form action="{{ route('admin.wisata.update', $destination->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Nama --}}
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Nama Destinasi</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $destination->name) }}" required>
        </div>

        {{-- Lokasi dengan Autocomplete --}}
        <div class="mb-3">
            <label for="location_input" class="form-label fw-semibold">Lokasi (Cari Tempat)</label>
            <div class="position-relative">
                <input type="text" class="form-control" id="location_input" 
                       placeholder="Ketik nama tempat (misal: Pantai Sanur, Bali)" 
                       autocomplete="off" value="{{ old('address', $destination->address) }}">
                <div id="autocomplete_list" class="position-absolute w-100 bg-white border rounded-2 mt-1 shadow" 
                     style="display: none; max-height: 300px; overflow-y: auto; z-index: 1050;">
                </div>
            </div>
            <small class="text-muted d-block mt-2">✓ Mulai ketik nama tempat untuk mendapatkan saran lokasi</small>
        </div>

        {{-- Map untuk pilih lokasi manual --}}
        <div class="mb-3">
            <label class="form-label fw-semibold">atau Pilih Lokasi di Map</label>
            <div style="margin-bottom: 10px;">
                <button type="button" class="btn btn-sm btn-outline-secondary" id="zoomIn">
                    <i class="fas fa-search-plus"></i> Zoom In
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="zoomOut">
                    <i class="fas fa-search-minus"></i> Zoom Out
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="resetMap">
                    <i class="fas fa-redo"></i> Reset
                </button>
            </div>
            <div id="map" style="height: 450px; border-radius: 8px; border: 2px solid #ddd;"></div>
            <small class="text-muted d-block mt-2">
                💡 <strong>Klik di map untuk set koordinat</strong> | Gunakan tombol zoom untuk detail lebih baik | Koordinat real-time akan muncul saat klik
            </small>
        </div>

        {{-- Alamat --}}
        <div class="mb-3">
            <label for="address" class="form-label fw-semibold">Alamat Lengkap</label>
            <textarea class="form-control" id="address" name="address" rows="2" required 
                      placeholder="Alamat akan terisi otomatis dari pencarian lokasi">{{ old('address', $destination->address) }}</textarea>
        </div>

        {{-- Deskripsi --}}
        <div class="mb-3">
            <label for="description" class="form-label fw-semibold">Deskripsi</label>
            <textarea class="form-control" id="description" name="description" rows="4" required>{{ old('description', $destination->description) }}</textarea>
        </div>

        {{-- Fasilitas --}}
        <div class="mb-3">
            <label for="facilities" class="form-label fw-semibold">Fasilitas</label>
            <textarea class="form-control" id="facilities" name="facilities" rows="3" 
                      placeholder="Contoh: Toilet, Area Parkir, Wi-Fi">{{ old('facilities', $destination->facilities) }}</textarea>
        </div>

        {{-- Harga Tiket per Orang --}}
        <div class="mb-3">
            <label for="price_per_person" class="form-label fw-semibold">Harga per Orang (Rp)</label>
            <input type="number" class="form-control" id="price_per_person" name="price_per_person" min="0" value="{{ old('price_per_person', $destination->price_per_person) }}" required>
        </div>

        {{-- Harga Parkir --}}
        <div class="mb-3">
            <label for="parking_price" class="form-label fw-semibold">Harga Parkir (Rp)</label>
            <input type="number" class="form-control" id="parking_price" name="parking_price" min="0" value="{{ old('parking_price', $destination->parking_price) }}">
        </div>

        {{-- Kategori --}}
        <div class="mb-3">
            <label for="category_id" class="form-label fw-semibold">Kategori</label>
            <select name="category_id" id="category_id" class="form-select" required>
                <option value="">-- Pilih Kategori --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id', $destination->category_id) == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Aktivitas Populer --}}
        <div class="mb-3">
            <label for="popular_activities" class="form-label fw-semibold">Aktivitas Populer</label>
            @php
                $activities = old('popular_activities', $destination->popular_activities ?? []);
                if (is_array($activities)) {
                    $activities = implode(', ', $activities);
                }
            @endphp

            <textarea class="form-control" id="popular_activities" name="popular_activities" rows="3" placeholder="Contoh: Snorkeling, Hiking, Menikmati Kuliner">{{ $activities }}</textarea>
        </div>

        {{-- Koordinat Lokasi --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="latitude" class="form-label fw-semibold">Latitude</label>
                <input type="text" class="form-control" id="latitude" name="latitude" placeholder="-5.4302" value="{{ old('latitude', $destination->latitude) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="longitude" class="form-label fw-semibold">Longitude</label>
                <input type="text" class="form-control" id="longitude" name="longitude" placeholder="105.2621" value="{{ old('longitude', $destination->longitude) }}" required>
            </div>
        </div>

        {{-- Rating --}}
        <div class="mb-3">
            <label for="rating" class="form-label fw-semibold">Rating Awal (0 - 5)</label>
            <input type="number" step="0.01" class="form-control" id="rating" name="rating" min="0" max="5" value="{{ old('rating', $destination->rating) }}">
        </div>

        {{-- Destinasi Unggulan --}}
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $destination->is_featured) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_featured">
                Tandai sebagai destinasi unggulan
            </label>
        </div>

        {{-- Preview Gambar Saat Ini --}}
        @if($destination->image_url)
        <div class="mb-3">
            <label class="form-label fw-semibold">Gambar Saat Ini</label>
            <div>
                <img src="{{ asset($destination->image_url) }}" alt="{{ $destination->name }}" class="img-thumbnail" style="max-width: 300px;">
            </div>
        </div>
        @endif

        {{-- Upload Foto Baru --}}
        <div class="mb-4">
            <label for="image" class="form-label fw-semibold">Upload Foto Baru (Opsional)</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <small class="text-muted">Format: JPG, PNG, atau JPEG (maksimal 2MB). Kosongkan jika tidak ingin mengubah gambar.</small>
        </div>

        {{-- Tombol --}}
        <button type="submit" class="btn btn-primary px-4">Update</button>
        <a href="{{ route('admin.beranda') }}" class="btn btn-secondary ms-2 px-4">Batal</a>
    </form>
</div>

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>

{{-- Script untuk Google Places Autocomplete + Map --}}
<script>
    const locationInput = document.getElementById('location_input');
    const autocompleteList = document.getElementById('autocomplete_list');
    const addressField = document.getElementById('address');
    const latitudeField = document.getElementById('latitude');
    const longitudeField = document.getElementById('longitude');
    const mapContainer = document.getElementById('map');

    let debounceTimer;
    let map;
    let marker;

    // Initialize Map (default ke Lampung center)
    function initMap() {
        map = L.map('map').setView([{{ old('latitude', $destination->latitude ?? -4.68) }}, {{ old('longitude', $destination->longitude ?? 104.55) }}], 10);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Zoom controls
        document.getElementById('zoomIn').onclick = () => map.zoomIn();
        document.getElementById('zoomOut').onclick = () => map.zoomOut();
        document.getElementById('resetMap').onclick = () => map.setView([{{ old('latitude', $destination->latitude ?? -4.68) }}, {{ old('longitude', $destination->longitude ?? 104.55) }}], 10);

        // Click event untuk set koordinat
        map.on('click', function(e) {
            const lat = e.latlng.lat;
            const lng = e.latlng.lng;
            
            latitudeField.value = lat.toFixed(8);
            longitudeField.value = lng.toFixed(8);
            
            updateMapMarker(lat, lng);
            
            // Reverse geocode untuk get alamat
            reverseGeocode(lat, lng);
        });
    }

    // Update marker di map
    function updateMapMarker(lat, lng) {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker([lat, lng]).addTo(map);
        map.setView([lat, lng], 15);
    }

    // Reverse geocode (ambil alamat dari koordinat)
    function reverseGeocode(lat, lng) {
        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`)
            .then(r => r.json())
            .then(data => {
                if (data.address) {
                    // Buat alamat yang readable dari komponen address
                    const addr = data.address;
                    const parts = [];
                    
                    if (addr.house_number) parts.push(addr.house_number);
                    if (addr.road) parts.push(addr.road);
                    if (addr.neighbourhood) parts.push(addr.neighbourhood);
                    if (addr.suburb) parts.push(addr.suburb);
                    if (addr.village) parts.push(addr.village);
                    if (addr.district) parts.push(addr.district);
                    if (addr.city) parts.push(addr.city);
                    if (addr.county) parts.push(addr.county);
                    if (addr.state) parts.push(addr.state);
                    
                    const fullAddress = parts.filter(p => p).join(', ');

                    addressField.value = fullAddress || `Lokasi: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                } else {
                    addressField.value = `Lokasi: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
                }
            })
            .catch(() => {
                addressField.value = `Lokasi: ${lat.toFixed(4)}, ${lng.toFixed(4)}`;
            });
    }

    // Autocomplete input
    locationInput.addEventListener('input', function(e) {
        clearTimeout(debounceTimer);
        const query = e.target.value.trim();

        if (query.length < 2) {
            autocompleteList.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(() => {
            // Batas pencarian hanya Lampung
            // Format: minLon, minLat, maxLon, maxLat
            const lampungBBOX = "103.35,-6.05,105.75,-4.32";

            fetch(`https://photon.komoot.io/api/?q=${encodeURIComponent(query)}&limit=10&bbox=${lampungBBOX}`)
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

                        const name = p.name || "";
                        const village = p.suburb || p.village || p.district || "";
                        const city = p.city || p.county || "";
                        const state = p.state || "";

                        const display = `${name}, ${village}, ${city}, ${state}`.replace(/, ,/g, ",").replace(/,\s*$/, "");

                        const div = document.createElement('div');
                        div.className = 'p-2 border-bottom';
                        div.style.cursor = 'pointer';
                        div.innerHTML = `<small>${display}</small>`;

                        div.onclick = () => {
                            locationInput.value = display;
                            autocompleteList.style.display = 'none';

                            const lng = coord[0];
                            const lat = coord[1];

                            latitudeField.value = lat.toFixed(8);
                            longitudeField.value = lng.toFixed(8);
                            
                            updateMapMarker(lat, lng);
                            reverseGeocode(lat, lng);
                            
                            // Auto zoom ke lokasi dengan level detail
                            map.setView([lat, lng], 16);
                        };

                        autocompleteList.appendChild(div);
                    });

                    autocompleteList.style.display = "block";
                });

        }, 300);
    });

    // Close autocomplete when clicking elsewhere
    document.addEventListener("click", function(e) {
        if (!autocompleteList.contains(e.target) && e.target !== locationInput) {
            autocompleteList.style.display = "none";
        }
    });

    // Update map ketika latitude/longitude diubah manual
    latitudeField.addEventListener('change', updateMapFromFields);
    longitudeField.addEventListener('change', updateMapFromFields);

    function updateMapFromFields() {
        const lat = parseFloat(latitudeField.value);
        const lng = parseFloat(longitudeField.value);
        
        if (!isNaN(lat) && !isNaN(lng)) {
            updateMapMarker(lat, lng);
        }
    }

    // Initialize map saat page load
    window.addEventListener('load', initMap);
</script>

@endsection

