<?php

// database/migrations/xxxx_xx_xx_create_private_schedules_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('private_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamp('scheduled_at');
            $table->boolean('briefing')->default(false);
            $table->boolean('meeting')->default(false);
            $table->boolean('supervision')->default(false);
            $table->boolean('handover')->default(false);
            $table->string('external_task')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('private_schedules');
    }
};
