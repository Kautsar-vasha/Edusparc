<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Violation;
use App\Models\Kelas;
use App\Models\Tatib;

class ViolationController extends Controller
{
    // 1. Fungsi Tampil Halaman & Filter Pencarian (DENGAN PAGINASI)
    public function index(Request $request) {
        $role = session('role');
        if (!in_array($role, ['admin', 'guru'])) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        $query = Violation::with('student');

        // Filter Kelas
        if ($request->filled('kelas')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class', $request->kelas);
            });
        }

        // Filter Jenis Poin
        if ($request->filled('jenis_poin')) {
            $query->where('jenis_poin', $request->jenis_poin);
        }

        // Filter Urutan Waktu
        if ($request->filled('sort_waktu') && $request->sort_waktu == 'terlama') {
            $query->orderBy('created_at', 'asc'); // Terlama ke Terbaru
        } else {
            $query->latest(); // Default: Terbaru ke Terlama
        }

        // MENGGUNAKAN PAGINATE (10 data per halaman)
        $violations = $query->paginate(10)->withQueryString();

        $data_kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        $students = Student::orderBy('name', 'asc')->get();
        $data_tatib = Tatib::orderBy('jenis')->orderBy('kode')->get();

        return view('pelanggaran.index', compact('violations', 'data_kelas', 'students', 'data_tatib'));
    }

    // 2. Fungsi AJAX Scanner (Mencari Siswa)
    public function cariSiswa($nis) {
        $student = Student::where('nisn', $nis)->first();

        if ($student) {
            return response()->json(['success' => true, 'data' => $student]);
        } else {
            return response()->json(['success' => false, 'message' => 'Siswa dengan NIS tersebut tidak ditemukan.']);
        }
    }

    // 3. Fungsi Simpan Poin (Manual & Scanner)
    public function store(Request $request) {
        $request->validate([
            'student_id'  => 'required',
            'jenis_poin'  => 'required',
            'type'        => 'required',
            'points'      => 'required|numeric',
        ]);

        Violation::create([
            'student_id'  => $request->student_id,
            'jenis_poin'  => $request->jenis_poin,
            'category'    => $request->category ?? 'Etika/Perilaku',
            'type'        => $request->type,
            'points'      => $request->points,
            'description' => $request->description,
            'motivation'  => $request->motivation,
        ]);

        $student = Student::findOrFail($request->student_id);

        // Logika Saldo
        if ($request->jenis_poin == 'positif') {
            $student->total_points += $request->points;
        } else {
            $student->total_points -= $request->points;
        }
        $student->save();

        return redirect('/pelanggaran')->with('success', 'Aktivitas berhasil dicatat untuk siswa: ' . $student->name);
    }

    // 4. Fungsi Hapus Riwayat
    public function destroy($id) {
        $role = session('role');
        if (!in_array($role, ['admin', 'guru'])) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        $violation = Violation::findOrFail($id);
        $student = Student::findOrFail($violation->student_id);

        if ($violation->jenis_poin == 'positif') {
            $student->total_points -= $violation->points;
        } else {
            $student->total_points += $violation->points;
        }
        $student->save();

        $violation->delete();

        return redirect('/pelanggaran')->with('success', 'Data riwayat berhasil dihapus, poin disesuaikan.');
    }

    // 5. Menampilkan Halaman Menu Khusus Scanner
    public function scanner() {
        $role = session('role');
        if (!in_array($role, ['admin', 'guru'])) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        return view('pelanggaran.scanner');
    }

    // 6. Fungsi Menyimpan Poin Otomatis dari Scanner (Tanpa Refresh Halaman)
    public function storeAjax(Request $request) {
        // Cari siswa berdasarkan NIS
        $student = Student::where('nisn', $request->nis)->first();

        if (!$student) {
            return response()->json(['success' => false, 'message' => 'NIS '.$request->nis.' tidak terdaftar!']);
        }

        // Simpan Pelanggaran
        Violation::create([
            'student_id'  => $student->id,
            'jenis_poin'  => $request->jenis_poin,
            'category'    => $request->category,
            'type'        => $request->type,
            'points'      => $request->points,
            'motivation'  => $request->motivation,
        ]);

        // Sesuaikan Saldo Poin
        if ($request->jenis_poin == 'positif') {
            $student->total_points += $request->points;
        } else {
            $student->total_points -= $request->points;
        }
        $student->save();

        return response()->json([
            'success'      => true,
            'student_name' => $student->name,
            'class'        => $student->class,
            'points'       => $request->points,
            'jenis'        => $request->jenis_poin
        ]);
    }
}
