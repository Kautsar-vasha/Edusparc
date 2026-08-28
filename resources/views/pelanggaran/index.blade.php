@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<style>
    /* Penyesuaian khusus tampilan mobile */
    @media (max-width: 767.98px) {
        .card {
            padding: 1.25rem !important;
        }
        h3 {
            font-size: 1.5rem;
        }
        .btn-filter-group {
            flex-direction: column;
        }
        .btn-filter-group .btn {
            width: 100%;
        }
        /* Penyesuaian lebar alert di mobile */
        .animated-alert {
            min-width: 90vw !important;
            margin-right: 5vw !important;
        }
    }

    /* Memaksa tabel agar bisa digeser (scroll horizontal) di mobile */
    .table-custom-responsive th,
    .table-custom-responsive td {
        white-space: normal;
        word-break: break-word;
    }

    /* =========================================
       KUSTOMISASI ANIMASI ALERT NOTIFIKASI
       ========================================= */
    @keyframes slideInRight {
        0% { transform: translateX(110%); opacity: 0; }
        100% { transform: translateX(0); opacity: 1; }
    }

    @keyframes slideOutRight {
        0% { transform: translateX(0); opacity: 1; }
        100% { transform: translateX(110%); opacity: 0; }
    }

    @keyframes progressShrink {
        0% { width: 100%; }
        100% { width: 0%; }
    }

    .animated-alert {
        animation: slideInRight 0.5s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
        min-width: 320px;
        position: relative;
        overflow: hidden; /* Agar progress bar tidak keluar dari radius border */
        border-radius: 8px;
    }

    .animated-alert.hide-alert {
        animation: slideOutRight 0.5s cubic-bezier(0.25, 0.8, 0.25, 1) forwards;
    }

    .alert-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 4px;
        width: 100%;
        /* Durasi ini disesuaikan dengan setTimeout di javascript (4 detik) */
        animation: progressShrink 4s linear forwards;
    }
</style>

