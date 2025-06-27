<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        return Schedule::with(['staff', 'shift'])->get()->map(function ($schedule) {
        return [
            'id' => $schedule->id,
            'title' => $schedule->staff->name,
            'start' => $schedule->start,
            'end' => date('Y-m-d', strtotime($schedule->end . ' +1 day')), // FullCalendar is exclusive
            'extendedProps' => [
                'staff_id' => $schedule->staff_id,
                'shift' => $schedule->shift->code ?? '',
                'staff_name' => $schedule->staff->name,
            ],
        ];
    });
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'shift_id' => 'required|exists:shifts,id',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $schedule = Schedule::create($validated);
        return response()->json($schedule, 201);
    }


    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

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
        $schedule->delete();
        return response()->noContent();
    }

    private function getShiftTime($shift, $type)
    {
        $times = [
            'P' => ['start' => 'T07:00:00', 'end' => 'T14:00:00'], // Pagi
            'S' => ['start' => 'T14:00:00', 'end' => 'T21:00:00'], // Sore
            'M' => ['start' => 'T21:00:00', 'end' => 'T07:00:00']  // Malam (next day)
        ];
        return $times[$shift][$type];
    }
}
