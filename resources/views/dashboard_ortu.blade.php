@extends('layouts.app')

@section('content')
@php
    $siswa = \App\Models\Student::find(session('user_id'));
    $riwayat = \App\Models\Violation::where('student_id', $siswa->id)->latest()->get();
    $labels = []; $dataPos = []; $dataNeg = [];
    for ($i = 5; $i >= 0; $i--) {
        $bulan = now()->subMonths($i);
        $labels[] = $bulan->format('M');
        $dataPos[] = \App\Models\Violation::where('student_id', $siswa->id)->where('jenis_poin', 'positif')->whereMonth('created_at', $bulan->month)->whereYear('created_at', $bulan->year)->sum('points');
        $dataNeg[] = \App\Models\Violation::where('student_id', $siswa->id)->where('jenis_poin', 'negatif')->whereMonth('created_at', $bulan->month)->whereYear('created_at', $bulan->year)->sum('points');
    }
@endphp

<div class="container-fluid py-2 py-md-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3 mb-md-4">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Header Informasi Ortu -->
    <div class="row mb-3 mb-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body bg-success text-white rounded d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center p-3 p-md-4 gap-3">
                    <div>
                        <h4 class="fw-bold mb-1 fs-5 fs-md-3">Halo, Orang Tua dari {{ $siswa->name }}!</h4>
                        <div class="d-flex flex-wrap gap-2 align-items-center small opacity-90">
                            <span><i class="bi bi-person-badge me-1"></i>NISN: {{ $siswa->nisn }}</span>
                            <span class="d-none d-sm-inline">|</span>
                            <span><i class="bi bi-door-open me-1"></i>Kelas: {{ $siswa->class }}</span>
                        </div>
                    </div>
                    <div class="bg-white text-success px-3 py-2 rounded shadow-sm fw-bold small text-center w-100 w-md-auto">
                        Sistem Pantau EDUSPARC
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Area Akumulasi Poin dan Grafik -->
    <div class="row g-3 g-md-4">
        <div class="col-12 col-md-5 col-lg-4">
            <div class="card border-0 shadow-sm p-3 p-md-4 text-center h-100 d-flex flex-column justify-content-center">
                <h6 class="text-muted text-uppercase small fw-bold mb-2 mb-md-3">Akumulasi Poin Saat Ini</h6>
                <h1 class="display-3 display-md-1 fw-bold {{ $siswa->total_points >= 0 ? 'text-success' : 'text-danger' }} my-2">{{ $siswa->total_points }}</h1>
                @if($siswa->total_points >= 0)
                    <div class="badge bg-success-subtle text-success p-2 mx-auto small"><i class="bi bi-check-circle-fill me-1"></i> Perilaku Terjaga Baik</div>
                @else
                    <div class="badge bg-danger-subtle text-danger p-2 mx-auto small"><i class="bi bi-exclamation-triangle-fill me-1"></i> Butuh Perhatian Ekstra</div>
                @endif
            </div>
        </div>
        <div class="col-12 col-md-7 col-lg-8">
            <div class="card border-0 shadow-sm p-3 p-md-4 h-100">
                <h5 class="fw-bold mb-3 mb-md-4 fs-6 fs-md-5"><i class="bi bi-activity text-primary me-2"></i>Grafik Perkembangan Karakter (6 Bulan)</h5>
                <div style="position: relative; height: 230px; width: 100%;"><canvas id="chartOrangTua"></canvas></div>
            </div>
        </div>
    </div>

    <!-- Area Riwayat Catatan -->
    <div class="row mt-3 mt-md-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm p-3 p-md-4">
                <h5 class="fw-bold mb-3 fs-6 fs-md-5"><i class="bi bi-card-list text-secondary me-2"></i>Detail Riwayat Catatan</h5>

                <!-- 1. Tampilan Desktop & Tablet (Tabel Responsive) -->
                <div class="table-responsive d-none d-md-block">
                    <table class="table table-hover mt-2 align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 15%">Tanggal</th>
                                <th style="width: 25%">Aktivitas / Kejadian</th>
                                <th style="width: 15%">Kategori</th>
                                <th style="width: 10%" class="text-center">Poin</th>
                                <th style="width: 35%">Catatan Sekolah & Bukti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $v)
                            <tr>
                                <td class="small text-muted">{{ $v->created_at->format('d M Y') }}</td>
                                <td class="fw-bold text-dark">{{ $v->type }}</td>
                                <td><span class="badge bg-light text-secondary border">{{ $v->category }}</span></td>
                                <td class="text-center fw-bold fs-5 {{ $v->jenis_poin == 'positif' ? 'text-success' : 'text-danger' }}">
                                    {{ $v->jenis_poin == 'positif' ? '+' : '-' }}{{ $v->points }}
                                </td>
                                <td class="small text-muted">
                                    {{ $v->description ?? '-' }}

                                    @if($v->bukti_foto)
                                        <div class="mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-3 py-0" data-bs-toggle="modal" data-bs-target="#modalOrtuFoto{{ $v->id }}">
                                                <i class="bi bi-image me-1"></i> Buka Foto Bukti
                                            </button>
                                        </div>
                                    @endif

                                    @if($v->motivation)
                                        <div class="mt-2 pt-2 border-top border-light">
                                            <span class="text-primary fw-semibold" style="font-size: 0.8rem;"><i class="bi bi-chat-heart-fill me-1"></i>Pesan Sekolah:</span><br>
                                            <span class="text-dark fst-italic">"{{ $v->motivation }}"</span>
                                        </div>
                                    @endif

                                    <div class="mt-2">
                                        @if($v->tanggapan_ortu)
                                            <div class="bg-light p-2 rounded border-start border-3 border-success">
                                                <span class="fw-bold text-success" style="font-size: 0.8rem;"><i class="bi bi-reply-all-fill me-1"></i>Tanggapan Anda:</span><br>
                                                <span class="text-dark fst-italic">"{{ $v->tanggapan_ortu }}"</span>
                                            </div>
                                        @else
                                            <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill" data-bs-toggle="modal" data-bs-target="#tanggapanModal{{ $v->id }}">
                                                <i class="bi bi-chat-dots me-1"></i> Beri Tanggapan
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-4 text-muted fst-italic">Belum ada catatan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- 2. Tampilan Khusus Mobile HP (Kartu Vertikal) -->
                <div class="d-block d-md-none">
                    @forelse($riwayat as $v)
                        <div class="card border shadow-sm mb-3">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-light text-secondary border fs-7">{{ $v->category }}</span>
                                    <span class="fw-bold fs-5 {{ $v->jenis_poin == 'positif' ? 'text-success' : 'text-danger' }}">
                                        {{ $v->jenis_poin == 'positif' ? '+' : '-' }}{{ $v->points }} Poin
                                    </span>
                                </div>

                                <h6 class="fw-bold text-dark mb-1">{{ $v->type }}</h6>
                                <p class="small text-muted mb-2"><i class="bi bi-calendar3 me-1"></i>{{ $v->created_at->format('d M Y') }}</p>

                                @if($v->description)
                                    <div class="p-2 bg-light rounded small text-secondary mb-2">
                                        {{ $v->description }}
                                    </div>
                                @endif

                                @if($v->bukti_foto)
                                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill w-100 mb-2" data-bs-toggle="modal" data-bs-target="#modalOrtuFoto{{ $v->id }}">
                                        <i class="bi bi-image me-1"></i> Buka Foto Bukti
                                    </button>
                                @endif

                                @if($v->motivation)
                                    <div class="mt-2 p-2 bg-primary-subtle rounded border-start border-3 border-primary">
                                        <span class="text-primary fw-semibold small"><i class="bi bi-chat-heart-fill me-1"></i>Pesan Sekolah:</span>
                                        <p class="text-dark fst-italic small mb-0 mt-1">"{{ $v->motivation }}"</p>
                                    </div>
                                @endif

                                <div class="mt-2">
                                    @if($v->tanggapan_ortu)
                                        <div class="bg-light p-2 rounded border-start border-3 border-success">
                                            <span class="fw-bold text-success small"><i class="bi bi-reply-all-fill me-1"></i>Tanggapan Anda:</span>
                                            <p class="text-dark fst-italic small mb-0 mt-1">"{{ $v->tanggapan_ortu }}"</p>
                                        </div>
                                    @else
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill w-100 mt-1" data-bs-toggle="modal" data-bs-target="#tanggapanModal{{ $v->id }}">
                                            <i class="bi bi-chat-dots me-1"></i> Beri Tanggapan
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted fst-italic">Belum ada catatan.</div>
                    @endforelse
                </div>

                <!-- Modal-Modal (Berlaku untuk Desktop & Mobile) -->
                @foreach($riwayat as $v)
                    @if($v->bukti_foto)
                        <div class="modal fade" id="modalOrtuFoto{{ $v->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-dark text-white border-0">
                                        <h6 class="modal-title"><i class="bi bi-camera me-2"></i>Bukti Visual (Sekolah)</h6>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-center p-2 bg-light">
                                        <img src="{{ asset('storage/' . $v->bukti_foto) }}" class="img-fluid rounded shadow-sm" alt="Bukti Kejadian">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(empty($v->tanggapan_ortu))
                        <div class="modal fade" id="tanggapanModal{{ $v->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-success text-white border-0">
                                        <h5 class="modal-title fw-bold"><i class="bi bi-chat-dots me-2"></i>Tanggapan Orang Tua</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form action="/pelanggaran/{{ $v->id }}/tanggapan" method="POST">
                                        @csrf
                                        <div class="modal-body p-3 p-md-4 bg-light">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold text-dark small">Tuliskan tanggapan Anda:</label>
                                                <textarea name="tanggapan_ortu" class="form-control" rows="4" required placeholder="Tuliskan tanggapan atau konfirmasi Anda di sini..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 bg-white">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-success fw-semibold"><i class="bi bi-send-fill me-1"></i> Kirim</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach

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
                    borderColor: '#198754', backgroundColor: 'rgba(25, 135, 84, 0.08)', borderWidth: 3, tension: 0.4, fill: true
                },
                {
                    label: 'Poin Pelanggaran (-)',
                    data: {!! json_encode($dataNeg) !!},
                    borderColor: '#dc3545', backgroundColor: 'rgba(220, 53, 69, 0.08)', borderWidth: 3, tension: 0.4, fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } },
            plugins: {
                legend: {
                    labels: {
                        boxWidth: 12,
                        font: { size: 11 }
                    }
                }
            }
        }
    });
</script>
@endsection
