<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Notification;
// Import models that will be sources for notifications
use App\Models\Schedule;
use App\Models\User;
use App\Models\Logistic;
use App\Models\CvcInfection;
use App\Models\CvcInsertion;
use App\Models\CvcMaintenance;
use App\Models\Staff;
use App\Models\PerformanceEvaluation;
use App\Models\TrainingNeed;
use App\Models\PrivateSchedule;

// Assuming these are imported from somewhere or defined in this file's scope
// use App\Models\HandHygieneForm;
// use App\Models\ApdForm;
// use App\Models\IdentifikasiPasienForm;
// use App\Models\WtriForm;
// use App\Models\KritisLabForm;
// use App\Models\FornasForm;
// use App\Models\VisiteForm;
// use App\Models\JatuhForm;
// use App\Models\CpForm;
// use App\Models\KepuasanForm;
// use App\Models\KrkForm;
// use App\Models\PoeForm;
// use App\Models\ScForm;

class NotificationController extends Controller
{
    // Common Quality Inspection Forms (to check if data has been submitted for QI reminders)
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

    /**
     * Display a listing of notifications for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $query = Notification::where('user_id', $user->id)
                             ->orderBy('priority', 'asc')   // Higher priority first
                             ->orderBy('remind_at', 'asc')  // Then by nearest reminder date
                             ->orderBy('created_at', 'desc'); // Then by creation date

        // Filter by read/dismissed status if requested, otherwise show non-dismissed only
        if ($request->has('is_read')) {
            $query->where('is_read', $request->boolean('is_read'));
        }
        if ($request->has('is_dismissed')) {
            $query->where('is_dismissed', $request->boolean('is_dismissed'));
        } else {
            // Default: only show non-dismissed notifications
            $query->where('is_dismissed', false);
        }

        // Only show notifications that are active/past their remind_at time
        $query->where(function ($q) {
            $q->whereNull('remind_at') // If remind_at is null, it's always active
              ->orWhere('remind_at', '<=', Carbon::now());
        });

        $notifications = $query->paginate(10); // Paginate for history

        return response()->json($notifications);
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $notification->update(['is_read' => true]);
        Log::info("Notification {$notification->id} marked as read by user {$notification->user_id}.");
        return response()->json(['message' => 'Notification marked as read', 'notification' => $notification]);
    }

    /**
     * Mark a notification as dismissed.
     */
    public function dismiss(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $notification->update(['is_dismissed' => true]);
        Log::info("Notification {$notification->id} dismissed by user {$notification->user_id}.");
        return response()->json(['message' => 'Notification dismissed', 'notification' => $notification]);
    }

