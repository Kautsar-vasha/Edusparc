@extends('layouts.app')

@section('content')
<style>
    .glass-card {
        background: #ffffff;
        border-radius: 15px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .profile-icon {
        width: 100px;
        height: 100px;
        font-size: 3rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
</style>

<div class="container-fluid py-2">
    <div class="d-flex align-items-center mb-4">
        <a href="/guru" class="btn btn-light border shadow-sm rounded-circle me-3" title="Kembali">
            <i class="bi bi-arrow-left"></i>
        </a>
        <div>
            <h3 class="fw-bold text-dark mb-0">Detail Data Guru</h3>
            <p class="text-muted mb-0">Informasi lengkap profil tenaga pendidik EDUSPARC</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="glass-card p-4 text-center h-100 border-top border-4 border-primary">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle profile-icon mb-3 shadow-sm">
                    <i class="bi bi-person-fill"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ $teacher->name }}</h4>
                <p class="text-muted small mb-3">NIP: {{ $teacher->nip }}</p>

                <div class="mb-3">
                    @if($teacher->role_tambahan == 'Guru BK')
                        <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm">{{ $teacher->role_tambahan }}</span>
                    @elseif($teacher->role_tambahan == 'Wali Kelas')
                        <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">{{ $teacher->role_tambahan }}</span>
                    @else
                        <span class="badge bg-primary px-3 py-2 rounded-pill shadow-sm">{{ $teacher->role_tambahan }}</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold mb-4 border-bottom pb-3"><i class="bi bi-info-circle text-primary me-2"></i>Informasi Akun & Kontak</h5>

                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold mb-1">Nama Lengkap & Gelar</label>
                        <p class="fw-bold text-dark fs-6 mb-0">{{ $teacher->name }}</p>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold mb-1">Nomor Induk Pegawai (NIP)</label>
                        <p class="fw-bold text-dark fs-6 mb-0">{{ $teacher->nip }}</p>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold mb-1">Mata Pelajaran</label>
                        <p class="fw-bold text-dark fs-6 mb-0">{{ $teacher->subject }}</p>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold mb-1">Nomor WhatsApp</label>
                        <p class="fw-bold mb-0">
                            @if($teacher->phone)
                                <a href="https://wa.me/{{ preg_replace('/^0/', '62', $teacher->phone) }}" target="_blank" class="text-decoration-none text-success">
                                    <i class="bi bi-whatsapp me-1"></i> {{ $teacher->phone }}
                                </a>
                            @else
                                <span class="text-muted fst-italic">Belum ditambahkan</span>
                            @endif
                        </p>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold mb-1">Username Login</label>
                        <div><code class="text-primary bg-light px-2 py-1 rounded border">{{ $teacher->username }}</code></div>
                    </div>

                    <div class="col-md-6">
                        <label class="text-muted small fw-semibold mb-1">Tanggal Terdaftar</label>
                        <p class="fw-bold text-dark mb-0">
                            {{ $teacher->created_at ? $teacher->created_at->format('d M Y, H:i') : '-' }} WIB
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
