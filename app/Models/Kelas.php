<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    // Menentukan nama tabel 
    protected $table = 'kelas';

    // Menentukan kolom apa saja yang boleh diisi
    protected $fillable = [
        'nama_kelas'
    ];
}
