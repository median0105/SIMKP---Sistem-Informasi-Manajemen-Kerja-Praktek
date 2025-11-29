<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kerja_praktek', function (Blueprint $table) {
            $table->boolean('acc_seminar')->default(false)->after('file_laporan');
            $table->date('tanggal_seminar')->nullable()->after('acc_seminar');
            $table->string('file_kartu_implementasi')->nullable()->after('tanggal_seminar');
            $table->boolean('acc_pembimbing_lapangan')->default(false)->after('file_kartu_implementasi');
            $table->date('tanggal_ujian')->nullable()->after('acc_pembimbing_lapangan');
            $table->boolean('lulus_ujian')->default(false)->after('tanggal_ujian');
            
            // Penilaian Detail
            $table->json('penilaian_detail')->nullable()->after('nilai_akhir');
            $table->text('keterangan_penilaian')->nullable()->after('penilaian_detail');
            
            // Status KP Lebih dari 1 Tahun
            $table->boolean('perlu_responsi')->default(false)->after('keterangan_penilaian');
            $table->text('catatan_responsi')->nullable()->after('perlu_responsi');
        });
    }

    public function down(): void
    {
        Schema::table('kerja_praktek', function (Blueprint $table) {
            $table->dropColumn([
                'acc_seminar', 'tanggal_seminar', 'file_kartu_implementasi', 
                'acc_pembimbing_lapangan', 'tanggal_ujian', 'lulus_ujian',
                'penilaian_detail', 'keterangan_penilaian', 'perlu_responsi', 
                'catatan_responsi'
            ]);
        });
    }
};