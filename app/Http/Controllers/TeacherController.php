<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;

class TeacherController extends Controller
{
    // Akses: Admin & Guru
    public function index() {
        if (session('role') !== 'admin' && session('role') !== 'guru') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        $teachers = Teacher::all();
        return view('guru.index', compact('teachers'));
    }

    // Akses: Hanya Admin
    public function create() {
        if (session('role') !== 'admin') {
            return redirect('/dashboard-guru')->with('error', 'Hanya Admin yang dapat menambah data guru.');
        }
        return view('guru.create');
    }

    // Akses: Hanya Admin
    public function store(Request $request) {
        if (session('role') !== 'admin') {
            return redirect('/dashboard-guru')->with('error', 'Akses ditolak!');
        }

        Teacher::create([
            'nip' => $request->nip,
            'name' => $request->name,
            'username' => $request->username,
            'subject' => $request->subject,
            'role_tambahan' => $request->role_tambahan,
            'phone' => $request->phone,
            'password' => bcrypt('12345')
        ]);
        return redirect('/guru')->with('success', 'Data Guru berhasil ditambahkan!');
    }

    // Akses: Admin & Guru
    public function show($id) {
        if (session('role') !== 'admin' && session('role') !== 'guru') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        $teacher = Teacher::findOrFail($id);
        return view('guru.show', compact('teacher'));
    }
}
