<?php

namespace App\Imports;

use App\Models\Teacher;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Hash;

class TeacherImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Lewati jika NIP atau Username kosong di Excel
        if (!isset($row['nip']) || !isset($row['username'])) {
            return null;
        }

        // Cek apakah NIP atau Username sudah ada untuk menghindari duplikat
        $existingTeacher = Teacher::where('nip', $row['nip'])
                                  ->orWhere('username', $row['username'])
                                  ->first();

        if ($existingTeacher) {
            return null;
        }

        // Jika password di Excel kosong, beri default '12345'
        $password = isset($row['password']) && $row['password'] != '' ? $row['password'] : '12345';

        // Atur Peran default jika kosong
        $peran = isset($row['peran']) && $row['peran'] != '' ? $row['peran'] : 'Guru Mapel';

        return new Teacher([
            'nip'           => $row['nip'],
            'name'          => $row['nama'],
            'username'      => $row['username'],
            'subject'       => $row['mapel'],
            'role_tambahan' => $peran,
            'phone'         => isset($row['no_hp']) ? $row['no_hp'] : null,
            'password'      => Hash::make($password),
        ]);
    }
}
