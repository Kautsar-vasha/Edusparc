@extends('layouts.app')

@section('content')
<style>
    .glass-card { background: #ffffff; border-radius: 15px; border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    .form-control:focus { border-color: #ffc107; box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25); }
</style>

<div class="container-fluid py-2">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <a href="/guru" class="text-decoration-none text-muted mb-2 d-inline-block">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Guru
            </a>
            <h3 class="fw-bold text-dark mb-0">
                <i class="bi bi-pencil-square text-warning me-2"></i>Edit Data Guru
            </h3>
        </div>
    </div>

    <div class="glass-card p-4 border-top border-4 border-warning mx-auto" style="max-width: 800px;">
        <p class="text-muted mb-4">Perbarui informasi data tenaga pendidik di bawah ini.</p>

        <form action="/guru/{{ $teacher->id }}" method="POST">
            @csrf
            @method('PUT') <div class="row g-4">
                <div class="col-md-6">
                    <div>
                        <label class="form-label text-dark fw-semibold mb-1">NIP / NUPTK <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-upc-scan text-muted"></i></span>
                            <input type="text" name="nip" class="form-control border-start-0 ps-0" value="{{ $teacher->nip }}" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div>
                        <label class="form-label text-dark fw-semibold mb-1">Nama Lengkap (Berta Gelar) <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                            <input type="text" name="name" class="form-control border-start-0 ps-0" value="{{ $teacher->name }}" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div>
                        <label class="form-label text-dark fw-semibold mb-1">Username Login <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-person-badge text-muted"></i></span>
                            <input type="text" name="username" class="form-control border-start-0 ps-0" value="{{ $teacher->username }}" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div>
                        <label class="form-label text-dark fw-semibold mb-1">Nomor WhatsApp</label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-whatsapp text-muted"></i></span>
                            <input type="text" name="phone" class="form-control border-start-0 ps-0" value="{{ $teacher->phone }}">
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div>
                        <label class="form-label text-dark fw-semibold mb-1">Mata Pelajaran <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-book text-muted"></i></span>
                            <input type="text" name="subject" class="form-control border-start-0 ps-0" value="{{ $teacher->subject }}" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div>
                        <label class="form-label text-dark fw-semibold mb-1">Peran / Tugas Tambahan <span class="text-danger">*</span></label>
                        <div class="input-group shadow-sm">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-diagram-3 text-muted"></i></span>
                            <input type="text" name="role_tambahan" class="form-control border-start-0 ps-0" value="{{ $teacher->role_tambahan }}" required>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mt-4 mb-4 text-muted">

            <div class="d-flex justify-content-end gap-2">
                <a href="/guru" class="btn btn-light border shadow-sm">Batal</a>
                <button type="submit" class="btn btn-warning px-4 shadow-sm fw-semibold">
                    <i class="bi bi-save me-1"></i> Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
