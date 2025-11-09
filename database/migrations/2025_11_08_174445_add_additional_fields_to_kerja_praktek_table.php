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
        Schema::table('kerja_praktek', function (Blueprint $table) {
            $table->string('bidang_usaha_sendiri')->nullable()->after('tempat_magang_sendiri');
            $table->string('email_perusahaan_sendiri')->nullable()->after('bidang_usaha_sendiri');
            $table->string('telepon_perusahaan_sendiri')->nullable()->after('email_perusahaan_sendiri');
            $table->integer('kuota_mahasiswa_sendiri')->nullable()->after('telepon_perusahaan_sendiri');
            $table->text('deskripsi_perusahaan_sendiri')->nullable()->after('kuota_mahasiswa_sendiri');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kerja_praktek', function (Blueprint $table) {
            $table->dropColumn([
                'bidang_usaha_sendiri',
                'email_perusahaan_sendiri',
                'telepon_perusahaan_sendiri',
                'kuota_mahasiswa_sendiri',
                'deskripsi_perusahaan_sendiri',
            ]);
        });
    }
};
