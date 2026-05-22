<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Kelas; // Tambahan: Import model Kelas agar bisa mengambil data kelas

class StudentController extends Controller
{
    // Akses: Admin & Guru
    public function index(Request $request) {
        if (session('role') !== 'admin' && session('role') !== 'guru') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        // Tambahan: Ambil data kelas untuk mengisi dropdown di halaman index
        $data_kelas = Kelas::all();

        // Logika untuk menangani Filter Kelas
        $query = Student::query();

        if ($request->has('class') && $request->class != '') {
            $query->where('class', $request->class);
        }

        $students = $query->get();

        // Tambahan: Kirim juga $data_kelas menggunakan compact
        return view('siswa.index', compact('students', 'data_kelas'));
    }

    // Akses: Admin & Guru
    public function store(Request $request) {
        if (session('role') !== 'admin' && session('role') !== 'guru') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        // Validasi input untuk mencegah duplikasi NISN
        $request->validate([
            'nisn' => 'required|unique:students,nisn',
            'name' => 'required|string',
            'class' => 'required|string',
            'birth_date' => 'required|date'
        ]);

        // Menyimpan data ke database
        Student::create([
            'nisn' => $request->nisn,
            'name' => $request->name,
            'class' => $request->class,
            'birth_date' => $request->birth_date,
            'total_points' => 0 // Poin default saat siswa baru ditambahkan
        ]);

        return back()->with('success', 'Data Siswa berhasil disimpan!');
    }

    // Tambahan: Menampilkan form Edit Siswa
    public function edit($id) {
        if (session('role') !== 'admin' && session('role') !== 'guru') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        $student = Student::findOrFail($id);
        $data_kelas = Kelas::all(); // Tetap butuh data kelas untuk dropdown edit

        return view('siswa.edit', compact('student', 'data_kelas'));
    }

    // Tambahan: Memproses Update (Edit) Data Siswa
    public function update(Request $request, $id) {
        if (session('role') !== 'admin' && session('role') !== 'guru') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        $student = Student::findOrFail($id);

        $request->validate([
            // unique:students,nisn,'.$id berguna agar NISN miliknya sendiri tidak dianggap duplikat
            'nisn' => 'required|unique:students,nisn,' . $id,
            'name' => 'required|string',
            'class' => 'required|string',
            'birth_date' => 'required|date'
        ]);

        $student->update([
            'nisn' => $request->nisn,
            'name' => $request->name,
            'class' => $request->class,
            'birth_date' => $request->birth_date
        ]);

        // Setelah sukses edit, kembali ke daftar siswa
        return redirect('/siswa')->with('success', 'Data Siswa berhasil diperbarui!');
    }

    // Tambahan: Memproses Hapus Data Siswa
    public function destroy($id) {
        if (session('role') !== 'admin' && session('role') !== 'guru') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }

        $student = Student::findOrFail($id);
        $student->delete();

        return back()->with('success', 'Data Siswa berhasil dihapus!');
    }
}
