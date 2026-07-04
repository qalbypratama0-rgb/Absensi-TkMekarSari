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
        // Add softDeletes to kelas
        Schema::table('kelas', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add softDeletes to siswa
        Schema::table('siswa', function (Blueprint $table) {
            $table->softDeletes();
        });

        // Add indices to absensi
        Schema::table('absensi', function (Blueprint $table) {
            $table->index('waktu_hadir');
            $table->index('siswa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indices from absensi
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropIndex(['waktu_hadir']);
            $table->dropIndex(['siswa_id']);
        });

        // Drop softDeletes from siswa
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        // Drop softDeletes from kelas
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
