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
use App\Models\User;

// Akses Publik
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout']);

// Fungsi untuk mengambil data statistik dan grafik Dashboard
function getDashboardData() {
    // Menghitung data grafik 6 bulan terakhir
    $chart_labels = [];
    $chart_data = [];
    for ($i = 5; $i >= 0; $i--) {
        $date = now()->subMonths($i);
        $chart_labels[] = $date->format('M');
        $chart_data[] = Violation::whereMonth('created_at', $date->month)
                                 ->whereYear('created_at', $date->year)
                                 ->count();
    }

    return [
        'total_siswa' => Student::count(),
        'total_kelas' => Kelas::count(),
        // Menghitung semua staf (Admin + Guru) agar tidak 0
        'total_guru' => User::whereIn('role', ['admin', 'guru'])->count(),
        'kasus_bulan_ini' => Violation::whereMonth('created_at', date('m'))
                                      ->whereYear('created_at', date('Y'))->count(),
        'poin_tertinggi' => Student::max('total_points') ?? 0,
        'aktivitas_terbaru' => Violation::with('student')->latest()->take(5)->get(),
        'chart_labels' => $chart_labels,
        'chart_data' => $chart_data,
    ];
}

// Dashboard Berdasarkan Role
Route::get('/dashboard-admin', function() {
    if (session('role') !== 'admin') return redirect('/')->with('error', 'Akses Ditolak!');
    return view('dashboard_guru', getDashboardData());
});

Route::get('/dashboard-guru', function() {
    if (session('role') !== 'guru') return redirect('/')->with('error', 'Akses Ditolak!');
    return view('dashboard_guru', getDashboardData());
});

Route::get('/dashboard-ortu', function() {
    if (session('role') !== 'ortu') return redirect('/')->with('error', 'Akses Ditolak!');
    return view('dashboard_ortu');
});

// Grup Manajemen (Admin & Guru)
Route::middleware(['web'])->group(function () {
    Route::get('/guru', [TeacherController::class, 'index']);
    Route::get('/guru/create', [TeacherController::class, 'create']);
    Route::post('/guru', [TeacherController::class, 'store']);
    Route::get('/guru/{id}', [TeacherController::class, 'show']);

    Route::get('/siswa', [StudentController::class, 'index']);
    Route::post('/siswa', [StudentController::class, 'store']);
    Route::get('/siswa/{id}/edit', [StudentController::class, 'edit']);
    Route::put('/siswa/{id}', [StudentController::class, 'update']);
    Route::delete('/siswa/{id}', [StudentController::class, 'destroy']);

    Route::get('/pelanggaran', [ViolationController::class, 'index']);
    Route::post('/pelanggaran', [ViolationController::class, 'store']);
    Route::delete('/pelanggaran/{id}', [ViolationController::class, 'destroy']);

    Route::get('/kelas', [KelasController::class, 'index']);
    Route::post('/kelas', [KelasController::class, 'store']);
    Route::put('/kelas/{id}', [KelasController::class, 'update']);
    Route::delete('/kelas/{id}', [KelasController::class, 'destroy']);

    // --- PENGATURAN & TEMA ---
    Route::get('/pengaturan', [App\Http\Controllers\SettingsController::class, 'index']);
    Route::post('/pengaturan/password', [App\Http\Controllers\SettingsController::class, 'updatePassword']);

    // --- MANAJEMEN LAPORAN ---
    Route::get('/laporan/guru/{format}', [App\Http\Controllers\ReportController::class, 'cetakGuru']);
    Route::get('/laporan/kelas/{format}', [App\Http\Controllers\ReportController::class, 'cetakKelas']);
    Route::get('/laporan/siswa', [App\Http\Controllers\ReportController::class, 'cetakSiswa']);
    Route::get('/laporan/pelanggaran/{format}', [App\Http\Controllers\ReportController::class, 'cetakPelanggaran']);
});
