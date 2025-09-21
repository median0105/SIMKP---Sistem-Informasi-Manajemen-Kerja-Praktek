<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kerja_praktek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tempat_magang_id')->nullable()->constrained('tempat_magang')->onDelete('set null');
            $table->string('judul_kp');
            $table->integer('pilihan_tempat'); // 1, 2, atau 3
            $table->string('tempat_magang_sendiri')->nullable(); // untuk pilihan 3
            $table->text('alamat_tempat_sendiri')->nullable(); // untuk pilihan 3
            $table->string('kontak_tempat_sendiri')->nullable(); // untuk pilihan 3
            $table->date('tanggal_mulai')->nullable();
            $table->date('tanggal_selesai')->nullable();
            $table->enum('status', ['pengajuan', 'disetujui', 'sedang_kp', 'selesai', 'ditolak'])->default('pengajuan');
            $table->string('file_laporan')->nullable();
            $table->integer('nilai_akhir')->nullable();
            $table->text('catatan_dosen')->nullable();
            $table->text('catatan_pengawas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kerja_praktek');
    }
};