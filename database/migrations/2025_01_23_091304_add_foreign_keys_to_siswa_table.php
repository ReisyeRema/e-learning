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
        Schema::table('siswa', function (Blueprint $table) {
            $table->foreign('user_id','fk_siswa_to_users')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kelas_id','fk_siswa_to_kelas')->references('id')->on('kelas')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropForeign('fk_siswa_to_users');
            $table->dropForeign('fk_siswa_to_kelas');
        });
    }
};
