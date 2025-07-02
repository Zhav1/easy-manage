<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TnaRecordController extends Controller
{
    // app/Http/Controllers/TnaController.php
namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\TnaRecord;
use Illuminate\Http\Request;

class TnaController extends Controller
{
    public function index()
    {
        return view('tna');
    }

    public function getStaff()
    {
        $staff = Staff::with(['position', 'department'])
                    ->where('status', 'Aktif')
                    ->get();
        return response()->json($staff);
    }

    public function getTnaData()
    {
        $staffWithTna = Staff::with(['position', 'department', 'tnaRecords' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->where('status', 'Aktif')->get();

        return response()->json($staffWithTna);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'type' => 'required|in:seminar,training,education',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'organizer' => 'nullable|string|max:255',
        ]);

        $tna = TnaRecord::create($validated);
        return response()->json($tna, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'type' => 'required|in:seminar,training,education',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'organizer' => 'nullable|string|max:255',
        ]);

        $tna = TnaRecord::findOrFail($id);
        $tna->update($validated);
        return response()->json($tna);
    }

    public function destroy($id)
    {
        TnaRecord::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}


}
