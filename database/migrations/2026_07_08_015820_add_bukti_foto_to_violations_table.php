<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk menambah kolom.
     */
    public function up()
    {
        Schema::table('violations', function (Blueprint $table) {
            // Menambahkan kolom bukti_foto (boleh kosong/nullable)
            $table->string('bukti_foto')->nullable()->after('tanggapan_ortu');
        });
    }

    /**
     * Kembalikan migrasi (hapus kolom jika dibatalkan).
     */
    public function down()
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->dropColumn('bukti_foto');
        });
    }
};
