<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       \App\Models\Teacher::create([
        'name' => 'Admin Guru',
        'subject' => 'BK',
        'username' => 'guru123',
        'password' => bcrypt('12345') // Pakai bcrypt, bukan bcrypthash
    ]);
    }
}
