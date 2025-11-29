<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('npm')->nullable()->after('email');
            $table->enum('role', ['mahasiswa', 'admin_dosen', 'superadmin', 'pengawas_lapangan'])->default('mahasiswa')->after('npm');
            $table->string('phone')->nullable()->after('role');
            $table->string('avatar')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['npm', 'role', 'phone', 'avatar', 'is_active']);
        });
    }
};