@extends('layouts.app')

@section('content')
<style>
    .glass-card {
        background: #ffffff;
        border-radius: 15px;
        border: 1px solid rgba(0,0,0,0.05);
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
    }
    .form-control:focus, .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }
</style>

<div class="container-fluid py-2">

    {{-- Header --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-0">
                <i class="bi bi-pencil-square text-primary me-2"></i>Edit Data Siswa
            </h3>
            <p class="text-muted mb-0 mt-1">Perbarui informasi peserta didik EDUSPARC</p>
        </div>
        <div class="mt-3 mt-md-0">
            <a href="/siswa" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Alert Error Validasi --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Gagal menyimpan! Pastikan semua kolom diisi dengan benar dan NISN belum digunakan siswa lain.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Form Edit --}}
    <div class="glass-card p-4 border-top border-4 border-primary">
        <h5 class="fw-bold mb-4">
            <i class="bi bi-person-lines-fill text-primary me-2"></i>
            Formulir Edit — <span class="text-primary">{{ $student->name }}</span>
        </h5>

        <form action="/siswa/{{ $student->id }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-4">

                {{-- NISN --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small">
                        NISN <span class="text-danger">*</span>
                    </label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-upc-scan text-muted"></i>
                        </span>
                        <input type="text"
                               name="nisn"
                               class="form-control border-start-0 @error('nisn') is-invalid @enderror"
                               value="{{ old('nisn', $student->nisn) }}"
                               placeholder="Masukkan NISN"
                               required>
                        @error('nisn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Nama Lengkap --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small">
                        Nama Lengkap <span class="text-danger">*</span>
                    </label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-person text-muted"></i>
                        </span>
                        <input type="text"
                               name="name"
                               class="form-control border-start-0 @error('name') is-invalid @enderror"
                               value="{{ old('name', $student->name) }}"
                               placeholder="Nama lengkap siswa"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Kelas --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small">
                        Kelas <span class="text-danger">*</span>
                    </label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-door-open text-muted"></i>
                        </span>
                        <select name="class"
                                class="form-select border-start-0 @error('class') is-invalid @enderror"
                                required>
                            <option value="" disabled>Pilih Kelas...</option>
                            @foreach($data_kelas as $k)
                                <option value="{{ $k->nama_kelas }}"
                                    {{ old('class', $student->class) == $k->nama_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('class')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Tanggal Lahir --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small">
                        Tanggal Lahir <span class="text-danger">*</span>
                    </label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-calendar-date text-muted"></i>
                        </span>
                        <input type="date"
                               name="birth_date"
                               class="form-control border-start-0 @error('birth_date') is-invalid @enderror"
                               value="{{ old('birth_date', $student->birth_date) }}"
                               required>
                        @error('birth_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <small class="text-muted ms-1">
                        <i class="bi bi-info-circle me-1"></i>
                        Tanggal lahir juga digunakan sebagai password login orang tua.
                    </small>
                </div>

                {{-- Info Total Poin (readonly) --}}
                <div class="col-md-6">
                    <label class="form-label fw-semibold text-muted small">
                        Total Poin Pelanggaran
                    </label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-shield-exclamation text-muted"></i>
                        </span>
                        <input type="text"
                               class="form-control border-start-0 bg-light text-muted"
                               value="{{ $student->total_points }} Poin"
                               readonly>
                    </div>
                    <small class="text-muted ms-1">
                        <i class="bi bi-lock me-1"></i>
                        Poin dikelola otomatis melalui menu Pelanggaran.
                    </small>
                </div>

            </div>

            {{-- Tombol Aksi --}}
            <div class="d-flex gap-2 mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-primary px-5 shadow-sm fw-semibold">
                    <i class="bi bi-save me-1"></i> Simpan Perubahan
                </button>
                <a href="/siswa" class="btn btn-outline-secondary px-4">
                    <i class="bi bi-x-circle me-1"></i> Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
