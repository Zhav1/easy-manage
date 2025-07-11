<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth; 
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
use Illuminate\Support\Str; 

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
    public function getAllQualityFormDataForReport()
    {
        $user = Auth::user();
        $allQualityEntries = collect();

        foreach ($this->formModels as $formType => $modelClass) {
            $model = new $modelClass();

            // Fetch ALL entries for the current user and form type
            $records = $model::where('user_id', $user->id)
                               ->orderBy('created_at', 'asc') // Order chronologically
                               ->get();

            foreach ($records as $record) {
                // Determine the primary date for the entry (week_start_date or created_at)
                // Replaced ?? with isset() ? :
                $activityDate = isset($record->week_start_date) ? $record->week_start_date : $record->created_at->toDateString();
                $submittedAt = $record->created_at->toDateTimeString();
                $formName = ucwords(str_replace('-', ' ', $formType));

                $patientOrEntity = 'N/A';
                $score = 'N/A';
                $notes = 'Tidak ada catatan';
                $details = []; // Collect specific details for each form type

                // Access the 'data' JSON column or direct properties
                $formData = $record->data;

                // --- Common fields processing ---
                // Score extraction
                if (isset($formData['compliance_percentage'])) {
                    $score = $formData['compliance_percentage'] . '%';
                } elseif (isset($formData['overall_score'])) {
                    $score = $formData['overall_score'] . '%';
                } elseif (isset($formData['totals']['compliant_count']) && isset($formData['totals']['total_observed'])) {
                    if ($formData['totals']['total_observed'] > 0) {
                        $score = round(($formData['totals']['compliant_count'] / $formData['totals']['total_observed']) * 100) . '%';
                    }
                } elseif (isset($record->compliance_percentage)) { // Some models might store it directly
                    $score = $record->compliance_percentage . '%';
                }
                
                // Notes/Keterangan extraction
                if (isset($formData['notes'])) {
                    $notes = $formData['notes'];
                } elseif (isset($formData['keterangan'])) {
                    $notes = $formData['keterangan'];
                } elseif (isset($formData['summary'])) {
                    $notes = $formData['summary'];
                }

                // Patient/Entity identification
                if (isset($formData['patient_name'])) {
                    $patientOrEntity = $formData['patient_name'];
                } elseif (isset($formData['medical_record_number'])) {
                    $patientOrEntity = 'No. RM: ' . $formData['medical_record_number'];
                } elseif (isset($record->patient_name)) {
                    $patientOrEntity = $record->patient_name;
                } elseif (isset($record->medical_record_number)) {
                    $patientOrEntity = 'No. RM: ' . $record->medical_record_number;
                }

                // --- Form-specific details for the 'Detail Formulir' column ---
                // We'll summarize the key unique data points from each form's entries
                switch ($formType) {
                    case 'hand-hygiene':
                        if (isset($formData['entries']) && is_array($formData['entries'])) {
                            $totalSesi = collect($formData['entries'])->sum('sesi');
                            $totalKesempatan = collect($formData['entries'])->sum('total_kesempatan');
                            $details[] = "Total Sesi: {$totalSesi}";
                            $details[] = "Total Kesempatan: {$totalKesempatan}";
                        }
                        break;
                    case 'apd':
                        if (isset($formData['entries']) && is_array($formData['entries'])) {
                            $patuhCount = collect($formData['entries'])->where('kepatuhan', 'Patuh')->count();
                            $tidakPatuhCount = collect($formData['entries'])->where('kepatuhan', 'Tidak')->count();
                            $details[] = "Patuh: {$patuhCount}x";
                            $details[] = "Tidak Patuh: {$tidakPatuhCount}x";
                        }
                        break;
                    case 'identifikasi':
                        // Replaced ?? with isset() ? :
                        $details[] = "Unit Kerja: " . (isset($formData['unit_kerja']) ? $formData['unit_kerja'] : '-');
                        if (isset($formData['entries']) && is_array($formData['entries'])) {
                            $dilakukanCount = collect($formData['entries'])->where('dilakukan', true)->count();
                            $tidakDilakukanCount = collect($formData['entries'])->where('tidak_dilakukan', true)->count();
                            $details[] = "Dilakukan: {$dilakukanCount}x";
                            $details[] = "Tidak Dilakukan: {$tidakDilakukanCount}x";
                        }
                        break;
                    case 'wtri':
                        // Replaced ?? with isset() ? :
                        $details[] = "Unit Kerja: " . (isset($formData['unit']) ? $formData['unit'] : '-');
                        if (isset($formData['entries']) && is_array($formData['entries'])) {
                            $avgResponCA = collect($formData['entries'])->avg('respon_time_ca');
                            $avgResponCB = collect($formData['entries'])->avg('respon_time_cb');
                            $details[] = "Avg. Respon C-A: " . round($avgResponCA, 0) . " min";
                            $details[] = "Avg. Respon C-B: " . round($avgResponCB, 0) . " min";
                        }
                        break;
                    case 'kritis-lab':
                        if (isset($formData['entries']) && is_array($formData['entries'])) {
                            $withinTarget = collect($formData['entries'])->where('pelaporan_status', '≤ 30 Menit')->count();
                            $outsideTarget = collect($formData['entries'])->where('pelaporan_status', '> 30 Menit')->count();
                            $details[] = "≤30 Menit: {$withinTarget}x";
                            $details[] = ">30 Menit: {$outsideTarget}x";
                        }
                        break;
                    case 'fornas':
                        if (isset($formData['entries']) && is_array($formData['entries'])) {
                            $totalResep = collect($formData['entries'])->sum('jumlah_resep');
                            $fornasResep = collect($formData['entries'])->where('formularium_nasional', true)->sum('jumlah_resep');
                            $details[] = "Total Resep: {$totalResep}";
                            $details[] = "FORNAS: {$fornasResep}";
                        }
                        break;
                    case 'visite':
                        if (isset($formData['entries']) && is_array($formData['entries'])) {
                            $totalVisites = collect($formData['entries'])->count();
                            // Replaced ?? with isset() ? :
                            $lateVisites = collect($formData['entries'])->where('val_iv', 1)->count(); // Visite >14.00
                            $details[] = "Total Visite: {$totalVisites}";
                            $details[] = "Visite Lambat (>14.00): {$lateVisites}";
                        }
                        break;
                    case 'jatuh':
                        if (isset($formData['totals'])) {
                            // Replaced ?? with isset() ? :
                            $details[] = "3 Upaya Ya: " . (isset($formData['totals']['ketiga_upaya_ya']) ? $formData['totals']['ketiga_upaya_ya'] : 0) . "x";
                            $details[] = "3 Upaya Tidak: " . (isset($formData['totals']['ketiga_upaya_tidak']) ? $formData['totals']['ketiga_upaya_tidak'] : 0) . "x";
                        }
                        break;
                    case 'cp':
                        // Replaced ?? with isset() ? :
                        $details[] = "Judul CP: " . (isset($formData['judul_cp']) ? $formData['judul_cp'] : '-');
                        $details[] = "Ruangan: " . (isset($formData['ruangan']) ? $formData['ruangan'] : '-');
                        $details[] = "Kepatuhan: " . (isset($formData['rata_rata_kepatuhan']) ? $formData['rata_rata_kepatuhan'] : '-');
                        break;
                    case 'kepuasan':
                        if (isset($formData['entries']) && is_array($formData['entries'])) {
                            $puasSangatPuas = collect($formData['entries'])
                                ->whereIn('nilai_kepuasan', ['4 (Puas)', '5 (Sangat Puas)'])->count();
                            $details[] = "Puas/Sangat Puas: {$puasSangatPuas}x";
                            $details[] = "Total Responden: " . count($formData['entries']);
                        }
                        break;
                    case 'krk':
                        if (isset($formData['entries']) && is_array($formData['entries'])) {
                            $totalComplains = collect($formData['entries'])->count();
                            $sesuaiGrading = collect($formData['entries'])->where('penyelesaian_ya', true)->count();
                            $details[] = "Total Komplain: {$totalComplains}";
                            $details[] = "Sesuai Grading: {$sesuaiGrading}x";
                        }
                        break;
                    case 'poe':
                        if (isset($formData['entries']) && is_array($formData['entries'])) {
                            $totalOps = collect($formData['entries'])->count();
                            $tertundaCount = collect($formData['entries'])->where('penundaan_gt_1hr', true)->count();
                            $details[] = "Total Operasi: {$totalOps}";
                            $details[] = "Tertunda (>1 jam): {$tertundaCount}x";
                        }
                        break;
                    case 'sc':
                        if (isset($formData['entries']) && is_array($formData['entries'])) {
                            $totalOps = collect($formData['entries'])->count();
                            $inTargetCount = collect($formData['entries'])->where('gt_30_menit', 'Tidak')->count(); // 'Tidak' means within target
                            $details[] = "Total Operasi SC: {$totalOps}";
                            $details[] = "Dalam Target (≤30 min): {$inTargetCount}x";
                        }
                        break;
                    default:
                        // Fallback for any unexpected form types
                        $details[] = "Raw Data: " . Str::limit(json_encode($formData), 50, '...');
                        break;
                }

                $allQualityEntries->push([
                    'form_type_slug' => $formType,
                    'form_name' => $formName,
                    'activity_date' => $activityDate,
                    'patient_entity' => $patientOrEntity,
                    'score' => $score,
                    'notes' => Str::limit($notes, 200, '...'), // Keep notes concise
                    'details_summary' => implode('; ', $details), // Combine specific details
                    'submitted_at' => $submittedAt,
                ]);
            }
        }
        
        return $allQualityEntries->sortBy('submitted_at')->values(); // Sort by submission time for consistency
    }
}