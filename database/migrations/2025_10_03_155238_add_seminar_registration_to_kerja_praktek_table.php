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
            // Seminar Registration Fields
            $table->boolean('pendaftaran_seminar')->default(false)->after('acc_pembimbing_laporan');
            $table->timestamp('tanggal_daftar_seminar')->nullable()->after('pendaftaran_seminar');
            $table->boolean('acc_pendaftaran_seminar')->default(false)->after('tanggal_daftar_seminar');

            // Seminar Schedule Fields
            $table->datetime('jadwal_seminar')->nullable()->after('acc_pendaftaran_seminar');
            $table->string('ruangan_seminar')->nullable()->after('jadwal_seminar');
            $table->text('catatan_seminar')->nullable()->after('ruangan_seminar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kerja_praktek', function (Blueprint $table) {
            $table->dropColumn([
                'pendaftaran_seminar',
                'tanggal_daftar_seminar',
                'acc_pendaftaran_seminar',
                'jadwal_seminar',
                'ruangan_seminar',
                'catatan_seminar'
            ]);
        });
    }
};
