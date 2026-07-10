<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDUSPARC - SMPN 4 Jember Mudadidaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        /* Desain Khusus Landing Page */
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8f9fa; overflow-x: hidden; }
        .navbar { box-shadow: 0 2px 15px rgba(0,0,0,0.05); background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
        .hero-section { background: linear-gradient(135deg, #f0f7ff 0%, #ffffff 100%); padding: 100px 0 80px; position: relative; overflow: hidden; }
        .hero-title { font-weight: 800; font-size: 3rem; color: #0f2c59; line-height: 1.2; }
        .badge-hero { background: #e6f0fa; color: #0d6efd; font-weight: 600; padding: 8px 15px; border-radius: 50px; font-size: 0.9rem; }

        /* Desain Khusus Kotak Penjelasan EDUSPARC */
        .edusparc-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 25px;
            border-left: 6px solid #0d6efd;
            box-shadow: 0 10px 30px rgba(13, 110, 253, 0.08);
            position: relative;
            z-index: 2;
            transition: transform 0.3s ease;
        }
        .edusparc-card:hover { transform: translateY(-5px); }

        .feature-card { border: none; border-radius: 15px; transition: 0.3s; background: white; box-shadow: 0 5px 20px rgba(0,0,0,0.03); height: 100%; padding: 30px; }
        .feature-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(13, 110, 253, 0.1); }
        .feature-icon { width: 60px; height: 60px; border-radius: 15px; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin-bottom: 20px; }

        .step-number { width: 40px; height: 40px; background: #0d6efd; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 1.2rem; margin: 0 auto -20px; position: relative; z-index: 2; border: 4px solid white; }
        .step-card { background: white; border-radius: 15px; padding: 40px 20px 20px; border: 1px solid #e9ecef; box-shadow: 0 4px 15px rgba(0,0,0,0.02); height: 100%; text-align: center; }

        .footer { background-color: #0f2c59; color: white; padding: 60px 0 30px; }
        .footer a { color: #adb5bd; text-decoration: none; transition: 0.2s; }
        .footer a:hover { color: white; }

        /* Efek Hover Sosmed */
        .social-icon { transition: transform 0.2s ease, color 0.2s ease; }
        .social-icon:hover { transform: translateY(-5px); color: #0d6efd !important; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top py-3" data-aos="fade-down" data-aos-duration="800">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center fw-bold text-primary" href="/">
                <img src="{{ asset('images/logo_sekolah.png') }}" alt="Logo" width="40" class="me-2">
                EDUSPARC
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto fw-semibold">
                    <li class="nav-item"><a class="nav-link px-3" href="#beranda">Beranda</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#fitur">Fitur Unggulan</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#akses">Akses Pengguna</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#panduan">Cara Kerja</a></li>
                </ul>
                <div class="d-flex">
                    <a href="/login" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm">Masuk Portal <i class="bi bi-box-arrow-in-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </nav>

    <section id="beranda" class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0" data-aos="fade-right" data-aos-duration="1000">
                    <div class="d-inline-block mb-3 badge-hero">
                        <i class="bi bi-star-fill text-warning me-1"></i> Inovasi Pendidikan SMPN 4 Jember
                    </div>
                    <h1 class="hero-title">
                        Bentuk Sikap,<br>
                        <span class="text-primary">Catat Karakter,</span><br>
                        Wujudkan Generasi Hebat
                    </h1>

                    <div class="edusparc-card mt-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                        <i class="bi bi-quote position-absolute top-0 end-0 text-primary opacity-10" style="font-size: 5rem; margin-top: -15px; margin-right: 15px;"></i>
                        <h4 class="fw-bolder text-primary mb-0" style="letter-spacing: 1px;">EDUSPARC</h4>
                        <p class="text-secondary fw-semibold mb-3" style="font-size: 0.9rem;">
                            (Educational System for Profile, Attitude, Rating, and Character)
                        </p>
                        <p class="text-muted mb-0 lh-base" style="font-size: 0.95rem; text-align: justify;">
                            Sistem pemantauan perkembangan karakter berbasis digital yang dirancang untuk membantu guru, wali kelas, dan kepala sekolah dalam memantau perkembangan sikap, perilaku, dan prestasi siswa secara <span class="fw-bold text-dark">real-time</span>.
                        </p>
                    </div>
                    <div class="d-flex gap-3" data-aos="fade-up" data-aos-delay="400">
                        <a href="/login" class="btn btn-primary btn-lg fw-bold px-4 rounded-pill shadow">Mulai Sekarang</a>
                        <a href="#fitur" class="btn btn-outline-secondary btn-lg fw-bold px-4 rounded-pill">Pelajari Lanjut</a>
                    </div>

                    <div class="d-flex align-items-center mt-5 gap-4">
                        <div data-aos="fade-up" data-aos-delay="500">
                            <h4 class="fw-bold text-dark mb-0"><i class="bi bi-people-fill text-primary me-2"></i>700+</h4>
                            <small class="text-muted">Siswa Aktif</small>
                        </div>
                        <div class="border-start ps-4" data-aos="fade-up" data-aos-delay="600">
                            <h4 class="fw-bold text-dark mb-0"><i class="bi bi-shield-check text-success me-2"></i>Terpusat</h4>
                            <small class="text-muted">Monitoring Real-time</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 text-center position-relative" data-aos="fade-left" data-aos-duration="1000">
                    <i class="bi bi-diagram-3-fill text-primary opacity-25 d-none d-lg-block" style="font-size: 20rem; line-height: 1;"></i>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold mb-2">Program Sekolah Terpadu</span>
                <h2 class="fw-bold text-dark">Fitur Unggulan EDUSPARC</h2>
                <p class="text-muted">Membangun karakter unggul melalui sistem pencatatan digital yang modern.</p>
            </div>

            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-qr-code-scan"></i></div>
                        <h5 class="fw-bold">Quick Scanner Terintegrasi</h5>
                        <p class="text-muted small mb-0">Catat pelanggaran atau kebaikan siswa dalam hitungan detik menggunakan Kamera HP atau alat Scanner Barcode USB.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon bg-success bg-opacity-10 text-success"><i class="bi bi-graph-up-arrow"></i></div>
                        <h5 class="fw-bold">Sistem Saldo Poin</h5>
                        <p class="text-muted small mb-0">Perhitungan poin yang cerdas. Kebaikan akan menambah saldo, pelanggaran akan mengurangi saldo secara otomatis.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon bg-info bg-opacity-10 text-info"><i class="bi bi-file-earmark-pdf"></i></div>
                        <h5 class="fw-bold">Export Laporan Berkala</h5>
                        <p class="text-muted small mb-0">Unduh data rekam jejak karakter siswa dalam format Excel dan PDF untuk keperluan rapor dan bimbingan konseling (BK).</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="akses" class="py-5 bg-light">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="fw-bold text-dark">Solusi untuk Setiap Peran</h2>
                <p class="text-muted">Dari tenaga pendidik hingga orang tua, semua saling terhubung</p>
            </div>

            <div class="row g-4 justify-content-center">
                <div class="col-12 col-md-4" data-aos="flip-left" data-aos-duration="800">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 border-top border-4 border-primary">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-person-badge-fill fs-2 text-primary me-3"></i>
                            <h4 class="fw-bold mb-0">Guru & Admin</h4>
                        </div>
                        <p class="small text-muted mb-4">Pengelola utama data karakter siswa. Berhak mencatat aktivitas dan mengevaluasi kedisiplinan.</p>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> Input poin massal via QR</li>
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> Kenaikan kelas 1-klik</li>
                            <li><i class="bi bi-check2 text-success me-2"></i> Evaluasi grafik sikap siswa</li>
                        </ul>
                    </div>
                </div>
                <div class="col-12 col-md-4" data-aos="flip-right" data-aos-duration="800" data-aos-delay="200">
                    <div class="card border-0 shadow-sm rounded-4 h-100 p-4 border-top border-4 border-success">
                        <div class="d-flex align-items-center mb-3">
                            <i class="bi bi-people-fill fs-2 text-success me-3"></i>
                            <h4 class="fw-bold mb-0">Orang Tua</h4>
                        </div>
                        <p class="small text-muted mb-4">Mendampingi anak secara digital. Pantau perkembangan sikap anak secara transparan dari rumah.</p>
                        <ul class="list-unstyled small text-muted mb-0">
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> Notifikasi poin real-time</li>
                            <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i> Laporan sikap harian</li>
                            <li><i class="bi bi-check2 text-success me-2"></i> Pesan pembinaan dari guru</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="panduan" class="py-5 bg-white">
        <div class="container py-5">
            <div class="text-center mb-5" data-aos="zoom-in">
                <h2 class="fw-bold text-dark">Cara Kerja Platform EDUSPARC</h2>
                <p class="text-muted">4 langkah mudah untuk memantau kedisiplinan dan membangun karakter</p>
            </div>

            <div class="row g-4 mt-4">
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-number shadow-sm">1</div>
                    <div class="step-card">
                        <i class="bi bi-upc-scan text-primary fs-1 mb-3 d-block"></i>
                        <h6 class="fw-bold">Scan Kartu Siswa</h6>
                        <p class="small text-muted">Arahkan Kartu Pelajar ke alat scanner atau input NIS secara manual.</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-number shadow-sm">2</div>
                    <div class="step-card">
                        <i class="bi bi-journal-text text-success fs-1 mb-3 d-block"></i>
                        <h6 class="fw-bold">Input Kejadian</h6>
                        <p class="small text-muted">Pilih jenis aktivitas (kebaikan/pelanggaran) dan masukkan poin.</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-number shadow-sm">3</div>
                    <div class="step-card">
                        <i class="bi bi-hdd-network text-info fs-1 mb-3 d-block"></i>
                        <h6 class="fw-bold">Data Terpusat</h6>
                        <p class="small text-muted">Sistem akan secara otomatis menyesuaikan akumulasi poin individu.</p>
                    </div>
                </div>
                <div class="col-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                    <div class="step-number shadow-sm">4</div>
                    <div class="step-card">
                        <i class="bi bi-pie-chart-fill text-warning fs-1 mb-3 d-block"></i>
                        <h6 class="fw-bold">Evaluasi Bersama</h6>
                        <p class="small text-muted">Guru BK dan Orang Tua meninjau grafik perkembangan karakter siswa.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer mt-auto border-top">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-12 col-lg-6 text-center text-lg-start" data-aos="fade-right">
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start mb-3">
                        <img src="{{ asset('images/logo_sekolah.png') }}" alt="Logo" width="45" class="me-3 bg-white p-1 rounded-circle">
                        <h4 class="fw-bold mb-0 text-white">EDUSPARC</h4>
                    </div>
                    <p class="small text-white-50 mb-0 pe-lg-5">
                        Sistem Edukasi Profil Sikap & Karakter merupakan program dedikasi SMPN 4 Jember untuk mencetak generasi penerus yang tidak hanya cerdas secara akademis, namun juga unggul dalam budi pekerti.
                    </p>
                </div>

                <div class="col-12 col-md-6 col-lg-3 text-center text-lg-start" data-aos="fade-up" data-aos-delay="100">
                    <h6 class="fw-bold text-white mb-3">Kontak Sekolah</h6>
                    <ul class="list-unstyled small text-white-50">
                        <li class="mb-2"><i class="bi bi-telephone me-2"></i> (0331) 485525</li>
                        <li class="mb-3"><i class="bi bi-envelope me-2"></i> smpnjember4@gmail.com</li>
                        <li class="lh-sm"><i class="bi bi-geo-alt me-2 d-inline-block align-top"></i> <span class="d-inline-block" style="max-width: 85%;">Jl. Nusa Indah No.14, Jember Lor, Kecamatan Patrang, Kabupaten Jember, Jawa Timur</span></li>
                    </ul>
                </div>

                <div class="col-12 col-md-6 col-lg-3 text-center text-lg-start" data-aos="fade-up" data-aos-delay="200">
                    <h6 class="fw-bold text-white mb-3">Ikuti Kami</h6>
                    <div class="d-flex gap-3 justify-content-center justify-content-lg-start mb-4">
                        <a href="https://youtube.com/@smpn4jember?si=JKn3S6LlkoCiR4wE" class="text-white fs-4 social-icon"><i class="bi bi-youtube"></i></a>
                        <a href="https://www.instagram.com/smpn4jember?igsh=MXZqb25xb3NhazU4ag==" class="text-white fs-4 social-icon"><i class="bi bi-instagram"></i></a>
                        <a href="https://vt.tiktok.com/ZSQEGbsT7/" class="text-white fs-4 social-icon"><i class="bi bi-tiktok"></i></a>
                        <a href="https://www.facebook.com/share/1E66iUxYxN/" class="text-white fs-4 social-icon"><i class="bi bi-facebook"></i></a>
                    </div>
                    <p class="small text-white-50 mb-0">&copy; {{ date('Y') }} SMPN 4 Jember.<br>Hak Cipta Dilindungi.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true,
            offset: 100,
            duration: 800
        });
    </script>
</body>
</html>
