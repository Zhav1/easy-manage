<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hand_hygiene_forms', function (Blueprint $table) {
            $table->id();
            $table->date('week_start_date')->unique(); // To track the week
            $table->json('data')->nullable(); // Store the form data as JSON
            $table->timestamps();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hand_hygiene_forms');
    }
};