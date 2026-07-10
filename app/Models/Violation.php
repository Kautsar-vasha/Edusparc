<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Violation extends Model
{
    protected $fillable = [
        'student_id',
        'jenis_poin',
        'type',
        'category',
        'points',
        'description',
        'motivation',
        'tanggapan_ortu',
        'bukti_foto' // <--- Izin akses kolom foto baru
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
