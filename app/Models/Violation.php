<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    //agar Laravel mengizinkan data disimpan ke kolom-kolom ini
    protected $fillable = ['student_id', 'jenis_poin', 'type', 'category', 'points', 'description', 'motivation'];

    // Relasi ke tabel Student (Penting agar tidak error saat panggil nama siswa)
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
