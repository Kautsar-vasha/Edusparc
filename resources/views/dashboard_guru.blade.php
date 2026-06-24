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
        flex-shrink: 0;
    }
    .bg-gradient-primary { background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); }
    .bg-gradient-success { background: linear-gradient(135deg, #198754 0%, #146c43 100%); }
    .bg-gradient-warning { background: linear-gradient(135deg, #ffc107 0%, #ffcd39 100%); }
    .bg-gradient-danger { background: linear-gradient(135deg, #dc3545 0%, #b02a37 100%); }
    .bg-gradient-info { background: linear-gradient(135deg, #0dcaf0 0%, #087990 100%); }
    .bg-gradient-purple { background: linear-gradient(135deg, #6f42c1 0%, #4e2a84 100%); }

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
    }
    .timeline-pos::before { background-color: #198754; }
    .timeline-neg::before { background-color: #dc3545; }
</style>

<div class="container-fluid py-2">
    <div class="row mb-4 align-items-center g-3">
        <div class="col-12 col-md-8">
            <div class="d-flex align-items-center flex-column flex-sm-row text-center text-sm-start">
                <img src="{{ asset('images/logo_sekolah.png') }}" alt="Logo" class="mb-3 mb-sm-0 me-sm-3" style="height: 60px; width: auto;">
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
        <div class="col-12 col-md-4 text-center text-md-end">
            <div class="badge bg-white text-dark p-3 fs-6 shadow-sm border rounded-pill d-inline-block">
                <i class="bi bi-calendar-event text-primary me-2"></i>
                <span id="datetime" class="fw-semibold"></span> WIB
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="glass-card p-3 d-flex flex-column align-items-center text-center h-100">
                <div class="icon-box bg-info text-white mb-2 bg-gradient-info"><i class="bi bi-person-badge-fill"></i></div>
                <h6 class="text-muted mb-1 small fw-bold">Total Guru</h6>
                <h4 class="fw-bold mb-0">{{ $total_guru }}</h4>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="glass-card p-3 d-flex flex-column align-items-center text-center h-100">
                <div class="icon-box bg-primary text-white mb-2 bg-gradient-primary"><i class="bi bi-people-fill"></i></div>
                <h6 class="text-muted mb-1 small fw-bold">Total Siswa</h6>
                <h4 class="fw-bold mb-0">{{ $total_siswa }}</h4>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="glass-card p-3 d-flex flex-column align-items-center text-center h-100">
                <div class="icon-box bg-success text-white mb-2 bg-gradient-success"><i class="bi bi-journal-check"></i></div>
                <h6 class="text-muted mb-1 small fw-bold">Total Kelas</h6>
                <h4 class="fw-bold mb-0">{{ $total_kelas }}</h4>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="glass-card p-3 d-flex flex-column align-items-center text-center h-100">
                <div class="icon-box bg-warning text-dark mb-2 bg-gradient-warning"><i class="bi bi-exclamation-triangle-fill"></i></div>
                <h6 class="text-muted mb-1 small fw-bold">kasus Bulan Ini</h6>
                <h4 class="fw-bold mb-0">{{ $kasus_bulan_ini }}</h4>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="glass-card p-3 d-flex flex-column align-items-center text-center h-100">
                <div class="icon-box text-white mb-2 bg-gradient-purple"><i class="bi bi-trophy-fill"></i></div>
                <h6 class="text-muted mb-1 small fw-bold">Poin Tertinggi</h6>
                <h4 class="fw-bold mb-0 text-purple" style="color: #6f42c1;">{{ $poin_tertinggi }}</h4>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="glass-card p-3 d-flex flex-column align-items-center text-center h-100">
                <div class="icon-box text-white mb-2 bg-gradient-danger"><i class="bi bi-arrow-down-circle-fill"></i></div>
                <h6 class="text-muted mb-1 small fw-bold">Poin Terendah</h6>
                <h4 class="fw-bold mb-0 text-danger">{{ $poin_terendah }}</h4>
            </div>
        </div>
    </div>

    <h5 class="fw-bold text-dark mb-3">Akses Cepat</h5>
    <div class="row g-3 mb-4 text-center">
        <div class="col-6 col-sm-4 col-lg-2">
            <a href="/guru" class="glass-card hover-lift p-3 text-decoration-none text-dark d-block h-100 border-info border-opacity-25">
                <div class="text-info mb-2"><i class="bi bi-person-badge fs-2"></i></div>
                <h6 class="mb-0 small fw-semibold">Data Guru</h6>
            </a>
        </div>
        <div class="col-6 col-sm-4 col-lg-2">
            <a href="/siswa" class="glass-card hover-lift p-3 text-decoration-none text-dark d-block h-100">
                <div class="text-success mb-2"><i class="bi bi-person-vcard fs-2"></i></div>
                <h6 class="mb-0 small fw-semibold">Data Siswa</h6>
            </a>
        </div>
        <div class="col-6 col-sm-4 col-lg-2">
            <a href="/kelas" class="glass-card hover-lift p-3 text-decoration-none text-dark d-block h-100">
                <div class="text-secondary mb-2"><i class="bi bi-door-open fs-2"></i></div>
                <h6 class="mb-0 small fw-semibold">Data Kelas</h6>
            </a>
        </div>
        <div class="col-6 col-sm-4 col-lg-2">
            <a href="/pelanggaran" class="glass-card hover-lift p-3 text-decoration-none text-dark d-block h-100 border-danger border-opacity-25">
                <div class="text-danger mb-2"><i class="bi bi-shield-exclamation fs-2"></i></div>
                <h6 class="mb-0 small fw-semibold">Input Karakter</h6>
            </a>
        </div>
        <div class="col-6 col-sm-4 col-lg-2">
            <a href="#" data-bs-toggle="modal" data-bs-target="#modalLaporan" class="glass-card hover-lift p-3 text-center text-decoration-none text-dark d-block h-100 border-warning border-opacity-25">
                <div class="text-warning mb-2"><i class="bi bi-file-earmark-bar-graph fs-2"></i></div>
                <h6 class="mb-0 small fw-semibold">Laporan</h6>
            </a>
        </div>
        <div class="col-6 col-sm-4 col-lg-2">
            <a href="/pengaturan" class="glass-card hover-lift p-3 text-decoration-none text-dark d-block h-100">
                <div class="text-primary mb-2"><i class="bi bi-gear-fill fs-2"></i></div>
                <h6 class="mb-0 small fw-semibold">Pengaturan</h6>
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-8">
            <div class="glass-card p-4 h-100">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 g-2">
                    <h5 class="fw-bold mb-0"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Grafik Perkembangan Sikap & Karakter</h5>
                    <span class="badge bg-light text-dark p-2 border mt-2 mt-sm-0">6 Bulan Terakhir</span>
                </div>
                <div style="position: relative; height: 300px; width: 100%;">
                    <canvas id="karakterChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold mb-4"><i class="bi bi-clock-history text-warning me-2"></i>Aktivitas Terakhir</h5>
                <div class="timeline">
                    @forelse($aktivitas_terbaru as $akt)
                    <div class="timeline-item {{ $akt->jenis_poin == 'positif' ? 'timeline-pos' : 'timeline-neg' }}">
                        <div class="fw-bold text-dark">{{ $akt->student->name ?? 'Siswa Terhapus' }} ({{ $akt->student->class ?? '-' }})</div>
                        @if($akt->jenis_poin == 'positif')
                            <small class="text-success d-block fw-semibold">{{ $akt->type }} (+{{ $akt->points }} Poin Kebaikan)</small>
                        @else
                            <small class="text-danger d-block fw-semibold">{{ $akt->type }} (-{{ $akt->points }} Poin Pelanggaran)</small>
                        @endif
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

                    <div class="col-12 col-md-4">
                        <div class="card border-0 shadow-sm h-100 p-3">
                            <h6 class="fw-bold"><i class="bi bi-person-badge text-info me-2"></i>1. Data Guru</h6>
                            <p class="small text-muted mb-3">Cetak daftar lengkap seluruh tenaga pendidik.</p>
                            <div class="mt-auto d-flex gap-2">
                                <a href="/laporan/guru/pdf" target="_blank" class="btn btn-sm btn-outline-danger flex-fill"><i class="bi bi-filetype-pdf me-1"></i> PDF</a>
                                <a href="/laporan/guru/excel" class="btn btn-sm btn-outline-success flex-fill"><i class="bi bi-file-earmark-excel me-1"></i> Excel</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="card border-0 shadow-sm h-100 p-3">
                            <h6 class="fw-bold"><i class="bi bi-door-open text-secondary me-2"></i>2. Data Kelas</h6>
                            <p class="small text-muted mb-2">Cetak daftar siswa dalam satu kelas (Urut NISN).</p>
                            <form action="/laporan/kelas" method="GET" target="_blank" class="mt-auto">
                                <select name="kelas" class="form-select form-select-sm mb-2" required>
                                    <option value="" selected disabled>-- Pilih Kelas --</option>
                                    @foreach(\App\Models\Kelas::orderBy('nama_kelas')->get() as $k)
                                        <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                <div class="d-flex gap-2">
                                    <button type="submit" name="format" value="pdf" class="btn btn-sm btn-outline-danger flex-fill"><i class="bi bi-filetype-pdf me-1"></i> PDF</button>
                                    <button type="submit" name="format" value="excel" class="btn btn-sm btn-outline-success flex-fill"><i class="bi bi-file-earmark-excel me-1"></i> Excel</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="card border-0 shadow-sm h-100 p-3 border-top border-3 border-primary">
                            <h6 class="fw-bold"><i class="bi bi-person-lines-fill text-primary me-2"></i>3. Riwayat Siswa</h6>
                            <p class="small text-muted mb-2">Cetak detail poin kebaikan & pelanggaran individu.</p>
                            <form action="/laporan/riwayat" method="GET" target="_blank" class="mt-auto">
                                <select id="pilihKelas" class="form-select form-select-sm mb-2" onchange="loadSiswa()" required>
                                    <option value="" selected disabled>-- 1. Pilih Kelas --</option>
                                    @foreach(\App\Models\Kelas::orderBy('nama_kelas')->get() as $k)
                                        <option value="{{ $k->nama_kelas }}">{{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                <select name="student_id" id="pilihSiswa" class="form-select form-select-sm mb-2" required disabled>
                                    <option value="" selected disabled>-- 2. Pilih Siswa --</option>
                                </select>
                                <div class="d-flex gap-2">
                                    <button type="submit" name="format" value="pdf" class="btn btn-sm btn-outline-danger flex-fill"><i class="bi bi-filetype-pdf me-1"></i> PDF</button>
                                    <button type="submit" name="format" value="excel" class="btn btn-sm btn-outline-success flex-fill"><i class="bi bi-file-earmark-excel me-1"></i> Excel</button>
                                </div>
                            </form>
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

    // Inisialisasi Chart 2 Garis
    const ctx = document.getElementById('karakterChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($chart_labels) !!},
            datasets: [
                {
                    label: 'Poin Kebaikan (+)',
                    data: {!! json_encode($chart_data_pos) !!},
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.08)',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#198754',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Poin Pelanggaran (-)',
                    data: {!! json_encode($chart_data_neg) !!},
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.08)',
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#dc3545',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: { boxWidth: 12, font: { weight: 'bold' } }
                }
            },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#e9ecef' }, border: { display: false } },
                x: { grid: { display: false }, border: { display: false } }
            }
        }
    });

    // Script untuk memfilter dropdown siswa berdasarkan kelas
    const semuaSiswa = @json(\App\Models\Student::select('id', 'name', 'class')->orderBy('name')->get());

    function loadSiswa() {
        const kelasDipilih = document.getElementById('pilihKelas').value;
        const dropdownSiswa = document.getElementById('pilihSiswa');

        // Kosongkan dan aktifkan dropdown siswa
        dropdownSiswa.innerHTML = '<option value="" selected disabled>-- 2. Pilih Siswa --</option>';
        dropdownSiswa.disabled = false;

        // Filter siswa yang kelasnya sama dengan yang dipilih
        const siswaDifilter = semuaSiswa.filter(siswa => siswa.class === kelasDipilih);

        // Masukkan ke dropdown
        siswaDifilter.forEach(siswa => {
            dropdownSiswa.innerHTML += `<option value="${siswa.id}">${siswa.name}</option>`;
        });
    }
</script>
@endsection

