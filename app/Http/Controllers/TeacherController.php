<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\TeacherImport;

class TeacherController extends Controller
{
    // Akses: Admin & Guru
    public function index() {
        $role = session('role');
        if (!in_array($role, ['admin', 'guru'])) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        $teachers = Teacher::all();
        return view('guru.index', compact('teachers'));
    }

    // Akses: Hanya Admin
    public function create() {
        if (session('role') !== 'admin') {
            return redirect('/guru')->with('error', 'Hanya Admin yang dapat menambah data guru.');
        }
        return view('guru.create');
    }

    // Akses: Hanya Admin
    public function store(Request $request) {
        if (session('role') !== 'admin') {
            return redirect('/guru')->with('error', 'Akses ditolak!');
        }

        Teacher::create([
            'nip'           => $request->nip,
            'name'          => $request->name,
            'username'      => $request->username,
            'subject'       => $request->subject,
            'role_tambahan' => $request->role_tambahan,
            'phone'         => $request->phone,
            'password'      => bcrypt('12345'),
        ]);

        return redirect('/guru')->with('success', 'Data Guru berhasil ditambahkan!');
    }

    // Akses: Admin & Guru
    public function show($id) {
        $role = session('role');
        if (!in_array($role, ['admin', 'guru'])) {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        $teacher = Teacher::findOrFail($id);
        return view('guru.show', compact('teacher'));
    }

    // Akses: Hanya Admin
    public function destroy($id) {
        if (session('role') !== 'admin') {
            return redirect('/guru')->with('error', 'Hanya Admin yang dapat menghapus data guru.');
        }
        Teacher::findOrFail($id)->delete();
        return redirect('/guru')->with('success', 'Data Guru berhasil dihapus!');
    }

    // --- FUNGSI BARU UNTUK IMPORT EXCEL ---
    public function import(Request $request) {
        if (session('role') !== 'admin') {
            return redirect('/guru')->with('error', 'Hanya Admin yang dapat melakukan impor data!');
        }

        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new TeacherImport, $request->file('file'));
            return redirect('/guru')->with('success', 'Data Guru berhasil diimpor dari file Excel!');
        } catch (\Exception $e) {
            return redirect('/guru')->with('error', 'Gagal mengimpor file! Pastikan format tabel (header) sesuai.');
        }
    }
}
