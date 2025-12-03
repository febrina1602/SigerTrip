@extends('layouts.app')

@section('title', 'Pemandu Wisata - SigerTrip')
@section('content')
    <div class="bg-white">
    @include('components.layout.header')

        <div class="py-5 bg-white min-vh-100">
            <div class="container">
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 fw-bold text-dark mb-0">Agen Tour Lokal</h1>
                    
                    {{-- Action buttons for agent --}}
                    @auth
                        @if(auth()->user()->role === 'agent')
                            <button class="btn btn-primary rounded-3" data-bs-toggle="modal" data-bs-target="#addLocalTourAgentModal">
                                <i class="fas fa-plus me-2"></i> Tambah Agen Tour Lokal
                            </button>
                        @endif
                    @endauth
                </div>

                {{-- Content for agent --}}
                @if(auth()->check() && auth()->user()->role == 'agent')
                    <div class="row">
                        {{-- Display list of local tour agents for this agent --}}
                        @if(isset($localTourAgents) && $localTourAgents->count() > 0)
                            @foreach($localTourAgents as $localAgent)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border-0 rounded-4 shadow-sm h-100">
                                        <div class="card-body">
                                            <h5 class="card-title fw-bold mb-2">{{ $localAgent->name }}</h5>
                                            <p class="card-text small text-muted mb-2">
                                                <i class="fas fa-map-pin me-1"></i>
                                                {{ $localAgent->address ?? 'Alamat tidak ditentukan' }}
                                            </p>
                                            <p class="card-text small mb-3">
                                                <i class="fas fa-phone me-1"></i>
                                                {{ $localAgent->contact_phone ?? 'Telepon tidak ditentukan' }}
                                            </p>
                                            <p class="card-text small text-muted mb-3">
                                                {{ $localAgent->description ?? 'Tidak ada deskripsi' }}
                                            </p>
                                            <div class="d-flex gap-2">
                                                <button class="btn btn-sm btn-outline-primary" title="Edit">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <form action="{{ route('agent.local_tour_agents.delete', $localAgent->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin ingin menghapus?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                                                        <i class="fas fa-trash"></i> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="alert alert-info border-0 rounded-4" role="alert">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Belum ada agen tour lokal.</strong>
                                    <p class="mb-0 mt-2">Klik tombol "Tambah Agen Tour Lokal" di atas untuk memulai.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                {{-- Content for user/guest (Browse catalog) --}}
                @else
                    <div class="row">
                        @if(isset($localTourAgents) && $localTourAgents->count() > 0)
                            @foreach($localTourAgents as $localAgent)
                                <div class="col-md-6 col-lg-4 mb-3">
                                    <div class="card border-0 rounded-4 shadow-sm h-100 transition" style="cursor: pointer; transition: transform 0.3s ease;" onclick="window.location.href='{{ route('pemandu-wisata.show', $localAgent->id) }}';">
                                        @if($localAgent->banner_image_url)
                                            <img src="{{ $localAgent->banner_image_url }}" class="card-img-top" alt="{{ $localAgent->name }}" style="height: 200px; object-fit: cover;">
                                        @else
                                            <div style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                                <i class="fas fa-image" style="font-size: 3rem; color: rgba(255,255,255,0.3);"></i>
                                            </div>
                                        @endif
                                        <div class="card-body">
                                            <h5 class="card-title fw-bold mb-2">{{ $localAgent->name }}</h5>
                                            <p class="card-text small text-muted mb-2">
                                                <i class="fas fa-map-pin me-1"></i>
                                                {{ $localAgent->location ?? 'Lokasi tidak ditentukan' }}
                                            </p>
                                            <p class="card-text small mb-3">
                                                <i class="fas fa-star text-warning me-1"></i>
                                                Rating: {{ number_format($localAgent->rating ?? 0, 1) }}
                                            </p>
                                            @if($localAgent->description)
                                                <p class="card-text small text-muted">
                                                    {{ Illuminate\Support\Str::limit($localAgent->description, 100) }}
                                                </p>
                                            @endif
                                            <div class="mt-2">
                                                <span class="badge bg-info text-dark">
                                                    <i class="fas fa-briefcase me-1"></i>
                                                    {{ $localAgent->tourPackages->count() ?? 0 }} Paket
                                                </span>
                                                @if($localAgent->agent)
                                                    <span class="badge bg-secondary">
                                                        {{ $localAgent->agent->name }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <div class="alert alert-info border-0 rounded-4" role="alert">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Belum ada agen tour lokal tersedia.</strong>
                                    <p class="mb-0 mt-2">Agen tour akan muncul di sini setelah terdaftar dan diverifikasi oleh admin.</p>
                                </div>
                            </div>
                        @endif
                    </div>
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
                        @csrf
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
                        // Get CSRF token from meta tag or form hidden input
                        let csrfToken = document.querySelector('meta[name=csrf-token]')?.getAttribute('content') 
                                     || document.querySelector('input[name=_token]')?.value;
                        
                        if (!csrfToken) {
                            throw new Error('CSRF token tidak ditemukan. Refresh halaman dan coba lagi.');
                        }

                        const res = await fetch("{{ route('agent.local_tour_agents.store') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
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

                        // Reload to show new item
                        location.reload();

                    } catch (err) {
                        alertBox.classList.remove('d-none');
                        alertBox.classList.add('alert-danger');
                        alertBox.innerHTML = '<strong>Error:</strong> ' + ((err && err.message) ? err.message : 'Gagal menyimpan. Periksa input Anda.');
                    }
                });
            });
        </script>
    @endif
@endauth

@endsection