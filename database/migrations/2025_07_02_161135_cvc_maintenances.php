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
        Schema::create('cvc_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who filled the form
            $table->string('patient_name');
            $table->string('medical_record_number')->nullable();
            $table->date('maintenance_date');
            $table->string('nurse_name')->nullable(); // Perawat yang melakukan perawatan
            $table->integer('compliance_percentage')->default(0);

            // Elements of observation - each with status (Ya/Tidak/Tidak Dilakukan), notes, and optional photo path
            $table->json('elements_data'); // Store as JSON for flexibility

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cvc_maintenance_forms');
    }
};