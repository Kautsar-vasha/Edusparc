<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Jika kolom NISN kosong di Excel, lewati baris tersebut
        if (!isset($row['nisn'])) {
            return null;
        }

        // Cek apakah NISN sudah ada di database untuk menghindari error duplikat
        $existingStudent = Student::where('nisn', $row['nisn'])->first();
        if ($existingStudent) {
            return null; // Lewati jika sudah ada
        }

        // Handle format Tanggal Lahir (Mengatasi format default Excel)
        $tgl_lahir = isset($row['tanggal_lahir']) ? $row['tanggal_lahir'] : '2010-01-01';
        if (is_numeric($tgl_lahir)) {
            // Jika Excel mengubah tanggal menjadi angka seri
            $tgl_lahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tgl_lahir)->format('Y-m-d');
        } else {
            // Jika diketik manual dengan format teks (YYYY-MM-DD)
            $tgl_lahir = date('Y-m-d', strtotime($tgl_lahir));
        }

        return new Student([
            'nisn'         => $row['nisn'],
            'name'         => $row['nama'],
            'class'        => $row['kelas'],
            'birth_date'   => $tgl_lahir,
            'total_points' => 0, // Siswa baru otomatis poin 0
        ]);
    }
}
