@extends('layouts.app')

@section('title', 'Dashboard Agent - SigerTrip')

@section('content')
<div class="min-vh-100 bg-light">
    
    @include('components.layout.header')

    {{-- MAIN CONTENT --}}
    <div class="container mt-4 mb-5">
        
        {{-- WELCOME SECTION --}}
        <div class="row mb-5">
            <div class="col-12">
                <div class="bg-gradient-primary rounded-4 p-4 p-md-5 text-white shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="fw-bold mb-2">Selamat Datang, {{ $user->full_name }}! 👋</h2>
                            <p class="mb-0 text-white">
                                Kelola agen tour lokal Anda, buat paket perjalanan menarik, dan tingkatkan rating untuk menjadi partner terpercaya SigerTrip.
                            </p>
                        </div>
                        <div class="col-md-4 text-center d-none d-md-block">
                            <i class="fas fa-briefcase" style="font-size: 5rem; opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STATISTICS SECTION --}}
        <div class="row mb-5">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 rounded-4 shadow-sm h-100 transition" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small mb-1">Agen Tour Lokal</p>
                                <h3 class="fw-bold mb-0" style="color: #667eea;">{{ $totalLocalTourAgents }}</h3>
                            </div>
                            <div class="rounded-circle p-3" style="background-color: #e8eef7;">
                                <i class="fas fa-map-location-dot" style="font-size: 1.5rem; color: #667eea;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 rounded-4 shadow-sm h-100 transition" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small mb-1">Paket Perjalanan</p>
                                <h3 class="fw-bold mb-0" style="color: #f59e0b;">{{ $totalTourPackages }}</h3>
                            </div>
                            <div class="rounded-circle p-3" style="background-color: #fef3c7;">
                                <i class="fas fa-suitcase" style="font-size: 1.5rem; color: #f59e0b;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 rounded-4 shadow-sm h-100 transition" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small mb-1">Rating Rata-Rata</p>
                                <div>
                                    <h3 class="fw-bold mb-0 d-inline" style="color: #ec4899;">
                                        {{ number_format($averageRating, 1) }}
                                    </h3>
                                    <span class="text-warning ms-2">
                                        <i class="fas fa-star" style="font-size: 0.8rem;"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="rounded-circle p-3" style="background-color: #fce7f3;">
                                <i class="fas fa-star" style="font-size: 1.5rem; color: #ec4899;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card border-0 rounded-4 shadow-sm h-100 transition" style="transition: transform 0.3s ease;">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="text-muted small mb-1">Destinasi Unik</p>
                                <h3 class="fw-bold mb-0" style="color: #10b981;">{{ $totalDestinations }}</h3>
                            </div>
                            <div class="rounded-circle p-3" style="background-color: #d1fae5;">
                                <i class="fas fa-globe" style="font-size: 1.5rem; color: #10b981;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- QUICK ACTIONS --}}
        <div class="row mb-5">
            <div class="col-12">
                <h5 class="fw-bold mb-3">Aksi Cepat</h5>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        {{-- Tombol untuk menambah paket (Sementara Modal) --}}
                        <button class="btn btn-outline-success w-100 rounded-3 py-3" data-bs-toggle="modal" data-bs-target="#addTourPackageModal">
                            <i class="fas fa-plus me-2"></i> Buat Paket Perjalanan
                        </button>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('agent.pasar.index') }}" class="btn btn-outline-warning w-100 rounded-3 py-3">
                            <i class="fas fa-car me-2"></i> Kelola Pasar Digital
                        </a>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary w-100 rounded-3 py-3">
                            <i class="fas fa-gear me-2"></i> Pengaturan Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- PAKET PERJALANAN TABLE --}}
        <div class="row mb-5">
            <div class="col-12">
                <h5 class="fw-bold mb-3">Paket Perjalanan Terbaru</h5>

                @if($recentTourPackages->count() > 0)
                    <div class="card border-0 rounded-4 shadow-sm overflow-hidden">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="px-4">Nama Paket</th>
                                        <th>Harga</th>
                                        <th>Durasi</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTourPackages as $package)
                                        <tr>
                                            <td class="px-4 fw-medium">{{ Str::limit($package->name, 40) }}</td>
                                            <td class="fw-bold text-warning">
                                                Rp {{ number_format($package->price_per_person, 0, ',', '.') }}
                                            </td>
                                            <td>{{ $package->duration ?? '-' }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    {{-- Tombol Edit (Trigger Modal) --}}
                                                    <button type="button" 
                                                            class="btn btn-outline-primary" 
                                                            onclick="openEditPackageModal({{ json_encode($package) }})">
                                                        <i class="fas fa-edit"></i>
                                                    </button>

                                                    {{-- Tombol Hapus --}}
                                                    <form action="{{ route('agent.tour_packages.delete', $package->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus paket ini?');">
                                                        @csrf
                                                        {{-- Note: Controller uses standard POST for delete based on routes provided previously --}}
                                                        <button type="submit" class="btn btn-outline-danger" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info border-0 rounded-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada paket perjalanan yang dibuat.
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- MODAL: Add Local Tour Agent --}}
<div class="modal fade" id="addLocalTourAgentModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Tambah Agen Tour Lokal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addLocalTourAgentForm">
                @csrf
                <div class="modal-body">
                    <div id="agentAlert" class="alert d-none"></div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Agen / Cabang</label>
                        <input type="text" name="name" class="form-control" required placeholder="Contoh: Cabang Way Kambas">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alamat</label>
                        <input type="text" name="address" class="form-control" placeholder="Lokasi operasional">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nomor Telepon</label>
                        <input type="text" name="contact_phone" class="form-control" placeholder="08...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL: Add Tour Package (Placeholder) --}}
