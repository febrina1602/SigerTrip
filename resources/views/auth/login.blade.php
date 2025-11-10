<!DOCTYPE html>

<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk Akun - Sigertrip (Bootstrap)</title>

<!-- 1. Bootstrap 5 CSS CDN (Wajib) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

<!-- 2. Font Awesome untuk ikon (Wajib) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLMDJ8C1v2N2/o+1f/r6x3z5p9d4hW45R+kM9/9zP4r6y9W3Xh0o0Qy2z6/5z8o/g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- 3. Custom CSS untuk styling gradasi dan card shadow -->
<style>
    body {
        background-color: #f8f9fa; 
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        font-family: 'Inter', sans-serif;
    }
    .auth-card {
        max-width: 1200px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15); 
        border-radius: 1rem;
        overflow: hidden;
    }
    .form-control:focus {
        border-color: #dc3545; /* Merah */
        box-shadow: 0 0 0 0.25rem rgba(220, 53, 69, 0.25); 
    }
    .btn-custom-gradient {
        /* Gradasi warna Kuning ke Merah */
        background-image: linear-gradient(to right, #ffc107, #dc3545);
        color: white;
        border: none;
        font-weight: 600;
        transition: all 0.3s;
    }
    .btn-custom-gradient:hover {
        background-image: linear-gradient(to right, #e0a800, #c82333);
        color: white;
    }
</style>


</head>
<body>
<div class="container my-5">
<div class="row auth-card bg-white mx-auto">

    <!-- Kolom Kiri: Gambar (Tersembunyi di layar kecil) -->
    <div class="col-lg-6 d-none d-lg-block p-3">
        <!-- Ganti URL ini dengan URL gambar Anda sendiri -->
        <img src="https://placehold.co/600x800/ff9900/white/png?text=Sigertrip+Image" 
             alt="Pemandangan Sigertrip" 
             class="img-fluid h-100 object-fit-cover rounded-3"
        >
    </div>

    <!-- Kolom Kanan: Formulir Login -->
    <div class="col-lg-6 p-5 d-flex flex-column justify-content-center">
        
        <div class="mb-4">
            <h1 class="h3 fw-bold text-dark">Sigertrip</h1>
        </div>

        <div class="mb-4">
            <!-- Tombol Kembali -->
            <a href="{{ url('/') }}" class="text-danger text-decoration-none fw-semibold d-flex align-items-center">
                <i class="fas fa-arrow-left me-1"></i>
                Kembali
            </a>
        </div>

        <h2 class="h3 fw-bold text-dark mb-4 border-bottom pb-2">Masuk</h2>
        
        <!-- Tampilkan Error Validasi (dari AuthController@login) -->
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Gagal!</strong> Periksa kembali email dan kata sandi Anda.
                @if ($errors->has('email') && $errors->first('email') != 'Kredensial yang Anda masukkan tidak valid.')
                    <ul class="mt-2 mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endif
        
        <!-- FORMULIR LOGIN -->
        <form method="POST" action="{{ route('login.post') }}">
            @csrf 

            <!-- Field Email -->
            <div class="mb-3">
                <label for="email" class="form-label text-muted">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                       class="form-control @error('email') is-invalid @enderror" 
                       placeholder="contoh@sigertrip.com">
            </div>

            <!-- Field Kata Sandi -->
            <div class="mb-3">
                <label for="password" class="form-label text-muted">Kata Sandi</label>
                <div class="input-group">
                    <input id="password" type="password" name="password" required 
                           class="form-control @error('password') is-invalid @enderror" 
                           placeholder="Masukkan kata sandi Anda">
                </div>
            </div>

            <!-- Ingat Saya & Lupa Kata Sandi -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label text-dark" for="remember">
                        Ingat Saya
                    </label>
                </div>
                <a href="#" class="fw-semibold text-danger text-decoration-none">Lupa Kata Sandi?</a>
            </div>

            <!-- Tombol Masuk -->
            <div class="d-grid">
                <button type="submit" class="btn btn-custom-gradient py-2">
                    Masuk
                </button>
            </div>
        </form>

        <p class="mt-4 text-center text-muted small">
            Belum memiliki akun? 
            <a href="{{ route('register') }}" class="text-danger fw-semibold text-decoration-none">Daftar di Sini</a>
        </p>
    </div>
</div>


</div>

<!-- 4. Bootstrap 5 JS CDN (Wajib untuk komponen interaktif) -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>