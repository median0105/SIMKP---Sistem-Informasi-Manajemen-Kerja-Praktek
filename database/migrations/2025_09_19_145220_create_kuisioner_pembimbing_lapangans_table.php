<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuisioner_pembimbing_lapangan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kerja_praktek_id')->constrained('kerja_praktek')->onDelete('cascade');
            $table->foreignId('pembimbing_lapangan_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating_mahasiswa'); // 1-5 rating untuk mahasiswa
            $table->text('komentar_kinerja'); // Komentar tentang kinerja mahasiswa
            $table->text('saran_mahasiswa')->nullable(); // Saran untuk mahasiswa
            $table->boolean('rekomendasi_mahasiswa')->default(true); // Apakah merekomendasikan mahasiswa
            $table->text('kelebihan_mahasiswa')->nullable(); // Kelebihan mahasiswa
            $table->text('kekurangan_mahasiswa')->nullable(); // Kekurangan mahasiswa
            $table->timestamp('tanggal_feedback');
            $table->timestamps();

            $table->unique(['kerja_praktek_id'], 'unique_feedback_per_kp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuisioner_pembimbing_lapangan');
    }
};