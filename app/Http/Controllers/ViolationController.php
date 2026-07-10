<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Violation;
use App\Models\Kelas;
use App\Models\Tatib;
use Illuminate\Support\Facades\Storage; // <--- Wajib dipanggil untuk kelola file

class ViolationController extends Controller
{
    public function index(Request $request) {
        $role = session('role');
        if (!in_array($role, ['admin', 'guru'])) return redirect('/')->with('error', 'Akses ditolak!');

        $peran_guru = '';
        if ($role === 'guru') {
            $guru = \App\Models\Teacher::where('username', session('username'))->first();
            if ($guru) $peran_guru = strtolower($guru->role_tambahan);
        }

        $query = Violation::with('student');

        if ($request->filled('kelas')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class', $request->kelas);
            });
        }
        if ($request->filled('jenis_poin')) $query->where('jenis_poin', $request->jenis_poin);

        if ($request->filled('sort_waktu') && $request->sort_waktu == 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->latest();
        }

        $violations = $query->paginate(10)->withQueryString();
        $data_kelas = Kelas::orderBy('nama_kelas', 'asc')->get();
        $students = Student::orderBy('name', 'asc')->get();

        $data_tatib = Tatib::all()->sort(function($a, $b) {
            if ($a->jenis == $b->jenis) return strnatcmp($a->kode, $b->kode);
            return strcmp($a->jenis, $b->jenis);
        })->values();

        return view('pelanggaran.index', compact('violations', 'data_kelas', 'students', 'data_tatib', 'peran_guru'));
    }

    public function cariSiswa($nis) {
        $student = Student::where('nisn', $nis)->first();
        if ($student) return response()->json(['success' => true, 'data' => $student]);
        return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan.']);
    }

    public function store(Request $request) {
        $request->validate([
            'student_id'  => 'required',
            'jenis_poin'  => 'required',
            'type'        => 'required',
            'points'      => 'required|numeric',
            'bukti_foto'  => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
        ]);

        // --- LOGIKA SIMPAN FOTO ---
        $fotoPath = null;
        if ($request->hasFile('bukti_foto')) {
            $fotoPath = $request->file('bukti_foto')->store('bukti', 'public');
        }

        Violation::create([
            'student_id'  => $request->student_id,
            'jenis_poin'  => $request->jenis_poin,
            'category'    => $request->category ?? 'Etika/Perilaku',
            'type'        => $request->type,
            'points'      => $request->points,
            'description' => $request->description,
            'motivation'  => $request->motivation,
            'bukti_foto'  => $fotoPath, // Simpan ke database
        ]);

        $student = Student::findOrFail($request->student_id);

        if ($request->jenis_poin == 'positif') {
            $student->total_points += $request->points;
        } else {
            $student->total_points -= $request->points;
        }
        $student->save();

        return redirect('/pelanggaran')->with('success', 'Aktivitas & bukti berhasil dicatat untuk: ' . $student->name);
    }

    public function destroy($id) {
        $role = session('role');
        if (!in_array($role, ['admin', 'guru'])) return redirect('/')->with('error', 'Akses ditolak!');

        $violation = Violation::findOrFail($id);

        // --- LOGIKA HAPUS FILE FISIK ---
        if ($violation->bukti_foto) {
            Storage::disk('public')->delete($violation->bukti_foto);
        }

        $student = Student::findOrFail($violation->student_id);
        if ($violation->jenis_poin == 'positif') {
            $student->total_points -= $violation->points;
        } else {
            $student->total_points += $violation->points;
        }
        $student->save();

        $violation->delete();
        return redirect('/pelanggaran')->with('success', 'Data riwayat beserta fotonya berhasil dihapus.');
    }

    public function scanner() {
        if (!in_array(session('role'), ['admin', 'guru'])) return redirect('/')->with('error', 'Akses ditolak!');
        return view('pelanggaran.scanner');
    }

    public function storeAjax(Request $request) {
        $student = Student::where('nisn', $request->nis)->first();
        if (!$student) return response()->json(['success' => false, 'message' => 'NIS '.$request->nis.' tidak terdaftar!']);

        Violation::create([
            'student_id'  => $student->id,
            'jenis_poin'  => $request->jenis_poin,
            'category'    => $request->category,
            'type'        => $request->type,
            'points'      => $request->points,
            'motivation'  => $request->motivation,
        ]);

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

    public function tanggapanOrtu(Request $request, $id) {
        if (session('role') !== 'ortu') return redirect('/')->with('error', 'Akses ditolak!');
        $request->validate(['tanggapan_ortu' => 'required|string|max:500']);

        Violation::findOrFail($id)->update(['tanggapan_ortu' => $request->tanggapan_ortu]);
        return back()->with('success', 'Tanggapan Anda berhasil dikirim ke sekolah!');
    }
}
