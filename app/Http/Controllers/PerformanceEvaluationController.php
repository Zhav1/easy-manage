<?php

namespace App\Http\Controllers;

use App\Models\PerformanceEvaluation;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceEvaluationController extends Controller
{
    /**
     * Display a listing of the performance evaluations for the authenticated user's department and hospital.
     */
    public function index()
    {
        $user = Auth::user();

        $evaluations = PerformanceEvaluation::with(['staff.position', 'staff.department'])
            ->whereHas('staff', function ($query) use ($user) {
                $query->where('department_id', $user->department_id)
                      ->where('hospital_id', $user->hospital_id)
                      ->where('user_id', $user->id);
            })
            ->get();

        return response()->json($evaluations);
    }

    /**
     * Store a newly created performance evaluation in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'kedisiplinan' => 'required|integer|min:10|max:100',
            'komunikasi' => 'required|integer|min:10|max:100',
            'komplain' => 'required|integer|min:10|max:100|',
            'kepatuhan' => 'required|integer|min:10|max:100|',
            'target_kerja' => 'required|integer|min:10|max:100|',
            'notes' => 'nullable|string',
        ]);

        $averageRating = array_sum([
            $validatedData['kedisiplinan'],
            $validatedData['komunikasi'],
            $validatedData['komplain'],
            $validatedData['kepatuhan'],
            $validatedData['target_kerja']
        ]) / 5;

        $statusKinerja = $this->determinePerformanceStatus($averageRating);
        $validatedData['status_kinerja'] = $statusKinerja;

        $evaluation = PerformanceEvaluation::create($validatedData);

        return response()->json([
            'message' => 'Performance evaluation created successfully',
            'evaluation' => $evaluation->load('staff.position', 'staff.department')
        ], 201);
    }

    /**
     * Display the specified performance evaluation.
     */
    public function show(PerformanceEvaluation $performanceEvaluation)
    {
        $user = Auth::user();
        if ($performanceEvaluation->staff->department_id !== $user->department_id ||
            $performanceEvaluation->staff->hospital_id !== $user->hospital_id ||
            $performanceEvaluation->staff->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($performanceEvaluation->load('staff.position', 'staff.department'));
    }

    /**
     * Update the specified performance evaluation in storage.
     */
    public function update(Request $request, PerformanceEvaluation $performanceEvaluation)
    {
        $user = Auth::user();
        if ($performanceEvaluation->staff->department_id !== $user->department_id ||
            $performanceEvaluation->staff->hospital_id !== $user->hospital_id ||
            $performanceEvaluation->staff->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validate([
            'kedisiplinan' => 'sometimes|required|integer|min:10|max:100|',
            'komunikasi' => 'sometimes|required|integer|min:10|max:100|',
            'komplain' => 'sometimes|required|integer|min:10|max:100|',
            'kepatuhan' => 'sometimes|required|integer|min:10|max:100|',
            'target_kerja' => 'sometimes|required|integer|min:10|max:100|',
            'notes' => 'nullable|string',
        ]);

        $currentData = $performanceEvaluation->toArray();
        $updatedData = array_merge($currentData, $validatedData);

        $averageRating = array_sum([
            $updatedData['kedisiplinan'],
            $updatedData['komunikasi'],
            $updatedData['komplain'],
            $updatedData['kepatuhan'],
            $updatedData['target_kerja']
        ]) / 5;

        $statusKinerja = $this->determinePerformanceStatus($averageRating);
        $validatedData['status_kinerja'] = $statusKinerja;

        $performanceEvaluation->update($validatedData);

        return response()->json([
            'message' => 'Performance evaluation updated successfully',
            'evaluation' => $performanceEvaluation->load('staff.position', 'staff.department')
        ]);
    }

    /**
     * Remove the specified performance evaluation from storage.
     */
    public function destroy(PerformanceEvaluation $performanceEvaluation)
    {
        $user = Auth::user();
        if ($performanceEvaluation->staff->department_id !== $user->department_id ||
            $performanceEvaluation->staff->hospital_id !== $user->hospital_id ||
            $performanceEvaluation->staff->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $performanceEvaluation->delete();
        return response()->noContent();
    }

    /**
     * Helper function to determine performance status based on average rating.
     */
    private function determinePerformanceStatus($averageRating)
    {
        if ($averageRating >= 90) {
            return 'Sangat Baik';
        } elseif ($averageRating >= 70) {
            return 'Baik';
        } elseif ($averageRating >= 50) {
            return 'Cukup';
        } elseif ($averageRating >= 30) {
            return 'Kurang';
        } else {
            return 'Sangat Kurang';
        }
    }
}