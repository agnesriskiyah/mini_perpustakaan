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
        Schema::table('users', function (Blueprint $table) {
            $table->string('telepon', 20)->after('email');
            $table->string('nomor_identitas', 50)->after('telepon');
            $table->enum('role', ['peminjam', 'petugas'])->after('nomor_identitas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['telepon', 'nomor_identitas', 'role']);
        });
    }
};
