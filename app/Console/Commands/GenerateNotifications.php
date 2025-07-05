<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\NotificationController;
use App\Models\User; // Assuming User model is in App\Models
use Throwable; // Import Throwable for catching exceptions

class GenerateNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates daily notifications for all active users based on various criteria.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting notification generation...');

        $notificationController = new NotificationController();
        $users = User::where('is_active', true)->get(); // Example: Fetch only active users

        if ($users->isEmpty()) {
            $this->warn('No active users found to generate notifications for.');
            return Command::SUCCESS;
        }

        foreach ($users as $user) {
            try {
                $notificationController->generateRemindersForUser($user->id);
                $this->comment("Generated notifications for user: {$user->name} (ID: {$user->id})");
            } catch (Throwable $e) { // Catch any exceptions during generation for a specific user
                $this->error("Error generating notifications for user ID {$user->id}: {$e->getMessage()}");
                // Log the full exception for debugging
                \Illuminate\Support\Facades\Log::error("Notification generation failed for user {$user->id}", ['exception' => $e]);
            }
        }

        $this->info('Notification generation completed.');
        return Command::SUCCESS;
    }
}