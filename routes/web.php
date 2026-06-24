<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ViolationController;
use App\Http\Controllers\KelasController;
use App\Models\Student;
use App\Models\Kelas;
use App\Models\Violation;
use App\Models\Teacher;

// ── AKSES PUBLIK ───────────────────────────────────────────────────────────
// 1. Halaman Homepage Utama (Front-end)
Route::get('/', function () {
    return view('welcome'); // Akan memanggil file welcome.blade.php
});

// 2. Halaman Login
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

// ── FUNGSI STATISTIK & GRAFIK DASHBOARD ─────────────────────────────────────
function getDashboardData() {
    $chart_labels       = [];
    $chart_data_positif = [];
    $chart_data_negatif = [];

    // Menghitung data grafik tren 6 bulan terakhir
    for ($i = 5; $i >= 0; $i--) {
        $date           = now()->subMonths($i);
        $chart_labels[] = $date->format('M');

        // Akumulasi poin kebaikan (positif) per bulan
        $chart_data_positif[] = Violation::where('jenis_poin', 'positif')
                                   ->whereMonth('created_at', $date->month)
                                   ->whereYear('created_at', $date->year)
                                   ->sum('points');

        // Akumulasi poin pelanggaran (negatif) per bulan
        $chart_data_negatif[] = Violation::where('jenis_poin', 'negatif')
                                   ->whereMonth('created_at', $date->month)
                                   ->whereYear('created_at', $date->year)
                                   ->sum('points');
    }

    return [
        'total_siswa'       => Student::count(),
        'total_kelas'       => Kelas::count(),
        'total_guru'        => Teacher::count(),
        'kasus_bulan_ini'   => Violation::where('jenis_poin', 'negatif')
                                        ->whereMonth('created_at', date('m'))
                                        ->whereYear('created_at', date('Y'))
                                        ->count(),
        'poin_tertinggi'    => Student::max('total_points') ?? 0,
        'poin_terendah'     => Student::min('total_points') ?? 0,
        'aktivitas_terbaru' => Violation::with('student')->latest()->take(5)->get(),
        'chart_labels'      => $chart_labels,
        'chart_data_pos'    => $chart_data_positif,
        'chart_data_neg'    => $chart_data_negatif,
    ];
}

// ── DASHBOARD BERDASARKAN ROLE ──────────────────────────────────────────────
Route::get('/dashboard-admin', function () {
    if (!in_array(session('role'), ['admin', 'super_admin'])) {
        return redirect('/')->with('error', 'Akses Ditolak!');
    }
    return view('dashboard_guru', getDashboardData());
});

Route::get('/dashboard-guru', function () {
    if (session('role') !== 'guru') {
        return redirect('/')->with('error', 'Akses Ditolak!');
    }
    return view('dashboard_guru', getDashboardData());
});

Route::get('/dashboard-ortu', function () {
    if (session('role') !== 'ortu') {
        return redirect('/')->with('error', 'Akses Ditolak!');
    }
    return view('dashboard_ortu');
});

// ── GRUP MANAJEMEN (ADMIN & GURU) ──────────────────────────────
Route::middleware(['web'])->group(function () {

    // ── GURU ──────────────────────────────────────────────────────────────────
    Route::get('/guru',             [TeacherController::class, 'index']);
    Route::get('/guru/create',      [TeacherController::class, 'create']);
    Route::post('/guru',            [TeacherController::class, 'store']);
    Route::post('/guru/import',     [TeacherController::class, 'import']);
    Route::get('/guru/{id}',        [TeacherController::class, 'show']);
    Route::delete('/guru/{id}',     [TeacherController::class, 'destroy']);

    //QR CODE
    Route::get('/siswa/{id}/qr', [App\Http\Controllers\StudentController::class, 'cetakQr']);

    // ── SISWA ─────────────────────────────────────────────────────────────────
    Route::get('/siswa',            [StudentController::class, 'index']);
    Route::post('/siswa',           [StudentController::class, 'store']);
    Route::post('/siswa/import',    [StudentController::class, 'import']);
    Route::get('/kenaikan-kelas',   [StudentController::class, 'formKenaikan']);
    Route::post('/kenaikan-kelas',  [StudentController::class, 'prosesKenaikan']);

    // --- FITUR BARU: HAPUS ALUMNI (Harus di atas {id}) ---
    Route::delete('/siswa/hapus-lulus', [StudentController::class, 'hapusLulus']);

    Route::get('/siswa/{id}/edit',  [StudentController::class, 'edit']);
    Route::put('/siswa/{id}',       [StudentController::class, 'update']);
    Route::delete('/siswa/{id}',    [StudentController::class, 'destroy']);

    // ── PELANGGARAN & KEBAIKAN ────────────────────────────────────────────────
    Route::get('/pelanggaran',      [ViolationController::class, 'index']);
    Route::post('/pelanggaran',     [ViolationController::class, 'store']);
    Route::delete('/pelanggaran/{id}', [ViolationController::class, 'destroy']);

    // ── FITUR QUICK SCANNER ─────────────────────────────────────────
    Route::get('/scanner',                 [ViolationController::class, 'scanner']);
    Route::post('/scanner/store',          [ViolationController::class, 'storeAjax']);

    // ── KELAS ─────────────────────────────────────────────────────────────────
    Route::get('/kelas',            [KelasController::class, 'index']);
    Route::post('/kelas',           [KelasController::class, 'store']);
    Route::put('/kelas/{id}',       [KelasController::class, 'update']);
    Route::delete('/kelas/{id}',    [KelasController::class, 'destroy']);

    // ── MASTER TATA TERTIB (Khusus Admin) ─────────────────────────────────────
    Route::get('/tatib',            [\App\Http\Controllers\TatibController::class, 'index']);
    Route::post('/tatib',           [\App\Http\Controllers\TatibController::class, 'store']);
    Route::put('/tatib/{id}',       [\App\Http\Controllers\TatibController::class, 'update']);
    Route::delete('/tatib/{id}',    [\App\Http\Controllers\TatibController::class, 'destroy']);

    Route::post('/tatib/import',    [\App\Http\Controllers\TatibController::class, 'import']);
    Route::put('/tatib/{id}',       [\App\Http\Controllers\TatibController::class, 'update']);
    Route::delete('/tatib/{id}',    [\App\Http\Controllers\TatibController::class, 'destroy']);

    // ── PENGATURAN ────────────────────────────────────────────────────────────
    Route::get('/pengaturan',           [App\Http\Controllers\SettingsController::class, 'index']);
    Route::post('/pengaturan/password', [App\Http\Controllers\SettingsController::class, 'updatePassword']);

    // ── LAPORAN ───────────────────────────────────────────────────────────────
    Route::get('/laporan/guru/{format}',  [App\Http\Controllers\ReportController::class, 'cetakGuru']);
    Route::get('/laporan/kelas',          [App\Http\Controllers\ReportController::class, 'cetakKelas']);
    Route::get('/laporan/riwayat',        [App\Http\Controllers\ReportController::class, 'cetakRiwayat']);

});
