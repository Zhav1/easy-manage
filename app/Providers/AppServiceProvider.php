<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule; // <<< ADD THIS LINE
use App\Console\Commands\GenerateNotifications; // <<< ADD THIS LINE
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
        // Register your scheduled tasks here
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            // Register your notifications:generate command
            // $schedule->command(GenerateNotifications::class)->dailyAt('08:00');
            // For testing, you can make it run more frequently:
            $schedule->command(GenerateNotifications::class)->everyMinute();

            // You can add more scheduled commands here:
            // $schedule->command('backup:daily')->daily();
        });
    }
}