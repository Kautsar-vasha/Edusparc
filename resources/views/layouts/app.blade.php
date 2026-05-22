<!DOCTYPE html>
<html lang="id" data-bs-theme="light"> <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDUSPARC - SMPN 4 Jember</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { display: flex; min-height: 100vh; overflow-x: hidden; }

        /* Pengaturan Sidebar */
        .sidebar {
            min-width: 260px;
            max-width: 260px;
            background: #212529; /* Warna gelap mewah */
            color: white;
            padding: 20px;
            transition: all 0.3s ease-in-out;
            height: 100vh;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .sidebar.collapsed { margin-left: -260px; } /* Untuk menyembunyikan sidebar */

        /* Pengaturan Konten Utama */
        .content {
            flex: 1;
            padding: 25px;
            background-color: var(--bs-body-bg); /* Mengikuti tema Dark/Light */
            transition: all 0.3s ease-in-out;
            width: 100%;
        }

        /* Desain Menu Sidebar */
        .sidebar a { color: #adb5bd; text-decoration: none; display: block; padding: 12px 15px; border-radius: 8px; margin-bottom: 5px; transition: 0.2s;}
        .sidebar a:hover, .sidebar a.active { background: #0d6efd; color: #fff; }
        .sidebar i { margin-right: 10px; font-size: 1.1rem; }

        /* Tombol Toggle */
        #sidebarToggle { background: none; border: none; font-size: 1.8rem; cursor: pointer; color: var(--bs-body-color); padding: 0; }
    </style>

    <script>
        const savedTheme = localStorage.getItem('edusparc_theme') || 'light';
        document.documentElement.setAttribute('data-bs-theme', savedTheme);
    </script>
</head>
<body>

    <div class="sidebar" id="sidebar">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="m-0 fw-bold text-white tracking-wide">EDUSPARC</h4>
            <button class="btn btn-sm btn-outline-light d-md-none" id="closeSidebar"><i class="bi bi-x m-0"></i></button>
        </div>
        <hr class="border-secondary mb-4">

        {{-- Menu Khusus Guru & Admin --}}
        @if(session('role') == 'guru' || session('role') == 'admin')
            <a href="/dashboard-guru"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="/guru"><i class="bi bi-person-badge"></i> Data Guru</a>
            <a href="/siswa"><i class="bi bi-person-vcard"></i> Data Siswa</a>
            <a href="/kelas"><i class="bi bi-door-open"></i> Data Kelas</a>
            <a href="/pelanggaran"><i class="bi bi-shield-exclamation"></i> Input Pelanggaran</a>

            <hr class="border-secondary my-3">
            <a href="/pengaturan"><i class="bi bi-gear"></i> Pengaturan</a> @endif

        {{-- Menu Khusus Orang Tua --}}
        @if(session('role') == 'ortu')
            <a href="/dashboard-ortu"><i class="bi bi-speedometer2"></i> Dashboard Anak</a>
        @endif

        <a href="/logout" class="text-danger mt-4"><i class="bi bi-box-arrow-left"></i> Keluar</a>
    </div>

    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
            <button id="sidebarToggle" title="Toggle Sidebar"><i class="bi bi-list"></i></button>
            <div class="d-flex align-items-center">
                <span class="badge bg-primary rounded-pill p-2 px-3 shadow-sm" style="font-size: 0.9rem;">
                    <i class="bi bi-person-circle me-1"></i> {{ session('name') ?? 'Pengguna' }}
                </span>
            </div>
        </div>

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Logika agar Sidebar bisa dibuka-tutup
        const sidebar = document.getElementById('sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const closeSidebar = document.getElementById('closeSidebar');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });

        // Khusus untuk layar HP, ada tombol silang untuk menutup
        closeSidebar.addEventListener('click', () => {
            sidebar.classList.add('collapsed');
        });
    </script>
</body>
</html>
