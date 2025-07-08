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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Use UUID for primary key (Laravel's default for notifications)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // User who receives the notification
            $table->string('type'); // e.g., 'schedule_reminder', 'low_stock_alert', 'evaluation_deadline', 'meeting_reminder'
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // Store additional context (e.g., item_id, schedule_id, patient_name)
            $table->boolean('is_read')->default(false);
            $table->boolean('is_dismissed')->default(false); // For dismissing from view, but keeping in history
            $table->string('tag')->nullable(); // e.g., 'Besok', 'Urgent', 'Minggu Ini'
            $table->string('tag_color')->nullable(); // e.g., 'blue', 'yellow', 'red'
            $table->integer('priority')->default(5); // Lower number = higher priority (1 = urgent, 5 = low)
            $table->string('link')->nullable(); // URL to related page
            $table->timestamp('remind_at')->nullable(); // When the notification should become active/remind
            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};