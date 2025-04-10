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
        Schema::create('submit_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tugas_id')->constrained('tugas')->onDelete('cascade')->onUpdate('cascade'); 
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->string('file_path')->nullable(); 
            $table->string('mime_type')->nullable(); 
            $table->unsignedBigInteger('file_size')->nullable(); 
            $table->string('url')->nullable();
            $table->integer('skor')->nullable();
            $table->enum('status', ['belum_dikumpulkan', 'sudah_dikumpulkan','terlambat'])->default('belum_dikumpulkan'); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submit_tugas');
    }
};
