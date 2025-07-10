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
        Schema::table('cvc_maintenances', function (Blueprint $table) {
            // Add maintenance_location after maintenance_date
            $table->string('maintenance_location')->nullable()->after('maintenance_date');
            // Add days_inserted after maintenance_location
            $table->integer('days_inserted')->nullable()->after('maintenance_location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cvc_maintenances', function (Blueprint $table) {
            $table->dropColumn(['maintenance_location', 'days_inserted']);
        });
    }
};