<div class="container-fluid py-2">
    <!-- WADAH NOTIFIKASI FLOATING (TOAST) -->
    <div class="alert-container position-fixed top-0 end-0 p-3 mt-5" style="z-index: 1055;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show animated-alert border-0 shadow-lg bg-white" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-check-circle-fill fs-3 text-success me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-0 text-success">Berhasil!</h6>
                        <span class="small text-muted">{{ session('success') }}</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <div class="alert-progress bg-success"></div>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show animated-alert border-0 shadow-lg bg-white" role="alert">
                <div class="d-flex align-items-center">
                    <i class="bi bi-exclamation-triangle-fill fs-3 text-danger me-3"></i>
                    <div>
                        <h6 class="fw-bold mb-0 text-danger">Upload Gagal!</h6>
                        <span class="small text-muted">Pastikan gambar maks 2MB (JPG/PNG).</span>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <div class="alert-progress bg-danger"></div>
            </div>
        @endif
    </div>
    <!-- AKHIR WADAH NOTIFIKASI -->

    <h3 class="mb-4 fw-bold">Input Pencatatan Karakter Siswa</h3>

    <div class="card shadow-sm p-4 mb-4 border-0">
        <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Form Tambah Catatan</h5>

        <form action="/pelanggaran" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label fw-bold small">1. Pilih Kelas</label>
                    <select id="select-kelas" class="form-select select2-basic" required>
                        <option value="" disabled selected>-- Kelas --</option>
                        @foreach($data_kelas as $k)
                            <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label fw-bold small">2. Pilih Siswa</label>
                    <select name="student_id" id="select-siswa" class="form-select select2-basic" required disabled>
                        <option value="" disabled selected>-- Pilih Kelas Dulu --</option>
                    </select>
                </div>

                <div class="col-12 col-md-6">
                    <label class="form-label fw-bold small text-primary">3. Pilih Aturan / Aktivitas</label>
                    <select name="type" id="select-tatib" class="form-select border-primary" required>
                        <option value="" disabled selected>-- Ketik Mencari Aturan... --</option>
                        @if(isset($data_tatib) && count($data_tatib) > 0)
                            @foreach($data_tatib as $t)
                                <option value="{{ $t->uraian }}" data-jenis="{{ $t->jenis }}" data-kategori="{{ $t->kategori }}" data-poin="{{ $t->poin }}">
                                    [{{ $t->kode }}] {{ \Illuminate\Support\Str::limit($t->uraian, 75) }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-12 col-sm-4 col-md-4">
                    <label class="form-label fw-bold small text-muted">Jenis</label>
                    <input type="text" name="jenis_poin" id="input-jenis" class="form-control bg-light text-center fw-bold text-muted" readonly required>
                </div>
                <div class="col-12 col-sm-4 col-md-4">
                    <label class="form-label fw-bold small text-muted">Kategori</label>
                    <input type="text" name="category" id="input-kategori" class="form-control bg-light text-center fw-bold text-muted" readonly required>
                </div>
                <div class="col-12 col-sm-4 col-md-4">
                    <label class="form-label fw-bold small text-muted">Poin</label>
                    <input type="number" name="points" id="input-poin" class="form-control bg-light text-center fw-bold text-muted" readonly required>
                </div>

                <div class="col-12 col-md-6 mt-4">
                    <label class="form-label fw-bold small">Keterangan / Kronologi Tambahan (Opsional)</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>

                <div class="col-12 col-md-6 mt-4">
                    <label class="form-label fw-bold small">Pesan / Motivasi Pembinaan (Opsional)</label>
                    <textarea name="motivation" class="form-control" rows="2"></textarea>
                </div>

                <div class="col-12 mt-3">
                    <label class="form-label fw-bold small text-primary"><i class="bi bi-camera-fill me-1"></i>Unggah Bukti Foto (Opsional)</label>
                    <input type="file" name="bukti_foto" class="form-control border-primary shadow-sm" accept="image/jpeg,image/png,image/jpg" style="max-width: 400px;">
                    <div class="form-text small">Lampirkan foto kejadian/piagam penghargaan (Maksimal 2MB. Format: JPG, JPEG, PNG).</div>
                </div>

                <div class="col-12 mt-4 text-end text-sm-start">
                    <button type="submit" class="btn btn-primary w-100 w-sm-auto px-4 fw-semibold shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan Data Karakter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- FILTER RIWAYAT -->
    <div class="card shadow-sm p-4 border-0 bg-white mb-4">
        <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-search me-2 text-secondary"></i>Filter & Cari Riwayat</h5>
        <form action="/pelanggaran" method="GET">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-bold text-muted">Cari Kelas</label>
                    <select name="kelas" class="form-select form-select-sm select2-basic">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($data_kelas as $k)
                            <option value="{{ $k->nama_kelas }}" {{ request('kelas') == $k->nama_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-bold text-muted">Jenis Poin</label>
                    <select name="jenis_poin" class="form-select form-select-sm">
                        <option value="">-- Semua --</option>
                        <option value="positif" {{ request('jenis_poin') == 'positif' ? 'selected' : '' }}>Kebaikan (+)</option>
                        <option value="negatif" {{ request('jenis_poin') == 'negatif' ? 'selected' : '' }}>Pelanggaran (-)</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-bold text-muted">Urutan</label>
                    <select name="sort_waktu" class="form-select form-select-sm">
                        <option value="terbaru" {{ request('sort_waktu') == 'terbaru' || !request('sort_waktu') ? 'selected' : '' }}>Terbaru</option>
                        <option value="terlama" {{ request('sort_waktu') == 'terlama' ? 'selected' : '' }}>Terlama</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3 d-flex gap-2 btn-filter-group">
                    <button type="submit" class="btn btn-sm btn-secondary flex-fill fw-semibold"><i class="bi bi-funnel"></i> Terapkan</button>
                    <a href="/pelanggaran" class="btn btn-sm btn-light border flex-fill text-center">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <!-- TABEL RIWAYAT -->
    <div class="card shadow-sm p-4 border-0">
        <h5 class="fw-bold mb-3 text-dark">Riwayat Catatan Karakter Terbaru</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle table-custom-responsive mb-0" style="min-width: 800px;">
                <thead class="table-light">
                    <tr>
                        <th style="min-width: 120px;">Waktu</th>
                        <th style="min-width: 160px;">Nama Siswa</th>
                        <th style="min-width: 250px;">Aktivitas & Bukti</th>
                        <th style="min-width: 90px;">Poin</th>
                        <th style="min-width: 120px;" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($violations as $v)
                    @php
                        $is_authorized = false;
                        if (session('role') === 'admin' || session('role') === 'super_admin') {
                            $is_authorized = true;
                        } elseif (session('role') === 'guru') {
                            $kelas_siswa = strtolower($v->student->class);
                            $peran = isset($peran_guru) ? $peran_guru : '';
                            if (str_contains($peran, 'bk') || str_contains($peran, $kelas_siswa)) $is_authorized = true;
                        }
                    @endphp

                    <tr>
                        <td class="small text-muted">{{ $v->created_at->format('d/m/y H:i') }}</td>
                        <td>
                            <b class="text-dark">{{ $v->student->name }}</b> <br>
                            <span class="badge bg-light text-secondary border">{{ $v->student->class }}</span>
                        </td>
                        <td>
                            <span class="fw-semibold text-dark">{{ $v->type }}</span>
                            <span class="badge bg-secondary ms-1 small">{{ $v->category }}</span><br>

                            @if($v->bukti_foto)
                                <button type="button" class="btn btn-sm btn-outline-primary mt-2 rounded-pill px-3 py-0" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $v->id }}">
                                    <i class="bi bi-image me-1"></i> Lihat Bukti
                                </button>
                            @endif
                        </td>
                        <td class="fw-bold {{ $v->jenis_poin == 'positif' ? 'text-success' : 'text-danger' }}">
                            {{ $v->jenis_poin == 'positif' ? '+' : '-' }}{{ $v->points }}
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                @if($is_authorized)
                                    <button type="button" class="btn btn-sm {{ $v->tanggapan_ortu ? 'btn-info text-white' : 'btn-outline-info' }}" data-bs-toggle="modal" data-bs-target="#modalTanggapan{{ $v->id }}" title="Lihat Ulasan">
                                        <i class="bi bi-chat-dots"></i>
                                    </button>
                                @else
                                    <button type="button" class="btn btn-sm btn-outline-secondary bg-light" disabled><i class="bi bi-lock-fill text-muted"></i></button>
                                @endif

                                <form action="/pelanggaran/{{ $v->id }}" method="POST" onsubmit="return confirm('Hapus catatan ini beserta fotonya?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted fst-italic">Tidak ada data riwayat.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4 d-flex justify-content-end">{{ $violations->links('pagination::bootstrap-5') }}</div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // =========================================
        // LOGIKA AUTO-CLOSE ALERT DENGAN ANIMASI
        // =========================================
        setTimeout(() => {
            $('.animated-alert').each(function() {
                // Tambahkan class animasi keluar
                $(this).addClass('hide-alert');

                // Tunggu 500ms (sampai animasi selesai) baru elemen dihilangkan dari DOM
                setTimeout(() => {
                    $(this).remove();
                }, 500);
            });
        }, 4000); // Alert tampil selama 4 detik

        // =========================================
        // SCRIPT FORM SEBELUMNYA
        // =========================================
        $('.select2-basic').select2({ theme: 'bootstrap-5', width: '100%' });
        $('#select-tatib').select2({ theme: 'bootstrap-5', placeholder: "-- Ketik Mencari Aturan... --", width: '100%' });

        const allStudents = @json($students);
        $('#select-kelas').on('change', function() {
            const selectedKelas = $(this).val();
            const $selectSiswa = $('#select-siswa');
            $selectSiswa.empty().append('<option value="" disabled selected>-- Pilih Siswa --</option>');
            $selectSiswa.prop('disabled', false);

            const filtered = allStudents.filter(s => s.class === selectedKelas);
            if(filtered.length > 0) {
                filtered.forEach(s => $selectSiswa.append(new Option(s.name, s.id)));
            } else {
                $selectSiswa.empty().append('<option value="" disabled selected>-- Tidak ada siswa --</option>');
                $selectSiswa.prop('disabled', true);
            }
            $selectSiswa.trigger('change');
        });

        $('#select-tatib').on('change', function() {
            const selectedOption = $(this).find(':selected');
            $('#input-jenis').val(selectedOption.data('jenis'));
            $('#input-kategori').val(selectedOption.data('kategori'));
            $('#input-poin').val(selectedOption.data('poin'));

            if(selectedOption.data('jenis') === 'negatif') {
                $('#input-jenis, #input-poin').attr('class', 'form-control bg-danger bg-opacity-10 text-danger fw-bold border-danger text-center');
            } else {
                $('#input-jenis, #input-poin').attr('class', 'form-control bg-success bg-opacity-10 text-success fw-bold border-success text-center');
            }
        });
    });
</script>
@endsection
