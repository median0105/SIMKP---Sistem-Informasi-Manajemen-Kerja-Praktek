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
        Schema::table('bimbingan', function (Blueprint $table) {
            $table->dropForeign(['kerja_praktek_id']);
            $table->foreignId('kerja_praktek_id')->nullable()->change();
            $table->foreign('kerja_praktek_id')->references('id')->on('kerja_praktek')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bimbingan', function (Blueprint $table) {
            $table->dropForeign(['kerja_praktek_id']);
            $table->foreignId('kerja_praktek_id')->nullable(false)->change();
            $table->foreign('kerja_praktek_id')->references('id')->on('kerja_praktek')->onDelete('cascade');
        });
    }
};
