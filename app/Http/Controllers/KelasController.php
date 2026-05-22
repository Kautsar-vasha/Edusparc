<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    // Menampilkan halaman data kelas
    public function index()
    {
        $data_kelas = Kelas::all();
        return view('kelas.index', compact('data_kelas'));
    }

    // Menyimpan kelas baru
    public function store(Request $request)
    {
        // Validasi agar tidak ada nama kelas yang kosong atau kembar
        $request->validate([
            'nama_kelas' => 'required|unique:kelas,nama_kelas'
        ], [
            'nama_kelas.required' => 'Nama kelas wajib diisi.',
            'nama_kelas.unique' => 'Nama kelas ini sudah terdaftar!'
        ]);

        Kelas::create([
            'nama_kelas' => $request->nama_kelas
        ]);

        return back()->with('success', 'Kelas baru berhasil ditambahkan!');
    }

    // Mengupdate/mengedit nama kelas
    public function update(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);

        $request->validate([
            // Mengecualikan ID ini dari aturan "unique" agar bisa disimpan jika namanya tidak berubah
            'nama_kelas' => 'required|unique:kelas,nama_kelas,' . $id
        ]);

        $kelas->update([
            'nama_kelas' => $request->nama_kelas
        ]);

        return back()->with('success', 'Data kelas berhasil diperbarui!');
    }

    // Menghapus kelas
    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return back()->with('success', 'Kelas berhasil dihapus!');
    }
}
