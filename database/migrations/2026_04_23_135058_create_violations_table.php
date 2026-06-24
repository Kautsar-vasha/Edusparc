<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
    {
       Schema::create('violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->enum('jenis_poin', ['positif', 'negatif'])->default('negatif'); // Tambahan Baru
            $table->string('type');
            $table->enum('category', ['Administratif', 'Etika/Perilaku', 'Prestasi/Kebaikan']); // Tambah kategori
            $table->integer('points');
            $table->text('description')->nullable();
            $table->text('motivation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};
