@extends('layouts.app')

@section('title', 'Pemandu Wisata - SigerTrip')
@section('content')
    <div class="bg-white">
    @include('components.layout.header')

        <div class="py-5 bg-white min-vh-100">
            <div class="container">
                
                <h1 class="h3 fw-bold text-dark mb-4 text-start">Agen Tour Lokal</h1>
                    @if(auth()->check() && auth()->user()->role == 'agent')
                        <div class="col">
                            <div class="alert alert-info">
                                <p class="fs-5 mb-2">Belum ada agen tour lokal</p>
                                <p class="small">Agen tour yang Anda kelola akan muncul di sini</p>
                            </div>
                        </div>
                    @else
                        <div class="col">
                            <div class="alert alert-info">
                                <p class="fs-5 mb-2">Belum ada agen tour lokal tersedia</p>
                                <p class="small">Agen tour akan muncul di sini setelah terdaftar dan diverifikasi oleh admin</p>
                            </div>
                        </div>
                    @endif
                                <p class="small">Agen tour akan muncul di sini setelah terdaftar dan diverifikasi oleh admin</p>
                            </div>


                    
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