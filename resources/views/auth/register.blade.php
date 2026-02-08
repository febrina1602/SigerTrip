@php 
    $isAgent = true; 
@endphp

@extends('layouts.app')

@section('title', 'Registrasi Mitra - SigerTrip')

@push('styles')
<style>
  .auth-wrapper{
    max-width:1180px; margin:28px auto; background:#fff;
    border-radius:18px; box-shadow:0 12px 30px rgba(0,0,0,.08); overflow:hidden;
  }
  .topbar{
    height:70px; background:linear-gradient(90deg,#FFD15C 0%,#FF9739 45%,#FF3D3D 100%);
    display:flex; align-items:center; padding:0 22px;
  }
  .brand{ display:flex; align-items:center; gap:.6rem; font-weight:800; }
  .brand img{ height:34px; }
  .content{ padding:28px; }
  .image-col img{ width:100%; height:600px; object-fit:cover; border-radius:16px; }

  .kembali{ color:#cf1a1a; text-decoration:none; font-weight:600; }
  .kembali:hover{ color:#a50f0f; }

  .form-title{ font-weight:800; text-align:center; margin:.25rem 0 1.2rem; }
  .form-control{ height:46px; border-radius:10px; padding-right:42px; }
  .input-icon{ position:absolute; right:12px; top:50%; transform:translateY(-50%); opacity:.6; }

  .btn-grad{
    background:linear-gradient(90deg,#FFD15C 0%,#FF9739 45%,#FF3D3D 100%);
    color:#fff; border:none; height:46px; border-radius:12px; font-weight:700;
  }
  .btn-grad:hover{ filter:brightness(.96); color:#fff; }

  @media (max-width: 767.98px){
    .image-col{ display:none; }
    .content{ padding:20px; }
  }
  @media (min-width: 768px){
    .image-col{ display:block !important; }
  }
</style>
@endpush

@section('content')
<div class="auth-wrapper">
  <div class="topbar">
    <div class="brand">
      <img src="{{ asset('images/logo-sigertrip.png') }}" alt="Logo">
      <span>SigerTrip</span>
    </div>
  </div>

  <div class="content">
    <div class="row g-4 align-items-center">

      <div class="col-lg-6 image-col d-none d-md-block">
        <img
          src="{{ asset('images/sunsetlog.png') }}"
          alt="Sunset SigerTrip"
          class="w-100"
          style="height:600px;object-fit:cover;border-radius:16px"
          loading="lazy">
      </div>

      <div class="col-lg-6">
        <a href="{{ url()->previous() }}" class="kembali d-inline-block mb-2">
          <i class="fa-solid fa-chevron-left me-1"></i> Kembali
        </a>

        <h2 class="form-title">Registrasi Mitra SigerTrip</h2>

        {{-- Pesan sukses --}}
        @if (session('success'))
          <div class="alert alert-info">
            {{ session('success') }}
          </div>
        @endif

        {{-- Error --}}
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $error) 
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- Form Registrasi MITRA --}}
        <form method="POST" action="{{ route('register.agent.post') }}" class="mt-2">
          @csrf

          <div class="mb-3 position-relative">
            <input type="text" name="full_name" value="{{ old('full_name') }}"
                   class="form-control @error('full_name') is-invalid @enderror"
                   placeholder="Nama Lengkap" required>
            <i class="fa-regular fa-user input-icon"></i>
          </div>

          <div class="mb-3 position-relative">
            <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                   class="form-control @error('phone_number') is-invalid @enderror"
                   placeholder="Nomor Telepon" required>
            <i class="fa-solid fa-phone input-icon"></i>
          </div>

          <div class="mb-3 position-relative">
            <input type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="Email" required>
            <i class="fa-regular fa-envelope input-icon"></i>
          </div>

          <div class="mb-3 position-relative">
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Password" required>
            <button type="button" class="btn btn-link p-0 input-icon"
                    onclick="togglePwd('password', this)">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>

          <div class="mb-3 position-relative">
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-control" placeholder="Konfirmasi Password" required>
            <button type="button" class="btn btn-link p-0 input-icon"
                    onclick="togglePwd('password_confirmation', this)">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>

          <div class="mb-3">
            <div class="form-check d-flex align-items-center gap-2">
              <input class="form-check-input" type="checkbox" id="agree" required>
              <label for="agree" class="form-check-label small">
                Saya menyetujui <a href="#" class="text-decoration-none">Ketentuan Layanan</a> dan
                <a href="#" class="text-decoration-none">Kebijakan Privasi</a>
              </label>
            </div>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-grad">
              Daftar sebagai Mitra
            </button>
          </div>

          <p class="text-center mt-3 mb-0">
            Sudah memiliki akun? 
            <a href="{{ route('login') }}" class="text-danger fw-semibold text-decoration-none">
              Masuk Disini
            </a>
          </p>
        </form>

      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  function togglePwd(id, el){
    const input = document.getElementById(id);
    const icon  = el.querySelector('i');
    if(input.type === 'password'){ 
      input.type='text'; 
      icon.classList.replace('fa-eye','fa-eye-slash'); 
    } else { 
      input.type='password'; 
      icon.classList.replace('fa-eye-slash','fa-eye'); 
    }
  }
</script>
@endpush

@endsection
