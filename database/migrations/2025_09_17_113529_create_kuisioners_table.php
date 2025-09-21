<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuisioner', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kerja_praktek_id')->constrained('kerja_praktek')->onDelete('cascade');
            $table->integer('rating_tempat_magang'); // 1-5
            $table->integer('rating_bimbingan'); // 1-5
            $table->integer('rating_sistem'); // 1-5
            $table->text('saran_perbaikan')->nullable();
            $table->text('kesan_pesan')->nullable();
            $table->boolean('rekomendasi_tempat')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuisioner');
    }
};