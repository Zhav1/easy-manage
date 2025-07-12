<?php

// app/Http/Controllers/PrivateScheduleController.php

namespace App\Http\Controllers;

use App\Models\PrivateSchedule;
use App\Models\SpecialCase; // Import the SpecialCase model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrivateScheduleController extends Controller
{
    /**
     * Display a listing of the user's private schedules.
     */
    public function indexPrivateSchedules()
    {
        return response()->json(Auth::user()->privateSchedules()->latest()->get());
    }

    /**
     * Store a newly created private schedule in storage.
     */
    public function storePrivateSchedule(Request $request)
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

    /**
     * Display the specified private schedule.
     */
    public function showPrivateSchedule($id)
    {
        return response()->json(Auth::user()->privateSchedules()->findOrFail($id));
    }

    /**
     * Update the specified private schedule in storage.
     */
    public function updatePrivateSchedule(Request $request, $id)
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

    /**
     * Remove the specified private schedule from storage.
     */
    public function destroyPrivateSchedule($id)
    {
        $schedule = Auth::user()->privateSchedules()->findOrFail($id);
        $schedule->delete();
        return response()->noContent();
    }

    // --- Special Cases Methods ---

    /**
     * Display a listing of the user's special cases.
     */
    public function indexSpecialCases()
    {
        return response()->json(Auth::user()->specialCases()->latest('case_date')->get());
    }

    /**
     * Store a newly created special case in storage.
     */
    public function storeSpecialCase(Request $request)
    {
        $data = $request->validate([
            'case_date' => 'required|date',
            'patient_name' => 'required|string|max:255',
            'case_type' => 'required|string|max:255',
            'details' => 'nullable|string',
            'action_taken' => 'nullable|string',
        ]);

        $data['user_id'] = Auth::id();

        $specialCase = SpecialCase::create($data);
        return response()->json($specialCase, 201);
    }

    /**
     * Display the specified special case.
     */
    public function showSpecialCase($id)
    {
        return response()->json(Auth::user()->specialCases()->findOrFail($id));
    }

    /**
     * Update the specified special case in storage.
     */
    public function updateSpecialCase(Request $request, $id)
    {
        $specialCase = Auth::user()->specialCases()->findOrFail($id);

        $data = $request->validate([
            'case_date' => 'required|date',
            'patient_name' => 'required|string|max:255',
            'case_type' => 'required|string|max:255',
            'details' => 'nullable|string',
            'action_taken' => 'nullable|string',
        ]);

        $specialCase->update($data);
        return response()->json($specialCase);
    }

    /**
     * Remove the specified special case from storage.
     */
    public function destroySpecialCase($id)
    {
        $specialCase = Auth::user()->specialCases()->findOrFail($id);
        $specialCase->delete();
        return response()->noContent();
    }
}