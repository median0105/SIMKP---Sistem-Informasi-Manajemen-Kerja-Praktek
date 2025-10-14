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
            $table->json('penilaian_pengawas')->nullable()->after('penilaian_detail');
            $table->decimal('rata_rata_pengawas', 5, 2)->nullable()->after('penilaian_pengawas');
            $table->json('penilaian_dosen')->nullable()->after('rata_rata_pengawas');
            $table->decimal('rata_rata_dosen', 5, 2)->nullable()->after('penilaian_dosen');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kerja_praktek', function (Blueprint $table) {
            $table->dropColumn(['penilaian_pengawas', 'rata_rata_pengawas', 'penilaian_dosen', 'rata_rata_dosen']);
        });
    }
};
