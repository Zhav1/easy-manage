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
    Schema::table('training_needs', function (Blueprint $table) {
        $table->date('tanggal')->after('staff_id'); // ->nullable() kalau perlu
    });
}

public function down(): void
{
    Schema::table('training_needs', function (Blueprint $table) {
        $table->dropColumn('tanggal');
    });
}

};
