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
     * @return string
     */
    private function getCurrentWeekStartDate()
    {
        return Carbon::now()->startOfWeek()->format('Y-m-d');
    }

    /**
     * Fetches all available entries for a form type, combined into one array,
     * and returns it along with the current week's start date context.
     * @param string $formType
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentWeekForm(string $formType)
    {
        if (!isset($this->formModels[$formType])) {
            return response()->json(['message' => 'Invalid form type'], 404);
        }

        $model = $this->formModels[$formType];
        $currentWeekStartDate = $this->getCurrentWeekStartDate();

        // Get all records for this form type, ordered by date to process chronologically
        $allForms = $model::orderBy('week_start_date', 'asc')->get();

        $combinedEntries = [];
        $latestOverallData = []; // To hold top-level fields like 'unit_kerja', 'ruangan', 'bulan', 'nb', 'totals'

        foreach ($allForms as $formRecord) {
            $data = json_decode($formRecord->data, true); // Decode the JSON 'data' column

            // Aggregate top-level fields from the latest record
            // This assumes that fields like 'unit_kerja' or 'ruangan' are generally constant
            // or the latest value is the desired one.
            $latestOverallData = array_merge($latestOverallData, array_diff_key($data, ['entries' => 0]));
        
            // Combine entries from all records
            if (isset($data['entries']) && is_array($data['entries'])) {
                foreach ($data['entries'] as $entry) {
                    $combinedEntries[] = $entry;
                }
            }
        }

        // Sort combined entries by their internal date/time field if they have one
        // This ensures the display order in the frontend is logical.
        if (!empty($combinedEntries)) {
            usort($combinedEntries, function($a, $b) use ($formType) {
                $dateFieldA = null;
                $dateFieldB = null;

                // Determine the primary date/time field for sorting
                if ($formType === 'hand-hygiene' && isset($a['bulan'])) {
                    $dateFieldA = Carbon::parse($a['bulan'] . '-01')->timestamp;
                    $dateFieldB = Carbon::parse($b['bulan'] . '-01')->timestamp;
                } elseif (isset($a['tgl'])) {
                    $dateFieldA = Carbon::parse($a['tgl'])->timestamp;
                    $dateFieldB = Carbon::parse($b['tgl'])->timestamp;
                } elseif (isset($a['tanggal'])) {
                    $dateFieldA = Carbon::parse($a['tanggal'])->timestamp;
                    $dateFieldB = Carbon::parse($b['tanggal'])->timestamp;
                } elseif (isset($a['tgl_registrasi'])) {
                    $dateFieldA = Carbon::parse($a['tgl_registrasi'])->timestamp;
                    $dateFieldB = Carbon::parse($b['tgl_registrasi'])->timestamp;
                }

                if ($dateFieldA !== null && $dateFieldB !== null) {
                    return $dateFieldA - $dateFieldB;
                }
                return 0; // Maintain original relative order if no date field for comparison
            });

            // Re-assign 'no' if the frontend depends on it being sequential after combining
            foreach ($combinedEntries as $index => &$entry) {
                $entry['no'] = $index + 1;
            }
            unset($entry); // Unset reference to prevent unexpected modification
        }
        
        // Ensure 'bulan' is present for Hand Hygiene even if no data or from older records
        if ($formType === 'hand-hygiene' && !isset($latestOverallData['bulan'])) {
            $latestOverallData['bulan'] = Carbon::now()->format('YYYY-MM');
        }


        // Construct the final data structure to send to the frontend
        $finalData = [
            // week_start_date here is for the *current context*, used for saving a new snapshot
            'week_start_date' => $currentWeekStartDate,
            'data' => array_merge($latestOverallData, ['entries' => $combinedEntries]),
            // Pass the ID and timestamps of the most recent overall form record for reference
            'id' => $allForms->last()->id ?? null,
            'created_at' => $allForms->last()->created_at ?? null,
            'updated_at' => $allForms->last()->updated_at ?? null,
        ];

        return response()->json($finalData);
    }

    /**
     * Submits form data. This will always create/update a record for the CURRENT week_start_date,
     * containing the ENTIRE set of entries and top-level data from the frontend.
     * This effectively creates a new 'snapshot' for the current week.
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

        // Data is always saved against the CURRENT week's start date as a new cumulative snapshot
        $weekStartDate = $this->getCurrentWeekStartDate();

        // The 'data' field now contains the *entire* collection of entries and top-level fields
        // that the frontend has.
        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
            'data.entries' => 'nullable|array', // Entries are part of the main 'data' blob
            // Add specific validation rules for other top-level fields within 'data' if needed
            // e.g., 'data.unit_kerja' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            // Encode the entire 'data' array (including all entries and top-level fields) as JSON
            $formData = $model::updateOrCreate(
                ['week_start_date' => $weekStartDate],
                ['data' => json_encode($request->input('data'))] // Store the entire data object as JSON
            );

            // Associate with authenticated user if applicable
            if (auth()->check()) {
                $formData->user_id = auth()->id();
                $formData->save();
            }

            return response()->json(['message' => 'Form data saved successfully!', 'data' => $formData], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error saving form data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Fetches historical form data records. This still fetches individual weekly snapshots.
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

        // Optional: Filter history by a specific week if requested (used by auto-submit logic)
        if ($request->has('week_start_date')) {
            $query->where('week_start_date', $request->input('week_start_date'));
        }

        // Order by week_start_date in descending order for most recent history first
        $history = $query->orderBy('week_start_date', 'desc')->get();

        return response()->json($history);
    }
}