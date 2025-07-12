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
        Schema::table('schedules', function (Blueprint $table) {
            // Cek dulu apakah kolom user_id sudah ada
            if (!Schema::hasColumn('schedules', 'user_id')) {
                $table->foreignId('user_id')
                      ->nullable()
                      ->constrained()
                      ->onDelete('cascade')
                      ->after('id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Drop foreign key dulu baru drop column
            if (Schema::hasColumn('schedules', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};
