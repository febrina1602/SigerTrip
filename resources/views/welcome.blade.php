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

    <!-- FOOTER -->
    <footer class="footer position-relative">
        <div class="container py-3">
            <div class="row align-items-start">
                <div class="col-md-3 d-flex align-items-center mb-3 mb-md-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SigerTrip" class="me-2" style="height:50px;">
                </div>

                <div class="col-md-3 text-center mb-3 mb-md-0">
                    <h6 class="fw-bold mb-2">Ikuti Kami</h6>
                    <div class="d-flex justify-content-center align-items-center social-icons">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-x-twitter"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>

                <div class="col-md-3 mb-3 mb-md-0">
                    <h6 class="fw-bold mb-2">Dibuat Oleh:</h6>
                    <p class="mb-1">Febrina Aulia Azahra</p>
                    <p class="mb-1">Carissa Oktavia Sanjaya</p>
                    <p class="mb-1">Dilvi Yola</p>
                    <p class="mb-0">M. Hafiz Abyan</p>
                </div>

                <div class="col-md-3">
                    <h6 class="fw-bold mb-2">Informasi</h6>
                    <p class="mb-1"><a href="#" class="footer-link">Tentang</a></p>
                    <p class="mb-0"><a href="#" class="footer-link">FAQ</a></p>
                </div>
            </div>
        </div>
        <img src="{{ asset('images/siger-pattern.png') }}" alt="Siger Pattern" class="siger-pattern">
    </footer>
@endsection