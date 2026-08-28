@extends('layouts.app')

@section('content')
<style>
    .glass-card {
        background: #ffffff;
        border-radius: 15px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    /* Memastikan input pencarian full width di mobile, tapi terbatas di desktop */
    .search-box {
        width: 100%;
    }
    @media (min-width: 768px) {
        .search-box {
            width: 250px;
        }
    }
</style>

<div class="container-fluid py-2">
    <!-- Header Section: Stack di mobile (flex-column), sejajar di desktop (flex-md-row) -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-0">
                <i class="bi bi-person-badge text-primary me-2"></i>Manajemen Data Guru
            </h3>
            <p class="text-muted mb-0 mt-1">Kelola data tenaga pendidik EDUSPARC</p>
        </div>

        {{-- Tombol Tambah & Export hanya muncul untuk Admin --}}
        @if(session('role') === 'admin')
        <div class="d-flex flex-column flex-sm-row gap-2 w-100 justify-content-md-end" style="max-width: 400px;">
            <button type="button" class="btn btn-outline-success shadow-sm rounded-pill px-4 flex-fill" data-bs-toggle="modal" data-bs-target="#modalImportGuru">
                <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
            </button>
            <a href="/guru/create" class="btn btn-primary shadow-sm rounded-pill px-4 flex-fill text-center">
                <i class="bi bi-plus-circle me-1"></i> Tambah Guru
            </a>
        </div>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="glass-card p-3 p-md-4">
        <!-- Toolbar Card: Stack di mobile -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-3">
            <h5 class="fw-bold text-dark m-0"><i class="bi bi-list-ul text-primary me-2"></i>Daftar Guru Aktif</h5>
            <div class="input-group search-box">
                <input type="text" class="form-control bg-light border-secondary" placeholder="Cari nama guru...">
                <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
            </div>
        </div>

        <div class="table-responsive">
            <!-- Tambahkan text-nowrap agar teks tidak terlipat berantakan di layar kecil -->
            <table class="table table-hover align-middle mb-0 text-nowrap">
                <thead class="table-light text-secondary">
                    <tr>
                        <th class="text-center rounded-start">No</th>
                        <th>NIP/NUPTK</th>
                        <th>Nama Guru</th>
                        <th>Mata Pelajaran</th>
                        <th>Tugas Tambahan / Peran</th>
                        <th class="text-center rounded-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $key => $t)
                    <tr>
                        <td class="text-center fw-semibold text-muted">{{ $key + 1 }}</td>
                        <td class="text-muted"><i class="bi bi-credit-card-2-front me-1"></i>{{ $t->nip }}</td>
                        <td class="fw-bold text-dark">{{ $t->name }}</td>
                        <td><span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3">{{ $t->subject }}</span></td>
                        <td>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary rounded-pill px-3">
                                {{ $t->role_tambahan ?? 'Guru Mapel' }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- Tombol Detail --}}
                                <a href="/guru/{{ $t->id }}" class="btn btn-sm btn-outline-info rounded-circle shadow-sm" title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>

                                {{-- Tombol Edit & Hapus hanya untuk Admin --}}
                                @if(session('role') === 'admin')
                                <a href="/guru/{{ $t->id }}/edit" class="btn btn-sm btn-outline-warning rounded-circle shadow-sm" title="Edit Guru">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="/guru/{{ $t->id }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data guru {{ $t->name }}?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle shadow-sm" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                            Belum ada data guru yang ditambahkan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalImportGuru" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-success text-white border-0">
        <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-excel me-2"></i>Impor Data Guru</h5>
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
                      * <b>peran</b> diisi bebas sebagai jabatan profil (contoh: Guru Mapel, Wali Kelas VII A, Guru BK).<br>
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
