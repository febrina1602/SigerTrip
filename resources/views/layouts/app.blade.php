<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SigerTrip | Jelajahi Pesona Lampung')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #ffffff;
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh; 
            display: flex;
            flex-direction: column;
        }

        .main-content {
            flex: 1;
        }

        h2 {
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        p { font-weight: 400; color: #555; }
        section { padding: 60px 0; text-align: center; }
        header { background: linear-gradient(90deg, #D19878, #FFE75D); padding: 15px 40px; display: flex; justify-content: space-between; align-items: center; }
        header img { height: 50px; }
        .btn-custom { background-color: red; color: white; border-radius: 8px; padding: 8px 20px; font-weight: 600; border: none; text-decoration: none; transition: all 0.3s ease-in-out; box-shadow: 0 0 0 rgba(0, 0, 0, 0); }
        .btn-custom:hover { background-color: #cc0000; transform: scale(1.0); box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); }
        .nav-custom { background-color: white; border-bottom: 1px solid #dee2e6; }
        .nav-link-custom { padding: 12px 8px; color: #333; text-decoration: none; font-weight: 600; border-bottom: 2px solid transparent; transition: all 0.3s ease; display: inline-block; }
        .nav-link-custom:hover { color: #dc3545; }
        .nav-link-custom.active { color: #dc3545; border-bottom-color: #dc3545; }
        .category-section { background: linear-gradient(to bottom, #FFF8E7, #FFFFFF); padding: 40px 0; }
        .category-card { background-color: white; border-radius: 8px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); padding: 20px; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: all 0.3s ease; cursor: pointer; }
        .category-card:hover { box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15); transform: translateY(-2px); }
        .category-icon { width: 64px; height: 64px; }
        .hero-img { width: 100%; height: 400px; object-fit: cover; border-radius: 20px; transition: transform 0.5s ease, box-shadow 0.5s ease; }
        .hero-img:hover { transform: scale(1.02); box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); }
        .typing { display: inline-block; border-right: 3px solid #333; white-space: nowrap; overflow: hidden; animation: typing 3s steps(30, end), blink 0.75s step-end infinite; }
        @keyframes typing { from { width: 0 } to { width: 35% } }
        @keyframes blink { 50% { border-color: transparent } }
        .footer { background: linear-gradient(90deg, #D19878, #FFE75D); color: #000; position: relative; overflow: hidden; padding: 30px 0 60px 0; /* Tambahan: Mencegah footer memakan ruang body */ flex-shrink: 0; }
        .footer a.footer-link { color: #000; text-decoration: none; }
        .footer a.footer-link:hover { text-decoration: underline; }
        .social-icons a { color: #000; font-size: 22px; margin: 0 10px; transition: all 0.3s ease-in-out; }
        .social-icons a:hover { color: red; transform: translateY(-3px); }
        .siger-pattern { position: absolute; bottom: 0; left: 0; width: 100%; height: auto; pointer-events: none; }
        @media (max-width: 768px) {
            .siger-pattern { opacity: 0.2; }
            header { padding: 15px 20px; }
            .nav-link-custom { font-size: 14px; padding: 10px 6px; }
        }
    </style>

    @stack('styles')
</head>
<body>
    
    <div class="main-content">
        @yield('content')
    </div>
    
    {{-- FOOTER--}}
    <footer class="footer position-relative">
        <div class="container py-3">
            <div class="row align-items-start">
                <div class="col-md-3 d-flex align-items-center mb-3 mb-md-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SigerTrip" class="me-2" style="height:50px;" loading="lazy">
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
        <img src="{{ asset('images/siger-pattern.png') }}" alt="Siger Pattern" class="siger-pattern" loading="lazy">
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    
</body>
</html>