<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
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
use Illuminate\Support\Facades\Log; // Don't forget to import Log

class QualityInspectionController extends Controller
{
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

    /**
     * Get the start date of the current week (Monday).
     * @return string
     */
    private function getCurrentWeekStartDate()
    {
        // Set locale to 'id' for Carbon to ensure Monday is the start of the week.
        // If your Carbon default week start is already Monday, this might be redundant but is good practice.
        return Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
    }

    /**
     * Get all form data for a given type, ordered by creation date.
     * @param string $formType
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllFormData(string $formType)
    {
        if (!isset($this->formModels[$formType])) {
            return response()->json(['message' => 'Invalid form type'], 404);
        }

        $model = $this->formModels[$formType];
        // Fetch all data for this form type, ordered by creation date (descending)
        // This ensures the first record in the collection is the latest.
        $allDataRecords = $model::orderBy('created_at', 'desc')->get();

        $combinedEntries = [];
        $topLevelData = [];

        // Aggregate all entries from all historical records.
        foreach ($allDataRecords as $record) {
            if (isset($record->data['entries']) && is_array($record->data['entries'])) {
                $combinedEntries = array_merge($combinedEntries, $record->data['entries']);
            }
        }

        // Get top-level data from the latest record, if one exists.
        // This ensures fields like 'unit_kerja', 'ruangan', 'judul_cp', 'bulan' (if stored top-level)
        // reflect the most recent state.
        $latestRecord = $allDataRecords->first();
        if ($latestRecord) {
            $topLevelData = $latestRecord->data;
            // Remove 'entries' from topLevelData to prevent duplication later,
            // as 'combinedEntries' already holds all of them.
            unset($topLevelData['entries']);
        }

        return response()->json([
            'data' => array_merge($topLevelData, ['entries' => $combinedEntries]),
            'history' => $allDataRecords->toArray()
        ]);
    }

    /**
     * Submit form data.
     * This method will now ensure `week_start_date` is always set.
     * @param Request $request
     * @param string $formType
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitForm(Request $request, string $formType)
    {
        if (!isset($this->formModels[$formType])) {
            return response()->json(['message' => 'Invalid form type'], 404);
        }

        $model = $this->formModels[$formType];

        // Validate basic request data
        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            // Determine the week_start_date.
            // If provided in the request (e.g., from auto-submit of old forms), use it.
            // Otherwise, default to the current week's start date.
            $weekStartDate = $request->input('week_start_date', $this->getCurrentWeekStartDate());

            // Get authenticated user ID
            $userId = auth()->id(); // This will be null if not authenticated

            // Find an existing record for the current week and user
            // Use updateOrCreate to either update the existing record or create a new one
            $formData = $model::updateOrCreate(
                [
                    'week_start_date' => $weekStartDate,
                    'user_id' => $userId, // Important: Include user_id in the unique constraint check
                ],
                [
                    'data' => $request->input('data'),
                    // 'created_at' and 'updated_at' are automatically handled by Laravel
                ]
            );

            // Return the newly saved/updated data or a success message
            return response()->json(['message' => 'Form data saved successfully!', 'data' => $formData->load('user')], 200); // Load user if needed
        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error("Error saving {$formType} form data: " . $e->getMessage(), ['exception' => $e]);
            return response()->json(['message' => 'Error saving form data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get historical form data for a specific type.
     * @param string $formType
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFormHistory(string $formType, Request $request)
    {
        if (!isset($this->formModels[$formType])) {
            return response()->json(['message' => 'Invalid form type'], 404);
        }

        $model = $this->formModels[$formType];

        $query = $model::query();

        // This endpoint is primarily for history, so it gets all, but can be filtered.
        if ($request->has('week_start_date')) {
            $query->where('week_start_date', $request->input('week_start_date'));
        }

        $history = $query->orderBy('week_start_date', 'desc')->get();

        return response()->json($history);
    }
}