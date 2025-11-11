{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk - SigerTrip</title>

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

  <style>
    :root{
      --grad: linear-gradient(90deg,#FFD15C 0%,#FF9739 45%,#FF3D3D 100%);
      --card: #fff;
      --bg: #efefef;
      --radius: 18px;
      --shadow: 0 12px 30px rgba(0,0,0,.08);
    }

    body{
      background: var(--bg);
      font-family: system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
    }

    .auth-wrapper{
      max-width: 1240px;          /* sedikit lebih lebar agar layout lega */
      margin: 28px auto;
      background: var(--card);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    .topbar{
      height: 70px;
      background: var(--grad);
      display:flex;
      align-items:center;
      padding:0 22px;
    }

    .brand{ display:flex; align-items:center; gap:.6rem; font-weight:800; }
    .brand img{ height:34px; }

    .content{ padding: 28px; }

    /* ==== Gambar kiri diperbesar tingginya ==== */
    .image-col{ display:flex; }
    .image-col img{
      width: 100%;
      height: 620px;        /* tinggi default di desktop */
      max-height: 72vh;     /* jangan melebihi tinggi layar */
      object-fit: cover;
      border-radius: 16px;
    }
    @media (min-width: 1400px){
      .image-col img{ height: 700px; } /* layar ekstra lebar */
    }
    @media (max-width: 1199.98px){
      .image-col img{ height: 520px; } /* layar lebih kecil */
    }

    .kembali{ color:#cf1a1a; text-decoration:none; font-weight:600; }
    .kembali:hover{ color:#a50f0f; }

    .title{ font-weight:800; text-align:center; margin:.25rem 0 1.2rem; }
    .form-control{ height:46px; border-radius:10px; padding-right:42px; }
    .input-icon-btn{ position:absolute; right:12px; top:50%; transform:translateY(-50%); opacity:.6; }

    .btn-grad{
      background:var(--grad);
      color:#fff;
      border:none;
      height:46px;
      border-radius:12px;
      font-weight:700;
    }
    .btn-grad:hover{ filter:brightness(.96); color:#fff; }

    .small-links a{ color:#cf1a1a; text-decoration:none; }
    .small-links a:hover{ text-decoration:underline; }

    @media (max-width: 991.98px){
      .image-col{ display:none; }
      .content{ padding:20px; }
    }
  </style>
</head>
<body>

<div class="auth-wrapper">
  <!-- Top gradient bar -->
  <div class="topbar">
    <div class="brand">
      <img src="{{ asset('images/logo-sigertrip.png') }}" alt="Logo" onerror="this.style.display='none'">
      <span>SigerTrip</span>
    </div>
  </div>

  <div class="content">
    <div class="row g-4 align-items-center">
      <!-- Left image -->
      <div class="col-lg-6 image-col">
        <img
          src="{{ asset('images/sunsetlog .png') }}"
          alt="Sunset SigerTrip"
          loading="lazy"
          onerror="this.src='https://images.unsplash.com/photo-1511764220567-4b75a0a36eb1?q=80&w=1200&auto=format&fit=crop'">
      </div>

      <!-- Right form -->
      <div class="col-lg-6">
        <a href="{{ url()->previous() }}" class="kembali d-inline-block mb-2">
          <i class="fa-solid fa-chevron-left me-1"></i> Kembali
        </a>
        <h2 class="title">Masuk</h2>

        {{-- Error block --}}
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="mt-2">
          @csrf

          <div class="mb-3 position-relative">
            <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="Email" required autofocus>
            <i class="fa-regular fa-envelope input-icon-btn"></i>
          </div>

          <div class="mb-2 position-relative">
            <input id="password"
                   type="password"
                   name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Kata Sandi" required>
            <button type="button" class="btn btn-link p-0 input-icon-btn"
                    onclick="togglePwd('password', this)" aria-label="Tampilkan/Kunci Password">
              <i class="fa-regular fa-eye"></i>
            </button>
          </div>

          <div class="d-flex justify-content-end mb-3 small-links">
            <a href="#">Lupa Kata sandi ?</a>
          </div>

          <div class="d-grid">
            <button type="submit" class="btn btn-grad">Masuk</button>
          </div>

          <p class="text-center mt-3 mb-1">
            Belum memiliki akun? <a href="{{ route('register') }}" class="fw-semibold">Daftar Disini</a>
          </p>
          <p class="text-center text-muted small mb-0">
            Dengan mengakses sistem ini, Anda dianggap menyetujui
            <a href="#" class="fw-semibold">Ketentuan Layanan</a> dan
            <a href="#" class="fw-semibold">Kebijakan Privasi</a>.
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
      icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
      input.type = 'password';
      icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
  }
</script>
</body>
</html>
