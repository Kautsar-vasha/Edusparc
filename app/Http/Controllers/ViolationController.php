<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Violation;
use App\Models\Student;
use App\Models\Kelas;

class ViolationController extends Controller
{
    public function index() {
        if (session('role') !== 'admin' && session('role') !== 'guru') return redirect('/')->with('error', 'Akses ditolak!');

        $violations = Violation::latest()->get();
        $data_kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        $students = Student::orderBy('name', 'asc')->get();

        return view('pelanggaran.index', compact('violations', 'students', 'data_kelas'));
    }

    public function store(Request $request) {
        $request->validate([
            'student_id' => 'required',
            'type' => 'required',
            'category' => 'required',
            'points' => 'required|numeric'
        ]);

        // 1. Simpan Pelanggaran (Termasuk Motivasi)
        Violation::create([
            'student_id' => $request->student_id,
            'type' => $request->type,
            'category' => $request->category,
            'points' => $request->points,
            'description' => $request->description,
            'motivation' => $request->motivation // Input motivasi ditangkap di sini
        ]);

        // 2. UPDATE POIN SISWA (Tambah)
        $student = Student::find($request->student_id);
        $student->increment('total_points', $request->points);

        return back()->with('success', 'Pelanggaran dicatat dan poin siswa bertambah!');
    }

    public function destroy($id) {
        if (session('role') !== 'admin' && session('role') !== 'guru') return redirect('/')->with('error', 'Akses ditolak!');

        $violation = Violation::findOrFail($id);

        // 1. UPDATE POIN SISWA (Kurangi) sebelum data pelanggaran dihapus
        $student = Student::find($violation->student_id);
        if($student) {
            $student->decrement('total_points', $violation->points);
        }

        // 2. Hapus Data
        $violation->delete();

        return back()->with('success', 'Data dihapus dan poin siswa telah dikembalikan!');
    }
}
