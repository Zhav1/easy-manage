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
        Schema::create('daily_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who logged this
            $table->timestamp('log_time')->useCurrent();
            $table->boolean('briefing_conducted')->default(false);
            $table->boolean('meeting_held')->default(false);
            $table->boolean('supervision_conducted')->default(false);
            $table->boolean('handover_done')->default(false);
            $table->boolean('external_task_performed')->default(false);
            $table->string('report_status')->default('Belum Terkirim'); // e.g., Terkirim, Belum Terkirim
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_logs');
    }
};