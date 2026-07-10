@extends('layouts.app')

@section('content')
<style>
    .glass-card {
        background: #ffffff;
        border-radius: 15px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
</style>

<div class="container-fluid py-2">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <a href="/kelas" class="text-decoration-none text-muted mb-2 d-inline-block">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar Kelas
            </a>
            <h3 class="fw-bold text-dark mb-0">
                <i class="bi bi-people text-primary me-2"></i>Daftar Siswa Kelas {{ $kelas->nama_kelas }}
            </h3>
        </div>
    </div>

    <div class="glass-card p-4">
        <h5 class="fw-bold mb-4"><i class="bi bi-list-ul text-primary me-2"></i>Data Siswa</h5>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted">
                    <tr>
                        <th width="5%" class="text-center rounded-start">No</th>
                        <th width="20%">NISN</th>
                        <th width="45%">Nama Lengkap</th>
                        <th width="15%" class="text-center">Total Poin</th>
                        <th width="15%" class="text-center rounded-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $s)
                    <tr>
                        <td class="text-center text-muted fw-bold">{{ $index + 1 }}</td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border">
                                <i class="bi bi-credit-card-2-front me-1"></i>{{ $s->nisn }}
                            </span>
                        </td>
                        <td class="fw-bold text-dark">{{ $s->name }}</td>
                        <td class="text-center">
                            <span class="badge {{ $s->total_points >= 0 ? 'bg-success' : 'bg-danger' }} rounded-pill px-3 py-2">
                                {{ $s->total_points > 0 ? '+' : '' }}{{ $s->total_points }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="/siswa/{{ $s->id }}" class="btn btn-sm btn-outline-info rounded-circle shadow-sm" title="Lihat Profil & Riwayat Siswa">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-5">
                            <i class="bi bi-person-x display-4 d-block mb-3 text-secondary"></i>
                            Belum ada data siswa yang terdaftar di kelas <b>{{ $kelas->nama_kelas }}</b>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
