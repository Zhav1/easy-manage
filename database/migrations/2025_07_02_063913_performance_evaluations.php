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
        Schema::create('performance_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('staff')->onDelete('cascade');
            $table->integer('kedisiplinan')->comment('Scale: 1-5 or descriptive mapping');
            $table->integer('komunikasi')->comment('Scale: 1-5 or descriptive mapping');
            $table->integer('komplain')->comment('Scale: 1-5 or descriptive mapping');
            $table->integer('kepatuhan')->comment('Scale: 1-5 or descriptive mapping');
            $table->integer('target_kerja')->comment('Scale: 1-5 or descriptive mapping');
            $table->string('status_kinerja')->nullable(); // e.g., Excellent, Good, Need Mentoring, Need Improvement
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('performance_evaluations');
    }
};