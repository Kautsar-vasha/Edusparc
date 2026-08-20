<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Kelas;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentImport;
use Illuminate\Support\Facades\Storage; 

class StudentController extends Controller
{
    public function index(Request $request) {
        if (!in_array(session('role'), ['admin', 'guru'])) return redirect('/')->with('error', 'Akses ditolak!');
        $data_kelas = Kelas::all();
        $query = Student::query();
        if ($request->has('class') && $request->class != '') $query->where('class', $request->class);
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

    public function show($id) {
        if (!in_array(session('role'), ['admin', 'guru'])) return redirect('/')->with('error', 'Akses ditolak!');
        $student = \App\Models\Student::findOrFail($id);
        $is_wali_or_bk = false;

        if (session('role') === 'admin') {
            $is_wali_or_bk = true;
        } elseif (session('role') === 'guru') {
            $guru = \App\Models\Teacher::where('username', session('username'))->first();
            if ($guru) {
                $peran = strtolower($guru->role_tambahan);
                $kelas_siswa = strtolower($student->class);
                if (str_contains($peran, 'bk') || str_contains($peran, $kelas_siswa)) {
                    $is_wali_or_bk = true;
                }
            }
        }
        $riwayat = \App\Models\Violation::where('student_id', $id)->latest()->get();
        return view('siswa.show', compact('student', 'riwayat', 'is_wali_or_bk'));
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

    public function import(Request $request) {
        if (session('role') !== 'admin') return redirect('/')->with('error', 'Hanya Admin!');
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv']);
        try {
            Excel::import(new StudentImport, $request->file('file'));
            return back()->with('success', 'Data siswa berhasil diimpor!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal mengimpor file!']);
        }
    }

    public function cetakQr($id) {
        if (!in_array(session('role'), ['admin', 'guru'])) return redirect('/')->with('error', 'Akses ditolak!');
        $student = \App\Models\Student::findOrFail($id);
        return view('siswa.qr', compact('student'));
    }

    public function formKenaikan() {
        if (session('role') !== 'admin' && session('role') !== 'super_admin') return redirect('/')->with('error', 'Hanya Admin!');
        return view('siswa.kenaikan');
    }

    public function prosesKenaikan(Request $request) {
        if (session('role') !== 'admin' && session('role') !== 'super_admin') return redirect('/')->with('error', 'Akses Ditolak!');

        $students = Student::all();
        $naik = 0; $lulus = 0;

        foreach($students as $siswa) {
            $kelasLama = trim($siswa->class);
            if (preg_match('/^(IX|9)\b/i', $kelasLama)) {
                $siswa->class = 'Lulus'; $lulus++;
            } elseif (preg_match('/^(VIII|8)\b/i', $kelasLama)) {
                $siswa->class = preg_replace('/^(VIII|8)\b/i', 'IX', $kelasLama); $naik++;
            } elseif (preg_match('/^(VII|7)\b/i', $kelasLama)) {
                $siswa->class = preg_replace('/^(VII|7)\b/i', 'VIII', $kelasLama); $naik++;
            }

            if ($request->has('reset_poin')) $siswa->total_points = 0;
            $siswa->save();
        }

        // --- PENGHAPUSAN FILE FISIK SEBELUM TRUNCATE ---
        if ($request->has('reset_riwayat')) {
            $riwayatBerfoto = \App\Models\Violation::whereNotNull('bukti_foto')->get();
            foreach($riwayatBerfoto as $r) {
                Storage::disk('public')->delete($r->bukti_foto);
            }
            \App\Models\Violation::truncate();
        }

        return redirect('/siswa')->with('success', "Tahun Ajaran Baru Berhasil Dimulai! $naik naik kelas, $lulus lulus.");
    }

    public function hapusLulus() {
        if (!in_array(session('role'), ['admin', 'super_admin'])) return redirect('/')->with('error', 'Akses Ditolak!');

        $siswaLulus = Student::where('class', 'Lulus')->get();
        $jumlah = $siswaLulus->count();

        if ($jumlah > 0) {
            $idSiswaLulus = $siswaLulus->pluck('id');

            // --- HAPUS FILE FISIK MILIK ALUMNI SAJA ---
            $riwayatBerfotoLulus = \App\Models\Violation::whereIn('student_id', $idSiswaLulus)
                                        ->whereNotNull('bukti_foto')->get();
            foreach($riwayatBerfotoLulus as $r) {
                Storage::disk('public')->delete($r->bukti_foto);
            }

            \App\Models\Violation::whereIn('student_id', $idSiswaLulus)->delete();
            Student::whereIn('id', $idSiswaLulus)->delete();

            return redirect('/siswa')->with('success', "Pembersihan Sukses! $jumlah data alumni beserta riwayat karakternya telah dihapus permanen.");
        }

        return redirect('/siswa')->with('error', "Tidak ada data siswa berstatus 'Lulus'.");
    }
}
