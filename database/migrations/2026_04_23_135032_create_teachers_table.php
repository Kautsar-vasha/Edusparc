<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique(); // NIP/NUPTK
            $table->string('name'); // Nama lengkap & gelar
            $table->string('username')->unique();
            $table->string('subject'); // Mata Pelajaran
            $table->enum('role_tambahan', ['Guru Mapel', 'Wali Kelas', 'Guru BK'])->default('Guru Mapel'); // Peran tambahan
            $table->string('phone')->nullable(); // No WhatsApp
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
