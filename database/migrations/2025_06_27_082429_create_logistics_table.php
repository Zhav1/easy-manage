<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('logistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null'); // NEW
            $table->string('category');
            $table->string('item_name');
            $table->string('brand')->nullable();
            $table->string('item_code')->nullable();
            $table->string('maintenance_schedule')->nullable();
            $table->date('calibration_date')->nullable();
            $table->date('calibration_expiry_date')->nullable();
            $table->integer('stock')->default(0);
            $table->string('unit_of_measure')->default('unit');
            $table->string('status')->default('Tersedia');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('logistics');
    }
};