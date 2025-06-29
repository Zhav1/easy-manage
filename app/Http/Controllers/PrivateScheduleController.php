<?php

// app/Http/Controllers/PrivateScheduleController.php

namespace App\Http\Controllers;

use App\Models\PrivateSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivateScheduleController extends Controller
{
    public function index()
    {
        return response()->json(Auth::user()->privateSchedules()->latest()->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'scheduled_at' => 'required|date',
            'briefing' => 'required|boolean',
            'meeting' => 'required|boolean',
            'supervision' => 'required|boolean',
            'handover' => 'required|boolean',
            'external_task' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();

        $schedule = PrivateSchedule::create($data);
        return response()->json($schedule, 201);
    }

    public function show($id)
    {
        return response()->json(Auth::user()->privateSchedules()->findOrFail($id));
    }
    
    public function update(Request $request, $id)
    {
        $schedule = Auth::user()->privateSchedules()->findOrFail($id);

        $data = $request->validate([
            'scheduled_at' => 'required|date',
            'briefing' => 'required|boolean',
            'meeting' => 'required|boolean',
            'supervision' => 'required|boolean',
            'handover' => 'required|boolean',
            'external_task' => 'nullable|string|max:255',
            'note' => 'nullable|string',
        ]);

        $schedule->update($data);
        return response()->json($schedule);
    }

    public function destroy($id)
    {
        $schedule = Auth::user()->privateSchedules()->findOrFail($id);
        $schedule->delete();
        return response()->noContent();
    }
}

