<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tempat_magang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan');
            $table->text('alamat');
            $table->string('kontak_person');
            $table->string('email_perusahaan');
            $table->string('telepon_perusahaan');
            $table->string('bidang_usaha');
            $table->integer('kuota_mahasiswa')->default(1);
            $table->boolean('is_active')->default(true);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tempat_magang');
    }
};