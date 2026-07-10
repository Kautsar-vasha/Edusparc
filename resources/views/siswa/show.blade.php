@extends('layouts.app')

@section('content')
<style>
    .glass-card { background: #ffffff; border-radius: 15px; border: 1px solid rgba(0,0,0,0.05); box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
</style>

<div class="container-fluid py-2">
    <div class="mb-4">
        <a href="javascript:history.back()" class="text-decoration-none text-muted mb-2 d-inline-block">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
        <h3 class="fw-bold text-dark mb-0">
            <i class="bi bi-person-badge text-primary me-2"></i>Profil & Riwayat Karakter Siswa
        </h3>
    </div>

    <div class="row g-4">
        {{-- Kartu Profil Siswa (Kiri) --}}
        <div class="col-12 col-lg-4">
            <div class="glass-card p-4 text-center h-100 d-flex flex-column justify-content-center">
                <div class="mb-3">
                    <i class="bi bi-person-circle text-secondary" style="font-size: 5rem;"></i>
                </div>
                <h4 class="fw-bold text-dark mb-1">{{ $student->name }}</h4>
                <p class="text-muted mb-3">
                    <i class="bi bi-credit-card-2-front me-1"></i>NISN: {{ $student->nisn }} <br>
                    <i class="bi bi-door-open me-1 mt-1"></i>Kelas: {{ $student->class }}
                </p>

                <hr class="text-muted w-75 mx-auto">

                <h6 class="text-muted text-uppercase small fw-bold mt-2">Total Poin Saat Ini</h6>
                <h1 class="display-3 fw-bold {{ $student->total_points >= 0 ? 'text-success' : 'text-danger' }}">
                    {{ $student->total_points > 0 ? '+' : '' }}{{ $student->total_points }}
                </h1>

                @if($student->total_points >= 0)
                    <span class="badge bg-success-subtle text-success p-2 px-3 rounded-pill mt-2 fs-6">
                        <i class="bi bi-shield-check me-1"></i> Perilaku Terjaga Baik
                    </span>
                @else
                    <span class="badge bg-danger-subtle text-danger p-2 px-3 rounded-pill mt-2 fs-6">
                        <i class="bi bi-exclamation-triangle me-1"></i> Butuh Perhatian Ekstra
                    </span>
                @endif
            </div>
        </div>

        {{-- Tabel Riwayat Perilaku (Kanan) --}}
        <div class="col-12 col-lg-8">
            <div class="glass-card p-4 h-100">
                <h5 class="fw-bold mb-4"><i class="bi bi-clock-history text-primary me-2"></i>Daftar Riwayat Tercatat</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted">
                            <tr>
                                <th width="15%">Tanggal</th>
                                <th width="30%">Aktivitas</th>
                                <th width="15%" class="text-center">Poin</th>
                                <th width="40%">Keterangan & Tanggapan Ortu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $v)
                            <tr>
                                <td class="small text-muted">{{ $v->created_at->format('d M Y') }}</td>
                                <td>
                                    <span class="fw-bold text-dark">{{ $v->type }}</span><br>
                                    <span class="badge bg-light text-secondary border mt-1">{{ $v->category }}</span>
                                </td>
                                <td class="text-center fw-bold fs-6 {{ $v->jenis_poin == 'positif' ? 'text-success' : 'text-danger' }}">
                                    {{ $v->jenis_poin == 'positif' ? '+' : '-' }}{{ $v->points }}
                                </td>
                                <td class="small text-muted">
                                    {{ $v->description ?? '-' }}

                                    {{-- Jika ada pesan dari guru --}}
                                    @if($v->motivation)
                                        <div class="mt-2 pt-2 border-top border-light">
                                            <span class="text-primary fw-semibold" style="font-size: 0.75rem;"><i class="bi bi-chat-heart-fill me-1"></i>Catatan Guru:</span><br>
                                            <span class="fst-italic text-dark">"{{ $v->motivation }}"</span>
                                        </div>
                                    @endif

                                    {{-- Jika ada balasan dari orang tua (HANYA UNTUK WALI KELAS/BK) --}}
                                    @if($v->tanggapan_ortu)
                                        @if(isset($is_wali_or_bk) && $is_wali_or_bk)
                                            <div class="mt-2 bg-success bg-opacity-10 p-2 rounded border-start border-3 border-success">
                                                <span class="fw-bold text-success" style="font-size: 0.75rem;"><i class="bi bi-reply-all-fill me-1"></i>Balasan Wali Murid:</span><br>
                                                <span class="fst-italic text-dark">"{{ $v->tanggapan_ortu }}"</span>
                                            </div>
                                        @else
                                            <div class="mt-2 bg-secondary bg-opacity-10 p-2 rounded border-start border-3 border-secondary">
                                                <span class="text-muted" style="font-size: 0.75rem;"><i class="bi bi-lock-fill me-1"></i>Pesan balasan orang tua disembunyikan (Khusus Wali Kelas & BK)</span>
                                            </div>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted fst-italic">
                                    <i class="bi bi-inbox display-6 d-block mb-3 text-secondary"></i>
                                    Siswa ini belum memiliki catatan riwayat karakter apapun.
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
@endsection
