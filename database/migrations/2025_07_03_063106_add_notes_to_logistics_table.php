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
    Schema::table('logistics', function (Blueprint $table) {
        $table->string('notes', 255)->nullable()->after('item_code');
    });
}

public function down(): void
{
    Schema::table('logistics', function (Blueprint $table) {
        $table->dropColumn('notes');
    });
}

};
