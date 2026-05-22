<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'nip',
        'name',
        'username',
        'subject',
        'role_tambahan',
        'phone',
        'password'
    ];
}
