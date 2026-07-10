<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('violations', function (Blueprint $table) {
            // Menambahkan kolom tanggapan_ortu tipe teks yang boleh kosong (nullable)
            $table->text('tanggapan_ortu')->nullable()->after('motivation');
        });
    }

    public function down()
    {
        Schema::table('violations', function (Blueprint $table) {
            $table->dropColumn('tanggapan_ortu');
        });
    }
};
