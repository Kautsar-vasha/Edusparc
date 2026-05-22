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
        border-color: #198754;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.15);
    }
</style>

<div class="container-fluid py-2">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">
                <i class="bi bi-people text-success me-2"></i>Manajemen Data Siswa
            </h3>
            <p class="text-muted mb-0 mt-1">Kelola data peserta didik EDUSPARC</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="/siswa/export-pdf?class={{ request('class') }}" class="btn btn-outline-danger shadow-sm rounded-pill px-4">
                <i class="bi bi-file-earmark-pdf me-1"></i> Export PDF
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Gagal menyimpan! Pastikan form diisi lengkap dan NISN belum pernah terdaftar.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="glass-card p-4 mb-4 border-top border-4 border-success">
        <h5 class="fw-bold mb-3"><i class="bi bi-person-plus text-success me-2"></i>Tambah Siswa Baru</h5>

        <form action="/siswa" method="POST">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-semibold mb-1">NISN <span class="text-danger">*</span></label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-upc-scan text-muted"></i></span>
                        <input type="text" name="nisn" class="form-control border-start-0 ps-0" placeholder="Masukkan NISN" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-muted small fw-semibold mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                        <input type="text" name="name" class="form-control border-start-0 ps-0" placeholder="Nama siswa" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small fw-semibold mb-1">Kelas <span class="text-danger">*</span></label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-door-open text-muted"></i></span>
                        <select name="class" class="form-select border-start-0 ps-0" required>
                            <option value="" disabled selected>Pilih...</option>
                            @if(isset($data_kelas) && count($data_kelas) > 0)
                                @foreach($data_kelas as $k)
                                    <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
                                @endforeach
                            @else
                                <option value="" disabled>Belum ada data kelas</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-md-2">
                    <label class="form-label text-muted small fw-semibold mb-1">Tanggal Lahir <span class="text-danger">*</span></label>
                    <div class="input-group shadow-sm">
                        <input type="date" name="birth_date" class="form-control" required>
                    </div>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100 shadow-sm fw-semibold">
                        <i class="bi bi-save me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="glass-card p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-list-ul me-2 text-success"></i>Daftar Siswa</h5>

            <form action="/siswa" method="GET" class="d-flex gap-2">
                <div class="input-group shadow-sm" style="max-width: 250px;">
                    <span class="input-group-text bg-light"><i class="bi bi-funnel text-muted"></i></span>
                    <select name="class" class="form-select" onchange="this.form.submit()">
                        <option value="">Semua Kelas</option>
                        @if(isset($data_kelas))
                            @foreach($data_kelas as $k)
                                <option value="{{ $k->nama_kelas }}" {{ request('class') == $k->nama_kelas ? 'selected' : '' }}>
                                    Kelas {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </form>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted">
                    <tr>
                        <th width="5%" class="text-center rounded-start">No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th class="text-center">Total Poin</th>
                        <th width="15%" class="text-center rounded-end">Aksi</th> </tr>
                </thead>
                <tbody>
                    @forelse($students as $key => $s)
                    <tr>
                        <td class="text-center fw-bold text-muted">{{ $key+1 }}</td>
                        <td><code class="text-dark bg-light px-2 py-1 rounded">{{ $s->nisn }}</code></td>
                        <td class="fw-semibold text-dark">{{ $s->name }}</td>
                        <td>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill px-3 py-1">
                                {{ $s->class }}
                            </span>
                        </td>
                        <td class="text-center">
                            @if($s->total_points > 0)
                                <span class="badge bg-danger rounded-pill px-3 shadow-sm">{{ $s->total_points }} Poin</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 shadow-sm">0 Poin</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group" role="group">
                                <a href="/siswa/{{ $s->id }}/edit" class="btn btn-sm btn-outline-primary" title="Edit Siswa">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="/siswa/{{ $s->id }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data {{ $s->name }}? Data poin pelanggaran siswa ini mungkin juga akan terhapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Siswa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                            Data siswa tidak ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
