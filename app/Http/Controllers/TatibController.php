<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tatib;

class TatibController extends Controller
{
    // Menampilkan halaman daftar Tata Tertib
    public function index()
    {
        if (!in_array(session('role'), ['admin', 'super_admin'])) {
            return redirect('/')->with('error', 'Hanya Admin yang dapat mengakses Master Tata Tertib!');
        }

        // Urutkan berdasarkan jenis (Negatif dulu, baru Positif) lalu berdasarkan Kode
        $tatibs = Tatib::orderBy('jenis')->orderBy('kode')->get();
        return view('tatib.index', compact('tatibs'));
    }

    // Menyimpan aturan baru
    public function store(Request $request)
    {
        $request->validate([
            'kode'     => 'required|unique:tatibs,kode',
            'jenis'    => 'required',
            'kategori' => 'required',
            'uraian'   => 'required',
            'poin'     => 'required|numeric|min:1',
        ]);

        Tatib::create($request->all());
        return back()->with('success', 'Aturan tata tertib berhasil ditambahkan!');
    }

    // Mengupdate aturan yang ada
    public function update(Request $request, $id)
    {
        $tatib = Tatib::findOrFail($id);

        $request->validate([
            'kode'     => 'required|unique:tatibs,kode,' . $id,
            'jenis'    => 'required',
            'kategori' => 'required',
            'uraian'   => 'required',
            'poin'     => 'required|numeric|min:1',
        ]);

        $tatib->update($request->all());
        return back()->with('success', 'Aturan tata tertib berhasil diperbarui!');
    }

    // Menghapus aturan
    public function destroy($id)
    {
        Tatib::findOrFail($id)->delete();
        return back()->with('success', 'Aturan tata tertib berhasil dihapus!');
    }
    // Fungsi Import Data dari Excel/CSV
    public function import(Request $request)
    {
        // Validasi file harus berupa CSV
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048'
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getPathname(), "r");

        $header = true;
        $berhasil = 0;

        // Looping untuk membaca baris per baris isi CSV
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Lewati baris pertama karena biasanya berisi judul Header (Kode, Jenis, dll)
            if ($header) {
                $header = false;
                continue;
            }

            // Pastikan kolom data cukup (minimal 5 kolom: kode, jenis, kategori, uraian, poin)
            if (count($data) >= 5) {
                // updateOrCreate berguna agar jika kode (misal a.1) sudah ada, datanya diperbarui, bukan error
                Tatib::updateOrCreate(
                    ['kode' => trim($data[0])],
                    [
                        'jenis'    => strtolower(trim($data[1])), // Harus 'negatif' atau 'positif'
                        'kategori' => trim($data[2]),             // 'Spiritual' atau 'Sosial'
                        'uraian'   => trim($data[3]),
                        'poin'     => (int) $data[4],
                        'sanksi'   => isset($data[5]) ? trim($data[5]) : null,
                    ]
                );
                $berhasil++;
            }
        }
        fclose($handle);

        return back()->with('success', "Luar biasa! $berhasil aturan tata tertib berhasil diimpor.");
    }
}
