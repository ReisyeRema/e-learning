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
        Schema::create('pertemuan_kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelajaran_id')->constrained('pembelajaran')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('pertemuan_id')->constrained('pertemuan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('kuis_id')->constrained('kuis')->onDelete('cascade')->onUpdate('cascade');
            $table->dateTime('deadline')->nullable();
            $table->string('token')->nullable()->unique();
            // $table->enum('kategori_kuis', ['Ujian Akhir', 'Ujian Mid', 'Latihan', 'Kuis']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertemuan_kuis');
    }
};
