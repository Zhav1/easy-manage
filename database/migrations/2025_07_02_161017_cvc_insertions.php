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
        Schema::create('cvc_insertions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who filled the form
            $table->string('patient_name');
            $table->string('medical_record_number')->nullable(); // Nomor RM
            $table->date('insertion_date');
            $table->string('insertion_location'); // V. Subklavia, V. Jugularis Interna, V. Femoralis, Lainnya
            $table->string('operator_name')->nullable(); // Dokter/Perawat yang melakukan insersi
            $table->integer('compliance_percentage')->default(0); // Calculated compliance percentage

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
        Schema::dropIfExists('cvc_insertion_forms');
    }
};