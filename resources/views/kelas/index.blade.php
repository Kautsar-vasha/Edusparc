@extends('layouts.app')

@section('content')
<style>
    .glass-card {
        background: #ffffff;
        border-radius: 15px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
    /* Efek hover untuk link nama kelas */
    .link-kelas:hover .badge {
        background-color: #0d6efd !important;
        color: #ffffff !important;
        transition: 0.3s;
    }
</style>

<div class="container-fluid py-2">
    <div class="mb-4">
        <h3 class="fw-bold text-dark mb-0">
            <i class="bi bi-door-open text-primary me-2"></i>Manajemen Kelas
        </h3>
        <p class="text-muted mb-0 mt-1">Kelola daftar kelas EDUSPARC SMPN 4 Jember</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        {{-- Form Tambah Kelas HANYA tampil untuk Admin --}}
        @if(session('role') === 'admin')
        <div class="col-md-4 mb-4">
            <div class="glass-card p-4 border-top border-4 border-primary">
                <h5 class="fw-bold mb-3"><i class="bi bi-plus-circle text-primary me-2"></i>Tambah Kelas</h5>
                <form action="/kelas" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label text-muted small fw-semibold">Nama Kelas <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kelas" class="form-control" placeholder="Contoh: VII A, VIII B, IX C" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-semibold shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan Kelas
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Tabel Kelas melebar penuh (col-12) jika Guru, namun col-8 jika Admin --}}
        <div class="{{ session('role') === 'admin' ? 'col-md-8' : 'col-md-12' }}">
            <div class="glass-card p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-list-ul text-primary me-2"></i>Daftar Kelas</h5>

                @if(session('role') === 'guru')
                    <p class="text-muted small mb-3"><i class="bi bi-info-circle text-info me-1"></i>Klik pada nama kelas untuk melihat daftar siswa di dalamnya.</p>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted">
                            <tr>
                                <th width="10%" class="text-center rounded-start">No</th>
                                <th>Nama Kelas</th>

                                {{-- Kolom Aksi HANYA tampil untuk Admin --}}
                                @if(session('role') === 'admin')
                                <th width="25%" class="text-center rounded-end">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($data_kelas as $key => $k)
                            <tr>
                                <td class="text-center fw-bold text-muted">{{ $key+1 }}</td>
                                <td>
                                    {{-- Nama Kelas sekarang menjadi link yang bisa diklik --}}
                                    <a href="/kelas/{{ $k->id }}" class="text-decoration-none link-kelas">
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill px-3 py-2 fs-6">
                                            <i class="bi bi-people me-1"></i> {{ $k->nama_kelas }}
                                        </span>
                                    </a>
                                </td>

                                {{-- Tombol Aksi HANYA tampil untuk Admin --}}
                                @if(session('role') === 'admin')
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $k->id }}" title="Edit Kelas">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>

                                        <form action="/kelas/{{ $k->id }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus kelas {{ $k->nama_kelas }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Kelas">
                                                <i class="bi bi-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>

                            {{-- Modal Edit HANYA dirender jika Admin --}}
                            @if(session('role') === 'admin')
                            <div class="modal fade" id="editModal{{ $k->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header bg-light border-bottom-0">
                                            <h5 class="modal-title fw-bold">Edit Kelas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <form action="/kelas/{{ $k->id }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body p-4">
                                                <div class="mb-3">
                                                    <label class="form-label text-muted small fw-semibold">Nama Kelas</label>
                                                    <input type="text" name="nama_kelas" class="form-control" value="{{ $k->nama_kelas }}" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer border-top-0">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary px-4">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif

                            @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                    Belum ada data kelas yang ditambahkan.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
