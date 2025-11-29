<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePeriodesTable extends Migration
{
    public function up()
    {
        Schema::create('periodes', function (Blueprint $table) {
            $table->id();
            $table->string('tahun_akademik');
            $table->integer('semester_ke');
            $table->enum('semester_type', ['ganjil', 'genap']);
            $table->date('tanggal_mulai_kp');
            $table->date('tanggal_selesai_kp');
            $table->boolean('status')->default(false)->comment('Active or not active');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('periodes');
    }
}
