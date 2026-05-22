@extends('layouts.app')

@section('content')
<style>
    .glass-card {
        background: #ffffff;
        border-radius: 15px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
</style>

<div class="container-fluid py-2">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <a href="/guru" class="text-decoration-none text-muted mb-2 d-inline-block">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Guru
            </a>
            <h3 class="fw-bold text-dark mb-0">
                <i class="bi bi-person-plus-fill text-primary me-2"></i>Tambah Data Guru
            </h3>
        </div>
    </div>

    <div class="glass-card p-4 border-top border-4 border-primary mx-auto" style="max-width: 800px;">
        <p class="text-muted mb-4">Silakan lengkapi form di bawah ini untuk menambahkan data tenaga pendidik baru ke dalam sistem EDUSPARC.</p>

        <form action="/guru" method="POST">
            @csrf

            <div class="row g-4">
                <div class="col-md-6">
                    <div>
                        <label class="form-label text-dark fw-semibold mb-1">NIP / NUPTK <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-upc-scan text-muted"></i></span>
                            <input type="text" name="nip" class="form-control border-start-0 ps-0" placeholder="Masukkan NIP/NUPTK" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div>
                        <label class="form-label text-dark fw-semibold mb-1">Nama Lengkap (Berta Gelar) <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" name="name" class="form-control border-start-0 ps-0" placeholder="Contoh: Budi Santoso, S.Pd." required>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div>
                        <label class="form-label text-dark fw-semibold mb-1">Username Login <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person-badge text-muted"></i></span>
                            <input type="text" name="username" class="form-control border-start-0 ps-0" placeholder="Buat username (tanpa spasi)" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div>
                        <label class="form-label text-dark fw-semibold mb-1">Nomor WhatsApp</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-whatsapp text-muted"></i></span>
                            <input type="text" name="phone" class="form-control border-start-0 ps-0" placeholder="Contoh: 08123456789">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div>
                        <label class="form-label text-dark fw-semibold mb-1">Mata Pelajaran <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-book text-muted"></i></span>
                            <input type="text" name="subject" class="form-control border-start-0 ps-0" placeholder="Contoh: Matematika" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div>
                        <label class="form-label text-dark fw-semibold mb-1">Peran / Tugas Tambahan <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-diagram-3 text-muted"></i></span>
                            <select name="role_tambahan" class="form-select border-start-0 ps-0" required>
                                <option value="" disabled selected>-- Pilih Peran Guru --</option>
                                <option value="Guru Mapel">Guru Mata Pelajaran</option>
                                <option value="Wali Kelas">Wali Kelas</option>
                                <option value="Guru BK">Guru Bimbingan Konseling (BK)</option>
                            </select>
                        </div>
                        <small class="text-muted mt-1 d-block"><i class="bi bi-info-circle me-1"></i>Tugas tambahan mempengaruhi hak akses guru pada sistem.</small>
                    </div>
                </div>
            </div>

            <hr class="mt-4 mb-4 text-muted">

            <div class="d-flex justify-content-end gap-2">
                <button type="reset" class="btn btn-light border shadow-sm">Batal</button>
                <button type="submit" class="btn btn-primary px-4 shadow-sm fw-semibold">
                    <i class="bi bi-save me-1"></i> Simpan Data Guru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
