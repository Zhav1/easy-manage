<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use Carbon\CarbonInterface; // Import CarbonInterface
use App\Models\HandHygieneForm;
use App\Models\ApdForm;
use App\Models\IdentifikasiPasienForm;
use App\Models\WtriForm;
use App\Models\KritisLabForm;
use App\Models\FornasForm;
use App\Models\VisiteForm;
use App\Models\JatuhForm;
use App\Models\CpForm;
use App\Models\KepuasanForm;
use App\Models\KrkForm;
use App\Models\PoeForm;
use App\Models\ScForm;

class ResetWeeklyForms extends Command
{
    protected $signature = 'forms:reset-weekly';
    protected $description = 'Ensures an empty form entry exists for the current week for each inspection type.';

    private $formModels = [
        'hand-hygiene' => HandHygieneForm::class,
        'apd' => ApdForm::class,
        'identifikasi' => IdentifikasiPasienForm::class,
        'wtri' => WtriForm::class,
        'kritis-lab' => KritisLabForm::class,
        'fornas' => FornasForm::class,
        'visite' => VisiteForm::class,
        'jatuh' => JatuhForm::class,
        'cp' => CpForm::class,
        'kepuasan' => KepuasanForm::class,
        'krk' => KrkForm::class,
        'poe' => PoeForm::class,
        'sc' => ScForm::class,
    ];

    public function handle()
    {
        $this->info('Starting weekly form reset...');

        // Use CarbonInterface::MONDAY to correctly reference the constant
        $currentWeekStartDate = Carbon::now()->startOfWeek(CarbonInterface::MONDAY)->format('Y-m-d');
        $previousWeekStartDate = Carbon::now()->subWeek()->startOfWeek(CarbonInterface::MONDAY)->format('Y-m-d');

        foreach ($this->formModels as $formType => $modelClass) {
            // 1. Ensure the previous week's form data is finalized (if not already)
            // This logic is designed to create an empty placeholder if no data was submitted for the previous week,
            // effectively "finalizing" it as an empty record in history for that week.
            $previousWeekFormExists = $modelClass::where('week_start_date', $previousWeekStartDate)->exists();
            if (!$previousWeekFormExists) {
                $modelClass::create([
                    'week_start_date' => $previousWeekStartDate,
                    'data' => [], // Empty data for the previous week's "finalized" record
                    'user_id' => null, // Or an anonymous user ID if you have one
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                $this->info("Created empty previous week form for {$formType} (week starting {$previousWeekStartDate}).");
            }

            // 2. Ensure an empty form exists for the *current* week
            // This ensures that when the frontend requests the "current" form,
            // there's always an entry for the active week, even if it's empty.
            $currentWeekFormExists = $modelClass::where('week_start_date', $currentWeekStartDate)->exists();

            if (!$currentWeekFormExists) {
                $modelClass::create([
                    'week_start_date' => $currentWeekStartDate,
                    'data' => [], // Empty data for the current week's new form
                    'user_id' => null, // No user yet, or set a default system user
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
                $this->info("Created empty current week form for {$formType} (week starting {$currentWeekStartDate}).");
            } else {
                $this->info("Form for {$formType} for week starting {$currentWeekStartDate} already exists, skipping creation.");
            }
        }

        $this->info('Weekly form reset completed.');
        return 0;
    }
}