<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kerja_praktek', function (Blueprint $table) {
            // semester_ke opsional, untuk catat semester berapa KP dilaksanakan
            $table->unsignedTinyInteger('semester_ke')->nullable()->after('tanggal_mulai');
            // IPK semester berjalan saat KP (0.00 - 4.00)
            $table->decimal('ipk_semester', 3, 2)->nullable()->after('nilai_akhir');
        });
    }

    public function down(): void
    {
        Schema::table('kerja_praktek', function (Blueprint $table) {
            $table->dropColumn(['semester_ke', 'ipk_semester']);
        });
    }
};
