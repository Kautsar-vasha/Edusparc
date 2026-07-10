<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Student; // Pastikan model Student dipanggil
use Illuminate\Http\Request;

class KelasController extends Controller
{
    // Menampilkan halaman data kelas
    public function index()
    {
        $data_kelas = Kelas::all();
        return view('kelas.index', compact('data_kelas'));
    }

    // --- FUNGSI BARU: Melihat Struktur Siswa dalam Kelas ---
    public function show($id)
    {
        $kelas = Kelas::findOrFail($id);

        // Cari siswa yang kolom 'class'-nya sama dengan nama kelas ini
        $students = Student::where('class', $kelas->nama_kelas)
                           ->orderBy('name', 'asc')
                           ->get();

        return view('kelas.show', compact('kelas', 'students'));
    }

    // Menyimpan kelas baru
    public function store(Request $request)
    {
        if (session('role') !== 'admin') return redirect('/kelas')->with('error', 'Akses ditolak!');

        $request->validate([
            'nama_kelas' => 'required|unique:kelas,nama_kelas'
        ], [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'nama_kelas.unique' => 'Nama kelas ini sudah terdaftar!'
        ]);

        Kelas::create(['nama_kelas' => $request->nama_kelas]);
        return back()->with('success', 'Kelas baru berhasil ditambahkan!');
    }

    // Mengupdate/mengedit nama kelas
    public function update(Request $request, $id)
    {
        if (session('role') !== 'admin') return redirect('/kelas')->with('error', 'Akses ditolak!');

        $kelas = Kelas::findOrFail($id);
        $request->validate([
            'nama_kelas' => 'required|unique:kelas,nama_kelas,' . $id
        ]);

        $kelas->update(['nama_kelas' => $request->nama_kelas]);
        return back()->with('success', 'Data kelas berhasil diperbarui!');
    }

    // Menghapus kelas
    public function destroy($id)
    {
        if (session('role') !== 'admin') return redirect('/kelas')->with('error', 'Akses ditolak!');

        $kelas = Kelas::findOrFail($id);
        $kelas->delete();
        return back()->with('success', 'Kelas berhasil dihapus!');
    }
}
