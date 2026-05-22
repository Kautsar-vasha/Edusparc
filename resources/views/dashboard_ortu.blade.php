@extends('layouts.app')

@section('content')
@php
    // Mengambil data siswa yang sedang login
    $siswa = \App\Models\Student::find(session('user_id'));
    // Mengambil riwayat pelanggaran siswa tersebut
    $riwayat = \App\Models\Violation::where('student_id', $siswa->id)->latest()->get();

    // Menyiapkan data untuk Grafik (6 bulan terakhir)
    $labels = [];
    $dataPoin = [];
    for ($i = 5; $i >= 0; $i--) {
        $bulan = now()->subMonths($i);
        $labels[] = $bulan->format('M');
        $dataPoin[] = \App\Models\Violation::where('student_id', $siswa->id)
                        ->whereMonth('created_at', $bulan->month)
                        ->whereYear('created_at', $bulan->year)
                        ->sum('points');
    }
@endphp

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body bg-success text-white rounded">
                    <h2>Halo, Orang Tua dari {{ $siswa->name }}!</h2>
                    <p class="mb-0">NISN: {{ $siswa->nisn }} | Kelas: {{ $siswa->class }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 text-center h-100">
                <h6 class="text-muted text-uppercase small fw-bold">Akumulasi Poin Pelanggaran</h6>
                <h1 class="display-2 fw-bold {{ $siswa->total_points > 50 ? 'text-danger' : 'text-success' }}">
                    {{ $siswa->total_points }}
                </h1>
                <div class="progress mt-3" style="height: 10px;">
                    <div class="progress-bar {{ $siswa->total_points > 50 ? 'bg-danger' : 'bg-success' }}"
                         role="progressbar" style="width: {{ min($siswa->total_points, 100) }}%"></div>
                </div>
                <p class="small text-muted mt-3">Makin kecil poin, semakin baik perilaku siswa di sekolah.</p>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4 h-100">
                <h5 class="fw-bold mb-4">Grafik Perkembangan Karakter (6 Bulan Terakhir)</h5>
                <div style="height: 250px;">
                    <canvas id="chartOrangTua"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm p-4">
                <h5 class="fw-bold mb-3">Detail Riwayat Pelanggaran</h5>
                <div class="table-responsive">
                    <table class="table table-hover mt-2">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Kategori</th>
                                <th class="text-center">Poin</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $v)
                            <tr>
                                <td>{{ $v->created_at->format('d/m/Y') }}</td>
                                <td class="fw-bold text-dark">{{ $v->type }}</td>
                                <td>
                                    <span class="badge {{ $v->category == 'Etika/Perilaku' ? 'bg-warning text-dark' : 'bg-info' }}">
                                        {{ $v->category }}
                                    </span>
                                </td>
                                <td class="text-center text-danger fw-bold">+{{ $v->points }}</td>
                                <td class="small text-muted">
                                    {{ $v->description ?? '-' }}

                                    @if($v->motivation)
                                        <div class="mt-2 pt-2 border-top border-secondary-subtle">
                                            <span class="text-primary fw-semibold">
                                                <i class="bi bi-chat-heart-fill me-1"></i>Pesan Pembinaan Guru:
                                            </span><br>
                                            <span class="text-dark fst-italic">"{{ $v->motivation }}"</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted fst-italic">
                                    <i class="bi bi-info-circle me-2"></i>Belum ada data pelanggaran tercatat. Putra/Putri Anda memiliki catatan yang baik!
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
    const ctx = document.getElementById('chartOrangTua');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Poin Pelanggaran',
                data: {!! json_encode($dataPoin) !!},
                borderColor: '#198754', // Hijau Sukses
                backgroundColor: 'rgba(25, 135, 84, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointBackgroundColor: '#198754'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 10 }
                }
            }
        }
    });
</script>
@endsection
