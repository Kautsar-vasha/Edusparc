<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDUSPARC - SMPN 4 Jember</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { display: flex; min-height: 100vh; overflow-x: hidden; position: relative; }

        /* --- Pengaturan Sidebar Utama --- */
        .sidebar {
            min-width: 260px;
            max-width: 260px;
            background: #212529;
            color: white;
            padding: 20px 15px; /* Padding disesuaikan sedikit */
            transition: all 0.3s ease-in-out;
            height: 100vh;
            position: sticky;
            top: 0;
            z-index: 1050;
        }

        /* --- Pengaturan Konten Utama --- */
        .content {
            flex: 1;
            padding: 25px;
            background-color: var(--bs-body-bg);
            transition: all 0.3s ease-in-out;
            width: 100%;
        }

        /* Desain Menu Sidebar */
        .sidebar a.nav-link {
            color: #adb5bd;
            text-decoration: none;
            display: block;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: 0.2s;
        }
        .sidebar a.nav-link:hover, .sidebar a.nav-link.active {
            background: #0d6efd;
            color: #fff;
        }
        .sidebar a.nav-link i { margin-right: 10px; font-size: 1.1rem; }

        /* Tombol Toggle */
        #sidebarToggle { background: none; border: none; font-size: 1.8rem; cursor: pointer; color: var(--bs-body-color); padding: 0; }

        /* Desain Tombol Profil User (Baru) */
        .user-profile-btn {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            transition: 0.2s;
        }
        .user-profile-btn:hover {
            background: rgba(255,255,255,0.1);
        }
        .user-avatar {
            width: 38px;
            height: 38px;
            background: #0d6efd;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
        }

        /* =========================================
           PENGATURAN RESPONSIVITAS (MEDIA QUERIES)
           ========================================= */
        @media (min-width: 769px) {
            .sidebar.collapsed { margin-left: -260px; }
        }

        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -260px;
                box-shadow: none;
            }
            .sidebar.show {
                left: 0;
                box-shadow: 4px 0 15px rgba(0,0,0,0.5);
            }
            .content { padding: 15px; }
        }

        .sidebar-overlay {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
            visibility: hidden; opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }
        .sidebar-overlay.show { visibility: visible; opacity: 1; }
    </style>

    <script>
        const savedTheme = localStorage.getItem('edusparc_theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    </script>
</head>
<body>

    <div id="sidebarOverlay" class="sidebar-overlay d-md-none"></div>

    <div class="sidebar d-flex flex-column" id="sidebar">

        <div>
            <div class="d-flex justify-content-between align-items-center mb-3 px-2">
                <h4 class="m-0 fw-bold text-white tracking-wide">EDUSPARC</h4>
                <button class="btn btn-sm btn-outline-light d-md-none" id="closeSidebar"><i class="bi bi-x m-0"></i></button>
            </div>
            <hr class="border-secondary mb-4 mx-2">

            {{-- Menu Khusus Admin --}}
            @if(session('role') == 'admin')
                <a href="/dashboard-admin" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="/guru" class="nav-link"><i class="bi bi-person-badge"></i> Data Guru</a>
                <a href="/siswa" class="nav-link"><i class="bi bi-person-vcard"></i> Data Siswa</a>
                <a href="/kenaikan-kelas" class="nav-link"><i class="bi bi-capslock-fill"></i> Kenaikan Kelas</a>
                <a href="/kelas" class="nav-link"><i class="bi bi-door-open"></i> Data Kelas</a>
                <a href="/pelanggaran" class="nav-link"><i class="bi bi-shield-exclamation"></i> Input Karakter</a>
                <a href="/tatib" class="nav-link"><i class="bi bi-book-half"></i> Master Tata Tertib</a>
                <a href="/scanner" class="nav-link"><i class="bi bi-qr-code-scan"></i> Quick Scanner</a>
            @endif

            {{-- Menu Khusus Guru --}}
            @if(session('role') == 'guru')
                <a href="/dashboard-guru" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a>
                <a href="/guru" class="nav-link"><i class="bi bi-person-badge"></i> Data Guru</a>
                <a href="/siswa" class="nav-link"><i class="bi bi-person-vcard"></i> Data Siswa</a>
                <a href="/kelas" class="nav-link"><i class="bi bi-door-open"></i> Data Kelas</a>
                <a href="/pelanggaran" class="nav-link"><i class="bi bi-shield-exclamation"></i> Input Karakter</a>
                <a href="/scanner" class="nav-link"><i class="bi bi-qr-code-scan"></i> Quick Scanner</a>
            @endif

            {{-- Menu Khusus Orang Tua --}}
            @if(session('role') == 'ortu')
                <a href="/dashboard-ortu" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard Anak</a>
            @endif
        </div>

        <div class="mt-auto pt-3 border-top border-secondary">
            <div class="dropup w-100">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none user-profile-btn w-100 p-2 rounded" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar me-3 shadow-sm">
                        {{ strtoupper(substr(session('name') ?? 'U', 0, 1)) }}
                    </div>
                    <div class="d-flex flex-column flex-grow-1 overflow-hidden" style="line-height: 1.2;">
                        <span class="fw-bold text-truncate" style="font-size: 0.95rem;">{{ session('name') ?? 'Pengguna' }}</span>
                        <small class="text-secondary text-truncate" style="font-size: 0.8rem;">{{ ucfirst(session('role') ?? 'Akses Terbatas') }}</small>
                    </div>
                    <i class="bi bi-chevron-expand ms-2 text-secondary"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-dark text-small shadow w-100 mb-2 rounded-3 border-secondary">
                    @if(in_array(session('role'), ['admin', 'guru']))
                        <li>
                            <a class="dropdown-item py-2 d-flex align-items-center" href="/pengaturan">
                                <i class="bi bi-gear me-3 text-secondary"></i> Pengaturan Akun
                            </a>
                        </li>
                        <li><hr class="dropdown-divider border-secondary"></li>
                    @endif
                    <li>
                        <a class="dropdown-item py-2 d-flex align-items-center text-danger fw-semibold" href="/logout">
                            <i class="bi bi-box-arrow-right me-3"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>

    <div class="content">
        <div class="mb-4 d-flex align-items-center">
            <button id="sidebarToggle" title="Toggle Sidebar" class="btn btn-outline-secondary border-0 fs-4 p-1"><i class="bi bi-list"></i></button>
        </div>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const closeSidebar = document.getElementById('closeSidebar');
        const overlay = document.getElementById('sidebarOverlay');

        sidebarToggle.addEventListener('click', () => {
            if (window.innerWidth <= 768) {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            } else {
                sidebar.classList.toggle('collapsed');
            }
        });

        function closeMenu() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        }

        closeSidebar.addEventListener('click', closeMenu);
        overlay.addEventListener('click', closeMenu);
    </script>
</body>
</html>
