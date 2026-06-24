@extends('layouts.app')

@section('content')
@php
    // Mengambil data siswa yang sedang login
    $siswa = \App\Models\Student::find(session('user_id'));
    // Mengambil riwayat pelanggaran & kebaikan siswa tersebut
    $riwayat = \App\Models\Violation::where('student_id', $siswa->id)->latest()->get();

    // Menyiapkan data untuk Grafik Tren (6 bulan terakhir)
    $labels = [];
    $dataPos = [];
    $dataNeg = [];

    for ($i = 5; $i >= 0; $i--) {
        $bulan = now()->subMonths($i);
        $labels[] = $bulan->format('M');

        // Poin Kebaikan Siswa
        $dataPos[] = \App\Models\Violation::where('student_id', $siswa->id)
                        ->where('jenis_poin', 'positif')
                        ->whereMonth('created_at', $bulan->month)
                        ->whereYear('created_at', $bulan->year)
                        ->sum('points');

        // Poin Pelanggaran Siswa
        $dataNeg[] = \App\Models\Violation::where('student_id', $siswa->id)
                        ->where('jenis_poin', 'negatif')
                        ->whereMonth('created_at', $bulan->month)
                        ->whereYear('created_at', $bulan->year)
                        ->sum('points');
    }
@endphp

<div class="container-fluid py-2">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body bg-success text-white rounded d-flex flex-column flex-md-row justify-content-between align-items-md-center p-4">
                    <div>
                        <h3 class="fw-bold mb-1">Halo, Orang Tua dari  {{ $siswa->name }}!</h3>
                        <p class="mb-0 fs-6">
                            <i class="bi bi-person-badge me-2"></i>NISN: {{ $siswa->nisn }} |
                            <i class="bi bi-door-open ms-2 me-1"></i>Kelas: {{ $siswa->class }}
                        </p>
                    </div>
                    <div class="mt-3 mt-md-0 bg-white text-success px-4 py-2 rounded shadow-sm fw-bold">
                        Sistem Pantau EDUSPARC
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm p-4 text-center h-100 d-flex flex-column justify-content-center">
                <h6 class="text-muted text-uppercase small fw-bold mb-3">Akumulasi Poin Karakter Saat Ini</h6>

                <h1 class="display-1 fw-bold {{ $siswa->total_points >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $siswa->total_points }}
                </h1>

                @if($siswa->total_points >= 0)
                    <div class="badge bg-success-subtle text-success p-2 mb-3 mx-auto" style="max-width: 200px;">
                        <i class="bi bi-check-circle-fill me-1"></i> Perilaku Terjaga Baik
                    </div>
                @else
                    <div class="badge bg-danger-subtle text-danger p-2 mb-3 mx-auto" style="max-width: 200px;">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Butuh Perhatian Ekstra
                    </div>
                @endif

                <p class="small text-muted mt-2 mb-0">
                    Poin bertambah (+) untuk setiap prestasi/kebaikan, dan berkurang (-) untuk setiap pelanggaran indisipliner.
                </p>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h5 class="fw-bold mb-4"><i class="bi bi-activity text-primary me-2"></i>Grafik Perkembangan Karakter (6 Bulan)</h5>
                <div style="position: relative; height: 250px; width: 100%;">
                    <canvas id="chartOrangTua"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-4">
                <h5 class="fw-bold mb-3"><i class="bi bi-card-list text-secondary me-2"></i>Detail Riwayat Catatan</h5>
                <div class="table-responsive">
                    <table class="table table-hover mt-2 align-middle min-w-600">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 15%">Tanggal</th>
                                <th style="width: 25%">Aktivitas / Kejadian</th>
                                <th style="width: 15%">Kategori</th>
                                <th style="width: 10%" class="text-center">Poin</th>
                                <th style="width: 35%">Catatan Wali/Guru</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $v)
                            <tr>
                                <td class="small text-muted">{{ $v->created_at->format('d M Y') }}</td>
                                <td class="fw-bold text-dark">{{ $v->type }}</td>
                                <td>
                                    <span class="badge bg-light text-secondary border">
                                        {{ $v->category }}
                                    </span>
                                </td>
                                <td class="text-center fw-bold fs-5 {{ $v->jenis_poin == 'positif' ? 'text-success' : 'text-danger' }}">
                                    {{ $v->jenis_poin == 'positif' ? '+' : '-' }}{{ $v->points }}
                                </td>
                                <td class="small text-muted">
                                    {{ $v->description ?? '-' }}

                                    @if($v->motivation)
                                        <div class="mt-2 pt-2 border-top border-light">
                                            <span class="text-primary fw-semibold" style="font-size: 0.8rem;">
                                                <i class="bi bi-chat-heart-fill me-1"></i>Pesan Guru:
                                            </span><br>
                                            <span class="text-dark fst-italic">"{{ $v->motivation }}"</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted fst-italic">
                                    <div class="mb-2"><i class="bi bi-shield-check text-success display-6"></i></div>
                                    Belum ada catatan tercatat. Pertahankan sikap yang baik!
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('chartOrangTua').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [
                {
                    label: 'Poin Kebaikan (+)',
                    data: {!! json_encode($dataPos) !!},
                    borderColor: '#198754', // Hijau
                    backgroundColor: 'rgba(25, 135, 84, 0.08)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#198754'
                },
                {
                    label: 'Poin Pelanggaran (-)',
                    data: {!! json_encode($dataNeg) !!},
                    borderColor: '#dc3545', // Merah
                    backgroundColor: 'rgba(220, 53, 69, 0.08)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#dc3545'
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
                    labels: { boxWidth: 12 }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { borderDash: [5, 5], color: '#e9ecef' },
                    border: { display: false }
                },
                x: {
                    grid: { display: false },
                    border: { display: false }
                }
            }
        }
    });
</script>
@endsection