<div class="modal fade" id="addTourPackageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Buat Paket Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-person-digging fa-3x text-warning mb-3"></i>
                <h5>Fitur Segera Hadir</h5>
                <p class="text-muted">Untuk saat ini, silakan hubungi admin untuk menambahkan paket perjalanan kompleks.</p>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: Edit Tour Package --}}
<div class="modal fade" id="editPackageModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Edit Paket Perjalanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editPackageForm">
                @csrf
                <div class="modal-body">
                    <div id="editPackageAlert" class="alert d-none"></div>
                    <input type="hidden" id="edit_package_id">

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Harga per Orang (Rp)</label>
                        <input type="number" name="price_per_person" id="edit_price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Durasi</label>
                        <input type="text" name="duration" id="edit_duration" class="form-control" placeholder="Contoh: 2 Hari 1 Malam">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Fasilitas</label>
                        <textarea name="facilities" id="edit_facilities" class="form-control" rows="2" placeholder="Makan siang, Tiket masuk..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Deskripsi</label>
                        <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary rounded-3 px-4">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // === 1. Handle Add Local Agent (AJAX) ===
    document.getElementById('addLocalTourAgentForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const alertBox = document.getElementById('agentAlert');
        const btn = this.querySelector('button[type="submit"]');
        
        btn.disabled = true;
        btn.innerText = 'Menyimpan...';
        alertBox.classList.add('d-none');

        const formData = new FormData(this);

        try {
            const res = await fetch("{{ route('agent.local_tour_agents.store') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await res.json();

            if (!res.ok) throw new Error(data.message || 'Gagal menyimpan.');

            // Success
            location.reload(); 

        } catch (err) {
            alertBox.classList.remove('d-none', 'alert-success');
            alertBox.classList.add('alert-danger');
            alertBox.innerText = err.message;
            btn.disabled = false;
            btn.innerText = 'Simpan';
        }
    });

    // === 2. Handle Open Edit Modal ===
    function openEditPackageModal(packageData) {
        document.getElementById('edit_package_id').value = packageData.id;
        document.getElementById('edit_price').value = packageData.price_per_person;
        document.getElementById('edit_duration').value = packageData.duration;
        document.getElementById('edit_description').value = packageData.description;
        
        // Handle facilities array/string
        let facilities = packageData.facilities;
        if(Array.isArray(facilities)) {
            facilities = facilities.join(', ');
        }
        document.getElementById('edit_facilities').value = facilities;

        new bootstrap.Modal(document.getElementById('editPackageModal')).show();
    }

    // === 3. Handle Edit Package Submit (AJAX) ===
    document.getElementById('editPackageForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const pkgId = document.getElementById('edit_package_id').value;
        const alertBox = document.getElementById('editPackageAlert');
        const btn = this.querySelector('button[type="submit"]');
        
        // Construct URL manually since ID is dynamic
        // Assuming route: /agent/tour-packages/{id}/update
        const url = "{{ url('/agent/tour-packages') }}/" + pkgId + "/update";

        btn.disabled = true;
        btn.innerText = 'Updating...';
        alertBox.classList.add('d-none');

        const formData = new FormData(this);

        try {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await res.json();

            if (!res.ok) throw new Error(data.message || 'Gagal update.');

            location.reload();

        } catch (err) {
            alertBox.classList.remove('d-none');
            alertBox.classList.add('alert-danger');
            alertBox.innerText = err.message;
            btn.disabled = false;
            btn.innerText = 'Update';
        }
    });
</script>
@endpush

@endsection