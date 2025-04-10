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
        Schema::create('kuis', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreignId('materi_id')->nullable()->constrained('materi')->onDelete('cascade')->onUpdate('cascade'); 
            $table->string('judul');
            $table->enum('kategori', ['Kuis', 'Ujian Akhir', 'Ujian Mid'])->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Key ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuis');
    }
};
