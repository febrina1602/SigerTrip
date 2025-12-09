@extends('layouts.app')

@section('title', 'Lupa Kata Sandi - SigerTrip')

@push('styles')
<style>
  /* Khusus halaman lupa kata sandi */
  .auth-wrapper {
    max-width: 1180px;
    margin: 28px auto;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, .08);
    overflow: hidden;
  }
  .topbar {
    height: 70px;
    background: linear-gradient(90deg, #FFD15C 0%, #FF9739 45%, #FF3D3D 100%);
    display: flex;
    align-items: center;
    padding: 0 22px;
  }
  .brand {
    display: flex;
    align-items: center;
    gap: .6rem;
    font-weight: 800;
  }
  .brand img {
    height: 34px;
  }
  .content {
    padding: 28px;
  }
  .image-col img {
    width: 100%;
    height: 600px;
    object-fit: cover;
    border-radius: 16px;
  }
  .kembali {
    color: #cf1a1a;
    text-decoration: none;
    font-weight: 600;
  }
  .kembali:hover {
    color: #a50f0f;
  }
  .title {
    font-weight: 800;
    text-align: center;
    margin: .25rem 0 1.2rem;
  }
  .form-control {
    height: 46px;
    border-radius: 10px;
    padding-right: 42px;
  }
  .input-icon-btn {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    opacity: .6;
  }
  .btn-grad {
    background: linear-gradient(90deg, #FFD15C 0%, #FF9739 45%, #FF3D3D 100%);
    color: #fff;
    border: none;
    height: 46px;
    border-radius: 12px;
    font-weight: 700;
  }
  .btn-grad:hover {
    filter: brightness(.96);
    color: #fff;
  }
  .small-links a {
    color: #cf1a1a;
    text-decoration: none;
  }
  .small-links a:hover {
    text-decoration: underline;
  }

  @media (max-width: 767.98px) {
    .image-col {
      display: none;
    }
    .content {
      padding: 20px;
    }
  }
</style>
@endpush

@section('content')
<div class="auth-wrapper">
  <div class="topbar">
    <div class="brand">
      <img src="{{ asset('images/logo-sigertrip.png') }}" alt="Logo" onerror="this.style.display='none'">
      <span>SigerTrip</span>
    </div>
  </div>

  <div class="content">
    <div class="row g-4 align-items-center">
      {{-- Gambar kiri --}}
      <div class="col-lg-6 image-col">
        <img
          src="{{ asset('images/sunsetlog.png') }}"
          alt="Sunset SigerTrip"
          loading="lazy"
          onerror="this.src='https://images.unsplash.com/photo-1511764220567-4b75a0a36eb1?q=80&w=1200&auto=format&fit=crop'">
      </div>

      {{-- Form kanan --}}
      <div class="col-lg-6">
        <a href="{{ route('login') }}" class="kembali d-inline-block mb-2">
          <i class="fa-solid fa-chevron-left me-1"></i> Kembali ke Masuk
        </a>
        <h2 class="title">Lupa Kata Sandi</h2>

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-2" autocomplete="off">
          @csrf
          <div class="mb-3 position-relative">
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required autofocus autocomplete="off">
            <i class="fa-regular fa-envelope input-icon-btn"></i>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-grad">Kirim Tautan Reset</button>
          </div>

          <p class="text-center mt-3 mb-1">
            Ingat kata sandi? <a href="{{ route('login') }}" class="fw-semibold">Masuk Disini</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  // Bersihkan email & password kalau halaman datang dari tombol Back/Forward
  window.addEventListener('pageshow', function (event) {
      const navEntries = performance.getEntriesByType('navigation');
      const isBackOrForward = navEntries.length && navEntries[0].type === 'back_forward';

      if (event.persisted || isBackOrForward) {
          const emailInput = document.querySelector('input[name="email"]');
          if (emailInput) emailInput.value = '';
      }
  });
</script>
@endpush
@endsection
