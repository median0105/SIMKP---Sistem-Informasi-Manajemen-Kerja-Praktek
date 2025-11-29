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
        Schema::table('kuisioner_pengawas_questions', function (Blueprint $table) {
            $table->string('type', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kuisioner_pengawas_questions', function (Blueprint $table) {
            $table->enum('type', ['rating', 'text', 'yes_no'])->change();
        });
    }
};
