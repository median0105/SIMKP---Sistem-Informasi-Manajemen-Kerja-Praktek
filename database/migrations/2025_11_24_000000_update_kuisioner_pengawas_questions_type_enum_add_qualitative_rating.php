<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Updating enum type to include qualitative_rating
        DB::statement("ALTER TABLE kuisioner_pengawas_questions MODIFY COLUMN type ENUM('rating', 'text', 'yes_no', 'qualitative_rating') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert enum type back without qualitative_rating
        DB::statement("ALTER TABLE kuisioner_pengawas_questions MODIFY COLUMN type ENUM('rating', 'text', 'yes_no') NOT NULL");
    }
};
