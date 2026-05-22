@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4">Input Pelanggaran Siswa</h3>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm p-4 mb-4 border-0">
        <form action="/pelanggaran" method="POST">
            @csrf
            <div class="row g-3">
                <div class="col-md-2">
                    <label class="form-label fw-bold small">1. Pilih Kelas</label>
                    <select id="select-kelas" class="form-select" required>
                        <option value="" disabled selected>-- Kelas --</option>
                        @foreach($data_kelas as $k)
                            <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold small">2. Pilih Siswa</label>
                    <select name="student_id" id="select-siswa" class="form-select" required disabled>
                        <option value="" disabled selected>-- Pilih Kelas Dulu --</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small">Jenis Pelanggaran</label>
                    <input type="text" name="type" class="form-control" placeholder="Contoh: Bolos" required>
                </div>
                <div class="col-md-2">
                    <label class="form-label fw-bold small">Kategori</label>
                    <select name="category" class="form-select">
                        <option value="Administratif">Administratif</option>
                        <option value="Etika/Perilaku">Etika/Perilaku</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label fw-bold small">Poin</label>
                    <input type="number" name="points" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small">Keterangan Tambahan (Opsional)</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold small text-primary">Pesan / Motivasi untuk Siswa</label>
                    <textarea name="motivation" class="form-control border-primary" rows="2" placeholder="Tuliskan kata-kata pembinaan..."></textarea>
                </div>

                <div class="col-12 mt-3">
                    <button type="submit" class="btn btn-danger px-4">Simpan Pelanggaran</button>
                </div>
            </div>
        </form>
    </div>

    <div class="card shadow-sm p-4 border-0">
        <h5 class="fw-bold mb-3">Riwayat Pelanggaran Terbaru</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Waktu</th>
                        <th>Nama Siswa</th>
                        <th>Pelanggaran & Motivasi</th>
                        <th>Poin</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($violations as $v)
                    <tr>
                        <td class="small">{{ $v->created_at->format('d/m/y H:i') }}</td>
                        <td><b>{{ $v->student->name }}</b> <br> <small class="text-muted">{{ $v->student->class }}</small></td>
                        <td>
                            <span class="fw-semibold">{{ $v->type }}</span>
                            <span class="badge bg-secondary ms-1">{{ $v->category }}</span><br>
                            @if($v->motivation)
                                <small class="text-primary fst-italic">"{{ $v->motivation }}"</small>
                            @endif
                        </td>
                        <td class="text-danger fw-bold">+{{ $v->points }}</td>
                        <td class="text-center">
                            <form action="/pelanggaran/{{ $v->id }}" method="POST" onsubmit="return confirm('Hapus pelanggaran ini? Poin siswa akan dikurangi kembali.')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const allStudents = @json($students);
    const selectKelas = document.getElementById('select-kelas');
    const selectSiswa = document.getElementById('select-siswa');

    selectKelas.addEventListener('change', function() {
        const selectedKelas = this.value;
        selectSiswa.innerHTML = '<option value="" disabled selected>-- Pilih Siswa --</option>';
        selectSiswa.disabled = false;

        const filtered = allStudents.filter(s => s.class === selectedKelas);
        if(filtered.length > 0) {
            filtered.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.textContent = s.name;
                selectSiswa.appendChild(opt);
            });
        } else {
            selectSiswa.innerHTML = '<option value="" disabled selected>-- Tidak ada siswa --</option>';
            selectSiswa.disabled = true;
        }
    });
</script>
@endsection
