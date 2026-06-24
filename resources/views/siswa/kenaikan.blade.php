@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-0 text-dark"><i class="bi bi-capslock-fill text-danger me-2"></i>Kenaikan Kelas Massal</h3>
            <p class="text-muted small mt-1 mb-0">Manajemen pergantian tahun ajaran baru secara otomatis.</p>
        </div>
        <a href="/siswa" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm border-top border-4 border-danger">
                <div class="card-body p-4 p-md-5">

                    <div class="alert alert-danger mb-4 border-0 shadow-sm">
                        <h5 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> PERINGATAN AREA SENSITIF!</h5>
                        <p class="mb-0 small">
                            Fitur ini hanya digunakan <strong>satu kali dalam setahun</strong> pada saat pergantian tahun ajaran baru. Tindakan ini tidak dapat dibatalkan secara otomatis. Pastikan Anda telah mengunduh (Excel/PDF) laporan tahunan sebelum menekan tombol proses.
                        </p>
                    </div>

                    <h6 class="fw-bold mb-3 border-bottom pb-2">Logika Sistem yang Akan Dijalankan:</h6>
                    <ul class="list-group list-group-flush mb-4 small text-muted">
                        <li class="list-group-item bg-transparent px-0"><i class="bi bi-check2-circle text-success me-2"></i> Seluruh siswa <b>Kelas IX</b> akan diubah statusnya menjadi <b>"Lulus"</b>.</li>
                        <li class="list-group-item bg-transparent px-0"><i class="bi bi-check2-circle text-success me-2"></i> Seluruh siswa <b>Kelas VIII</b> akan naik tingkat menjadi <b>Kelas IX</b> (Contoh: VIII A &rarr; IX A).</li>
                        <li class="list-group-item bg-transparent px-0"><i class="bi bi-check2-circle text-success me-2"></i> Seluruh siswa <b>Kelas VII</b> akan naik tingkat menjadi <b>Kelas VIII</b> (Contoh: VII B &rarr; VIII B).</li>
                    </ul>

                    <form action="/kenaikan-kelas" method="POST" onsubmit="return confirm('Apakah Anda YAKIN 100% ingin memproses kenaikan kelas sekarang? Semua data kelas siswa akan langsung berubah!')">
                        @csrf
                        <h6 class="fw-bold mb-3 border-bottom pb-2 mt-4">Opsi Pembersihan Tahun Ajaran Baru:</h6>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="reset_poin" value="1" id="resetPoin" checked>
                            <label class="form-check-label fw-semibold" for="resetPoin">
                                Reset Total Poin Siswa Menjadi 0
                                <span class="d-block small text-muted fw-normal">Siswa akan memulai tahun ajaran baru dengan poin bersih/kosong.</span>
                            </label>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" name="reset_riwayat" value="1" id="resetRiwayat">
                            <label class="form-check-label fw-semibold text-danger" for="resetRiwayat">
                                Hapus Permanen Seluruh Riwayat Catatan Karakter
                                <span class="d-block small text-muted fw-normal">Mengosongkan tabel riwayat pelanggaran/kebaikan tahun sebelumnya. (Centang jika Anda benar-benar yakin database ingin dibersihkan).</span>
                            </label>
                        </div>

                        <div class="d-grid mt-5">
                            <button type="submit" class="btn btn-danger btn-lg fw-bold shadow">
                                <i class="bi bi-rocket-takeoff-fill me-2"></i> Ya, Proses Kenaikan Kelas Sekarang!
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
