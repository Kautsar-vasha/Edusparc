<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['nisn', 'name', 'class', 'birth_date', 'total_points'];
}
