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
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-0">
                <i class="bi bi-people text-success me-2"></i>Manajemen Data Siswa
            </h3>
            <p class="text-muted mb-0 mt-1">Kelola data peserta didik EDUSPARC</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-outline-success shadow-sm rounded-pill px-4 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalImport">
                <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
            </button>

            <form action="/siswa/hapus-lulus" method="POST" onsubmit="return confirm('PERINGATAN KERAS! Seluruh data siswa berstatus Lulus beserta riwayat karakternya akan dihapus PERMANEN dan tidak dapat dikembalikan. Lanjutkan?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger shadow-sm rounded-pill px-4 fw-semibold">
                    <i class="bi bi-trash3-fill me-1"></i> Bersihkan Data Lulus
                </button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $errors->first() ?: 'Gagal! Pastikan form diisi lengkap dan NISN belum terdaftar.' }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="glass-card p-4 mb-4 border-top border-4 border-success">
        <h5 class="fw-bold mb-3"><i class="bi bi-person-plus text-success me-2"></i>Tambah Siswa</h5>

        <form action="/siswa" method="POST">
            @csrf
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label text-muted small fw-semibold mb-1">NISN <span class="text-danger">*</span></label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-upc-scan text-muted"></i></span>
                        <input type="text" name="nisn" class="form-control border-start-0 ps-0" placeholder="Masukkan NISN" required>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label text-muted small fw-semibold mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-person text-muted"></i></span>
                        <input type="text" name="name" class="form-control border-start-0 ps-0" placeholder="Nama siswa" required>
                    </div>
                </div>
                <div class="col-12 col-md-2">
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
                                <option value="" disabled>Belum ada kelas</option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label text-muted small fw-semibold mb-1">Tgl Lahir <span class="text-danger">*</span></label>
                    <div class="input-group shadow-sm">
                        <input type="date" name="birth_date" class="form-control" required>
                    </div>
                </div>
                <div class="col-12 col-md-2">
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
            <table class="table table-hover align-middle min-w-600 mb-0">
                <thead class="table-light text-muted">
                    <tr>
                        <th width="5%" class="text-center rounded-start">No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th class="text-center">Total Poin</th>
                        <th width="15%" class="text-center rounded-end">Aksi</th>
                    </tr>
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
                                <span class="badge bg-success rounded-pill px-3 shadow-sm">+{{ $s->total_points }} Poin</span>
                            @elseif($s->total_points < 0)
                                <span class="badge bg-danger rounded-pill px-3 shadow-sm">{{ $s->total_points }} Poin</span>
                            @else
                                <span class="badge bg-secondary rounded-pill px-3 shadow-sm">0 Poin</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="/siswa/{{ $s->id }}/qr" target="_blank" class="btn btn-sm btn-outline-dark shadow-sm" title="Cetak QR Code">
                                    <i class="bi bi-qr-code"></i>
                                </a>
                                <a href="/siswa/{{ $s->id }}/edit" class="btn btn-sm btn-outline-primary shadow-sm" title="Edit Siswa">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="/siswa/{{ $s->id }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data {{ $s->name }}? Data poin akan ikut terhapus.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger shadow-sm" title="Hapus Siswa">
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
                            Belum ada data siswa. Silakan tambah manual atau Impor Excel.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-success text-white border-0">
        <h5 class="modal-title fw-bold" id="modalImportLabel"><i class="bi bi-file-earmark-excel me-2"></i>Impor Data Siswa</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="/siswa/import" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body p-4 bg-light">
              <div class="alert alert-info border-0 shadow-sm small">
                  <i class="bi bi-info-circle-fill me-1"></i>
                  <strong>Aturan Format Excel:</strong><br>
                  Pastikan baris pertama (Header) Excel kamu memuat teks berikut secara persis:
                  <code class="d-block mt-2 bg-white p-2 rounded text-dark text-center">nisn | nama | kelas | tanggal_lahir</code>
                  <div class="mt-2 text-muted">*Format Tanggal Lahir: YYYY-MM-DD (Contoh: 2010-12-30)</div>
              </div>

              <div class="mb-3">
                  <label class="form-label fw-bold">Pilih File Excel (.xlsx / .csv)</label>
                  <input class="form-control" type="file" name="file" accept=".xlsx, .xls, .csv" required>
              </div>
          </div>
          <div class="modal-footer border-0 bg-white">
            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success fw-semibold"><i class="bi bi-cloud-upload me-1"></i> Mulai Impor</button>
          </div>
      </form>
    </div>
  </div>
</div>
@endsection
