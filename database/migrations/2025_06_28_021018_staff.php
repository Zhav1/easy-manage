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
    Schema::create('staff', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->foreignId('position_id')->constrained();
        $table->foreignId('department_id')->constrained();
        $table->foreignId('hospital_id')->constrained();
        $table->enum('status', ['Aktif', 'Tidak Aktif', 'Cuti'])->default('Aktif');
        $table->timestamps();
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
