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
        Schema::create('siswa_kuis_session', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pertemuan_kuis_id')->constrained('pertemuan_kuis')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('token')->nullable();
            $table->integer('token_attempts')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa_kuis_session');
    }
};
