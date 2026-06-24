<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | EDUSPARC SMPN 4 Jember</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --text-main: #1f2937;
            --text-muted: #6b7280;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            margin: 0;
            position: relative;
            overflow: hidden; /* Mencegah scroll bar muncul saat gambar membesar */
        }

        /* --- FITUR BACKGROUND GAMBAR & ANIMASI --- */
        .bg-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -2;

            /* Gunakan gambar online sebagai contoh sementara.
               Nanti ganti URL ini dengan lokasi gambar aslimu */
            background: url('{{ asset('images/bg-sekolah.png') }}') no-repeat center center;
            background-size: cover;

            /* Animasi membesar secara perlahan bolak-balik */
            animation: zoomBackground 20s infinite alternate linear;
        }

        /* Overlay hitam kebiruan transparan agar form terbaca jelas */
        .bg-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: rgba(15, 23, 42, 0.75);
        }

        /* Keyframes untuk efek zoom */
        @keyframes zoomBackground {
            0% { transform: scale(1); }
            100% { transform: scale(1.1); }
        }
        /* ----------------------------------------- */

        .login-wrapper {
            width: 100%;
            padding: 20px;
            z-index: 1; /* Form di atas background */
        }

        .login-card {
            background: #ffffff;
            border-radius: 24px;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.4); /* Shadow dipertebal karena BG gelap */
            overflow: hidden;
            transition: transform 0.3s ease;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary) 0%, #1e40af 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .login-header::after {
            content: '';
            position: absolute;
            bottom: -20px;
            left: -10%;
            width: 120%;
            height: 40px;
            background: #ffffff;
            border-radius: 50%;
        }

        .login-header h2 {
            letter-spacing: -1px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .login-header p {
            color: #bfdbfe;
            font-size: 0.9rem;
            margin: 0;
            font-weight: 400;
        }

        .form-control {
            border-radius: 12px;
            padding: 12px 15px;
            border: 1.5px solid #e5e7eb;
            font-size: 0.95rem;
            color: var(--text-main);
            transition: all 0.2s;
        }

        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
            border-color: var(--primary);
        }

        .input-group-text {
            border-radius: 12px 0 0 12px;
            border: 1.5px solid #e5e7eb;
            border-right: none;
            background-color: transparent;
            color: var(--text-muted);
        }

        .form-control.border-start-0 {
            border-left: none;
            border-radius: 0 12px 12px 0;
        }

        .form-label {
            font-weight: 600;
            color: var(--text-main);
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .btn-primary {
            background-color: var(--primary);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px -10px var(--primary);
        }

        .help-text {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 6px;
            display: block;
        }

        .footer-text {
            color: #e5e7eb; /* Diubah menjadi abu-abu terang agar kontras dengan BG gelap */
            font-size: 0.85rem;
            font-weight: 500;
            text-shadow: 0 2px 4px rgba(0,0,0,0.5); /* Tambahan bayangan teks */
        }

        /* Efek Hover untuk Link Kembali */
        .back-link:hover {
            color: var(--primary) !important;
        }
    </style>
</head>
<body>

    <div class="bg-image"></div>
    <div class="bg-overlay"></div>

    <div class="login-wrapper">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-4">

                    <div class="login-card">
                        <div class="login-header">
                            <h2>EDUSPARC</h2>
                            <p>SMP Negeri 4 Jember</p>
                        </div>

                        <div class="p-4 pt-5 pb-5">

                            @if(session('error'))
                                <div class="alert alert-danger d-flex align-items-center mb-4" style="border-radius: 12px; border: none; font-size: 0.85rem;" role="alert">
                                    <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                                    <div>{{ session('error') }}</div>
                                </div>
                            @endif

                            <form action="/login" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label class="form-label">Username / NISN</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" name="username" class="form-control border-start-0" placeholder="Masukkan ID Anda" required autocomplete="off">
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label">Password / Tgl Lahir</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" class="form-control border-start-0" placeholder="••••••••" required>
                                    </div>
                                    <span class="help-text"><i class="bi bi-info-circle me-1"></i>Orang tua gunakan format YYYY-MM-DD</span>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 shadow-sm">
                                    Masuk ke Sistem <i class="bi bi-box-arrow-in-right ms-2"></i>
                                </button>

                                <!-- Tombol Kembali ke Beranda -->
                                <div class="text-center mt-4">
                                    <a href="/" class="text-decoration-none text-secondary fw-semibold back-link" style="font-size: 0.9rem; transition: 0.2s;">
                                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                                    </a>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
