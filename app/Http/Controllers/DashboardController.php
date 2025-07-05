<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Staff;
use App\Models\Schedule;
use App\Models\Logistic;
// Import all specific Quality Inspection Models to calculate submitted form count
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
use App\Models\PerformanceEvaluation;
use App\Models\Shift; // Ensure Shift model is imported

class DashboardController extends Controller
{
    // List of Quality Inspection Form Models (reused from ReportController)
    private $qualityFormModels = [
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

    public function index()
    {
        $user = Auth::user();

        // Initialize default values
        // These will be passed to the view directly.
        $userName = $user->name;
        $profilePhotoUrl = $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : asset('images/p.png');
        $greetingTime = $this->getGreetingTime();
        $currentDateFormatted = Carbon::now()->translatedFormat('l, d F Y');

        // Quick Stats Defaults
        $todaySchedulesCount = '0';
        $lowStockCount = '0';
        $ppiSubmittedTodayCount = '0';
        $tasksCompletedCount = '0';

        // Shortcut Cards Defaults
        $jadwalNextShiftTime = 'Tidak ada jadwal';
        $jadwalActiveNurses = '0';
        $logistikTotalStock = '0';
        $logistikThinningItems = '0';
        $ppiComplianceRate = 'N/A';
        $ppiLastAuditDaysAgo = 'N/A';

        // Ensure user has department and hospital for relevant data filtering
        if ($user->department_id && $user->hospital_id) {
            $departmentId = $user->department_id;
            $hospitalId = $user->hospital_id;

            // --- Quick Stats Data Calculation ---

            // Jadwal Hari Ini (Total schedules for staff managed by this user for today)
            // Assumes a `staff` relation on the User model for managed staff.
            $managedStaffIds = $user->staff()->pluck('id');
            if ($managedStaffIds->isNotEmpty()) {
                $todaySchedulesCount = Schedule::whereIn('staff_id', $managedStaffIds)
                                               ->whereDate('start', Carbon::today())
                                               ->count();
            }

            // Stok Menipis (Total items below threshold)
            $lowStockCount = Logistic::where('department_id', $departmentId)
                                      ->where('stock', '<', 5) // Customize your threshold
                                      ->count();

            // PPI Forms Submitted Today (Count of any QI form submitted by the user today)
            $todayDate = Carbon::today()->toDateString();
            $ppiFormsSubmittedToday = 0;
            foreach ($this->qualityFormModels as $formType => $modelClass) {
                $ppiFormsSubmittedToday += $modelClass::where('user_id', $user->id)
                                                        ->whereDate('created_at', $todayDate) // Assuming created_at marks submission date
                                                        ->count();
            }
            $ppiSubmittedTodayCount = $ppiFormsSubmittedToday;


            // Task Selesai (Example: Performance Evaluations completed this month)
            $tasksCompletedCount = PerformanceEvaluation::whereHas('staff', function($query) use ($user, $departmentId, $hospitalId) {
                                                                       $query->where('department_id', $departmentId)
                                                                             ->where('hospital_id', $hospitalId);
                                                                             // ->where('user_id', $user->id); // If staff are linked to the managing user
                                                                   })
                                                                   ->whereYear('created_at', Carbon::now()->year)
                                                                   ->whereMonth('created_at', Carbon::now()->month)
                                                                   ->count();


            // --- Shortcut Cards Data Calculation ---

            // Jadwal Dinas: Next shift and active nurses
            $nextShiftSchedule = null;
            if ($managedStaffIds->isNotEmpty()) {
                $nextShiftSchedule = Schedule::whereIn('staff_id', $managedStaffIds)
                                             ->where('start', '>=', Carbon::now())
                                             ->with('shift')
                                             ->orderBy('start', 'asc')
                                             ->first();
            }
            
            if ($nextShiftSchedule && $nextShiftSchedule->shift) {
                $jadwalNextShiftTime = 'Shift ' . ($nextShiftSchedule->shift->code ?? 'N/A') . ' (' . ($nextShiftSchedule->shift->start->format('H:i') ?? '') . ' - ' . ($nextShiftSchedule->shift->end->format('H:i') ?? '') . ')';
            } else {
                 $jadwalNextShiftTime = 'Tidak ada jadwal';
            }
            
            $jadwalActiveNurses = Staff::where('department_id', $departmentId)
                                          ->where('hospital_id', $hospitalId)
                                          ->where('status', 'Aktif')
                                          ->count();


            // Manajemen Logistik: Total stock and thinning items
            $logistikTotalStock = Logistic::where('department_id', $departmentId)->sum('stock');
            $logistikThinningItems = Logistic::where('department_id', $departmentId)
                                                ->where('stock', '<', 10) // Matches 'Terbatas' and 'Menipis' range in LogisticController
                                                ->count();

            // PPI: Compliance percentage and last audit (simplified for dashboard overview)
            $totalPpiFormsSubmitted = 0; // Total count of QI forms submitted by user
            foreach ($this->qualityFormModels as $formType => $modelClass) {
                $totalPpiFormsSubmitted += $modelClass::where('user_id', $user->id)->count();
            }
            $ppiComplianceRate = ($totalPpiFormsSubmitted > 0) ? 'Active' : 'N/A'; // Simple indicator
            
            $latestPpiSubmissionDate = null; // Find the most recent submission date across all QI forms
            foreach ($this->qualityFormModels as $formType => $modelClass) {
                $latestForType = $modelClass::where('user_id', $user->id)->latest('created_at')->first();
                if ($latestForType && (!$latestPpiSubmissionDate || $latestForType->created_at->greaterThan($latestPpiSubmissionDate))) {
                    $latestPpiSubmissionDate = $latestForType->created_at;
                }
            }

            if ($latestPpiSubmissionDate) {
                $ppiLastAuditDaysAgo = $latestPpiSubmissionDate->diffForHumans(Carbon::now());
            } else {
                $ppiLastAuditDaysAgo = 'Belum ada data';
            }
        }

        // Pass all calculated variables to the view
        return view('dashboard', compact(
            'userName', 'profilePhotoUrl', 'greetingTime', 'currentDateFormatted',
            'todaySchedulesCount', 'lowStockCount', 'ppiSubmittedTodayCount', 'tasksCompletedCount',
            'jadwalNextShiftTime', 'jadwalActiveNurses',
            'logistikTotalStock', 'logistikThinningItems',
            'ppiComplianceRate', 'ppiLastAuditDaysAgo'
        ));
    }

    /**
     * Determine the current greeting time (Pagi, Siang, Sore, Malam).
     */
    private function getGreetingTime(): string
    {
        $hour = Carbon::now()->format('H'); // Get current hour (0-23)
        if ($hour < 11) return 'Pagi';
        if ($hour < 15) return 'Siang';
        if ($hour < 19) return 'Sore';
        return 'Malam';
    }
}