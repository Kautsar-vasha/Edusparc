<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin() {
        return view('login');
    }

    public function login(Request $request) {
        // 1. Cek Admin / Super Admin (Tabel users)
        //    Role diambil langsung dari kolom 'role' di database,
        //    bukan di-hardcode, sehingga 'super_admin' bisa terbaca.
        $admin = User::where('username', $request->username)->first();
        if ($admin && Hash::check($request->password, $admin->password)) {
            Session::put([
                'role'     => $admin->role, // ✅ ambil dari DB, bukan hardcode 'admin'
                'user_id'  => $admin->id,
                'name'     => $admin->name,
                'username' => $admin->username,
            ]);

            // Arahkan super_admin & admin ke dashboard yang sama
            return redirect('/dashboard-admin');
        }

        // 2. Cek Guru (Tabel teachers)
        $teacher = Teacher::where('username', $request->username)->first();
        if ($teacher && Hash::check($request->password, $teacher->password)) {
            Session::put([
                'role'     => 'guru',
                'user_id'  => $teacher->id,
                'name'     => $teacher->name,
                'username' => $teacher->username,
            ]);
            return redirect('/dashboard-guru');
        }

        // 3. Cek Orang Tua (NISN & Tgl Lahir)
        $student = Student::where('nisn', $request->username)
                          ->where('birth_date', $request->password)
                          ->first();
        if ($student) {
            Session::put([
                'role'    => 'ortu',
                'user_id' => $student->id,
                'name'    => $student->name,
            ]);
            return redirect('/dashboard-ortu');
        }

        return back()->with('error', 'Username atau Password salah!');
    }

    public function logout() {
        Session::flush();
        return redirect('/');
    }
}
