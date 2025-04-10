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
        Schema::create('soal_kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuis_id')->constrained('kuis')->onDelete('cascade')->onUpdate('cascade'); 
            $table->string('teks_soal');
            $table->string('gambar')->nullable();
            $table->enum('type_soal', ['Objective', 'Essay', 'TrueFalse']); 
            $table->json('pilihan_jawaban')->nullable(); 
            $table->text('jawaban_benar'); 
            $table->integer('skor')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_kuis');
    }
};
