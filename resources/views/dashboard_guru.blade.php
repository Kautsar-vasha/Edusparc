@extends('layouts.app')

@section('content')
<style>
    .glass-card {
        background: #ffffff;
        border-radius: 15px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.08);
    }
    .icon-box {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 1.5rem;
    }
    .bg-gradient-primary { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #198754 0%, #146c43 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #ffc107 0%, #ffcd39 100%); }
    .bg-gradient-danger { background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #0dcaf0 0%, #087990 100%); }

    .timeline-item {
        border-left: 2px solid #e9ecef;
        padding-left: 15px;
        position: relative;
        margin-bottom: 15px;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -6px;
        top: 0;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: #0d6efd;
    }
</style>

<div class="container-fluid py-2">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <div class="d-flex align-items-center">
                <img src="{{ asset('images/logo_sekolah.png') }}" alt="Logo" class="me-3" style="height: 60px; width: auto;">
                <div>
                    <h3 class="fw-bold mb-1 text-dark">Selamat Datang, {{ session('name') }}! 👋</h3>
                    <p class="text-muted mb-0">
                        Anda login sebagai
                        @if(session('role') == 'admin')
                            <span class="badge bg-primary">Super Admin</span>
                        @else
                            <span class="badge bg-success">Guru / Tenaga Pendidik</span>
                        @endif
                        di Sistem EDUSPARC.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div class="badge bg-white text-dark p-3 fs-6 shadow-sm border rounded-pill">
                <i class="bi bi-calendar-event text-primary me-2"></i>
                <span id="datetime" class="fw-semibold"></span> WIB
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg col-md-4 col-sm-6">
            <div class="glass-card p-3 d-flex align-items-center h-100">
                <div class="icon-box bg-info text-white me-3 bg-gradient-info">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0 small fw-bold">Total Guru</h6>
                    <h3 class="fw-bold mb-0">{{ $total_guru }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg col-md-4 col-sm-6">
            <div class="glass-card p-3 d-flex align-items-center h-100">
                <div class="icon-box bg-primary text-white me-3 bg-gradient-primary">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0 small fw-bold">Total Siswa</h6>
                    <h3 class="fw-bold mb-0">{{ $total_siswa }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg col-md-4 col-sm-6">
            <div class="glass-card p-3 d-flex align-items-center h-100">
                <div class="icon-box bg-success text-white me-3 bg-gradient-success">
                    <i class="bi bi-journal-check"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0 small fw-bold">Total Kelas</h6>
                    <h3 class="fw-bold mb-0">{{ $total_kelas }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg col-md-6 col-sm-6">
            <div class="glass-card p-3 d-flex align-items-center h-100">
                <div class="icon-box bg-warning text-dark me-3 bg-gradient-warning">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0 small fw-bold">Kasus Bulan Ini</h6>
                    <h3 class="fw-bold mb-0">{{ $kasus_bulan_ini }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg col-md-6 col-sm-6">
            <div class="glass-card p-3 d-flex align-items-center h-100">
                <div class="icon-box bg-danger text-white me-3 bg-gradient-danger">
                    <i class="bi bi-clipboard-data-fill"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0 small fw-bold">Poin Tertinggi</h6>
                    <h3 class="fw-bold mb-0">{{ $poin_tertinggi }}</h3>
                </div>
            </div>
        </div>
    </div>

    <h5 class="fw-bold text-dark mb-3">Akses Cepat</h5>
    <div class="row g-3 mb-4 text-center">
        <div class="col-lg-2 col-md-4 col-6">
            <a href="/guru" class="glass-card hover-lift p-3 text-decoration-none text-dark d-block h-100 border-info border-opacity-25">
                <div class="text-info mb-2"><i class="bi bi-person-badge fs-2"></i></div>
                <h6 class="mb-0 fw-semibold">Data Guru</h6>
            </a>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <a href="/siswa" class="glass-card hover-lift p-3 text-decoration-none text-dark d-block h-100">
                <div class="text-success mb-2"><i class="bi bi-person-vcard fs-2"></i></div>
                <h6 class="mb-0 fw-semibold">Data Siswa</h6>
            </a>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <a href="/kelas" class="glass-card hover-lift p-3 text-decoration-none text-dark d-block h-100">
                <div class="text-secondary mb-2"><i class="bi bi-door-open fs-2"></i></div>
                <h6 class="mb-0 fw-semibold">Data Kelas</h6>
            </a>
        </div>
        <div class="col-lg-2 col-md-4 col-6">
            <a href="/pelanggaran" class="glass-card hover-lift p-3 text-decoration-none text-dark d-block h-100 border-danger border-opacity-25">
                <div class="text-danger mb-2"><i class="bi bi-shield-exclamation fs-2"></i></div>
                <h6 class="mb-0 fw-semibold">Input Pelanggaran</h6>
            </a>
        </div>
       <div class="col-lg-2 col-md-4 col-6">
            <a href="#" data-bs-toggle="modal" data-bs-target="#modalLaporan" class="glass-card hover-lift p-3 text-center text-decoration-none text-dark d-block h-100 border-warning border-opacity-25">
                <div class="text-warning mb-2"><i class="bi bi-file-earmark-bar-graph fs-2"></i></div>
                <h6 class="mb-0 fw-semibold">Laporan</h6>
            </a>
        </div>
       <div class="col-lg-2 col-md-4 col-6">
            <a href="/pengaturan" class="glass-card hover-lift p-3 text-decoration-none text-dark d-block h-100">
                <div class="text-primary mb-2"><i class="bi bi-gear-fill fs-2"></i></div>
                <h6 class="mb-0 fw-semibold">Pengaturan</h6>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="glass-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Tren Pelanggaran Siswa</h5>
                    <select class="form-select form-select-sm w-auto">
                        <option>6 Bulan Terakhir</option>
                    </select>
                </div>
                <div style="height: 300px;">
                    <canvas id="karakterChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold mb-4"><i class="bi bi-clock-history text-warning me-2"></i>Aktivitas Terakhir</h5>
                <div class="timeline">
                    @forelse($aktivitas_terbaru as $akt)
                    <div class="timeline-item">
                        <div class="fw-bold text-dark">{{ $akt->student->name }} ({{ $akt->student->class }})</div>
                        <small class="text-danger d-block">{{ $akt->type }} (+{{ $akt->points }} Poin)</small>
                        <small class="text-muted fst-italic" style="font-size: 0.75rem;">
                            {{ \Carbon\Carbon::parse($akt->created_at)->diffForHumans() }}
                        </small>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted small">
                        <i class="bi bi-check-circle text-success fs-3 d-block mb-2"></i>
                        Belum ada aktivitas hari ini.
                    </div>
                    @endforelse
                </div>
                <div class="mt-3 text-center">
                    <a href="/pelanggaran" class="text-decoration-none text-primary fw-semibold" style="font-size: 0.9rem;">Lihat Semua Aktivitas &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalLaporan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title fw-bold"><i class="bi bi-printer-fill me-2"></i>Cetak Laporan EDUSPARC</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 bg-light">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 p-3">
                                <h6 class="fw-bold"><i class="bi bi-person-badge text-info me-2"></i>Data Seluruh Guru</h6>
                                <p class="small text-muted mb-3">Cetak daftar lengkap tenaga pendidik.</p>
                                <div class="mt-auto d-flex gap-2">
                                    <a href="/laporan/guru/pdf" class="btn btn-sm btn-outline-danger flex-fill"><i class="bi bi-filetype-pdf me-1"></i> PDF</a>
                                    <a href="/laporan/guru/excel" class="btn btn-sm btn-outline-success flex-fill"><i class="bi bi-file-earmark-excel me-1"></i> Excel</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 p-3">
                                <h6 class="fw-bold"><i class="bi bi-door-open text-secondary me-2"></i>Data Kelas</h6>
                                <p class="small text-muted mb-3">Cetak rekapitulasi data kelas aktif.</p>
                                <div class="mt-auto d-flex gap-2">
                                    <a href="/laporan/kelas/pdf" class="btn btn-sm btn-outline-danger flex-fill"><i class="bi bi-filetype-pdf me-1"></i> PDF</a>
                                    <a href="/laporan/kelas/excel" class="btn btn-sm btn-outline-success flex-fill"><i class="bi bi-file-earmark-excel me-1"></i> Excel</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 p-3">
                                <h6 class="fw-bold"><i class="bi bi-people text-primary me-2"></i>Data Siswa per Kelas</h6>
                                <p class="small text-muted mb-2">Cetak data siswa berdasarkan kelas tertentu.</p>
                                <form action="/laporan/siswa" method="GET" class="mt-auto">
                                    <div class="input-group input-group-sm mb-2">
                                        <select name="kelas" class="form-select" required>
                                            <option value="" selected disabled>-- Pilih Kelas --</option>
                                            @foreach(\App\Models\Kelas::orderBy('nama_kelas')->get() as $k)
                                                <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="submit" name="format" value="pdf" class="btn btn-sm btn-outline-danger flex-fill"><i class="bi bi-filetype-pdf me-1"></i> PDF</button>
                                        <button type="submit" name="format" value="excel" class="btn btn-sm btn-outline-success flex-fill"><i class="bi bi-file-earmark-excel me-1"></i> Excel</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100 p-3">
                                <h6 class="fw-bold"><i class="bi bi-shield-exclamation text-danger me-2"></i>Riwayat Pelanggaran</h6>
                                <p class="small text-muted mb-3">Cetak rekap pelanggaran & poin siswa.</p>
                                <div class="mt-auto d-flex gap-2">
                                    <a href="/laporan/pelanggaran/pdf" class="btn btn-sm btn-outline-danger flex-fill"><i class="bi bi-filetype-pdf me-1"></i> PDF</a>
                                    <a href="/laporan/pelanggaran/excel" class="btn btn-sm btn-outline-success flex-fill"><i class="bi bi-file-earmark-excel me-1"></i> Excel</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function updateClock() {
        const now = new Date();
        const options = {
            weekday: 'long', day: '2-digit', month: 'long', year: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit',
            hour12: false, timeZone: 'Asia/Jakarta'
        };
        const formatter = new Intl.DateTimeFormat('id-ID', options);
        document.getElementById('datetime').innerHTML = formatter.format(now);
    }
    setInterval(updateClock, 1000);
    updateClock();

    const ctx = document.getElementById('karakterChart').getContext('2d');
    let gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(13, 110, 253, 0.4)');
    gradient.addColorStop(1, 'rgba(13, 110, 253, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chart_labels) !!}, // Label dinamis bulan
            datasets: [{
                label: 'Jumlah Pelanggaran',
                data: {!! json_encode($chart_data) !!}, // Data dinamis jumlah
                borderColor: '#0d6efd',
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#0d6efd',
                pointBorderWidth: 2,
                pointRadius: 4,
                tension: 0.4,
                fill: true,
                backgroundColor: gradient
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#e9ecef' }, border: { display: false } },
                x: { grid: { display: false }, border: { display: false } }
            }
        }
    });
</script>
@endsection
