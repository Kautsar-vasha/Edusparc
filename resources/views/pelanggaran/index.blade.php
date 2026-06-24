@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />

<div class="container-fluid">
    <h3 class="mb-4 fw-bold">Input Pencatatan Karakter Siswa</h3>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm p-4 mb-4 border-0">
        <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Form Tambah Catatan</h5>
        <form action="/pelanggaran" method="POST">
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
                        <option value="" disabled selected>-- Ketik Untuk Mencari Aturan... --</option>
                        @if(isset($data_tatib) && count($data_tatib) > 0)
                            @foreach($data_tatib as $t)
                                <option value="{{ $t->uraian }}"
                                        data-jenis="{{ $t->jenis }}"
                                        data-kategori="{{ $t->kategori }}"
                                        data-poin="{{ $t->poin }}">
                                    [{{ $t->kode }}] {{ \Illuminate\Support\Str::limit($t->uraian, 75) }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>Data Tata Tertib Kosong! Isi di menu Master terlebih dahulu.</option>
                        @endif
                    </select>
                </div>

                <div class="col-12 col-sm-4 col-md-4">
                    <label class="form-label fw-bold small text-muted">Jenis (Otomatis)</label>
                    <input type="text" name="jenis_poin" id="input-jenis" class="form-control bg-light text-center fw-bold text-muted" readonly required placeholder="-">
                </div>
                <div class="col-12 col-sm-4 col-md-4">
                    <label class="form-label fw-bold small text-muted">Kategori (Otomatis)</label>
                    <input type="text" name="category" id="input-kategori" class="form-control bg-light text-center fw-bold text-muted" readonly required placeholder="-">
                </div>
                <div class="col-12 col-sm-4 col-md-4">
                    <label class="form-label fw-bold small text-muted">Poin (Otomatis)</label>
                    <input type="number" name="points" id="input-poin" class="form-control bg-light text-center fw-bold text-muted" readonly required placeholder="0">
                </div>
                <div class="col-12 col-md-6 mt-4">
                    <label class="form-label fw-bold small">Keterangan Tambahan (Opsional)</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Detail kronologi kejadian..."></textarea>
                </div>

                <div class="col-12 col-md-6 mt-4">
                    <label class="form-label fw-bold small">Pesan / Motivasi Pembinaan untuk Siswa</label>
                    <textarea name="motivation" class="form-control" rows="2" placeholder="Tuliskan kalimat nasihat pembinaan/apresiasi kebaikan..."></textarea>
                </div>

                <div class="col-12 mt-3 text-end text-sm-start">
                    <button type="submit" class="btn btn-primary px-4 fw-semibold shadow-sm">
                        <i class="bi bi-save me-1"></i> Simpan Data Karakter
                    </button>
                </div>
            </div>
        </form>
    </div>

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
                        <option value="">-- Semua Catatan --</option>
                        <option value="positif" {{ request('jenis_poin') == 'positif' ? 'selected' : '' }}>Kebaikan (+)</option>
                        <option value="negatif" {{ request('jenis_poin') == 'negatif' ? 'selected' : '' }}>Pelanggaran (-)</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <label class="form-label small fw-bold text-muted">Urutan Waktu</label>
                    <select name="sort_waktu" class="form-select form-select-sm">
                        <option value="terbaru" {{ request('sort_waktu') == 'terbaru' || !request('sort_waktu') ? 'selected' : '' }}>Terbaru &rarr; Terlama</option>
                        <option value="terlama" {{ request('sort_waktu') == 'terlama' ? 'selected' : '' }}>Terlama &rarr; Terbaru</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-secondary flex-fill fw-semibold"><i class="bi bi-funnel"></i> Terapkan</button>
                    <a href="/pelanggaran" class="btn btn-sm btn-light border flex-fill text-center">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="card shadow-sm p-4 border-0">
        <h5 class="fw-bold mb-3 text-dark">Riwayat Catatan Karakter Terbaru</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle min-w-600 mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 15%">Waktu</th>
                        <th style="width: 25%">Nama Siswa</th>
                        <th style="width: 40%">Aktivitas & Pesan Pembinaan</th>
                        <th style="width: 10%">Poin</th>
                        <th style="width: 10%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($violations as $v)
                    <tr>
                        <td class="small text-muted">{{ $v->created_at->format('d/m/y H:i') }}</td>
                        <td>
                            <b class="text-dark">{{ $v->student->name }}</b> <br>
                            <span class="badge bg-light text-secondary border">{{ $v->student->class }}</span>
                        </td>
                        <td>
                            <span class="fw-semibold text-dark">{{ $v->type }}</span>
                            <span class="badge bg-secondary ms-1 small">{{ $v->category }}</span><br>
                            @if($v->motivation)
                                <div class="mt-1"><small class="text-primary fst-italic">"{{ $v->motivation }}"</small></div>
                            @endif
                        </td>
                        <td class="fw-bold {{ $v->jenis_poin == 'positif' ? 'text-success' : 'text-danger' }}">
                            {{ $v->jenis_poin == 'positif' ? '+' : '-' }}{{ $v->points }}
                        </td>
                        <td class="text-center">
                            <form action="/pelanggaran/{{ $v->id }}" method="POST" onsubmit="return confirm('Hapus catatan ini? Nilai akumulasi poin siswa akan otomatis disesuaikan kembali.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted fst-italic">
                            <i class="bi bi-info-circle me-2"></i>Tidak ada data riwayat yang cocok dengan filter pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4 d-flex justify-content-end">
            {{ $violations->links('pagination::bootstrap-5') }}
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {

        // Inisialisasi Select2 untuk pencarian Kelas & Siswa
        $('.select2-basic').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });

        // Inisialisasi Select2 khusus untuk Tata Tertib
        $('#select-tatib').select2({
            theme: 'bootstrap-5',
            placeholder: "-- Ketik Untuk Mencari Aturan... --",
            width: '100%'
        });

        // Script Filter Siswa Berdasarkan Kelas
        const allStudents = @json($students);

        $('#select-kelas').on('change', function() {
            const selectedKelas = $(this).val();
            const $selectSiswa = $('#select-siswa');

            $selectSiswa.empty().append('<option value="" disabled selected>-- Pilih Siswa --</option>');
            $selectSiswa.prop('disabled', false);

            const filtered = allStudents.filter(s => s.class === selectedKelas);
            if(filtered.length > 0) {
                filtered.forEach(s => {
                    $selectSiswa.append(new Option(s.name, s.id));
                });
            } else {
                $selectSiswa.empty().append('<option value="" disabled selected>-- Tidak ada siswa --</option>');
                $selectSiswa.prop('disabled', true);
            }
            // Trigger update tampilan Select2
            $selectSiswa.trigger('change');
        });

        // Script DROPDOWN PINTAR TATA TERTIB
        $('#select-tatib').on('change', function() {
            // Ambil elemen <option> yang sedang dipilih
            const selectedOption = $(this).find(':selected');

            // Tembakkan nilai data ke kotak yang terkunci
            $('#input-jenis').val(selectedOption.data('jenis'));
            $('#input-kategori').val(selectedOption.data('kategori'));
            $('#input-poin').val(selectedOption.data('poin'));

            // Beri warna otomatis (merah untuk pelanggaran, hijau untuk penghargaan)
            if(selectedOption.data('jenis') === 'negatif') {
                $('#input-jenis').attr('class', 'form-control bg-danger bg-opacity-10 text-danger fw-bold border-danger text-center');
                $('#input-poin').attr('class', 'form-control bg-danger bg-opacity-10 text-danger fw-bold border-danger text-center');
            } else {
                $('#input-jenis').attr('class', 'form-control bg-success bg-opacity-10 text-success fw-bold border-success text-center');
                $('#input-poin').attr('class', 'form-control bg-success bg-opacity-10 text-success fw-bold border-success text-center');
            }
        });

    });
</script>
@endsection
