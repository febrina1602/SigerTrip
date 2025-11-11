<!-- resources/views/register.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi - SigerTrip</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />

  <style>
    :root {
      --bg: #efefef;
      --card: #ffffff;
      --radius: 18px;
      --shadow: 0 12px 30px rgba(0,0,0,.08);
      --grad: linear-gradient(90deg, #FFD15C 0%, #FF9739 45%, #FF3D3D 100%);
    }
    body{ background: var(--bg); font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif; }
    .auth-wrapper{ max-width: 980px; margin: 28px auto; background: var(--card); border-radius: var(--radius); box-shadow: var(--shadow); overflow: hidden; }
    .topbar{ height: 64px; background: var(--grad); display: flex; align-items: center; padding: 0 24px; }
    .brand{ display: flex; gap: .6rem; align-items: center; font-weight: 800; letter-spacing: .2px; }
    .brand img{ height: 30px; width: auto; }
    .content{ padding: 26px; }
    .image-col img{ width: 100%; height: 510px; object-fit: cover; border-radius: 16px; }
    .kembali{ color: #d11c1c; text-decoration: none; font-weight: 600; }
    .kembali:hover{ color:#a90f0f; }
    .form-title{ font-weight: 800; text-align: center; margin: .25rem 0 1rem; }
    .form-control{ height: 46px; border-radius: 10px; padding-right: 42px; }
    .input-icon{ position: absolute; right: 12px; top: 50%; transform: translateY(-50%); opacity: .55; }
    .btn-grad{ background: var(--grad); border: none; color: #fff; height: 46px; border-radius: 12px; font-weight: 600; }
    .btn-grad:hover{ filter: brightness(.95); color:#fff; }
    .alert{ border-radius: 12px; }
    @media (max-width: 991.98px){ .image-col{ display:none; } .content{ padding: 20px; } }
  </style>
</head>
<body>

<div class="auth-wrapper">
  <div class="topbar">
    <div class="brand">
      <img src="{{ asset('images/logo-sigertrip.png') }}" alt="Logo" onerror="this.style.display='none';">
      <span>SigerTrip</span>
    </div>
  </div>

  <div class="content">
    <div class="row g-4 align-items-center">

      <!-- Left preview image (updated to sunsetlog .png with encoded space) -->
      <div class="col-lg-6 image-col">
        <img
          src="{{ asset('images/sunsetlog%20.png') }}"
          alt="SigerTrip Sunset"
          loading="lazy"
          onerror="this.src='https://images.unsplash.com/photo-1511764220567-4b75a0a36eb1?q=80&w=1080&auto=format&fit=crop'">
      </div>

      <!-- Right form -->
      <div class="col-lg-6">
        <a href="{{ url()->previous() }}" class="kembali d-inline-block mb-2"><i class="fa-solid fa-chevron-left me-1"></i> Kembali</a>
        <h2 class="form-title">Registrasi</h2>

        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="mt-2">
          @csrf

          <div class="mb-3 position-relative">
            <input type="text" name="full_name" value="{{ old('full_name') }}" class="form-control @error('full_name') is-invalid @enderror" placeholder="Nama Lengkap" required>
            <i class="fa-regular fa-user input-icon"></i>
          </div>

          <div class="mb-3 position-relative">
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email" required>
            <i class="fa-regular fa-envelope input-icon"></i>
          </div>

          <div class="mb-3 position-relative">
            <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password" required>
            <button type="button" class="btn btn-link p-0 input-icon" onclick="togglePwd('password', this)" aria-label="Tampilkan/Kunci Password">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>

          <div class="mb-3 position-relative">
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Konfirmasi Password" required>
            <button type="button" class="btn btn-link p-0 input-icon" onclick="togglePwd('password_confirmation', this)" aria-label="Tampilkan/Kunci Konfirmasi Password">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>

          <div class="mb-3">
            <div class="form-check d-flex align-items-center gap-2">
              <input class="form-check-input" type="checkbox" id="agree" required>
              <label for="agree" class="form-check-label small">
                Saya menyetujui <a href="#" class="text-decoration-none">Ketentuan Layanan</a> dan <a href="#" class="text-decoration-none">Kebijakan Privasi</a>
              </label>
            </div>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-grad">Daftar</button>
          </div>

          <p class="text-center mt-3 mb-0">
            Sudah memiliki akun? <a href="{{ route('login') }}" class="text-danger text-decoration-none fw-semibold">Masuk Disini</a>
          </p>
        </form>
      </div>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function togglePwd(id, el){
    const input = document.getElementById(id);
    const icon  = el.querySelector('i');
    if(input.type === 'password'){
      input.type = 'text';
      icon.classList.remove('fa-eye');
      icon.classList.add('fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.remove('fa-eye-slash');
      icon.classList.add('fa-eye');
    }
  }
</script>
</body>
</html>
