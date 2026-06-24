@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h3 class="fw-bold text-dark mb-0">
                <i class="bi bi-book-half text-primary me-2"></i>Master Tata Tertib
            </h3>
            <p class="text-muted mb-0 mt-1">Kelola data pelanggaran, penghargaan, poin, dan sanksi.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn btn-outline-success shadow-sm rounded-pill px-4 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalImportTatib">
                <i class="bi bi-file-earmark-excel me-1"></i> Import CSV
            </button>
            <button type="button" class="btn btn-primary shadow-sm rounded-pill px-4 fw-semibold" data-bs-toggle="modal" data-bs-target="#modalTambah">
                <i class="bi bi-plus-lg me-1"></i> Tambah Aturan Baru
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger shadow-sm border-0"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terdapat kesalahan input! Pastikan form diisi dengan benar.</div>
    @endif

    <div class="card shadow-sm border-0 p-4 rounded-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Kategori</th>
                        <th style="width: 35%">Uraian Aturan</th>
                        <th>Poin</th>
                        <th style="width: 25%">Sanksi / Pembinaan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tatibs as $t)
                    <tr>
                        <td><span class="badge bg-dark">{{ $t->kode }}</span></td>
                        <td>
                            @if($t->jenis == 'negatif')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill">{{ $t->kategori }} (Pelanggaran)</span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success border border-success rounded-pill">{{ $t->kategori }} (Penghargaan)</span>
                            @endif
                        </td>
                        <td class="fw-semibold text-dark">{{ $t->uraian }}</td>
                        <td class="fw-bold fs-5 {{ $t->jenis == 'negatif' ? 'text-danger' : 'text-success' }}">
                            {{ $t->jenis == 'negatif' ? '-' : '+' }}{{ $t->poin }}
                        </td>
                        <td class="text-muted small">{{ $t->sanksi ?? '-' }}</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-sm btn-outline-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $t->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <form action="/tatib/{{ $t->id }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus aturan ini?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger shadow-sm"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalEdit{{ $t->id }}" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <form action="/tatib/{{ $t->id }}" method="POST" class="modal-content border-0 shadow">
                                @csrf @method('PUT')
                                <div class="modal-header bg-light border-0"><h5 class="fw-bold">Edit Aturan</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
                                <div class="modal-body p-4 row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold">Kode</label>
                                        <input type="text" name="kode" class="form-control" value="{{ $t->kode }}" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold">Jenis</label>
                                        <select name="jenis" class="form-select" required>
                                            <option value="negatif" {{ $t->jenis == 'negatif' ? 'selected' : '' }}>Pelanggaran (-)</option>
                                            <option value="positif" {{ $t->jenis == 'positif' ? 'selected' : '' }}>Penghargaan (+)</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold">Kategori</label>
                                        <select name="kategori" class="form-select" required>
                                            <option value="Spiritual" {{ $t->kategori == 'Spiritual' ? 'selected' : '' }}>Spiritual</option>
                                            <option value="Sosial" {{ $t->kategori == 'Sosial' ? 'selected' : '' }}>Sosial</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-bold">Poin</label>
                                        <input type="number" name="poin" class="form-control" value="{{ $t->poin }}" min="1" required>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label small fw-bold">Uraian / Penjelasan Aktivitas</label>
                                        <textarea name="uraian" class="form-control" rows="2" required>{{ $t->uraian }}</textarea>
                                    </div>
                                    <div class="col-md-12">
                                        <label class="form-label small fw-bold">Sanksi / Pembinaan (Opsional)</label>
                                        <input type="text" name="sanksi" class="form-control" value="{{ $t->sanksi }}">
                                    </div>
                                </div>
                                <div class="modal-footer border-0"><button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Perubahan</button></div>
                            </form>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="6" class="text-center py-4 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2"></i>Belum ada data tata tertib.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form action="/tatib" method="POST" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header bg-primary text-white border-0"><h5 class="fw-bold"><i class="bi bi-plus-circle me-2"></i>Tambah Aturan Baru</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
            <div class="modal-body p-4 row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Kode</label>
                    <input type="text" name="kode" class="form-control" placeholder="Cth: a.1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Jenis</label>
                    <select name="jenis" class="form-select" required>
                        <option value="negatif">Pelanggaran (-)</option>
                        <option value="positif">Penghargaan (+)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Kategori</label>
                    <select name="kategori" class="form-select" required>
                        <option value="Spiritual">Spiritual</option>
                        <option value="Sosial">Sosial</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Poin</label>
                    <input type="number" name="poin" class="form-control" placeholder="Cth: 5" min="1" required>
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-bold">Uraian / Penjelasan Aktivitas</label>
                    <textarea name="uraian" class="form-control" rows="2" placeholder="Tuliskan detail pelanggaran atau penghargaan..." required></textarea>
                </div>
                <div class="col-md-12">
                    <label class="form-label small fw-bold">Sanksi / Pembinaan (Opsional)</label>
                    <input type="text" name="sanksi" class="form-control" placeholder="Cth: Teguran tertulis / Pemanggilan orang tua">
                </div>
            </div>
            <div class="modal-footer border-0 bg-light"><button type="submit" class="btn btn-primary px-4 fw-bold">Simpan Aturan</button></div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalImportTatib" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-success text-white border-0">
        <h5 class="modal-title fw-bold"><i class="bi bi-file-earmark-excel me-2"></i>Impor Data Tata Tertib</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form action="/tatib/import" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body p-4 bg-light">
              <div class="alert alert-info border-0 shadow-sm small">
                  <i class="bi bi-info-circle-fill me-1"></i>
                  <strong>Aturan Format Excel:</strong><br>
                  Buatlah tabel di Excel dengan urutan 6 kolom persis seperti ini (tanpa koma di dalam teks):
                  <code class="d-block mt-2 bg-white p-2 rounded text-dark text-center">kode | jenis | kategori | uraian | poin | sanksi</code>
                  <div class="mt-2 text-muted fw-bold text-danger">PENTING: Saat menyimpan di Excel, pilih File -> Save As -> Format: CSV (Comma delimited).</div>
              </div>

              <div class="mb-3">
                  <label class="form-label fw-bold">Pilih File CSV (.csv)</label>
                  <input class="form-control" type="file" name="file" accept=".csv" required>
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
