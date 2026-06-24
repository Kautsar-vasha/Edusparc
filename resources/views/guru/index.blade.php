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
            <h3 class="fw-bold text-dark mb-0">
                <i class="bi bi-person-badge text-primary me-2"></i>Manajemen Data Guru
            </h3>
            <p class="text-muted mb-0 mt-1">Kelola data tenaga pendidik EDUSPARC</p>
        </div>

        {{-- Tombol Tambah & Export hanya muncul untuk Admin --}}
        @if(session('role') === 'admin')
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <button type="button" class="btn btn-outline-success shadow-sm rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#modalImportGuru">
                <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
            </button>
            <a href="/guru/create" class="btn btn-primary shadow-sm rounded-pill px-4">
                <i class="bi bi-plus-circle me-1"></i> Tambah Guru
            </a>
        </div>
        @endif
    </div>

    {{-- Alert Sukses --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Alert Error --}}
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert">
        <i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="glass-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-list-ul me-2 text-primary"></i>Daftar Guru Aktif
            </h5>
            <div class="input-group" style="max-width: 250px;">
                <input type="text" class="form-control form-control-sm" placeholder="Cari nama guru...">
                <button class="btn btn-outline-secondary btn-sm" type="button">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted">
                    <tr>
                        <th width="5%" class="text-center rounded-start">No</th>
                        <th>NIP/NUPTK</th>
                        <th>Nama Guru</th>
                        <th>Peran</th>
                        <th>Mapel</th>
                        <th width="12%" class="text-center rounded-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $key => $t)
                    <tr>
                        <td class="text-center fw-bold text-muted">{{ $key + 1 }}</td>
                        <td><code class="text-dark bg-light px-2 py-1 rounded">{{ $t->nip }}</code></td>
                        <td class="fw-semibold text-dark">{{ $t->name }}</td>
                        <td>
                            @if($t->role_tambahan == 'Guru BK')
                                <span class="badge bg-danger">{{ $t->role_tambahan }}</span>
                            @elseif($t->role_tambahan == 'Wali Kelas')
                                <span class="badge bg-warning text-dark">{{ $t->role_tambahan }}</span>
                            @else
                                <span class="badge bg-primary">{{ $t->role_tambahan }}</span>
                            @endif
                        </td>
                        <td>{{ $t->subject }}</td>
                        <td class="text-center">
                            {{-- Tombol Detail (semua role bisa lihat) --}}
                            <a href="/guru/{{ $t->id }}"
                               class="btn btn-sm btn-outline-info rounded-circle shadow-sm"
                               title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>

                            {{-- Tombol Hapus hanya untuk Admin --}}
                            @if(session('role') === 'admin')
                            <form action="/guru/{{ $t->id }}" method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus data guru {{ $t->name }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-outline-danger rounded-circle shadow-sm"
                                        title="Hapus Guru">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-4 d-block mb-1"></i>
                            Belum ada data guru.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalImportGuru" tabindex="-1" aria-labelledby="modalImportGuruLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-success text-white border-0">
        <h5 class="modal-title fw-bold" id="modalImportGuruLabel"><i class="bi bi-file-earmark-excel me-2"></i>Impor Data Guru</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="/guru/import" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body p-4 bg-light">
              <div class="alert alert-info border-0 shadow-sm small">
                  <i class="bi bi-info-circle-fill me-1"></i>
                  <strong>Aturan Format Excel:</strong><br>
                  Baris pertama (Header) wajib berisi:
                  <code class="d-block mt-2 bg-white p-2 rounded text-dark text-center">nip | nama | username | mapel | peran | no_hp | password</code>
                  <div class="mt-2 text-muted">
                      * <b>peran</b> diisi: Guru Mapel, Wali Kelas, atau Guru BK.<br>
                      * <b>password</b> opsional (jika kosong, otomatis jadi 12345).
                  </div>
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
