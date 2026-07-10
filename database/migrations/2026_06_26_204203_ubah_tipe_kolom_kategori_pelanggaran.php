<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Mengubah kolom category menjadi teks bebas (VARCHAR)
        DB::statement("ALTER TABLE violations MODIFY category VARCHAR(255) NULL");
    }

    public function down()
    {
        // Tidak perlu diisi
    }
};
