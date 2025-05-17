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
        Schema::create('detail_absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('absensi_id')->constrained('absensi')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->enum('keterangan', ['Hadir', 'Izin', 'Sakit', 'Alfa'])->nullable();
            $table->string('surat')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_absensi');
    }
};
