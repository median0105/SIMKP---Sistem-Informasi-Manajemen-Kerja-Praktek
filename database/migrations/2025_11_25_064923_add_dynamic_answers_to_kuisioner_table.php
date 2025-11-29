<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDynamicAnswersToKuisionerTable extends Migration
{
    public function up()
    {
        Schema::table('kuisioner', function (Blueprint $table) {
            // jika MySQL mendukung JSON gunakan json(); kalau tidak gunakan text()
            $table->json('dynamic_answers')->nullable()->after('rekomendasi_tempat');
        });
    }

    public function down()
    {
        Schema::table('kuisioner', function (Blueprint $table) {
            $table->dropColumn('dynamic_answers');
        });
    }
}