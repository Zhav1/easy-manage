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

        // Fetch recent private schedules for in-page display (e.g., last 10, no date filter here)
        $privateSchedules = $user->privateSchedules()
                                ->orderBy('scheduled_at', 'desc')
                                ->take(10) // Keep limit for in-page overview
                                ->get()
                                ->map(function ($log) {
                                    return [
                                        'id' => $log->id,
                                        'date' => $log->scheduled_at,
                                        'briefing_conducted' => $log->briefing ? 'Ya' : 'Tidak',
                                        'meeting_held' => $log->meeting ? 'Ya' : 'Tidak',
                                        'supervision_conducted' => $log->supervision ? 'Ya' : 'Tidak',
                                        'handover_done' => $log->handover ? 'Ya' : 'Tidak',
                                        'external_task' => $log->external_task,
                                        'notes' => $log->note,
                                    ];
                                });

        // Fetch recent special cases for in-page display (e.g., last 10, no date filter here)
        $specialCases = $user->specialCases()
                            ->orderBy('case_date', 'desc')
                            ->take(10) // Keep limit for in-page overview
                            ->get()
                            ->map(function ($case) {
                                return [
                                    'id' => $case->id,
                                    'date' => $case->case_date,
                                    'patient_name' => $case->patient_name,
                                    'case_type' => $case->case_type,
                                    'details' => $case->details,
                                    'action_taken' => $case->action_taken,
                                ];
                            });

        // Return as separate collections for easier rendering in separate tables on the dashboard
        return response()->json([
            'private_schedules' => $privateSchedules,
            'special_cases' => $specialCases,
        ]);
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

        public function getMonthlyStaffSchedules(Request $request)
        {
            $user = Auth::user();

            $fromMonth = $request->input('from_month');
            $fromYear = $request->input('from_year');
            $toMonth = $request->input('to_month');
            $toYear = $request->input('to_year');

            // Defensive check for missing inputs, though client-side validation should prevent this for normal flow
            if (!$fromMonth || !$fromYear || !$toMonth || !$toYear) {
                // Return an empty structure if inputs are missing, to prevent further errors
                return [
                    'all_monthly_schedules_data' => [],
                    'report_period_title' => 'Periode Tidak Valid',
                    'overall_start_date' => null,
                    'overall_end_date' => null,
                ];
            }

            try {
                $currentMonthIterator = Carbon::createFromDate($fromYear, $fromMonth, 1)->startOfMonth();
                $endOfRange = Carbon::createFromDate($toYear, $toMonth, 1)->endOfMonth();
            } catch (\Exception $e) {
                // Return an empty structure on invalid date format
                return [
                    'all_monthly_schedules_data' => [],
                    'report_period_title' => 'Format Bulan/Tahun Tidak Valid',
                    'overall_start_date' => null,
                    'overall_end_date' => null,
                ];
            }

            // Fetch all staff once for the user's department and hospital
            $allStaff = Staff::where('user_id', $user->id)
                            ->where('department_id', $user->department_id)
                            ->where('hospital_id', $user->hospital_id)
                            ->orderBy('name')
                            ->get();

            $allMonthlySchedulesData = []; // This will hold the processed data for each month

            // Loop through each month in the selected range
            while ($currentMonthIterator->lte($endOfRange)) {
                $startOfMonth = $currentMonthIterator->copy()->startOfMonth();
                $endOfMonth = $currentMonthIterator->copy()->endOfMonth();

                // Fetch all schedules relevant to this specific month for the user's staff
                $schedules = Schedule::with(['staff', 'shift'])
                    ->whereHas('staff', function ($query) use ($user) {
                        $query->where('user_id', $user->id)
                            ->where('department_id', $user->department_id)
                            ->where('hospital_id', $user->hospital_id);
                    })
                    ->whereBetween('start', [$startOfMonth->toDateString(), $endOfMonth->endOfDay()->toDateTimeString()])
                    ->orderBy('staff_id')
                    ->orderBy('start')
                    ->get();

                $monthlyReportRawData = []; // Data structure for the current month being processed

                // Group schedules by staff_id for efficient lookup
                $staffSchedulesGrouped = $schedules->groupBy('staff_id');

                foreach ($allStaff as $staff) {
                    $staffRow = [
                        'staff_name' => $staff->name,
                        'schedules_by_date' => []
                    ];

                    $dateInMonthIterator = $startOfMonth->copy();
                    while ($dateInMonthIterator->lte($endOfMonth)) {
                        $dateKey = $dateInMonthIterator->format('Y-m-d');
                        $staffRow['schedules_by_date'][$dateKey] = [
                            'Pagi' => false,
                            'Siang' => false,
                            'Malam' => false,
                            'is_sunday' => ($dateInMonthIterator->dayOfWeek === Carbon::SUNDAY)
                        ];
                        $dateInMonthIterator->addDay();
                    }

                    if (isset($staffSchedulesGrouped[$staff->id])) {
                        foreach ($staffSchedulesGrouped[$staff->id] as $schedule) {
                            $scheduleDate = Carbon::parse($schedule->start);
                            $dateKey = $scheduleDate->format('Y-m-d');
                            $shiftCode = $schedule->shift->code ?? '';

                            if ($shiftCode === 'Sore') {
                                $shiftCode = 'Siang';
                            }
                            if (array_key_exists($shiftCode, $staffRow['schedules_by_date'][$dateKey])) {
                                $staffRow['schedules_by_date'][$dateKey][$shiftCode] = true;
                            }
                        }
                    }
                    $monthlyReportRawData[] = $staffRow;
                }

                // Add the processed data for the current month to the overall collection
                $allMonthlySchedulesData[] = [
                    'monthly_schedules' => $monthlyReportRawData,
                    'month_name' => $startOfMonth->isoFormat('MMMM YYYY'),
                    'start_date' => $startOfMonth->toDateString(),
                    'end_date' => $endOfMonth->toDateString(),
                    'days_in_month' => $endOfMonth->day,
                ];

                // Move to the next month for the loop
                $currentMonthIterator->addMonth();
            }

            // THIS IS THE CRITICAL RETURN. It must return a plain array with these specific keys.
            return [
                'all_monthly_schedules_data' => $allMonthlySchedulesData,
                'report_period_title' => Carbon::createFromDate($fromYear, $fromMonth, 1)->isoFormat('MMMM YYYY') . ' - ' . Carbon::createFromDate($toYear, $toMonth, 1)->isoFormat('MMMM YYYY'),
                'overall_start_date' => Carbon::createFromDate($fromYear, $fromMonth, 1)->toDateString(),
                'overall_end_date' => Carbon::createFromDate($toYear, $toMonth, 1)->toDateString(),
            ];
        }

        public function getLogisticsSummary()
        {
            $user = Auth::user();

            if (!$user->department_id) {
                return response()->json([
                    'total_stock_available' => 0,
                    'limited_stock' => 0,
                    'low_stock' => 0,
                    'categorized_items' => [], // These are the top N items per category
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

            // Define categories for overview and individual lists
            $categories = ['Alat Kesehatan', 'Linen', 'Barang Habis Pakai', 'Obat'];
            $categorizedItems = []; // For the in-page categorized lists (limited to 5)
            $categoriesOverview = []; // For the summary cards

            foreach ($categories as $category) {
                $items = Logistic::where('department_id', $departmentId)
                    ->where('category', $category)
                    ->orderBy('item_name')
                    ->limit(5) // Limit for in-page display
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
                        'used' => $item->used ?? 0, // Include the 'used' column for internal data
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

                'total_insertions_last_30_days' => $totalInsertionsLast30Days,
                'total_maintenances_last_30_days' => $totalMaintenancesLast30Days,
                
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

        private function calculateFormCompliance($formType, $data)
        {
            if (!$data || !isset($data['entries']) || empty($data['entries'])) {
                return 0;
            }
            $entries = collect($data['entries']);
            $numerator = 0;
            $denominator = $entries->count();
            if ($denominator == 0) return 0;

            switch ($formType) {
                case 'hand-hygiene':
                    $numerator = $entries->sum('total_handwash') + $entries->sum('total_handrub');
                    $denominator = $entries->sum('total_kesempatan');
                    if ($denominator > 0) $numerator = min($numerator, $denominator);
                    break;
                case 'apd':
                    $numerator = $entries->where('kepatuhan', 'Patuh')->count();
                    break;
                case 'jatuh':
                    $numerator = $entries->where('ketiga_upaya_ya', true)->count();
                    break;
                case 'identifikasi':
                    $numerator = $entries->where('dilakukan', true)->count();
                    break;
                case 'wtri':
                    $numerator = $entries->filter(fn($e) => (intval($e['respon_time_ca'] ?? 999)) <= 60)->count();
                    break;
                case 'kritis-lab':
                    $numerator = $entries->where('pelaporan_status', '≤ 30 Menit')->count();
                    break;
                case 'fornas':
                    $numerator = $entries->where('formularium_nasional', true)->count();
                    break;
                case 'visite':
                    $numerator = $entries->filter(fn($e) => (intval(substr($e['jam'] ?? '99', 0, 2))) < 14)->count();
                    break;
                case 'cp':
                    $totals = $data['totals'] ?? [];
                    $numerator = ($totals['asesmen_p'] ?? 0) + ($totals['fisik_p'] ?? 0) + ($totals['penunjang_p'] ?? 0) + ($totals['obat_p'] ?? 0);
                    $denominator = $numerator + ($totals['asesmen_n'] ?? 0) + ($totals['asesmen_c'] ?? 0) + ($totals['fisik_n'] ?? 0) + ($totals['fisik_c'] ?? 0) + ($totals['penunjang_n'] ?? 0) + ($totals['penunjang_c'] ?? 0) + ($totals['obat_n'] ?? 0) + ($totals['obat_c'] ?? 0);
                    break;
                case 'kepuasan':
                    $numerator = $entries->filter(fn($e) => in_array($e['nilai_kepuasan'] ?? '', ['4 (Puas)', '5 (Sangat Puas)']))->count();
                    break;
                case 'krk':
                    $numerator = $entries->where('penyelesaian_ya', true)->count();
                    break;
                case 'poe':
                    $numerator = $entries->where('penundaan_lt_1hr', true)->count();
                    break;
                case 'sc':
                    $numerator = $entries->filter(fn($e) => (intval($e['waktu_tanggap'] ?? 999)) <= 30)->count();
                    break;
            }

            return ($denominator > 0) ? round(($numerator / $denominator) * 100) : 0;
        }

        private function getQualityIndicatorsReportData()
        {
            $user = Auth::user();
            $reportData = [];

            foreach ($this->formModels as $formType => $modelClass) {
                $records = $modelClass::where('user_id', $user->id)->get();
                $allEntries = $records->pluck('data.entries')->flatten(1)->filter()->values();
                
                $latestRecordData = $records->sortByDesc('created_at')->first()->data ?? ['entries' => []];
                $latestRecordData['entries'] = $allEntries->toArray();
                
                $reportData[$formType] = [
                    'name' => ucwords(str_replace('-', ' ', $formType)),
                    'data' => $latestRecordData,
                    'compliance' => $this->calculateFormCompliance($formType, $latestRecordData),
                ];
            }
            return $reportData;
        }

        // --- New PDF Export Methods ---

        public function exportDailyLogsPdf(Request $request) // Add Request $request parameter
    {
        $user = Auth::user();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // 1. Fetch Private Schedules (filtered by date range)
        $privateSchedulesQuery = PrivateSchedule::where('user_id', $user->id);
        if ($startDate && $endDate) {
            $privateSchedulesQuery->whereBetween('scheduled_at', [$startDate, Carbon::parse($endDate)->endOfDay()]);
        } elseif ($startDate) {
            $privateSchedulesQuery->where('scheduled_at', '>=', $startDate);
        } elseif ($endDate) {
            $privateSchedulesQuery->where('scheduled_at', '<=', Carbon::parse($endDate)->endOfDay());
        }
        $privateSchedules = $privateSchedulesQuery->orderBy('scheduled_at', 'asc')->get();

        // 2. Fetch Special Cases (ALL data, NOT filtered by date)
        $specialCases = SpecialCase::where('user_id', $user->id)
                                ->orderBy('case_date', 'asc') // Still order them chronologically
                                ->get();

        $data = [
            'title' => 'Laporan Catatan Harian',
            'date' => Carbon::now()->format('d F Y H:i'),
            'privateSchedules' => $privateSchedules, // Pass separately to PDF view
            'specialCases' => $specialCases,         // Pass separately to PDF view
            'userInfo' => $this->getUserInfoForPdf(),
            'report_start_date' => $startDate ? Carbon::parse($startDate)->format('d F Y') : 'Mulai',
            'report_end_date' => $endDate ? Carbon::parse($endDate)->format('d F Y') : 'Sekarang',
        ];
        $pdf = Pdf::loadView('reports.daily_logs_pdf', $data);
        $fileName = 'Catatan_Harian_' . Carbon::now()->format('Y-m-d_H_i') . '.pdf';
        return $pdf->download($fileName);
    }

        // In app/Http/Controllers/ReportController.php

    public function exportStaffSchedulesPdf(Request $request)
    {
        $user = Auth::user();

        $processedScheduleData = $this->getMonthlyStaffSchedules($request); // No need for (array) cast if getMonthlyStaffSchedules returns array directly.

        $data = [
            'title' => 'Laporan Jadwal Dinas',
            'date' => Carbon::now()->format('d F Y H:i'),
            // CORRECTED: Access keys directly from $processedScheduleData
            'all_monthly_schedules_data' => $processedScheduleData['all_monthly_schedules_data'],
            'report_period_title' => $processedScheduleData['report_period_title'],
            'userInfo' => $this->getUserInfoForPdf(),
            'overall_start_date' => $processedScheduleData['overall_start_date'],
            'overall_end_date' => $processedScheduleData['overall_end_date'],
        ];

        $pdf = Pdf::loadView('reports.staff_schedules_pdf', $data)->setPaper('a3', 'landscape');
        $fileName = 'Jadwal_Dinas_' . str_replace(' ', '_', $processedScheduleData['report_period_title']) . '.pdf';
        return $pdf->download($fileName);
    }

    public function exportLogisticsPdf(Request $request)
    {
        $user = Auth::user();
        $departmentId = $user->department_id;

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Table 1: Alat Kesehatan (All time)
        $alatKesehatanItems = Logistic::with('department')
            ->where('department_id', $departmentId)
            ->where('category', 'Alat Kesehatan')
            ->orderBy('item_name')
            ->get();

        // Table 2: Barang Habis Pakai (All time)
        $barangHabisPakaiItems = Logistic::with('department')
            ->where('department_id', $departmentId)
            ->where('category', 'Barang Habis Pakai')
            ->orderBy('item_name')
            ->get();

        // Table 3: Consumption / Used Items (Filtered by date, includes 'used' column)
        $consumptionItemsQuery = Logistic::with('department')
            ->where('department_id', $departmentId)
            ->where('used', '>', 0); // Only items that have been 'used'

        if ($startDate && $endDate) {
            $consumptionItemsQuery->whereBetween('updated_at', [$startDate, Carbon::parse($endDate)->endOfDay()]);
        } elseif ($startDate) {
            $consumptionItemsQuery->where('updated_at', '>=', $startDate);
        } elseif ($endDate) {
            $consumptionItemsQuery->where('updated_at', '<=', Carbon::parse($endDate)->endOfDay());
        }
        $consumptionItems = $consumptionItemsQuery->orderBy('item_name')->get();

        $data = [
            'title' => 'Laporan Manajemen Logistik',
            'date' => Carbon::now()->format('d F Y H:i'),
            // Pass the correct item collections to the PDF view
            'alatKesehatanItems' => $alatKesehatanItems,
            'barangHabisPakaiItems' => $barangHabisPakaiItems,
            'consumptionItems' => $consumptionItems,
            'userInfo' => $this->getUserInfoForPdf(),
            'report_start_date' => $startDate ? Carbon::parse($startDate)->format('d F Y') : 'Mulai',
            'report_end_date' => $endDate ? Carbon::parse($endDate)->format('d F Y') : 'Sekarang',
        ];
        $pdf = Pdf::loadView('reports.logistics_pdf', $data);
        $fileName = 'Laporan_Logistik_' . Carbon::now()->format('Y-m-d_H_i') . '.pdf';
        return $pdf->download($fileName);
    }

        public function exportPpiReportsPdfWithCharts(Request $request)
    {
        $user = Auth::user();
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // 1. Fetch and filter all data collections
        $insertionsQuery = CvcInsertion::where('user_id', $user->id);
        if ($startDate) $insertionsQuery->where('insertion_date', '>=', $startDate);
        if ($endDate) $insertionsQuery->where('insertion_date', '<=', $endDate);
        $insertions = $insertionsQuery->orderBy('insertion_date', 'asc')->get();

        $maintenancesQuery = CvcMaintenance::where('user_id', $user->id);
        if ($startDate) $maintenancesQuery->where('maintenance_date', '>=', $startDate);
        if ($endDate) $maintenancesQuery->where('maintenance_date', '<=', $endDate);
        $maintenances = $maintenancesQuery->orderBy('maintenance_date', 'asc')->get();

        $infectionsQuery = CvcInfection::where('user_id', $user->id);
        if ($startDate) $infectionsQuery->where('infection_diagnosis_date', '>=', $startDate);
        if ($endDate) $infectionsQuery->where('infection_diagnosis_date', '<=', $endDate);
        $infections = $infectionsQuery->orderBy('infection_diagnosis_date', 'asc')->get();

        $needlesticksQuery = NeedlestickReport::where('user_id', $user->id);
        if ($startDate) $needlesticksQuery->where('incident_date', '>=', $startDate);
        if ($endDate) $needlesticksQuery->where('incident_date', '<=', $endDate);
        $needlesticks = $needlesticksQuery->orderBy('incident_date', 'asc')->get();

        // 2. Get the chart images from the request
        $chartImages = $request->input('chart_images', []);

        $data = [
            'title' => 'Laporan Lengkap Pengendalian & Pencegahan Infeksi (PPI)',
            'date' => Carbon::now()->format('d F Y H:i'),
            'userInfo' => $this->getUserInfoForPdf(),
            'report_start_date' => $startDate ? Carbon::parse($startDate)->format('d F Y') : 'Semua Waktu',
            'report_end_date' => $endDate ? Carbon::parse($endDate)->format('d F Y') : '',
            'chartImages' => $chartImages,
            'insertions' => $insertions,
            'maintenances' => $maintenances,
            'infections' => $infections,
            'needlesticks' => $needlesticks,
        ];
        
        $pdf = Pdf::loadView('reports.ppi_reports_pdf', $data)
                ->setPaper('a3', 'landscape');

        return $pdf->stream('Laporan_PPI_Lengkap.pdf');
    }

        public function exportStaffPerformancePdf(Request $request)
        {
            $user = Auth::user();
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            // Build the query with date filters
            $performanceQuery = PerformanceEvaluation::with('staff.position')
                ->whereHas('staff', function ($query) use ($user) {
                    $query->where('user_id', $user->id);
                });

            if ($startDate) $performanceQuery->whereDate('created_at', '>=', $startDate);
            if ($endDate) $performanceQuery->whereDate('created_at', '<=', $endDate);

            $performanceData = $performanceQuery->orderBy('created_at', 'asc')->get();

            // Map the data for the view
            $mappedPerformanceData = $performanceData->map(function ($perf) {
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
                    'overall_score' => $perf->overall_score, // Now correctly calculated by the model
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
                'report_start_date' => $startDate ? Carbon::parse($startDate)->format('d F Y') : 'Semua Waktu',
                'report_end_date' => $endDate ? Carbon::parse($endDate)->format('d F Y') : '',
            ];

            $pdf = Pdf::loadView('reports.staff_performance_pdf', $data)->setPaper('a4', 'landscape');
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

        public function exportQualityIndicatorsPdf(Request $request)
        {
            $data = [
                'title'         => 'Laporan Lengkap Indikator Mutu',
                'date'          => Carbon::now()->format('d F Y H:i'),
                'userInfo'      => $this->getUserInfoForPdf(),
                'reportData'    => $this->getQualityIndicatorsReportData(),
                'chartImages'   => $request->input('chart_images', []),
            ];

            $pdf = Pdf::loadView('reports.quality_indicators_pdf', $data)->setPaper('a4', 'portrait');
            return $pdf->stream('Laporan_Indikator_Mutu.pdf');
        }


        // Replace the existing exportQualityIndicatorsExcel method
        public function exportQualityIndicatorsExcel()
        {
            $fileName = 'Laporan_Indikator_Mutu_' . Carbon::now()->format('Y-m-d') . '.xlsx';
            return Excel::download(new QualityIndicatorsExport(), $fileName);
        }

        public function exportDailyLogsExcel(Request $request) // Add Request $request parameter
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $fileName = 'Catatan_Harian_' . Carbon::now()->format('Y-m-d_H_i') . '.xlsx';
        // Pass the start and end dates to the Excel export class
        return Excel::download(new DailyLogsExport($startDate, $endDate), $fileName);
    }

        public function exportStaffSchedulesExcel(Request $request)
        {
            
        $processedScheduleData = $this->getMonthlyStaffSchedules($request); // Get the processed array directly
            $fileName = 'Jadwal_Dinas_' . str_replace(' ', '_', $processedScheduleData['report_period_title']) . '.xlsx';
            return Excel::download(new StaffSchedulesExport(
                $processedScheduleData['all_monthly_schedules_data'], // Pass the array of monthly data
                $processedScheduleData['report_period_title']        // Pass the overall period title
            ), $fileName);
        }
    public function exportLogisticsExcel(Request $request) // Add Request $request parameter
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $fileName = 'Laporan_Logistik_' . Carbon::now()->format('Y-m-d_H_i') . '.xlsx';
        // Pass the start and end dates to the Excel export class
        return Excel::download(new LogisticsExport($startDate, $endDate), $fileName);
    }

    public function exportPpiReportsExcel(Request $request)
        {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $fileName = 'Laporan_PPI_Detail_' . Carbon::now()->format('Y-m-d') . '.xlsx';
            
            return Excel::download(new PpiReportsExport($startDate, $endDate), $fileName);
        }


        public function exportStaffPerformanceExcel(Request $request)
        {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $fileName = 'Laporan_Kinerja_Staff_' . Carbon::now()->format('Y-m-d_H_i') . '.xlsx';
            
            // Pass the dates to the Export class
            return Excel::download(new StaffPerformanceExport($startDate, $endDate), $fileName);
        }

        public function exportTnaRecordsExcel()
        {
            $fileName = 'Laporan_TNA_' . Carbon::now()->format('Y-m-d_H_i') . '.xlsx';
            return Excel::download(new TnaRecordsExport, $fileName);
        }
    }