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
    Schema::create('schedules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('staff_id')->constrained();
        $table->foreignId('shift_id')->constrained(); // refers to shifts table
        $table->date('start');
        $table->date('end');
        $table->timestamps();
        $table->unique(['staff_id', 'start']); // Prevent duplicate shifts for same staff per day
    });



    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
