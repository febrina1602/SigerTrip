@extends('layouts.app')

@section('title', 'SigerTrip | Jelajahi Pesona Lampung')

@section('content')
    <!-- HEADER -->
    <header>
        <div class="d-flex align-items-center">
            <img src="{{ asset('images/logo.png') }}" alt="SigerTrip Logo">
        </div>
        <div>
            <a href="{{ route('login') }}" class="btn-custom me-2">Masuk</a>
            <a href="{{ route('register') }}" class="btn-custom">Daftar</a>
        </div>
    </header>

    <!-- HERO -->
    <section>
        <div class="container">
            <img src="{{ asset('images/wave.png') }}" class="hero-img mb-4" alt="Pantai Surfing">
            <h4 class="fw-bold"><span class="typing">Selamat Datang di SigerTrip!</span></h4>
            <p class="text-muted">
                Temukan pengalaman healing yang menenangkan di penjuru wilayah <br>
                provinsi Lampung. Wisata praktis, personal, dan penuh makna.
            </p>
        </div>
    </section>

    <!-- PANDUAN -->
    <section style="background-color: #ffffff;">
        <div class="container">
            <img src="{{ asset('images/beach.png') }}" class="hero-img mb-4" alt="Pantai Tropis">
            <h4 class="fw-bold">Panduan Digital Terlengkap</h4>
            <p class="text-muted">
                Akses informasi lengkap tentang tempat wisata, budaya, dan kuliner <br> 
                hanya dalam satu aplikasi.
            </p>
        </div>
    </section>

    <!-- UMKM -->
    <section style="background-color: #ffffff;">
        <div class="container">
            <img src="{{ asset('images/sunset.png') }}" class="hero-img mb-4" alt="Senja di Pantai Lampung">
            <h4 class="fw-bold">Dukung UMKM Lokal</h4>
            <p class="text-muted">
                Beli oleh-oleh dan pesan akomodasi langsung dari pelaku usaha lokal. <br>  
                Liburanmu bawa dampak nyata.
            </p>
        </div>
    </section>

@endsection