    /**
     * Delete a notification (hard delete).
     */
    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $notificationId = $notification->id; // Get ID before deletion for logging
        $notification->delete();
        Log::info("Notification {$notificationId} deleted by user {$notification->user_id}.");
        return response()->json(['message' => 'Notification deleted'], 204);
    }

    /**
     * Generates daily reminders for a specific user based on various criteria.
     * This method is intended to be called by an Artisan Command (e.g., via scheduler).
     */
    public function generateRemindersForUser(int $userId)
    {
        $user = User::find($userId);
        if (!$user || !$user->department_id || !$user->hospital_id) {
            Log::warning("Skipping notification generation for user ID {$userId}: User not found or missing department/hospital.");
            return;
        }

        $now = Carbon::now(); // Use $now for the precise time when the command runs
        $today = Carbon::today();
        $tomorrow = Carbon::tomorrow();
        $startOfWeek = $today->startOfWeek(Carbon::MONDAY);
        $endOfWeek = $today->endOfWeek(Carbon::SUNDAY); // This is end of the *current* week
        $endOfCurrentMonth = Carbon::now()->endOfMonth(); // End of the month reminder should relate to the current month

        Log::info("Generating notifications for user: {$user->name} (ID: {$user->id}).");

        // --- Helper function to avoid duplicate notification creation ---
        // Enhanced: now checks for notifications created TODAY for daily/recurring reminders.
        // For one-off events (like specific schedule, or past due dates), it checks exact data.
        $notificationExists = function($userId, $type, $targetDate, $dataIdentifiers = [], $checkCreatedToday = true) use ($now) {
            $query = Notification::where('user_id', $userId)
                                 ->where('type', $type)
                                 ->where('is_dismissed', false);

            if ($checkCreatedToday) {
                // For recurring daily/weekly/monthly reminders, check if it was created on the current day
                $query->whereDate('created_at', $now->toDateString());
            } else {
                // For specific event reminders (e.g., schedule, logistic item), check against targetDate
                if ($targetDate instanceof Carbon) {
                    $query->where('remind_at', $targetDate); // Check exact datetime
                } else if (is_string($targetDate)) {
                    $query->where('remind_at', $targetDate);
                }
            }
            
            foreach ($dataIdentifiers as $key => $value) {
                // Ensure array_key_exists before attempting to stringify/check, for old PHP version compatibility
                // This checks if the JSON contains the value for the key. Be careful with complex nested JSON.
                $query->whereRaw("JSON_EXTRACT(data, '$.{$key}') = ?", [json_encode($value)]);
            }
            return $query->exists();
        };


        // --- 1. Jadwal Dinas Reminder (for staff managed by this user, for tomorrow's schedule) ---
        // This reminder should still trigger once, for tomorrow's schedule.
        $managedStaffIds = $user->staff()->pluck('id');
        if ($managedStaffIds->isNotEmpty()) {
            $managedStaffSchedulesTomorrow = Schedule::whereIn('staff_id', $managedStaffIds)
                                                     ->whereDate('start', $tomorrow)
                                                     ->with('staff', 'shift')
                                                     ->get();
            foreach($managedStaffSchedulesTomorrow as $schedule) {
                // Remind at 8 PM today for tomorrow's schedule (or 12 hours before if that makes sense)
                // Let's set it to today at a specific time for consistency of generation
                $remindAt = $today->copy()->setTime(20, 0, 0); // 8 PM today
                // Ensure notification only created ONCE for this specific schedule + remind date.
                if (!$notificationExists($user->id, 'schedule_reminder', $remindAt, ['schedule_id' => $schedule->id], false)) {
                    Notification::create([
                        'user_id' => $user->id,
                        'type' => 'schedule_reminder',
                        'title' => 'Jadwal Dinas Besok: ' . (isset($schedule->staff->name) ? $schedule->staff->name : 'Unknown Staff'),
                        'message' => (isset($schedule->staff->name) ? $schedule->staff->name : 'Unknown Staff') . ' dijadwalkan dinas ' . (isset($schedule->shift->name) ? $schedule->shift->name : 'Unknown Shift') . ' (' . (isset($schedule->shift->start) ? $schedule->shift->start->format('H:i') : '') . ' - ' . (isset($schedule->shift->end) ? $schedule->shift->end->format('H:i') : '') . ').',
                        'data' => ['schedule_id' => $schedule->id, 'staff_id' => (isset($schedule->staff->id) ? $schedule->staff->id : null), 'staff_name' => (isset($schedule->staff->name) ? $schedule->staff->name : null), 'shift_time' => (isset($schedule->shift->start) ? $schedule->shift->start->format('H:i') : '')],
                        'tag' => 'Besok',
                        'tag_color' => 'blue',
                        'priority' => 1,
                        'link' => '/dinas',
                        'remind_at' => $remindAt, // Set to a specific time today
                    ]);
                    Log::info("Created schedule reminder for user {$user->id}, staff {$schedule->staff->name}.");
                }
            }
        }

        // --- 2. Manajemen Logistik Reminder (Low Stock) ---
        // This should trigger daily if stock is low.
        $lowStockItems = Logistic::where('department_id', $user->department_id)
                                 ->whereRaw('stock < 5') // Customize your low stock threshold
                                 ->get();
        foreach ($lowStockItems as $item) {
            // Check if reminder was already generated today for this item
            if (!$notificationExists($user->id, 'low_stock_alert', $today, ['item_id' => $item->id], true)) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'low_stock_alert',
                    'title' => 'Stok Menipis: ' . $item->item_name,
                    'message' => $item->item_name . ' tersisa ' . $item->stock . ' ' . (isset($item->unit_of_measure) ? $item->unit_of_measure : 'unit') . '. Segera lakukan pemesanan ulang.',
                    'data' => ['item_id' => $item->id, 'item_name' => $item->item_name, 'current_stock' => $item->stock],
                    'tag' => 'Urgent',
                    'tag_color' => 'yellow',
                    'priority' => 2,
                    'link' => '/manajemen-logistik',
                    'remind_at' => $now, // Remind immediately when command runs
                ]);
                Log::info("Created low stock alert for user {$user->id}, item {$item->item_name}.");
            }
        }

        // --- 3. PPI Reminder (Weekly Audit) ---
        // This should trigger every day for the week leading up to Friday (or on Friday itself).
        // Let's make it trigger every day but indicate it's a "weekly" task due by Friday.
        $auditDueDate = $startOfWeek->copy()->addDays(4); // Friday of the current week
        if ($now->isBetween($auditDueDate->copy()->subDays(2)->startOfDay(), $auditDueDate->endOfDay())) { // Remind from Wednesday to Friday
            if (!$notificationExists($user->id, 'ppi_audit_reminder', $auditDueDate, [], true)) { // Check created today
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'ppi_audit_reminder',
                    'icon' => 'fas fa-shield-virus',
                    'title' => 'Pengingat Audit PPI Mingguan',
                    'message' => 'Lengkapi audit pengendalian infeksi untuk minggu ini. Target selesai: Jumat ' . $auditDueDate->isoFormat('DD MMMM') . '.',
                    'data' => ['due_date' => $auditDueDate->toDateString()],
                    'tag' => 'Minggu Ini',
                    'tag_color' => 'teal',
                    'priority' => 3,
                    'link' => '/pengendalian-dan-pencegahan-infeksi',
                    'remind_at' => $now, // Remind immediately when command runs
                ]);
                Log::info("Created PPI audit reminder for user {$user->id}.");
            }
        }

        // --- 4. Kinerja Staff Reminder (Monthly Evaluation Deadline) ---
        // Remind X days before month end, and on month end.
        $remindDateKinerja = $endOfCurrentMonth->copy()->subDays(3); // 3 days before month end
        // Only trigger if today is the reminder date or the end of month
        if ($now->isSameDay($remindDateKinerja) || $now->isSameDay($endOfCurrentMonth)) {
            if (!$notificationExists($user->id, 'performance_evaluation_deadline', $endOfCurrentMonth, [], true)) { // Check created today, using month-end as unique identifier
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'performance_evaluation_deadline',
                    'icon' => 'fas fa-chart-line',
                    'title' => 'Evaluasi Kinerja Bulanan',
                    'message' => 'Saatnya melakukan evaluasi kinerja untuk tim di unit Anda. Deadline: ' . $endOfCurrentMonth->isoFormat('DD MMMM YYYY') . '.',
                    'data' => ['deadline_date' => $endOfCurrentMonth->toDateString()],
                    'tag' => 'Deadline: ' . $now->diffInDays($endOfCurrentMonth) . ' Hari',
                    'tag_color' => 'green',
                    'priority' => 2,
                    'link' => '/kinerja-staff',
                    'remind_at' => $now,
                ]);
                Log::info("Created performance evaluation reminder for user {$user->id}.");
            }
        }

        // --- 5. TNA Reminder (Monthly Review) ---
        // Remind from the 25th of the previous month until the 5th of the current month.
        $tnaStartReminder = $now->copy()->subMonth()->day(25)->startOfDay(); // 25th of previous month
        $tnaEndReminder = $now->copy()->day(5)->endOfDay(); // 5th of current month
        
        if ($now->isBetween($tnaStartReminder, $tnaEndReminder)) {
            $tnaReminderIdentifier = $now->format('Y-m'); // Identify this reminder by current month
            if (!$notificationExists($user->id, 'tna_reminder', $tnaReminderIdentifier, [], true)) { // Check created today
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'tna_reminder',
                    'icon' => 'fas fa-book',
                    'title' => 'Review Training Needs Analysis',
                    'message' => 'Saatnya meninjau dan memperbarui kebutuhan pelatihan staff Anda untuk periode ini.',
                    'data' => ['month' => $now->format('Y-m')],
                    'tag' => 'Bulanan',
                    'tag_color' => 'purple',
                    'priority' => 4,
                    'link' => '/tna',
                    'remind_at' => $now,
                ]);
                Log::info("Created TNA reminder for user {$user->id}.");
            }
        }

        // --- 6. Indikator Mutu Reminder (Monthly Update) ---
        // Remind 5 days before month end.
        $remindDateMutu = $endOfCurrentMonth->copy()->subDays(5);
        if ($now->isSameDay($remindDateMutu) || $now->isSameDay($endOfCurrentMonth)) {
            if (!$notificationExists($user->id, 'quality_indicator_update', $endOfCurrentMonth, [], true)) { // Check created today
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'quality_indicator_update',
                    'icon' => 'fas fa-bullseye',
                    'title' => 'Update Indikator Mutu',
                    'message' => 'Waktu untuk memperbarui data indikator mutu pelayanan bulan ini. Deadline: ' . $endOfCurrentMonth->isoFormat('DD MMMM YYYY') . '.',
                    'data' => ['deadline_date' => $endOfCurrentMonth->toDateString()],
                    'tag' => 'Bulanan',
                    'tag_color' => 'indigo',
                    'priority' => 3,
                    'link' => '/indikator-mutu',
                    'remind_at' => $now,
                ]);
                Log::info("Created QI update reminder for user {$user->id}.");
            }
        }

        // --- 7. Schedule Reminder (Personal Meetings - from PrivateSchedule, for today) ---
        // This should trigger once, 30 minutes before the meeting.
        $personalMeetingsToday = PrivateSchedule::where('user_id', $user->id)
                                                ->whereDate('scheduled_at', $today->toDateString())
                                                ->orderBy('scheduled_at', 'asc')
                                                ->get();
        foreach ($personalMeetingsToday as $meeting) {
            $remindAt = Carbon::parse($meeting->scheduled_at)->subMinutes(30); // Remind 30 mins before
            // Only create if reminder time has passed or is now, and not already created.
            if ($now->greaterThanOrEqualTo($remindAt) && !$notificationExists($user->id, 'meeting_reminder', $remindAt, ['meeting_id' => $meeting->id], false)) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'meeting_reminder',
                    'icon' => 'fas fa-calendar-alt',
                    'title' => 'Meeting Tim Medis',
                    'message' => (isset($meeting->note) ? $meeting->note : 'Rapat koordinasi.') . ' Hari Ini ' . Carbon::parse($meeting->scheduled_at)->format('H:i') . '.',
                    'data' => ['meeting_id' => $meeting->id, 'meeting_time' => $meeting->scheduled_at->toTimeString(), 'meeting_note' => (isset($meeting->note) ? $meeting->note : null)],
                    'tag' => 'Hari Ini ' . Carbon::parse($meeting->scheduled_at)->format('H:i'),
                    'tag_color' => 'pink',
                    'priority' => 1,
                    'link' => '/schedule',
                    'remind_at' => $remindAt,
                ]);
                Log::info("Created meeting reminder for user {$user->id}, meeting ID {$meeting->id}.");
            }
        }

        // --- 8. Laporan Reminder (Weekly Reports) ---
        // Remind every day from Friday until Sunday for the upcoming weekly report deadline.
        $weeklyReportDeadline = $endOfWeek->copy(); // Sunday is the end of the week, assume deadline is then
        if ($now->isBetween($startOfWeek->copy()->addDays(4)->startOfDay(), $endOfWeek->endOfDay())) { // From Friday to Sunday
             if (!$notificationExists($user->id, 'weekly_report_deadline', $weeklyReportDeadline, [], true)) { // Check created today
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'weekly_report_deadline',
                    'icon' => 'fas fa-file-alt',
                    'title' => 'Laporan Mingguan',
                    'message' => 'Segera selesaikan laporan aktivitas mingguan untuk diserahkan ke supervisor. Deadline: Minggu ' . $weeklyReportDeadline->isoFormat('DD MMMM YYYY') . '.',
                    'data' => ['deadline_date' => $weeklyReportDeadline->toDateString()],
                    'tag' => 'Minggu Ini',
                    'tag_color' => 'red',
                    'priority' => 2,
                    'link' => '/laporan',
                    'remind_at' => $now,
                ]);
                Log::info("Created weekly report reminder for user {$user->id}.");
            }
        }
    }
}