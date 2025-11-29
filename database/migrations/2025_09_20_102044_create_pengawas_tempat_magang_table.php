<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengawas_tempat_magang', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengawas_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tempat_magang_id')->constrained('tempat_magang')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->string('jabatan_pengawas')->nullable(); // Jabatan di perusahaan
            $table->text('deskripsi_tugas')->nullable(); // Deskripsi tugas pengawasan
            $table->timestamps();

            // Satu pengawas bisa di beberapa tempat, tapi kombinasi harus unik
            $table->unique(['pengawas_id', 'tempat_magang_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengawas_tempat_magang');
    }
};