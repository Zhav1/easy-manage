<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; 
use Maatwebsite\Excel\Facades\Excel; 
use Barryvdh\DomPDF\Facade\Pdf;      
use Illuminate\Support\Str;

// Import all models required for data retrieval
use App\Models\PrivateSchedule;
use App\Models\SpecialCase;
use App\Models\Schedule;
use App\Models\Staff; 
use App\Models\Shift; // For Staff schedules
use App\Models\Logistic;
use App\Models\CvcInsertion;
use App\Models\CvcMaintenance;
use App\Models\CvcInfection;
use App\Models\NeedlestickReport;
use App\Models\PerformanceEvaluation;
use App\Models\TrainingNeed;
// Import all quality form models
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

// Import all Export Classes
use App\Exports\DailyLogsExport;
use App\Exports\StaffSchedulesExport;
use App\Exports\LogisticsExport;
use App\Exports\PpiReportsExport;
use App\Exports\StaffPerformanceExport;
use App\Exports\TnaRecordsExport;
use App\Exports\QualityIndicatorsExport;

class ReportController extends Controller
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

    private function getUserInfoForPdf() {
        return Auth::user()->load('department', 'hospital');
    }

    public function getHeaderStats()
    {
        $user = Auth::user();
        $activeStaffCount = Staff::where('user_id', $user->id)->where('status', 'Aktif')->count();
        $complianceRate = 95;

        return response()->json([
            'active_staff_count' => $activeStaffCount,
            'compliance_rate' => $complianceRate,
            'report_date' => Carbon::now()->isoFormat('dddd, DD MMMM YYYY'),
        ]);
    }

    public function getDailyLogs()
    {
        $user = Auth::user();

        $privateSchedules = $user->privateSchedules()
                                 ->orderBy('scheduled_at', 'desc')
                                 ->take(10)
                                 ->get()
                                 ->map(function ($log) {
                                     return [
                                         'type' => 'private_schedule',
                                         'id' => $log->id,
                                         'date' => $log->scheduled_at,
                                         'briefing_conducted' => $log->briefing,
                                         'meeting_held' => $log->meeting,
                                         'supervision_conducted' => $log->supervision,
                                         'handover_done' => $log->handover,
                                         'external_task' => $log->external_task,
                                         'notes' => $log->note,
                                     ];
                                 });

        $specialCases = $user->specialCases()
                             ->orderBy('case_date', 'desc')
                             ->take(10)
                             ->get()
                             ->map(function ($case) {
                                 return [
                                     'type' => 'special_case',
                                     'id' => $case->id,
                                     'date' => $case->case_date,
                                     'patient_name' => $case->patient_name,
                                     'case_type' => $case->case_type,
                                     'details' => $case->details,
                                     'action_taken' => $case->action_taken,
                                 ];
                             });

        $combinedLogs = $privateSchedules->concat($specialCases)
                                         ->sortByDesc('date')
                                         ->values();

        return response()->json($combinedLogs);
    }

    public function getStaffSchedules(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $schedules = Schedule::with(['staff', 'shift'])
            ->whereHas('staff', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('department_id', $user->department_id)
                      ->where('hospital_id', $user->hospital_id);
            })
            ->whereBetween('start', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->get();
        
        $allStaffNames = Staff::where('user_id', $user->id)
                                ->where('department_id', $user->department_id)
                                ->where('hospital_id', $user->hospital_id)
                                ->pluck('name')
                                ->unique()
                                ->values()
                                ->toArray();
                                
        $groupedSchedulesForTable = [];
        $shiftSummaryCounts = [
            'Pagi' => 0,
            'Siang' => 0, 
            'Malam' => 0,
        ];

        foreach ($allStaffNames as $staffName) {
            $groupedSchedulesForTable[$staffName] = ['staff_name' => $staffName];
            for ($i = 0; $i < 7; $i++) {
                $date = (clone $startOfWeek)->addDays($i);
                $dayKey = strtolower($date->isoFormat('dddd'));
                $groupedSchedulesForTable[$staffName][$dayKey] = [];
            }
        }

        foreach ($schedules as $schedule) {
            $staffName = $schedule->staff->name ?? 'Unknown Staff';
            $shiftFullName = $schedule->shift->code ?? null;
            
            if ($shiftFullName === 'Sore') {
                $shiftFullName = 'Siang';
            }

            $shiftShortCode = $shiftFullName ? $shiftFullName[0] : '-';
            $scheduleDate = Carbon::parse($schedule->start)->toDateString();
            $dayKey = strtolower(Carbon::parse($schedule->start)->isoFormat('dddd'));

            if (isset($groupedSchedulesForTable[$staffName][$dayKey])) {
                $groupedSchedulesForTable[$staffName][$dayKey][] = [
                    'shift_name' => $shiftFullName,
                    'shift_code' => $shiftShortCode,
                ];
            }

            if (!empty($shiftFullName) && array_key_exists($shiftFullName, $shiftSummaryCounts)) {
                $shiftSummaryCounts[$shiftFullName]++;
            }
        }

        $finalTableData = array_values($groupedSchedulesForTable);

        foreach ($finalTableData as &$staffRow) {
            for ($i = 0; $i < 7; $i++) {
                $date = (clone $startOfWeek)->addDays($i);
                $dayKey = strtolower($date->isoFormat('dddd'));

                $shiftsOnDay = $staffRow[$dayKey];
                if (!empty($shiftsOnDay)) {
                    $displayCodes = array_map(function($shift) {
                        return $shift['shift_code'];
                    }, $shiftsOnDay);
                    $staffRow[$dayKey] = [
                        'display' => implode(', ', $displayCodes),
                        'types' => array_map(function($shift){ return $shift['shift_name']; }, $shiftsOnDay)
                    ];
                } else {
                    $staffRow[$dayKey] = ['display' => '-', 'types' => ['empty']];
                }
            }
        }
        unset($staffRow);

        return response()->json([
            'schedules' => $finalTableData,
            'shift_summary' => $shiftSummaryCounts,
            'start_date' => $startOfWeek->format('Y-m-d'),
            'end_date' => $endOfWeek->format('Y-m-d'),
            'all_staff_names' => $allStaffNames,
        ]);
    }

    public function getLogisticsSummary()
    {
        $user = Auth::user();

        if (!$user->department_id) {
            return response()->json([
                'total_stock_available' => 0,
                'limited_stock' => 0,
                'low_stock' => 0,
                'categorized_items' => [],
                'categories_overview' => [],
            ]);
        }

        $departmentId = $user->department_id;

        $totalStock = Logistic::where('department_id', $departmentId)->sum('stock');
        $limitedStock = Logistic::where('department_id', $departmentId)
            ->where('stock', '<', 10)
            ->where('stock', '>=', 5)
            ->count();
        $lowStock = Logistic::where('department_id', $departmentId)
            ->where('stock', '<', 5)
            ->count();

        $categories = ['Alat Medis', 'Alat Kesehatan', 'Linen', 'Barang Habis Pakai', 'Obat'];
        $categorizedItems = [];
        $categoriesOverview = [];

        foreach ($categories as $category) {
            $items = Logistic::where('department_id', $departmentId)
                ->where('category', $category)
                ->orderBy('item_name')
                ->limit(5)
                ->get();

            $count = Logistic::where('department_id', $departmentId)
                ->where('category', $category)
                ->count();

            $mappedItems = $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'brand' => $item->brand,
                    'stock' => $item->stock,
                    'unit_of_measure' => $item->unit_of_measure,
                    'status' => $item->status,
                    'item_code' => $item->item_code,
                    'maintenance_schedule' => $item->maintenance_schedule,
                    'calibration_date' => $item->calibration_date ? Carbon::parse($item->calibration_date)->toDateString() : null,
                    'calibration_expiry_date' => $item->calibration_expiry_date ? Carbon::parse($item->calibration_expiry_date)->toDateString() : null,
                    'notes' => $item->notes,
                    'last_updated' => $item->updated_at ? $item->updated_at->toDateTimeString() : null,
                    'department_name' => $item->department->name ?? null,
                ];
            });

            $categorizedItems[Str::slug($category)] = $mappedItems;
            $categoriesOverview[] = [
                'name' => $category,
                'slug' => Str::slug($category),
                'count' => $count,
                'icon_class' => $this->getCategoryIconClass($category),
                'description_text' => $this->getCategoryDescriptionText($category),
            ];
        }

        return response()->json([
            'total_stock_available' => $totalStock,
            'limited_stock' => $limitedStock,
            'low_stock' => $lowStock,
            'categorized_items' => $categorizedItems,
            'categories_overview' => $categoriesOverview,
        ]);
    }

    private function getCategoryIconClass(string $category): string
    {
        return match ($category) {
            'Alat Medis' => 'fa-medkit',
            'Alat Kesehatan' => 'fa-stethoscope',
            'Linen' => 'fa-bed',
            'Barang Habis Pakai' => 'fa-boxes',
            'Obat' => 'fa-pills',
            default => 'fa-question-circle',
        };
    }

    private function getCategoryDescriptionText(string $category): string
    {
        return match ($category) {
            'Alat Medis' => 'Medical Equipment',
            'Alat Kesehatan' => 'Health Tools',
            'Linen' => 'Textile & Bedding',
            'Barang Habis Pakai' => 'Floor Supplies',
            'Obat' => 'Medicines',
            default => '',
        };
    }

    /**
     * Get PPI data including main stats, trends, and categorized incidents.
     * This method now aggregates data from various PPI-related models.
     */
    public function getPpiData()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $last30Days = Carbon::now()->subDays(30);
        $last6Months = Carbon::now()->subMonths(6);

        // 1. Get Summary Counts for today (for top cards in Laporan)
        $totalInsertionsToday = CvcInsertion::where('user_id', $user->id)
                                            ->whereDate('created_at', $today)
                                            ->count();
        $totalMaintenancesToday = CvcMaintenance::where('user_id', $user->id)
                                                ->whereDate('created_at', $today)
                                                ->count();
        $totalInfectionsToday = CvcInfection::where('user_id', $user->id)
                                            ->whereDate('created_at', $today)
                                            ->count();
        $totalNeedlestickCasesToday = NeedlestickReport::where('user_id', $user->id)
                                                    ->whereDate('created_at', $today)
                                                    ->count();

        // 2. Get Compliance Rates for last 30 days (for KPIs)
        $totalInsertionsLast30Days = CvcInsertion::where('user_id', $user->id)
                                                  ->where('insertion_date', '>=', $last30Days)
                                                  ->count();
        $compliantInsertionsLast30Days = CvcInsertion::where('user_id', $user->id)
                                                      ->where('insertion_date', '>=', $last30Days)
                                                      ->where('compliance_percentage', 100)
                                                      ->count();
        $insertionComplianceRate = ($totalInsertionsLast30Days > 0) ? round(($compliantInsertionsLast30Days / $totalInsertionsLast30Days) * 100, 2) : 0;

        $totalMaintenancesLast30Days = CvcMaintenance::where('user_id', $user->id)
                                                      ->where('maintenance_date', '>=', $last30Days)
                                                      ->count();
        $compliantMaintenancesLast30Days = CvcMaintenance::where('user_id', $user->id)
                                                          ->where('maintenance_date', '>=', $last30Days)
                                                          ->where('compliance_percentage', 100)
                                                          ->count();
        $maintenanceComplianceRate = ($totalMaintenancesLast30Days > 0) ? round(($compliantMaintenancesLast30Days / $totalMaintenancesLast30Days) * 100, 2) : 0;

        $totalNeedlestickLast30Days = NeedlestickReport::where('user_id', $user->id)
                                                        ->where('incident_date', '>=', $last30Days)
                                                        ->count();

        // 3. Trend Data for Charts (last 6 months)
        $infectionTrend = CvcInfection::where('user_id', $user->id)
            ->where('infection_diagnosis_date', '>=', $last6Months)
            ->selectRaw('DATE_FORMAT(infection_diagnosis_date, "%Y-%m") as month, count(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $needlestickTrend = NeedlestickReport::where('user_id', $user->id)
            ->where('incident_date', '>=', $last6Months)
            ->selectRaw('DATE_FORMAT(incident_date, "%Y-%m") as month, count(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // 4. Categorized Data for Pie/Bar Charts
        $infectionByLocation = CvcInfection::where('user_id', $user->id)
            ->selectRaw('insertion_location, count(*) as count')
            ->groupBy('insertion_location')
            ->get();

        $infectionByMicroorganism = CvcInfection::where('user_id', $user->id)
            ->whereNotNull('microorganism')
            ->selectRaw('microorganism, count(*) as count')
            ->groupBy('microorganism')
            ->orderByDesc('count')
            ->limit(5) // Limit top 5 for chart clarity
            ->get();

        $needlestickByDepartment = NeedlestickReport::where('user_id', $user->id)
            ->selectRaw('department, count(*) as count')
            ->groupBy('department')
            ->orderByDesc('count')
            ->get();

        $needlestickByPosition = NeedlestickReport::where('user_id', $user->id)
            ->selectRaw('injured_person_position, count(*) as count')
            ->groupBy('injured_person_position')
            ->orderByDesc('count')
            ->get();
        
        // 5. Recent Activities Combined
        $recentActivities = collect();
        $recentActivities->push(...CvcInsertion::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(3)->get()->map(function($item){
            return ['type' => 'insertion', 'activity_date' => $item->insertion_date, 'patient_name' => $item->patient_name, 'medical_record_number' => $item->medical_record_number, 'form_type' => 'Bundle Insersi', 'submitted_at' => $item->created_at];
        }));
        $recentActivities->push(...CvcMaintenance::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(3)->get()->map(function($item){
            return ['type' => 'maintenance', 'activity_date' => $item->maintenance_date, 'patient_name' => $item->patient_name, 'medical_record_number' => $item->medical_record_number, 'form_type' => 'Bundle Maintenance', 'submitted_at' => $item->created_at];
        }));
        $recentActivities->push(...CvcInfection::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(3)->get()->map(function($item){
            return ['type' => 'infection', 'activity_date' => $item->infection_diagnosis_date, 'patient_name' => $item->patient_name, 'medical_record_number' => $item->medical_record_number, 'form_type' => 'Laporan Infeksi', 'submitted_at' => $item->created_at];
        }));
        $recentActivities->push(...NeedlestickReport::where('user_id', $user->id)->orderBy('created_at', 'desc')->take(3)->get()->map(function($item){
            return ['type' => 'needlestick', 'activity_date' => $item->incident_date, 'patient_name' => $item->injured_person_name, 'medical_record_number' => 'N/A', 'form_type' => 'Laporan Tertusuk Jarum', 'submitted_at' => $item->created_at]; // Needlestick has injured_person_name instead of patient_name
        }));

        $sortedRecentActivities = $recentActivities->sortByDesc('submitted_at')->values()->take(10); // Top 10 overall recent activities

        return response()->json([
            'total_insertions_today' => $totalInsertionsToday,
            'total_maintenances_today' => $totalMaintenancesToday,
            'total_infections_today' => $totalInfectionsToday,
            'total_needlestick_cases_today' => $totalNeedlestickCasesToday,
            
            'insertion_compliance_rate' => $insertionComplianceRate,
            'maintenance_compliance_rate' => $maintenanceComplianceRate,
            'needlestick_rate_30_days' => $totalNeedlestickLast30Days, // Use the count from needlestick reports
            
            'infection_trend' => $infectionTrend,
            'needlestick_trend' => $needlestickTrend,
            'infection_by_location' => $infectionByLocation,
            'infection_by_microorganism' => $infectionByMicroorganism,
            'needlestick_by_department' => $needlestickByDepartment,
            'needlestick_by_position' => $needlestickByPosition,

            'recent_ppi_activities' => $sortedRecentActivities,
        ]);
    }

    public function getStaffPerformance()
    {
        $user = Auth::user();

        $performanceData = PerformanceEvaluation::with('staff.position')
            ->whereHas('staff', function ($query) use ($user) {
                $query->where('department_id', $user->department_id)
                      ->where('hospital_id', $user->hospital_id)
                      ->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($performanceData->map(function ($perf) {
            $averageRating = array_sum([
                $perf->kedisiplinan,
                $perf->komunikasi,
                $perf->komplain,
                $perf->kepatuhan,
                $perf->target_kerja
            ]) / 5;

            $overallScoreForStars = round($averageRating);

            return [
                'id' => $perf->id,
                'staff_id' => $perf->staff_id,
                'staff' => $perf->staff,
                'discipline_score' => $perf->kedisiplinan,
                'communication_score' => $perf->komunikasi,
                'complaint_count' => $perf->komplain,
                'compliance_score' => $perf->kepatuhan,
                'target_achievement' => $perf->target_kerja,
                'overall_score' => $overallScoreForStars,
                'notes' => $perf->notes,
                'evaluation_date' => $perf->created_at->toDateString(),
                'status_kinerja' => $perf->status_kinerja,
            ];
        }));
    }

    public function getTnaData()
    {
        $tnaData = TrainingNeed::with('staff.position')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($tnaData);
    }

    public function getQualityIndicators()
    {
        $user = Auth::user();
        $recentInspectionsCombined = collect();

        foreach ($this->formModels as $formType => $modelClass) {
            $model = new $modelClass();

            $recentEntries = $model::where('user_id', $user->id)
                                   ->orderBy('created_at', 'desc')
                                   ->take(3)
                                   ->get();

            $mappedEntries = $recentEntries->map(function ($entry) use ($formType) {
                $formData = $entry->data;
                $score = 'N/A';
                $notes = 'N/A';

                if (isset($formData['compliance_percentage'])) {
                    $score = $formData['compliance_percentage'] . '%';
                } elseif (isset($formData['overall_score'])) {
                    $score = $formData['overall_score'] . '%';
                } elseif (isset($formData['totals']['compliant_count']) && isset($formData['totals']['total_observed'])) {
                    if ($formData['totals']['total_observed'] > 0) {
                        $score = round(($formData['totals']['compliant_count'] / $formData['totals']['total_observed']) * 100) . '%';
                    }
                } elseif (isset($entry->compliance_percentage)) {
                    $score = $entry->compliance_percentage . '%';
                }

                if (isset($formData['notes'])) {
                    $notes = $formData['notes'];
                } elseif (isset($formData['keterangan'])) {
                    $notes = $formData['keterangan'];
                } elseif (isset($formData['summary'])) {
                    $notes = $formData['summary'];
                }

                return [
                    'id' => $entry->id,
                    'activity_date' => $entry->week_start_date ?? $entry->created_at->toDateString(),
                    'form_name' => ucwords(str_replace('-', ' ', $formType)),
                    'score' => $score,
                    'notes' => Str::limit($notes, 50, '...'),
                    'submitted_at' => $entry->created_at->toDateTimeString(),
                    'form_type_slug' => $formType,
                ];
            });
            $recentInspectionsCombined = $recentInspectionsCombined->concat($mappedEntries);
        }

        $finalRecentInspections = $recentInspectionsCombined->sortByDesc('submitted_at')->values()->take(10);

        $totalFormsTracked = count($this->formModels);
        $formsWithData = 0;
        foreach ($this->formModels as $formType => $modelClass) {
            $model = new $modelClass();
            if ($model::where('user_id', $user->id)->exists()) {
                $formsWithData++;
            }
        }
        $overallProgressRate = $totalFormsTracked > 0 ? round(($formsWithData / $totalFormsTracked) * 100, 2) : 0;

        return response()->json([
            'recent_inspections' => $finalRecentInspections,
            'overall_pass_rate' => $overallProgressRate,
        ]);
    }

    // --- New PDF Export Methods ---

    public function exportDailyLogsPdf()
    {
        $user = Auth::user();
        // Fetch full data, mirroring the DailyLogsExport logic
        $privateSchedules = PrivateSchedule::where('user_id', $user->id)
                                        ->orderBy('scheduled_at', 'asc')
                                        ->get();
        $specialCases = SpecialCase::where('user_id', $user->id)
                                    ->orderBy('case_date', 'asc')
                                    ->get();

        $dailyLogs = $privateSchedules->map(function ($log) {
            return [
                'type' => 'Catatan Harian Kegiatan',
                'date' => $log->scheduled_at,
                'patient_name' => '',
                'case_type' => '',
                'details' => $log->note,
                'action_taken' => '',
                'briefing_conducted' => $log->briefing ? 'Ya' : 'Tidak',
                'meeting_held' => $log->meeting ? 'Ya' : 'Tidak',
                'supervision_conducted' => $log->supervision ? 'Ya' : 'Tidak',
                'handover_done' => $log->handover ? 'Ya' : 'Tidak',
                'external_task' => $log->external_task,
            ];
        })->concat($specialCases->map(function ($case) {
            return [
                'type' => 'Kasus Perhatian Khusus',
                'date' => $case->case_date,
                'patient_name' => $case->patient_name,
                'case_type' => $case->case_type,
                'details' => $case->details,
                'action_taken' => $case->action_taken,
                'briefing_conducted' => '',
                'meeting_held' => '',
                'supervision_conducted' => '',
                'handover_done' => '',
                'external_task' => '',
            ];
        }))->sortBy('date')->values();

        $data = [
            'title' => 'Laporan Catatan Harian',
            'date' => Carbon::now()->format('d F Y H:i'),
            'dailyLogs' => $dailyLogs,
            'userInfo' => $this->getUserInfoForPdf(),
        ];
        $pdf = Pdf::loadView('reports.daily_logs_pdf', $data);
        $fileName = 'Catatan_Harian_' . Carbon::now()->format('Y-m-d_H_i') . '.pdf';
        return $pdf->download($fileName);
    }

    public function exportStaffSchedulesPdf()
    {
        $user = Auth::user();
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        $schedules = Schedule::with(['staff', 'shift'])
            ->whereHas('staff', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->where('department_id', $user->department_id)
                      ->where('hospital_id', $user->hospital_id);
            })
            ->whereBetween('start', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->orderBy('staff_id')
            ->orderBy('start')
            ->get();
        
        $allStaff = Staff::where('user_id', $user->id)
                         ->where('department_id', $user->department_id)
                         ->where('hospital_id', $user->hospital_id)
                         ->orderBy('name')
                         ->get();

        $groupedSchedulesForTable = [];
        foreach ($allStaff as $staff) {
            $groupedSchedulesForTable[$staff->id] = ['staff_name' => $staff->name];
            for ($i = 0; $i < 7; $i++) {
                $date = (clone $startOfWeek)->addDays($i);
                $dayKey = strtolower($date->isoFormat('dddd'));
                $groupedSchedulesForTable[$staff->id][$dayKey] = [];
            }
        }

        foreach ($schedules as $schedule) {
            $staffId = $schedule->staff_id;
            $shiftCode = $schedule->shift->code ?? 'N/A';
            if ($shiftCode === 'Sore') {
                $shiftCode = 'Siang';
            }
            $scheduleDate = Carbon::parse($schedule->start);
            $dayKey = strtolower($scheduleDate->isoFormat('dddd'));

            if (isset($groupedSchedulesForTable[$staffId][$dayKey])) {
                $groupedSchedulesForTable[$staffId][$dayKey][] = $shiftCode;
            }
        }

        $finalTableData = array_values($groupedSchedulesForTable);

        // Flatten the shifts for PDF display (e.g., "P, S", "M")
        foreach ($finalTableData as &$staffRow) {
            for ($i = 0; $i < 7; $i++) {
                $date = (clone $startOfWeek)->addDays($i);
                $dayKey = strtolower($date->isoFormat('dddd'));
                $shiftsOnDay = $staffRow[$dayKey];
                $staffRow[$dayKey] = empty($shiftsOnDay) ? '-' : implode(', ', $shiftsOnDay);
            }
        }
        unset($staffRow); // Unset the reference

        $data = [
            'title' => 'Laporan Jadwal Dinas',
            'date' => Carbon::now()->format('d F Y'),
            'schedules' => $finalTableData,
            'start_date' => $startOfWeek->format('d M Y'),
            'end_date' => $endOfWeek->format('d M Y'),
            'userInfo' => $this->getUserInfoForPdf(),
        ];
        $pdf = Pdf::loadView('reports.staff_schedules_pdf', $data);
        $fileName = 'Jadwal_Dinas_Minggu_Ini_' . Carbon::now()->format('Y-m-d') . '.pdf';
        return $pdf->download($fileName);
    }

    public function exportLogisticsPdf()
    {
        $user = Auth::user();
        $departmentId = $user->department_id;

        $logistics = Logistic::with('department')
            ->where('department_id', $departmentId)
            ->orderBy('category')
            ->orderBy('item_name')
            ->get();

        $data = [
            'title' => 'Laporan Manajemen Logistik',
            'date' => Carbon::now()->format('d F Y H:i'),
            'logistics' => $logistics,
            'userInfo' => $this->getUserInfoForPdf(),
        ];
        $pdf = Pdf::loadView('reports.logistics_pdf', $data);
        $fileName = 'Laporan_Logistik_' . Carbon::now()->format('Y-m-d_H_i') . '.pdf';
        return $pdf->download($fileName);
    }

    public function exportPpiReportsPdf()
    {
        $user = Auth::user();

        $allPpiActivities = collect();

        $insertions = CvcInsertion::where('user_id', $user->id)->orderBy('created_at', 'asc')->get()->map(function($item) {
            return [
                'form_type' => 'Bundle Insersi CVC',
                'activity_date' => $item->insertion_date,
                'patient_name' => $item->patient_name,
                'medical_record_number' => $item->medical_record_number,
                'details' => "Lokasi: {$item->insertion_location}, Operator: {$item->operator_name}, Kepatuhan: {$item->compliance_percentage}%",
                'submitted_at' => $item->created_at,
            ];
        });
        $allPpiActivities = $allPpiActivities->concat($insertions);

        $maintenances = CvcMaintenance::where('user_id', $user->id)->orderBy('created_at', 'asc')->get()->map(function($item) {
            return [
                'form_type' => 'Bundle Maintenance CVC',
                'activity_date' => $item->maintenance_date,
                'patient_name' => $item->patient_name,
                'medical_record_number' => $item->medical_record_number,
                'details' => "Lokasi: {$item->maintenance_location}, Perawat: {$item->nurse_name}, Hari Terpasang: {$item->days_inserted}, Kepatuhan: {$item->compliance_percentage}%",
                'submitted_at' => $item->created_at,
            ];
        });
        $allPpiActivities = $allPpiActivities->concat($maintenances);

        $infections = CvcInfection::where('user_id', $user->id)->orderBy('created_at', 'asc')->get()->map(function($item) {
            return [
                'form_type' => 'Laporan Infeksi CVC',
                'activity_date' => $item->infection_diagnosis_date,
                'patient_name' => $item->patient_name,
                'medical_record_number' => $item->medical_record_number,
                'details' => "Jenis Infeksi: {$item->infection_type}, Mikroorganisme: {$item->microorganism}, Gejala: {$item->clinical_symptoms}",
                'submitted_at' => $item->created_at,
            ];
        });
        $allPpiActivities = $allPpiActivities->concat($infections);

        $needlesticks = NeedlestickReport::where('user_id', $user->id)->orderBy('created_at', 'asc')->get()->map(function($item) {
            $immediateActionsStr = implode('; ', $item->immediate_actions ?? []);
            return [
                'form_type' => 'Laporan Tertusuk Jarum',
                'activity_date' => $item->incident_date,
                'patient_name' => $item->injured_person_name,
                'medical_record_number' => 'N/A',
                'details' => "Lokasi: {$item->location}, Jabatan: {$item->injured_person_position}, Deskripsi: {$item->incident_description}, Tindakan: {$immediateActionsStr}",
                'submitted_at' => $item->created_at,
            ];
        });
        $allPpiActivities = $allPpiActivities->concat($needlesticks);

        $ppiActivities = $allPpiActivities->sortBy('submitted_at')->values(); // Final sort for PDF

        $data = [
            'title' => 'Laporan Aktivitas PPI',
            'date' => Carbon::now()->format('d F Y H:i'),
            'ppiActivities' => $ppiActivities,
            'userInfo' => $this->getUserInfoForPdf(),
        ];
        $pdf = Pdf::loadView('reports.ppi_reports_pdf', $data);
        $fileName = 'Laporan_PPI_Aktivitas_' . Carbon::now()->format('Y-m-d_H_i') . '.pdf';
        return $pdf->download($fileName);
    }

    public function exportStaffPerformancePdf()
    {
        $user = Auth::user();
        $performanceData = PerformanceEvaluation::with('staff.position')
            ->whereHas('staff', function ($query) use ($user) {
                $query->where('department_id', $user->department_id)
                      ->where('hospital_id', $user->hospital_id)
                      ->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'asc') // Order chronologically
            ->get();

        // Map data for PDF, including the status Kinerja based on the score
        $mappedPerformanceData = $performanceData->map(function ($perf) {
            // Replicate getPerformanceStatus logic here for PDF consistency
            $getPerformanceStatus = function ($averageRating) {
                if ($averageRating >= 90) return 'Sangat Baik';
                if ($averageRating >= 70) return 'Baik';
                if ($averageRating >= 50) return 'Cukup';
                if ($averageRating >= 30) return 'Kurang';
                return 'Sangat Kurang';
            };

            return [
                'staff_name' => $perf->staff->name ?? 'N/A',
                'position_name' => $perf->staff->position->name ?? 'N/A',
                'discipline_score' => $perf->kedisiplinan,
                'communication_score' => $perf->komunikasi,
                'complaint_count' => $perf->komplain,
                'compliance_score' => $perf->kepatuhan,
                'target_achievement' => $perf->target_kerja,
                'overall_score' => $perf->overall_score,
                'status_kinerja' => $getPerformanceStatus($perf->overall_score),
                'notes' => $perf->notes,
                'evaluation_date' => $perf->created_at->format('Y-m-d H:i:s'),
            ];
        });

        $data = [
            'title' => 'Laporan Kinerja Staff',
            'date' => Carbon::now()->format('d F Y H:i'),
            'performanceData' => $mappedPerformanceData,
            'userInfo' => $this->getUserInfoForPdf(),
        ];
        $pdf = Pdf::loadView('reports.staff_performance_pdf', $data);
        $fileName = 'Laporan_Kinerja_Staff_' . Carbon::now()->format('Y-m-d_H_i') . '.pdf';
        return $pdf->download($fileName);
    }

    public function exportTnaRecordsPdf()
    {
        $user = Auth::user();
        $tnaData = TrainingNeed::with('staff.position')
            ->whereHas('staff', function($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('tanggal', 'asc') // Order by the 'tanggal' field
            ->get();

        $data = [
            'title' => 'Laporan Training Need Assessment (TNA)',
            'date' => Carbon::now()->format('d F Y H:i'),
            'tnaRecords' => $tnaData,
            'userInfo' => $this->getUserInfoForPdf(),
        ];
        $pdf = Pdf::loadView('reports.tna_records_pdf', $data);
        $fileName = 'Laporan_TNA_' . Carbon::now()->format('Y-m-d_H_i') . '.pdf';
        return $pdf->download($fileName);
    }

    public function exportQualityIndicatorsPdf() // Renamed from exportAllQualityFormsPdf for consistency
    {
        $qualityController = new QualityInspectionController();
        $allQualityEntries = $qualityController->getAllQualityFormDataForReport(); // Get unified data

        $data = [
            'title' => 'Laporan Lengkap Indikator Mutu',
            'date' => Carbon::now()->format('d F Y H:i'),
            'allQualityEntries' => $allQualityEntries, // Pass the consolidated data to PDF view
            'userInfo' => $this->getUserInfoForPdf(),
        ];
        $pdf = Pdf::loadView('reports.quality_indicators_pdf', $data); // Using the existing blade file
        $fileName = 'Laporan_Semua_Indikator_Mutu_' . Carbon::now()->format('Y-m-d_H_i') . '.pdf';
        return $pdf->download($fileName);
    }

    public function exportDailyLogsExcel()
    {
        $fileName = 'Catatan_Harian_' . Carbon::now()->format('Y-m-d_H_i') . '.xlsx';
        return Excel::download(new DailyLogsExport, $fileName);
    }

    public function exportStaffSchedulesExcel()
    {
        $fileName = 'Jadwal_Dinas_Minggu_Ini_' . Carbon::now()->format('Y-m-d') . '.xlsx';
        return Excel::download(new StaffSchedulesExport, $fileName);
    }

    public function exportLogisticsExcel()
    {
        $fileName = 'Laporan_Logistik_' . Carbon::now()->format('Y-m-d_H_i') . '.xlsx';
        return Excel::download(new LogisticsExport, $fileName);
    }

    public function exportPpiReportsExcel()
    {
        $fileName = 'Laporan_PPI_Aktivitas_' . Carbon::now()->format('Y-m-d_H_i') . '.xlsx';
        return Excel::download(new PpiReportsExport, $fileName);
    }

    public function exportStaffPerformanceExcel()
    {
        $fileName = 'Laporan_Kinerja_Staff_' . Carbon::now()->format('Y-m-d_H_i') . '.xlsx';
        return Excel::download(new StaffPerformanceExport, $fileName);
    }

    public function exportTnaRecordsExcel()
    {
        $fileName = 'Laporan_TNA_' . Carbon::now()->format('Y-m-d_H_i') . '.xlsx';
        return Excel::download(new TnaRecordsExport, $fileName);
    }

    public function exportQualityIndicatorsExcel() 
    {
        $fileName = 'Laporan_Semua_Indikator_Mutu_' . Carbon::now()->format('Y-m-d_H_i') . '.xlsx';
        $qualityController = new QualityInspectionController();
        return Excel::download(new QualityIndicatorsExport, $fileName);
    }
}