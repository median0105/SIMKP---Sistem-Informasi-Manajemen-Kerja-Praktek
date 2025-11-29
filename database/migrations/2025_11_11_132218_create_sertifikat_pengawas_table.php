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
        Schema::create('sertifikat_pengawas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pengawas');
            $table->string('nomor_sertifikat')->unique();
            $table->string('tahun_ajaran');
            $table->string('nama_kaprodi');
            $table->string('nip_kaprodi');
            $table->string('file_path')->nullable();
            $table->boolean('is_generated')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikat_pengawas');
    }
};
