<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Teacher;

class SettingsController extends Controller
{
    public function index() {
        if (session('role') !== 'admin' && session('role') !== 'guru') {
            return redirect('/')->with('error', 'Akses ditolak!');
        }
        return view('pengaturan.index');
    }

    public function updatePassword(Request $request) {
        // Validasi form
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:5|confirmed'
        ], [
            'new_password.min' => 'Password baru minimal 5 karakter!',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok!'
        ]);

        $role = session('role');
        $userId = session('user_id');
        $user = null;

        // Tentukan tabel mana yang akan diubah passwordnya berdasarkan role
        if ($role === 'admin') {
            $user = User::find($userId);
        } elseif ($role === 'guru') {
            $user = Teacher::find($userId);
        }

        // Jaring Pengaman jika session bermasalah
        if (!$user) {
            return back()->with('error', 'Gagal memproses! Sesi tidak valid atau data tidak ditemukan.');
        }

        // Cek apakah password lama sesuai
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai!');
        }

        // Simpan password baru
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diubah secara permanen!');
    }
}
