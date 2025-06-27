<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $staff = Staff::with(['position', 'department'])->get(); // Optional eager load
        return response()->json($staff);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position_id' => 'required|exists:positions,id',
            'department_id' => 'required|exists:departments,id',
            'status' => 'required|in:Aktif,Tidak Aktif,Cuti'
        ]);

        return Staff::create($request->all());
    }

    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'position_id' => 'sometimes|exists:positions,id',
            'department_id' => 'sometimes|exists:departments,id',
            'status' => 'sometimes|in:Aktif,Tidak Aktif,Cuti'
        ]);

        $staff->update($request->all());
        return $staff;
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return response()->noContent();
    }
}