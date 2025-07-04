<?php

namespace App\Http\Controllers;

use App\Models\DailyLog;
use App\Models\Staff;
use App\Models\Schedule;
use App\Models\PrivateSchedule;
use Illuminate\Support\Facades\Auth;
use App\Models\Shift;
use App\Models\Logistic;
use App\Models\CvcInsertion;
use App\Models\CvcMaintenance;
use App\Models\CvcInfection;
use App\Models\PerformanceEvaluation;
use App\Models\TrainingNeed;
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
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    // Define the form models here, similar to your QualityInspectionController
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
     * Get aggregated data for the Laporan page header.
     */
    public function getHeaderStats()
    {
        $activeStaffCount = Staff::where('status', 'Aktif')->count();
        // Placeholder for compliance rate - you'll need to define how this is calculated.
        // For example, based on daily log completeness, performance evaluations, etc.
        $complianceRate = 95; // Static for now, implement logic later

        return response()->json([
            'active_staff_count' => $activeStaffCount,
            'compliance_rate' => $complianceRate,
            'report_date' => Carbon::now()->isoFormat('dddd, DD MMMM YYYY'), // Corrected format for "Kamis, 04 Juli 2025"
        ]);
    }

    /**
     * Get daily logs for the 'Catatan Harian' tab.
     */
    public function getDailyLogs()
    {
        // Fetch only private schedules belonging to the authenticated user
        // and limit to a recent period (e.g., last 30 days) for reports
        // or simply take the latest 10 as per previous logic.
        $dailyLogs = Auth::user()->privateSchedules()
                          ->orderBy('scheduled_at', 'desc')
                          ->take(10) // Or filter by date: ->where('scheduled_at', '>=', Carbon::now()->subDays(30))
                          ->get();

        return response()->json($dailyLogs->map(function ($log) {
            return [
                'id' => $log->id,
                'log_time' => $log->scheduled_at, // Map scheduled_at to log_time
                'briefing_conducted' => $log->briefing, // Map briefing to briefing_conducted
                'meeting_held' => $log->meeting, // Map meeting to meeting_held
                'supervision_conducted' => $log->supervision, // Map supervision to supervision_conducted
                'handover_done' => $log->handover, // Map handover to handover_done
                'external_task_performed' => !empty($log->external_task), // Check if external_task exists
                'notes' => $log->note, // Map note to notes
            ];
        }));
    }

    /**
     * Get staff schedules for the 'Jadwal Dinas' tab.
     */
    public function getStaffSchedules()
    {
        $user = Auth::user();

        if (!$user->department_id || !$user->hospital_id) {
            return response()->json([
                'schedules' => [],
                'shift_summary' => ['Pagi' => 0, 'Sore' => 0, 'Malam' => 0, 'Unknown' => 0],
                'start_date' => Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d'),
                'end_date' => Carbon::now()->endOfWeek(Carbon::SUNDAY)->format('Y-m-d'),
                'all_staff_names' => [],
                'all_shifts_details' => [],
            ]);
        }

        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $allShifts = Shift::all()->keyBy('id');
        if ($allShifts->isEmpty()) {
             return response()->json([
                'schedules' => [],
                'shift_summary' => ['Pagi' => 0, 'Sore' => 0, 'Malam' => 0, 'Unknown' => 0],
                'start_date' => $startOfWeek->format('Y-m-d'),
                'end_date' => $endOfWeek->format('Y-m-d'),
                'all_staff_names' => [],
                'all_shifts_details' => [],
            ]);
        }

        $allStaff = Staff::where('department_id', $user->department_id)
                        ->where('hospital_id', $user->hospital_id)
                        ->where('user_id', $user->id) 
                        ->get();

        if ($allStaff->isEmpty()) {
            return response()->json([
                'schedules' => [],
                'shift_summary' => ['Pagi' => 0, 'Sore' => 0, 'Malam' => 0, 'Unknown' => 0],
                'start_date' => $startOfWeek->format('Y-m-d'),
                'end_date' => $endOfWeek->format('Y-m-d'),
                'all_staff_names' => [],
                'all_shifts_details' => [],
            ]);
        }

        $schedules = Schedule::with(['staff', 'shift'])
            ->whereIn('staff_id', $allStaff->pluck('id'))
            ->whereBetween('start', [$startOfWeek, $endOfWeek])
            ->get();

        $groupedSchedules = [];
        foreach ($allStaff as $staffMember) {
            $groupedSchedules[$staffMember->name] = [];
        }

        foreach ($schedules as $schedule) {
            $staffName = $schedule->staff->name ?? 'Unknown Staff';

            // CRITICAL FIX: Use shift->code for the name AND derive first letter for code
            $shiftFullName = $schedule->shift->code ?? null; // e.g., 'Pagi', 'Siang', 'Malam'
            $shiftShortCode = $shiftFullName ? $shiftFullName[0] : null; // e.g., 'P', 'S', 'M'

            if (!isset($groupedSchedules[$staffName])) {
                $groupedSchedules[$staffName] = [];
            }
            $groupedSchedules[$staffName][] = [
                'date' => $schedule->start->toDateString(),
                'shift_name' => $shiftFullName, // Use the actual shift code value as the "name"
                'shift_code' => $shiftShortCode, // Derive the single letter
            ];
        }

        $shiftCounts = [
            'Pagi' => 0,
            'Sore' => 0,
            'Malam' => 0,
            'Unknown' => 0,
        ];

        foreach ($schedules as $schedule) {
            // CRITICAL FIX: Use shift->code for counting, as that's where "Pagi", "Sore", "Malam" live
            $shiftNameForCount = $schedule->shift->code ?? null; 

            if (!empty($shiftNameForCount) && array_key_exists($shiftNameForCount, $shiftCounts)) {
                $shiftCounts[$shiftNameForCount]++;
            } else if (!empty($shiftNameForCount)) {
                $shiftCounts['Unknown']++;
            } else {
                $shiftCounts['Unknown']++;
            }
        }

        return response()->json([
            'schedules' => $groupedSchedules,
            'shift_summary' => $shiftCounts,
            'start_date' => $startOfWeek->format('Y-m-d'),
            'end_date' => $endOfWeek->format('Y-m-d'),
            'all_staff_names' => $allStaff->pluck('name')->toArray(),
            'all_shifts_details' => $allShifts->toArray(),
        ]);
    }

    /**
     * Get logistics data for the 'Manajemen Logistik' tab.
     */
    public function getLogisticsSummary()
    {
        $totalStock = Logistic::sum('quantity');
        $lowStockItems = Logistic::whereRaw('quantity <= min_stock_level')->count();
        $outOfStockItems = Logistic::where('quantity', 0)->count();

        $recentTransactions = Logistic::orderBy('last_updated', 'desc')->take(5)->get(); // Example: last 5 updated

        return response()->json([
            'total_stock_available' => $totalStock,
            'low_stock_items' => $lowStockItems,
            'out_of_stock_items' => $outOfStockItems,
            'recent_transactions' => $recentTransactions,
            // You might want to fetch a list of all items too, or provide filters.
            'all_logistics_items' => Logistic::orderBy('item_name')->get()
        ]);
    }

    /**
     * Get PPI data. This might require aggregating from multiple CVC related models.
     */
    public function getPpiData()
    {
        $user = Auth::user();
        $oneMonthAgo = Carbon::now()->subMonth();

        // 1. Get Summary Counts (these remain the same as before)
        $cvcInsertionsCount = CvcInsertion::where('user_id', $user->id)
                                           ->where('created_at', '>=', $oneMonthAgo)
                                           ->count();
        $cvcMaintenancesCount = CvcMaintenance::where('user_id', $user->id)
                                              ->where('created_at', '>=', $oneMonthAgo)
                                              ->count();
        $cvcInfectionsCount = CvcInfection::where('user_id', $user->id)
                                          ->where('created_at', '>=', $oneMonthAgo)
                                          ->count();

        // 2. Fetch and Map Recent Activities from ALL CVC Models
        $recentActivities = collect(); // Use a Laravel Collection for easy merging and sorting

        // Fetch recent CvcInsertion forms
        $insertions = CvcInsertion::where('user_id', $user->id)
                                  ->orderBy('created_at', 'desc')
                                  ->take(5) // Limit to a few recent ones
                                  ->get()
                                  ->map(function ($item) {
                                      return [
                                          'id' => $item->id,
                                          'activity_date' => $item->insertion_date,
                                          'patient_name' => $item->patient_name,
                                          'medical_record_number' => $item->medical_record_number,
                                          'form_type' => 'Bundle Insersi',
                                          'submitted_at' => $item->created_at, // Use created_at as timestamp
                                          'detail_type' => 'insertion', // For frontend detail button
                                      ];
                                  });
        $recentActivities = $recentActivities->concat($insertions);

        // Fetch recent CvcMaintenance forms
        $maintenances = CvcMaintenance::where('user_id', $user->id)
                                      ->orderBy('created_at', 'desc')
                                      ->take(5) // Limit to a few recent ones
                                      ->get()
                                      ->map(function ($item) {
                                          return [
                                              'id' => $item->id,
                                              'activity_date' => $item->maintenance_date,
                                              'patient_name' => $item->patient_name,
                                              'medical_record_number' => $item->medical_record_number,
                                              'form_type' => 'Bundle Maintenance',
                                              'submitted_at' => $item->created_at, // Use created_at as timestamp
                                              'detail_type' => 'maintenance', // For frontend detail button
                                          ];
                                      });
        $recentActivities = $recentActivities->concat($maintenances);

        // Fetch recent CvcInfection reports
        $infections = CvcInfection::where('user_id', $user->id)
                                   ->orderBy('created_at', 'desc')
                                   ->take(5) // Limit to a few recent ones
                                   ->get()
                                   ->map(function ($item) {
                                       return [
                                           'id' => $item->id,
                                           'activity_date' => $item->infection_diagnosis_date,
                                           'patient_name' => $item->patient_name,
                                           'medical_record_number' => $item->medical_record_number,
                                           'form_type' => 'Laporan Infeksi',
                                           'submitted_at' => $item->created_at, // Use created_at as timestamp
                                           'detail_type' => 'infection', // For frontend detail button
                                       ];
                                   });
        $recentActivities = $recentActivities->concat($infections);

        // Sort the combined activities by submitted_at in descending order
        $sortedRecentActivities = $recentActivities->sortByDesc('submitted_at')->values()->take(10); // Take top 10 overall

        return response()->json([
            'cvc_insertions_month' => $cvcInsertionsCount,
            'cvc_maintenances_month' => $cvcMaintenancesCount,
            'cvc_infections_month' => $cvcInfectionsCount,
            'recent_ppi_activities' => $sortedRecentActivities, // Use the combined and sorted data
        ]);
    }

    /**
     * Get staff performance data for the 'Kinerja Staff' tab.
     */
    public function getStaffPerformance()
    {
        $user = Auth::user();

        $performanceData = PerformanceEvaluation::with('staff.position') // Ensure 'staff.position' is loaded
            ->whereHas('staff', function ($query) use ($user) {
                $query->where('department_id', $user->department_id)
                      ->where('hospital_id', $user->hospital_id)
                      ->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Map the data to match the frontend's expected keys and calculate overall_score
        return response()->json($performanceData->map(function ($perf) {
            // Calculate average rating if status_kinerja is a string description
            $averageRating = array_sum([
                $perf->kedisiplinan,
                $perf->komunikasi,
                $perf->komplain,
                $perf->kepatuhan,
                $perf->target_kerja
            ]) / 5; // Average on a scale of 1-5

            // Convert average rating to a 0-100 scale for consistency with frontend's getStarRating if needed
            // If getStarRating expects 0-5 input, keep it as $averageRating
            // If getStarRating expects 0-100 input, convert: $overallScoreForStars = ($averageRating / 5) * 100;
            // Based on your getStarRating: `Math.round((score / 100) * maxStars);` it expects 0-100 score.
            $overallScoreForStars = round(($averageRating / 5) * 100);

            return [
                'id' => $perf->id,
                'staff_id' => $perf->staff_id,
                'staff' => $perf->staff, // Keep the eager-loaded staff object
                'discipline_score' => $perf->kedisiplinan,
                'communication_score' => $perf->komunikasi,
                'complaint_count' => $perf->komplain, // Assuming 'komplain' is the count of complaints
                'compliance_score' => $perf->kepatuhan,
                'target_achievement' => $perf->target_kerja, // Assuming target_kerja is the achievement score
                'overall_score' => $overallScoreForStars, // Pass calculated score for stars
                'notes' => $perf->notes,
                'evaluation_date' => $perf->created_at->toDateString(),
                'status_kinerja' => $perf->status_kinerja, // Keep the descriptive status for the badge
            ];
        }));
    }

    /**
     * Get TNA data for the 'TNA' tab.
     */
    public function getTnaData()
    {
        $tnaData = TrainingNeed::with('staff.position') // Eager load staff and their position
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($tnaData);
    }

    /**
     * Get Quality Indicator (Indikator Mutu) data by iterating through all specific form models.
     */
    public function getQualityIndicators()
    {
        $recentInspections = [];
        $totalEvaluations = 0;
        $totalPassScore = 0; // Assuming a "pass" means score is within an acceptable range based on form data

        $currentWeekStartDate = Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
        $oneMonthAgo = Carbon::now()->subMonth();

        foreach ($this->formModels as $formType => $modelClass) {
            $model = new $modelClass(); // Instantiate the model

            // Fetch the latest entry for each form type for recent inspections
            $latestForm = $model::orderBy('week_start_date', 'desc')->first();
            if ($latestForm) {
                // Determine a 'score' and 'notes' from the 'data' JSON based on your form structure
                // This is a *generic example* and needs to be adjusted based on the actual content
                // of the 'data' JSON for each form type.
                $formData = json_decode($latestForm->data, true);
                $score = 'N/A'; // Default
                $notes = 'No specific notes found'; // Default

                // Example: If 'data' contains an 'average_score' or 'compliance_percentage'
                if (isset($formData['average_score'])) {
                    $score = $formData['average_score'];
                } elseif (isset($formData['compliance_percentage'])) {
                    $score = $formData['compliance_percentage'];
                } elseif (isset($formData['total_score']) && isset($formData['max_score'])) {
                    if ($formData['max_score'] > 0) {
                        $score = ($formData['total_score'] / $formData['max_score']) * 100;
                    }
                }

                // Example for notes: if 'data' contains a 'summary' or 'remarks'
                if (isset($formData['summary'])) {
                    $notes = $formData['summary'];
                } elseif (isset($formData['remarks'])) {
                    $notes = $formData['remarks'];
                }

                $recentInspections[] = [
                    'id' => $latestForm->id,
                    'form_type' => ucwords(str_replace('-', ' ', $formType)), // Human-readable name
                    'date' => $latestForm->week_start_date,
                    'score' => is_numeric($score) ? round($score, 2) : $score,
                    'notes' => $notes,
                ];

                // Aggregate for overall pass rate
                // For a pass rate, you'd need a consistent 'pass' condition for each form.
                // This is a placeholder. You might need a dedicated method in each form model
                // to calculate its 'pass' status or a numerical score.
                if (is_numeric($score)) {
                    $totalEvaluations++;
                    // Example: Assume a pass if score is >= 80
                    if ($score >= 80) {
                        $totalPassScore++;
                    }
                }
            }
        }

        // Sort recent inspections by date
        usort($recentInspections, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        $overallPassRate = $totalEvaluations > 0 ? ($totalPassScore / $totalEvaluations) * 100 : 0;

        return response()->json([
            'recent_inspections' => array_slice($recentInspections, 0, 10), // Limit to top 10 recent
            'overall_pass_rate' => round($overallPassRate, 2),
            // Add other aggregated metrics as needed
        ]);
    }
}