<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tatib extends Model
{
    use HasFactory;

    // Mengizinkan penyimpanan ke kolom-kolom ini
    protected $fillable = [
        'kode',
        'jenis',
        'kategori',
        'uraian',
        'poin',
        'sanksi'
    ];
}
