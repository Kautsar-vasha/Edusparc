@extends('layouts.app')

@section('content')
<style>
    .glass-card {
        background: #ffffff;
        border-radius: 15px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
</style>

<div class="container-fluid py-2">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">
                <i class="bi bi-person-badge text-primary me-2"></i>Manajemen Data Guru
            </h3>
            <p class="text-muted mb-0 mt-1">Kelola data tenaga pendidik EDUSPARC</p>
        </div>
        <div class="mt-3 mt-md-0 d-flex gap-2">
            <a href="#" class="btn btn-outline-success shadow-sm rounded-pill px-4">
                <i class="bi bi-file-earmark-excel me-1"></i> Export
            </a>
            <a href="/guru/create" class="btn btn-primary shadow-sm rounded-pill px-4">
                <i class="bi bi-plus-circle me-1"></i> Tambah Guru
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="glass-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0"><i class="bi bi-list-ul me-2 text-primary"></i>Daftar Guru Aktif</h5>
            <div class="input-group" style="max-width: 250px;">
                <input type="text" class="form-control form-control-sm" placeholder="Cari nama guru...">
                <button class="btn btn-outline-secondary btn-sm" type="button"><i class="bi bi-search"></i></button>
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted">
                    <tr>
                        <th width="5%" class="text-center rounded-start">No</th>
                        <th>NIP/NUPTK</th>
                        <th>Nama Guru</th>
                        <th>Peran</th>
                        <th>Mapel</th>
                        <th width="10%" class="text-center rounded-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teachers as $key => $t)
                    <tr>
                        <td class="text-center fw-bold text-muted">{{ $key+1 }}</td>
                        <td><code class="text-dark bg-light px-2 py-1 rounded">{{ $t->nip }}</code></td>
                        <td class="fw-semibold text-dark">{{ $t->name }}</td>
                        <td>
                            @if($t->role_tambahan == 'Guru BK')
                                <span class="badge bg-danger">{{ $t->role_tambahan }}</span>
                            @elseif($t->role_tambahan == 'Wali Kelas')
                                <span class="badge bg-warning text-dark">{{ $t->role_tambahan }}</span>
                            @else
                                <span class="badge bg-primary">{{ $t->role_tambahan }}</span>
                            @endif
                        </td>
                        <td>{{ $t->subject }}</td>
                        <td class="text-center">
                            <a href="/guru/{{ $t->id }}" class="btn btn-sm btn-outline-info rounded-circle shadow-sm" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
