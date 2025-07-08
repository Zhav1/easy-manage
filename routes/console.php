<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;



Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('notifications:generate', function (\App\Http\Controllers\NotificationController $notificationController) {
    $this->info('Starting notification generation from Artisan command...');
    $users = User::all(); // Fetch all users (removed is_active filter as discussed)
    foreach ($users as $user) {
        try {
            $notificationController->generateRemindersForUser($user->id);
            $this->comment("Generated notifications for user: {$user->name} (ID: {$user->id})");
        } catch (Throwable $e) {
            $this->error("Error generating notifications for user ID {$user->id}: {$e->getMessage()}");
            \Illuminate\Support\Facades\Log::error("Notification generation failed for user {$user->id}", ['exception' => $e]);
        }
    }
    $this->info('Notification generation completed from Artisan command.');
})->purpose('Generates daily notifications for users.');
