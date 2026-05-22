<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom username
            $table->string('username')->unique()->after('name');

            // --- TAMBAHAN BARU: Menambahkan kolom role ---
            $table->string('role')->default('guru')->after('username');

            // Mengubah email menjadi boleh kosong (nullable)
            $table->string('email')->nullable()->change();
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            // Menghapus kolom jika migration di-rollback
            $table->dropColumn('username');
            $table->dropColumn('role');
            $table->string('email')->nullable(false)->change();
        });
    }
};
