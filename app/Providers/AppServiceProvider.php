<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule; // <--- IMPORTANT: Import Schedule facade
use Illuminate\Support\Facades\Log; // <--- Optional: For logging schedule events
use Throwable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // --- NEW: Schedule commands here ---
        // Access the scheduler instance and define your tasks.
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            $schedule->command('notifications:generate') // Call your defined command by its signature
                     ->dailyAt('00:05') // Run daily at 00:05 (12:05 AM)
                     ->onSuccess(function () {
                         Log::info('Scheduled command notifications:generate ran successfully.');
                     })
                     ->onFailure(function (Throwable $e) { // Ensure Throwable is imported if used
                         Log::error('Scheduled command notifications:generate failed: ' . $e->getMessage());
                     });
            
            $schedule->command('another:command')->hourly();
        });
    }
}