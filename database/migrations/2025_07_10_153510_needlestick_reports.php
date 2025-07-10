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
        Schema::create('needlestick_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('incident_date');
            $table->time('incident_time');
            $table->string('location');
            $table->string('department');
            $table->string('injured_person_name');
            $table->string('injured_person_position');
            $table->integer('injured_person_age');
            $table->string('injured_person_gender');
            $table->text('incident_description');
            $table->text('source_patient_status')->nullable();
            $table->json('immediate_actions'); // Store as JSON array of actions
            $table->text('follow_up_actions');
            $table->string('photo_path')->nullable(); // Path to the uploaded photo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('needlestick_reports');
    }
};