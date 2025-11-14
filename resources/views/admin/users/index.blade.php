@extends('layouts.app')

@section('title', 'Kelola User - SigerTrip')

@section('content')
<div class="min-vh-100 bg-white">

    {{-- HEADER --}}
    <header class="border-bottom bg-white shadow-sm">
        <div class="container py-2 d-flex align-items-center justify-content-between">
            
            {{-- Logo --}}
            <a href="{{ route('admin.beranda') }}" class="d-flex align-items-center text-decoration-none" style="min-width: 150px;">
                <img src="{{ asset('images/logo.png') }}" 
                    alt="SigerTrip Logo"
                    style="height:42px" 
                    loading="lazy" 
                    onerror="this.style.display='none'">
                <span class="ms-2 fw-bold text-dark d-none d-md-block">SigerTrip</span>
            </a>

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
        </div>
    </header>


    {{-- NAVIGATION --}}
    <nav class="nav-custom bg-light py-2 border-bottom">
        <div class="container d-flex gap-4">
            <a href="{{ route('admin.beranda') }}" class="nav-link-custom">Beranda</a>
            <a href="#" class="nav-link-custom">Pasar Digital</a>
            <a href="#" class="nav-link-custom">Pemandu Wisata</a>
            <a href="{{ route('admin.users.index') }}" class="nav-link-custom active">Kelola User</a>
        </div>
    </nav>

    {{-- CONTENT --}}
    <section class="py-4">
        <div class="container">
            {{-- Alert --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">
                    <i class="fas fa-users-cog me-2"></i>Kelola Akun User
                </h4>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-2"></i> Tambah User Baru
                </a>
            </div>

            {{-- Statistik Cards --}}
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-primary bg-gradient text-white">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users fa-2x me-3"></i>
                            <div>
                                <h3 class="fw-bold mb-0">{{ $totalUser }}</h3>
                                <small>Total User</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-info bg-gradient text-white">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-shield-alt fa-2x me-3"></i>
                            <div>
                                <h3 class="fw-bold mb-0">{{ $totalAdmin }}</h3>
                                <small>Admin</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-success bg-gradient text-white">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-tie fa-2x me-3"></i>
                            <div>
                                <h3 class="fw-bold mb-0">{{ $totalAgent }}</h3>
                                <small>Agen</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-warning bg-gradient text-white">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-hiking fa-2x me-3"></i>
                            <div>
                                <h3 class="fw-bold mb-0">{{ $totalRegularUser }}</h3>
                                <small>Wisatawan</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm rounded-3 p-3 bg-danger bg-gradient text-white">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock fa-2x me-3"></i>
                            <div>
                                <h3 class="fw-bold mb-0">{{ $totalPending }}</h3>
                                <small>Pending Verifikasi</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Filter --}}
            <div class="card border-0 shadow-sm rounded-3 mb-4">
                <div class="card-body">
                    <form action="{{ route('admin.users.index') }}" method="GET">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small">Filter Role</label>
                                <select name="role" class="form-select" onchange="this.form.submit()">
                                    <option value="" {{ !request('role') || request('role') == '' ? 'selected' : '' }}>Semua Role</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="agent" {{ request('role') == 'agent' ? 'selected' : '' }}>Agen</option>
                                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Wisatawan</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold small">Filter Status</label>
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="" {{ !request('status') || request('status') == '' ? 'selected' : '' }}>Semua Status</option>
                                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-semibold small">Cari User</label>
                                <input type="text" 
                                    name="search" 
                                    class="form-control" 
                                    placeholder="Cari nama, email, no hp..." 
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2 d-flex align-items-end gap-2">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="fas fa-search me-1"></i>Cari
                                </button>
                                @if(request('role') || request('status') || request('search'))
                                    <a href="{{ route('admin.users.index') }}" 
                                    class="btn btn-secondary" 
                                    title="Reset Filter">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Table --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">No</th>
                                    <th class="py-3">Foto</th>
                                    <th class="py-3">User</th>
                                    <th class="py-3">Role</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3">Terdaftar</th>
                                    <th class="py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $index => $user)
                                <tr>
                                    <td class="px-4">{{ $users->firstItem() + $index }}</td>
                                    <td>
                                        @if($user->profile_picture_url)
                                            <img src="{{ asset($user->profile_picture_url) }}" 
                                                 alt="{{ $user->full_name }}" 
                                                 class="rounded-circle"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="avatar-circle">
                                                {{ strtoupper(substr($user->full_name, 0, 2)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-semibold">{{ $user->full_name }}</div>
                                            <small class="text-muted">{{ $user->email }}</small>
                                            @if($user->phone_number)
                                                <br><small class="text-muted"><i class="fas fa-phone fa-xs me-1"></i>{{ $user->phone_number }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        @if($user->role === 'admin')
                                            <span class="badge bg-info">
                                                <i class="fas fa-shield-alt me-1"></i>ADMIN
                                            </span>
                                        @elseif($user->role === 'agent')
                                            <span class="badge bg-success">
                                                <i class="fas fa-user-tie me-1"></i>AGEN
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-hiking me-1"></i>WISATAWAN
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                    @if($user->status === 'pending' && $user->role === 'agent')
                                        <button 
                                            class="badge bg-warning text-dark border-0"
                                            style="cursor:pointer;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalVerifikasi{{ $user->id }}">
                                            <i class="fas fa-clock me-1"></i>Pending
                                        </button>
                                    @elseif($user->status === 'aktif')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Aktif
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Nonaktif
                                        </span>
                                    @endif
                                </td>

                                    <td>
                                        <small class="text-muted">
                                            {{ $user->created_at->format('d M Y') }}
                                        </small>
                                    </td>
                                    <td>
                                    <div class="d-flex gap-1 justify-content-center flex-wrap">
                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('admin.users.edit', $user->id) }}" 
                                        class="btn btn-sm btn-warning" 
                                        title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                                method="POST" 
                                                class="d-inline" 
                                                onsubmit="return confirm('Yakin hapus user {{ $user->full_name }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                                </tr>

                                {{-- MODAL UNTUK VERIFIKASI / TOLAK AGEN --}}
                                @if($user->status === 'pending' && $user->role === 'agent')
                                <div class="modal fade" id="modalVerifikasi{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Verifikasi Agen</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <p class="mb-2">
                                                    Apakah kamu ingin memverifikasi atau menolak akun:
                                                </p>
                                                <strong>{{ $user->full_name }}</strong><br>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>

                                            <div class="modal-footer d-flex justify-content-center gap-3">
                                                {{-- Tombol Tolak --}}
                                                <form action="{{ route('admin.users.reject', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-times-circle me-1"></i> Tolak
                                                    </button>
                                                </form>

                                                {{-- Tombol Verifikasi --}}
                                                <form action="{{ route('admin.users.verify', $user->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-check-circle me-1"></i> Verifikasi
                                                    </button>
                                                </form>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                @endif

                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        <i class="fas fa-users fa-3x mb-3 d-block"></i>
                                        Belum ada user
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </section>
</div>

<style>
.avatar-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 18px;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}
</style>
@endsection