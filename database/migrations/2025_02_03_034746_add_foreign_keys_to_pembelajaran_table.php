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
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->foreign('kelas_id','fk_kelas_to_pembelajaran')->references('id')->on('pembelajaran')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('tahun_ajaran_id','fk_tahun_ajaran_to_pembelajaran')->references('id')->on('pembelajaran')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('kurikulum_id','fk_kurikulum_to_pembelajaran')->references('id')->on('pembelajaran')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pembelajaran', function (Blueprint $table) {
            $table->dropForeign('fk_kelas_to_pembelajaran');
            $table->dropForeign('fk_tahun_ajaran_to_pembelajaran');
            $table->dropForeign('fk_kurikulum_to_pembelajaran');
        });
    }
};
