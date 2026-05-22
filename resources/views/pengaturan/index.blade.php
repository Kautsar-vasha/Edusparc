@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="fw-bold mb-4"><i class="bi bi-gear-fill text-secondary me-2"></i>Pengaturan Sistem</h3>

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm border-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger shadow-sm border-0">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h5 class="fw-bold mb-3"><i class="bi bi-palette-fill text-primary me-2"></i>Tampilan & Tema</h5>
                <p class="text-muted small">Pilih mode warna aplikasi EDUSPARC sesuai dengan kenyamanan mata Anda.</p>

                <div class="d-flex gap-3 mt-4">
                    <button class="btn btn-outline-primary flex-fill py-3 theme-btn" data-theme="light">
                        <i class="bi bi-sun-fill d-block fs-2 mb-2"></i> Mode Terang
                    </button>
                    <button class="btn btn-outline-dark flex-fill py-3 theme-btn" data-theme="dark">
                        <i class="bi bi-moon-stars-fill d-block fs-2 mb-2"></i> Mode Gelap
                    </button>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h5 class="fw-bold mb-3"><i class="bi bi-shield-lock-fill text-success me-2"></i>Ganti Password</h5>
                <form action="/pengaturan/password" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password Lama</label>
                        <input type="password" name="current_password" class="form-control" placeholder="Masukkan password saat ini" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Password Baru</label>
                        <input type="password" name="new_password" class="form-control" placeholder="Minimal 5 karakter" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label small fw-bold">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" class="form-control" placeholder="Ulangi password baru" required>
                    </div>
                    <button class="btn btn-success w-100 fw-bold"><i class="bi bi-save me-1"></i> Simpan Password Baru</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // JS Untuk Fitur Dark/Light Mode
    const themeBtns = document.querySelectorAll('.theme-btn');
    const currentTheme = localStorage.getItem('edusparc_theme') || 'light';

    // Menandai tombol mana yang sedang aktif
    themeBtns.forEach(btn => {
        if(btn.dataset.theme === currentTheme) {
            btn.classList.add('active');
        }

        btn.addEventListener('click', function() {
            const selectedTheme = this.dataset.theme;

            // 1. Ubah atribut HTML (Otomatis mengganti warna bawaan Bootstrap)
            document.documentElement.setAttribute('data-bs-theme', selectedTheme);

            // 2. Simpan di memori browser agar permanen
            localStorage.setItem('edusparc_theme', selectedTheme);

            // 3. Ubah efek aktif di tombol
            themeBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        });
    });
</script>
@endsection
