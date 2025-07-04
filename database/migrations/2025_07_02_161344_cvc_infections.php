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
        Schema::create('cvc_infections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who filled the form
            $table->string('patient_name');
            $table->string('medical_record_number')->nullable();
            $table->date('insertion_date')->nullable();
            $table->string('insertion_location')->nullable(); // V. Subklavia, V. Jugularis Interna, V. Femoralis, Lainnya
            $table->date('infection_diagnosis_date');
            $table->string('infection_type'); // CLABSI, Exit Site Infection, Tunnel Infection, Pocket Infection
            $table->text('clinical_symptoms')->nullable(); // Gejala Klinis
            $table->string('microorganism')->nullable(); // Hasil Mikrobiologi: The client explicitly asked for this.
            $table->text('management')->nullable(); // Tatalaksana
            $table->string('photo_path')->nullable(); // Path to uploaded photo
            $table->enum('status', ['Aktif', 'Selesai'])->default('Aktif'); // Status: Active or Resolved

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cvc_infection_reports');
    }
};