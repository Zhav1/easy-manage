<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class StaffController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $staff = Staff::where('department_id', $user->department_id)
                        ->where('hospital_id', $user->hospital_id)
                        ->get();

        return response()->json($staff);
    }
    

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'position_id' => 'required|exists:positions,id',
                'department_id' => 'required|exists:departments,id',
                'hospital_id' => 'required|exists:hospitals,id',
                'status' => 'required|in:Aktif,Tidak Aktif,Cuti'
            ]);

            $staff = Staff::create($validated);

            return response()->json([
                'message' => 'Staff successfully created',
                'staff' => $staff
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Something went wrong',
                'exception' => $e->getMessage()
            ], 500);
        }
    }


    public function update(Request $request, Staff $staff)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'position_id' => 'sometimes|exists:positions,id',
            'department_id' => 'sometimes|exists:departments,id',
            'hospital_id' => 'sometimes|exists:hospitals,id',
            'status' => 'sometimes|in:Aktif,Tidak Aktif,Cuti'
        ]);

        $staff->update($request->all());
        return response()->json([
            'message' => 'Staff updated successfully',
            'staff' => $staff
        ]);
    }

    public function destroy(Staff $staff)
    {
        $staff->delete();
        return response()->noContent();
    }
}