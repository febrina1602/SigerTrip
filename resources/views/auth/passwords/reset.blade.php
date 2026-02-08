@extends('layouts.app')

@section('title', 'Reset Kata Sandi - SigerTrip')

@push('styles')
<style>
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
    font-size: 20px;
    font-weight: 800;
    color: #000;
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

  /* ===== Tambahan: wrapper + icon password ===== */
  .password-wrapper {
    position: relative;
  }
  .toggle-password {
    position: absolute;
    top: 50%;
    right: 12px;
    transform: translateY(-50%);
    font-size: 18px;
    color: #777;
    cursor: pointer;
  }

  @media (max-width: 768px) {
    .image-col { display: none; }
  }
</style>
@endpush


@section('content')
<div class="auth-wrapper">

  {{-- TOPBAR --}}
  <div class="topbar">
    <div class="brand">SigerTrip</div>
  </div>

  <div class="content">
    <div class="row g-4 align-items-center">

      {{-- Gambar kiri --}}
      <div class="col-lg-6 image-col">
        <img src="{{ asset('images/sunsetlog.png') }}" alt="Reset Password Image">
      </div>

      {{-- Form kanan --}}
      <div class="col-lg-6">

        <a href="{{ route('login') }}" class="kembali d-inline-block mb-2">
          <i class="fa-solid fa-chevron-left me-1"></i> Kembali ke Masuk
        </a>

        <h2 class="title">Reset Kata Sandi</h2>

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
          @csrf

          <input type="hidden" name="token" value="{{ $token }}">

          <div class="mb-3">
            <input type="email" name="email" value="{{ $email }}" class="form-control" readonly>
          </div>

          {{-- ===== Password Baru (ditambahkan fitur eye) ===== --}}
          <div class="mb-3 password-wrapper">
            <input type="password" name="password" id="password" class="form-control" placeholder="Password Baru" required>
            <i class="fa-solid fa-eye-slash toggle-password" data-target="password"></i>
          </div>

          {{-- ===== Konfirmasi Password (ditambahkan fitur eye) ===== --}}
          <div class="mb-3 password-wrapper">
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
            <i class="fa-solid fa-eye-slash toggle-password" data-target="password_confirmation"></i>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-grad">Reset Password</button>
          </div>
        </form>

      </div>
    </div>
  </div>

</div>
@endsection

@push('scripts')
<script>
  // Toggle icon + show/hide password
  document.querySelectorAll('.toggle-password').forEach(icon => {

    icon.addEventListener('click', function () {
      let input = document.getElementById(this.dataset.target);

      if (input.type === "password") {
        input.type = "text";
        this.classList.remove("fa-eye-slash");
        this.classList.add("fa-eye");
      } else {
        input.type = "password";
        this.classList.remove("fa-eye");
        this.classList.add("fa-eye-slash");
      }
    });

  });
</script>
@endpush
