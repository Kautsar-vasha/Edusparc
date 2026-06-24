<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Kelas;
use Maatwebsite\Excel\Facades\Excel; // Tambahan untuk fitur Excel
use App\Imports\StudentImport;       // Import file logika impor

class StudentController extends Controller
{
    public function index(Request $request) {
        if (!in_array(session('role'), ['admin', 'guru'])) return redirect('/')->with('error', 'Akses ditolak!');

        $data_kelas = Kelas::all();
        $query = Student::query();

        if ($request->has('class') && $request->class != '') {
            $query->where('class', $request->class);
        }

        $students = $query->get();
        return view('siswa.index', compact('students', 'data_kelas'));
    }

    public function store(Request $request) {
        if (!in_array(session('role'), ['admin', 'guru'])) return redirect('/')->with('error', 'Akses ditolak!');

        $request->validate([
            'nisn' => 'required|unique:students,nisn',
            'name' => 'required|string',
            'class' => 'required|string',
            'birth_date' => 'required|date'
        ]);

        Student::create(array_merge($request->all(), ['total_points' => 0]));

        return back()->with('success', 'Data Siswa berhasil disimpan!');
    }

    public function edit($id) {
        if (!in_array(session('role'), ['admin', 'guru'])) return redirect('/')->with('error', 'Akses ditolak!');

        $student = Student::findOrFail($id);
        $data_kelas = Kelas::all();

        return view('siswa.edit', compact('student', 'data_kelas'));
    }

    public function update(Request $request, $id) {
        if (!in_array(session('role'), ['admin', 'guru'])) return redirect('/')->with('error', 'Akses ditolak!');

        $student = Student::findOrFail($id);
        $request->validate([
            'nisn' => 'required|unique:students,nisn,' . $id,
            'name' => 'required|string',
            'class' => 'required|string',
            'birth_date' => 'required|date'
        ]);

        $student->update($request->all());

        return redirect('/siswa')->with('success', 'Data Siswa berhasil diperbarui!');
    }

    public function destroy($id) {
        if (!in_array(session('role'), ['admin', 'guru'])) return redirect('/')->with('error', 'Akses ditolak!');

        Student::findOrFail($id)->delete();
        return back()->with('success', 'Data Siswa berhasil dihapus!');
    }

    // --- FUNGSI BARU UNTUK IMPORT EXCEL ---
    public function import(Request $request) {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Hanya Admin yang dapat melakukan impor data!');

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new StudentImport, $request->file('file'));
            return back()->with('success', 'Data siswa berhasil diimpor dari file Excel!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengimpor file! Pastikan format tabel (header) sesuai dengan ketentuan.']);
        }
    }

    // --- FUNGSI CETAK QR CODE ---
    public function cetakQr($id) {
        $role = session('role');
        if (!in_array($role, ['admin', 'guru'])) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        $student = \App\Models\Student::findOrFail($id);
        return view('siswa.qr', compact('student'));
    }

    // ── FITUR KENAIKAN KELAS MASSAL ───────────────────────────────────────────

    // 1. Tampilkan Halaman Konfirmasi Kenaikan Kelas
    public function formKenaikan() {
        if (session('role') !== 'admin' && session('role') !== 'super_admin') {
            return redirect('/')->with('error', 'Hanya Admin yang dapat mengakses fitur ini!');
        }
        return view('siswa.kenaikan');
    }

    // 2. Eksekusi Logika Kenaikan Kelas
    public function prosesKenaikan(Request $request) {
        if (session('role') !== 'admin' && session('role') !== 'super_admin') {
            return redirect('/')->with('error', 'Akses Ditolak!');
        }

        $students = Student::all();
        $naik = 0;
        $lulus = 0;

        foreach($students as $siswa) {
            $kelasLama = trim($siswa->class);

            // URUTAN SANGAT PENTING!

            // 1. Luluskan kelas 9 / IX
            // Logika regex ini mencari kata "IX" atau "9" di awal nama kelas (contoh: "IX A" atau "9 B")
            if (preg_match('/^(IX|9)\b/i', $kelasLama)) {
                $siswa->class = 'Lulus';
                $lulus++;
            }
            // 2. Naikkan kelas 8 / VIII ke 9 / IX
            elseif (preg_match('/^(VIII|8)\b/i', $kelasLama)) {
                $siswa->class = preg_replace('/^(VIII|8)\b/i', 'IX', $kelasLama);
                $naik++;
            }
            // 3. Naikkan kelas 7 / VII ke 8 / VIII
            elseif (preg_match('/^(VII|7)\b/i', $kelasLama)) {
                $siswa->class = preg_replace('/^(VII|7)\b/i', 'VIII', $kelasLama);
                $naik++;
            }

            // Opsi 1: Reset semua poin siswa menjadi 0 (Tabula Rasa Tahun Ajaran Baru)
            if ($request->has('reset_poin')) {
                $siswa->total_points = 0;
            }

            $siswa->save();
        }

        // Opsi 2: Hapus semua riwayat catatan karakter lama
        if ($request->has('reset_riwayat')) {
            \App\Models\Violation::truncate();
        }

        return redirect('/siswa')->with('success', "Tahun Ajaran Baru Berhasil Dimulai! $naik siswa telah naik kelas dan $lulus siswa dinyatakan lulus.");
    }
    
    // ── FITUR PEMBERSIHAN DATA ALUMNI (LULUS) ────────────────────────────────
    public function hapusLulus() {
        if (!in_array(session('role'), ['admin', 'super_admin'])) {
            return redirect('/')->with('error', 'Akses Ditolak!');
        }

        // Cari semua siswa yang kelasnya 'Lulus'
        $siswaLulus = Student::where('class', 'Lulus')->get();
        $jumlah = $siswaLulus->count();

        if ($jumlah > 0) {
            // Ambil semua ID siswa yang lulus
            $idSiswaLulus = $siswaLulus->pluck('id');

            // Hapus riwayat pelanggaran mereka lebih dulu (agar database tidak error)
            \App\Models\Violation::whereIn('student_id', $idSiswaLulus)->delete();

            // Setelah riwayatnya bersih, baru hapus data siswanya
            Student::whereIn('id', $idSiswaLulus)->delete();

            return redirect('/siswa')->with('success', "Pembersihan Sukses! $jumlah data alumni beserta riwayat karakternya telah dihapus permanen.");
        }

        return redirect('/siswa')->with('error', "Tidak ada data siswa berstatus 'Lulus' di dalam sistem saat ini.");
    }
}
