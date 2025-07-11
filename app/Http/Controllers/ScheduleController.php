<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        if (!$userId) {
            Log::warning('Unauthenticated access attempt to ScheduleController@index');
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $schedules = Schedule::with(['staff', 'shift'])
            ->whereHas('staff', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        Log::debug('Fetched ' . $schedules->count() . ' schedules for user ID: ' . $userId);

        $formattedSchedules = $schedules->map(function ($schedule) {
            if (!$schedule->shift || !$schedule->staff) {
                Log::warning('Skipping schedule ' . $schedule->id . ': Missing associated shift or staff.');
                return null;
            }

            try {
                $shiftStartTime = Carbon::parse($schedule->shift->start)->format('H:i:s'); 
                $shiftEndTime = Carbon::parse($schedule->shift->end)->format('H:i:s');
            } catch (\Exception $e) {
                Log::error('Error parsing shift time for schedule ' . $schedule->id . ': ' . $e->getMessage());
                return null;
            }
            
            $shiftCode = $schedule->shift->code ?? 'N/A';
            $staffName = $schedule->staff->name ?? 'Unknown Staff';

            $scheduleStartDate = Carbon::parse($schedule->start);
            $scheduleEndDate = Carbon::parse($schedule->end); 

            $eventStart = $scheduleStartDate->format('Y-m-d') . 'T' . $shiftStartTime;
            
            $calculatedEventEnd = $scheduleEndDate->format('Y-m-d') . 'T' . $shiftEndTime;

            $startOfShiftTime = Carbon::parse($shiftStartTime);
            $endOfShiftTime = Carbon::parse($shiftEndTime);

            if ($startOfShiftTime->greaterThan($endOfShiftTime)) {
                $calculatedEventEnd = $scheduleEndDate->addDay()->format('Y-m-d') . 'T' . $shiftEndTime;
            }
            
            Log::debug('Schedule Event Data:', [
                'ID' => $schedule->id,
                'Staff' => $staffName,
                'Shift' => $shiftCode,
                'Event Start' => $eventStart,
                'Event End' => $calculatedEventEnd
            ]);

            return [
                'id' => $schedule->id,
                'title' => $staffName . ' (' . $shiftCode . ')', 
                'start' => $eventStart,
                'end' => $calculatedEventEnd,
                'extendedProps' => [
                    'staff_id' => $schedule->staff_id,
                    'shift_id' => $schedule->shift_id, 
                    'shift_code' => $shiftCode,         
                    'staff_name' => $staffName,
                ],
                'allDay' => false, 
                // --- REMOVED backgroundColor and borderColor from here ---
                // 'backgroundColor' => $this->getShiftColor($shiftCode), 
                // 'borderColor' => $this->getShiftColor($shiftCode),

                // --- ADDED classNames for FullCalendar to pick up custom CSS ---
                'classNames' => ['fc-event-' . strtolower($shiftCode)],
            ];
        })
        ->filter() 
        ->values(); 

        return response()->json($formattedSchedules);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'shift_id' => 'required|exists:shifts,id',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $schedule = Schedule::create(array_merge($validated, ['user_id' => Auth::id()]));
        
        return response()->json($schedule, 201);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        if ($schedule->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'shift_id' => 'required|exists:shifts,id',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $schedule->update($validated);
        return response()->json($schedule);
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }
        
        $schedule->delete();
        return response()->noContent();
    }

    // This helper function is no longer directly used for event background/border
    // but might still be used for other purposes (e.g., legend).
    private function getShiftColor(string $shiftCode): string
    {
        switch ($shiftCode) {
            case 'Pagi': return '#4CAF50';
            case 'Siang': return '#FFC107';
            case 'Malam': return '#3F51B5';
            default: return '#607D8B';
        }
    }
}