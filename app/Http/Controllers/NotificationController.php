<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Notification; // Import the new Notification model
// Import models that will be sources for notifications (if generating here)
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
        // Ensure user exists and has a department/hospital assigned for filtering related data
        if (!$user || !$user->department_id || !$user->hospital_id) {
            Log::warning("Skipping notification generation for user ID {$userId}: User not found or missing department/hospital.");
            return;
        }

        $today = Carbon::now();
        $tomorrow = Carbon::tomorrow();
        $endOfMonth = Carbon::now()->endOfMonth();

        Log::info("Generating notifications for user: {$user->name} (ID: {$user->id}).");

        // --- Helper function to avoid duplicate notification creation ---
        // Checks if a non-dismissed notification of this type/data already exists for a given remind_at date.
        $notificationExists = function($userId, $type, $remindAt, $dataIdentifiers = []) {
            $query = Notification::where('user_id', $userId)
                                 ->where('type', $type)
                                 ->where('is_dismissed', false); // Don't create if already active/non-dismissed
                                 
            // Check for exact remind_at date for daily/specific reminders
            if ($remindAt instanceof Carbon) {
                $query->whereDate('remind_at', $remindAt->toDateString());
            } else if (is_string($remindAt)) { // If remind_at is a string like 'YYYY-MM-DD HH:MM:SS'
                $query->where('remind_at', $remindAt);
            }

            // Check specific data identifiers (e.g., schedule_id, item_id, meeting_id)
            foreach ($dataIdentifiers as $key => $value) {
                $query->whereJsonContains("data->{$key}", $value);
            }
            return $query->exists();
        };

        // --- 1. Jadwal Dinas Reminder (for staff managed by this user, for tomorrow's schedule) ---
        // Assumes User model has a `staff` hasMany relation (for managers of staff)
        $managedStaffIds = $user->staff()->pluck('id');
        if ($managedStaffIds->isNotEmpty()) {
            $managedStaffSchedulesTomorrow = Schedule::whereIn('staff_id', $managedStaffIds)
                                                    ->whereDate('start', $tomorrow)
                                                    ->with('staff', 'shift')
                                                    ->get();
            foreach($managedStaffSchedulesTomorrow as $schedule) {
                $remindAt = $tomorrow->subHours(12); // Remind 12 hours before schedule start
                if (!$notificationExists($user->id, 'schedule_reminder', $remindAt, ['schedule_id' => $schedule->id])) {
                    Notification::create([
                        'user_id' => $user->id,
                        'type' => 'schedule_reminder',
                        'title' => 'Jadwal Dinas Besok: ' . ($schedule->staff->name ?? 'Unknown Staff'),
                        'message' => ($schedule->staff->name ?? 'Unknown Staff') . ' dijadwalkan dinas ' . ($schedule->shift->name ?? 'Unknown Shift') . ' (' . ($schedule->shift->start->format('H:i') ?? '') . ' - ' . ($schedule->shift->end->format('H:i') ?? '') . ').',
                        'data' => ['schedule_id' => $schedule->id, 'staff_id' => $schedule->staff->id, 'staff_name' => $schedule->staff->name, 'shift_time' => ($schedule->shift->start->format('H:i') ?? '')],
                        'tag' => 'Besok',
                        'tag_color' => 'blue',
                        'priority' => 1, // Highest priority
                        'link' => '/dinas',
                        'remind_at' => $remindAt,
                    ]);
                    Log::info("Created schedule reminder for user {$user->id}, staff {$schedule->staff->name}.");
                }
            }
        }

        // --- 2. Manajemen Logistik Reminder (Low Stock) ---
        $lowStockItems = Logistic::where('department_id', $user->department_id)
                                 ->whereRaw('stock < 5') // Customize your low stock threshold
                                 ->get();
        foreach ($lowStockItems as $item) {
            // Only create if not already reminded today for this item
            if (!$notificationExists($user->id, 'low_stock_alert', $today, ['item_id' => $item->id])) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'low_stock_alert',
                    'title' => 'Stok Menipis: ' . $item->item_name,
                    'message' => $item->item_name . ' tersisa ' . $item->stock . ' ' . ($item->unit_of_measure ?? 'unit') . '. Segera lakukan pemesanan ulang.',
                    'data' => ['item_id' => $item->id, 'item_name' => $item->item_name, 'current_stock' => $item->stock],
                    'tag' => 'Urgent',
                    'tag_color' => 'yellow',
                    'priority' => 2,
                    'link' => '/manajemen-logistik',
                    'remind_at' => $today, // Remind immediately if stock is low
                ]);
                Log::info("Created low stock alert for user {$user->id}, item {$item->item_name}.");
            }
        }

        // --- 3. PPI Reminder (Weekly Audit - e.g., every Friday) ---
        if ($today->dayOfWeek === Carbon::FRIDAY) {
            // Check if reminder was already generated for this week's Friday
            if (!$notificationExists($user->id, 'ppi_audit_reminder', $today)) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'ppi_audit_reminder',
                    'icon' => 'fas fa-shield-virus', // Custom icon for this type
                    'title' => 'Audit PPI Mingguan',
                    'message' => 'Waktu audit pengendalian infeksi rutin untuk area rawat inap. Segera lengkapi formulir audit Anda.',
                    'data' => [],
                    'tag' => 'Minggu Ini',
                    'tag_color' => 'teal',
                    'priority' => 3,
                    'link' => '/pengendalian-dan-pencegahan-infeksi',
                    'remind_at' => $today,
                ]);
                Log::info("Created PPI audit reminder for user {$user->id}.");
            }
        }

        // --- 4. Kinerja Staff Reminder (Monthly Evaluation Deadline) ---
        // Remind 3 days before month end
        $remindDateKinerja = $endOfMonth->copy()->subDays(3);
        if ($today->isSameDay($remindDateKinerja) || $today->isSameDay($endOfMonth)) { // Remind on specific date(s)
            if (!$notificationExists($user->id, 'performance_evaluation_deadline', $today)) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'performance_evaluation_deadline',
                    'icon' => 'fas fa-chart-line',
                    'title' => 'Evaluasi Kinerja Bulanan',
                    'message' => 'Saatnya melakukan evaluasi kinerja untuk tim di unit Anda. Deadline: ' . $endOfMonth->isoFormat('DD MMMM') . '.',
                    'data' => ['deadline_date' => $endOfMonth->toDateString()],
                    'tag' => 'Deadline: ' . $today->diffInDays($endOfMonth) . ' Hari',
                    'tag_color' => 'green',
                    'priority' => 2,
                    'link' => '/kinerja-staff',
                    'remind_at' => $today,
                ]);
                Log::info("Created performance evaluation reminder for user {$user->id}.");
            }
        }

        // --- 5. TNA Reminder (e.g., on the 1st of every month) ---
        if ($today->day === 1) { // Remind on the 1st of the month
            if (!$notificationExists($user->id, 'tna_reminder', $today)) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'tna_reminder',
                    'icon' => 'fas fa-book',
                    'title' => 'Training Needs Analysis Review',
                    'message' => 'Tinjau kebutuhan pelatihan staff Anda untuk bulan ini.',
                    'data' => [],
                    'tag' => 'Bulanan',
                    'tag_color' => 'purple',
                    'priority' => 4,
                    'link' => '/tna',
                    'remind_at' => $today,
                ]);
                Log::info("Created TNA reminder for user {$user->id}.");
            }
        }

        // --- 6. Indikator Mutu Reminder (Monthly Update) ---
        // Remind 5 days before month end
        $remindDateMutu = $endOfMonth->copy()->subDays(5);
        if ($today->isSameDay($remindDateMutu) || $today->isSameDay($endOfMonth)) {
            if (!$notificationExists($user->id, 'quality_indicator_update', $today)) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'quality_indicator_update',
                    'icon' => 'fas fa-bullseye',
                    'title' => 'Update Indikator Mutu',
                    'message' => 'Waktu untuk memperbarui data indikator mutu pelayanan bulan ini.',
                    'data' => [],
                    'tag' => 'Bulanan',
                    'tag_color' => 'indigo',
                    'priority' => 3,
                    'link' => '/indikator-mutu',
                    'remind_at' => $today,
                ]);
                Log::info("Created QI update reminder for user {$user->id}.");
            }
        }

        // --- 7. Schedule Reminder (Personal Meetings - from PrivateSchedule, for today) ---
        $personalMeetingsToday = PrivateSchedule::where('user_id', $user->id)
                                                ->whereDate('scheduled_at', $today->toDateString())
                                                ->orderBy('scheduled_at', 'asc')
                                                ->get();
        foreach ($personalMeetingsToday as $meeting) {
            $remindAt = Carbon::parse($meeting->scheduled_at)->subMinutes(30); // Remind 30 mins before
            // Only create if not already reminded for this specific meeting and day
            if (!$notificationExists($user->id, 'meeting_reminder', $remindAt, ['meeting_id' => $meeting->id])) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'meeting_reminder',
                    'icon' => 'fas fa-calendar-alt',
                    'title' => 'Meeting Tim Medis',
                    'message' => ($meeting->note ?? 'Rapat koordinasi.') . ' Hari Ini ' . Carbon::parse($meeting->scheduled_at)->format('H:i') . '.',
                    'data' => ['meeting_id' => $meeting->id, 'meeting_time' => $meeting->scheduled_at->toTimeString(), 'meeting_note' => $meeting->note],
                    'tag' => 'Hari Ini ' . Carbon::parse($meeting->scheduled_at)->format('H:i'),
                    'tag_color' => 'pink',
                    'priority' => 1,
                    'link' => '/schedule',
                    'remind_at' => $remindAt,
                ]);
                Log::info("Created meeting reminder for user {$user->id}, meeting ID {$meeting->id}.");
            }
        }

        // --- 8. Laporan Reminder (Weekly Reports - e.g., every Sunday) ---
        if ($today->dayOfWeek === Carbon::SUNDAY) {
            if (!$notificationExists($user->id, 'weekly_report_deadline', $today)) {
                Notification::create([
                    'user_id' => $user->id,
                    'type' => 'weekly_report_deadline',
                    'icon' => 'fas fa-file-alt',
                    'title' => 'Laporan Mingguan',
                    'message' => 'Segera selesaikan laporan aktivitas mingguan untuk diserahkan ke supervisor.',
                    'data' => [],
                    'tag' => 'Deadline: Besok', // Or specific logic for next day deadline
                    'tag_color' => 'red',
                    'priority' => 2,
                    'link' => '/laporan',
                    'remind_at' => $today,
                ]);
                Log::info("Created weekly report reminder for user {$user->id}.");
            }
        }
    }
}