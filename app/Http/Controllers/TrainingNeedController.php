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

        // Fetch training needs for staff specifically linked to the authenticated user's ID
        $trainingNeeds = TrainingNeed::with(['staff.position', 'staff.department', 'staff.hospital'])
                                     ->whereHas('staff', function ($query) use ($user) {
                                         // Filter by the user_id on the staff table
                                         $query->where('user_id', $user->id)
                                               // Optionally, keep department and hospital filters if still desired for an extra layer
                                               ->where('department_id', $user->department_id)
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
            'tanggal' => 'required|date_format:Y-m-d',
        ]);

        // Before creating the TrainingNeed, ensure the staff_id belongs to the authenticated user.
        // This is a crucial security check.
        $user = Auth::user();
        $staffBelongsToUser = Staff::where('id', $validated['staff_id'])
                                   ->where('user_id', $user->id)
                                   ->exists();

        if (!$staffBelongsToUser) {
            return response()->json(['message' => 'Staff does not belong to the authenticated user.'], 403);
        }

        $trainingNeed = TrainingNeed::create($validated);
        return response()->json($trainingNeed, 201);
    }

    public function show(TrainingNeed $trainingNeed)
    {
        $user = Auth::user();

        // Authorize that the training need's staff belongs to the current user
        if ($trainingNeed->staff->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to view this record.'], 403);
        }

        return response()->json($trainingNeed->load(['staff.position', 'staff.department', 'staff.hospital']));
    }

    public function update(Request $request, TrainingNeed $trainingNeed)
    {
        $user = Auth::user();

        // Authorize that the training need's staff belongs to the current user
        if ($trainingNeed->staff->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to update this record.'], 403);
        }

        $validated = $request->validate([
            'staff_id' => 'sometimes|exists:staff,id',
            'seminar_workshop_webinar' => 'nullable|string|max:255',
            'pelatihan' => 'nullable|string|max:255',
            'pendidikan_lanjutan' => 'nullable|string|max:255',
        ]);

        // If staff_id is being updated, ensure the new staff_id also belongs to the user
        if (isset($validated['staff_id']) && $validated['staff_id'] !== $trainingNeed->staff_id) {
             $staffBelongsToUser = Staff::where('id', $validated['staff_id'])
                                   ->where('user_id', $user->id)
                                   ->exists();
            if (!$staffBelongsToUser) {
                return response()->json(['message' => 'New staff selected does not belong to the authenticated user.'], 403);
            }
        }

        $trainingNeed->update($validated);
        return response()->json($trainingNeed);
    }

    public function destroy(TrainingNeed $trainingNeed)
    {
        $user = Auth::user();

        // Authorize that the training need's staff belongs to the current user
        if ($trainingNeed->staff->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized to delete this record.'], 403);
        }

        $trainingNeed->delete();
        return response()->noContent();
    }
}