<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('tatibs', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique(); // Contoh: a.1, p.1
            $table->enum('jenis', ['negatif', 'positif']); // negatif = Pelanggaran, positif = Penghargaan
            $table->enum('kategori', ['Spiritual', 'Sosial']); // Berdasarkan dokumen
            $table->text('uraian'); // Penjelasan aktivitas
            $table->integer('poin'); // Jumlah poin mutlak
            $table->string('sanksi')->nullable(); // Bentuk sanksi/pembinaan (bisa kosong untuk penghargaan)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tatibs');
    }
};
