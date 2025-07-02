<?php

namespace App\Http\Controllers;

use App\Models\TrainingNeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff; // Import the Staff model

class TrainingNeedController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Fetch training needs for staff within the user's department and hospital
        $trainingNeeds = TrainingNeed::with(['staff.position', 'staff.department', 'staff.hospital'])
                                    ->whereHas('staff', function ($query) use ($user) {
                                        $query->where('department_id', $user->department_id)
                                              ->where('hospital_id', $user->hospital_id);
                                    })
                                    ->get();

        return response()->json($trainingNeeds);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'seminar_workshop_webinar' => 'nullable|string|max:255',
            'pelatihan' => 'nullable|string|max:255',
            'pendidikan_lanjutan' => 'nullable|string|max:255',
        ]);

        $trainingNeed = TrainingNeed::create($validated);
        return response()->json($trainingNeed, 201);
    }

    public function show(TrainingNeed $trainingNeed)
    {
        return response()->json($trainingNeed->load(['staff.position', 'staff.department', 'staff.hospital']));
    }

    public function update(Request $request, TrainingNeed $trainingNeed)
    {
        $validated = $request->validate([
            'staff_id' => 'sometimes|exists:staff,id',
            'seminar_workshop_webinar' => 'nullable|string|max:255',
            'pelatihan' => 'nullable|string|max:255',
            'pendidikan_lanjutan' => 'nullable|string|max:255',
        ]);

        $trainingNeed->update($validated);
        return response()->json($trainingNeed);
    }

    public function destroy(TrainingNeed $trainingNeed)
    {
        $trainingNeed->delete();
        return response()->noContent();
    }
}