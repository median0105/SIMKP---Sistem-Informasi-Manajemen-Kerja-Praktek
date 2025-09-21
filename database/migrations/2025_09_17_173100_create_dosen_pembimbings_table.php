<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosen_pembimbing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kerja_praktek_id')->constrained('kerja_praktek')->onDelete('cascade');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->enum('jenis_pembimbingan', ['akademik', 'lapangan'])->default('akademik');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['kerja_praktek_id', 'dosen_id', 'jenis_pembimbingan'], 'unique_dospem');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosen_pembimbing');
    }
};