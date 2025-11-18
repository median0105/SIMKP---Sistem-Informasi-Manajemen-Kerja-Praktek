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
        Schema::create('kuisioner_pengawas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengawas_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kuisioner_pengawas_question_id')->constrained('kuisioner_pengawas_questions')->onDelete('cascade');
            $table->text('answer')->nullable(); // For text answers
            $table->integer('rating')->nullable(); // For rating answers (1-5)
            $table->boolean('yes_no')->nullable(); // For yes/no answers
            $table->timestamp('submitted_at');
            $table->timestamps();

            $table->unique(['pengawas_id', 'kuisioner_pengawas_question_id'], 'unique_pengawas_question_response');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuisioner_pengawas');
    }
};
