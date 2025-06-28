<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use App\Models\Hospital;
use App\Models\Staff;

class UserDataController extends Controller
{
     public function index()
    {
        $user = Auth::user();

        $department = Department::find($user->department_id);
        $hospital = Hospital::find($user->hospital_id);

        $staffCount = Staff::where('department_id', $user->department_id)
                           ->where('hospital_id', $user->hospital_id)
                           ->count();

        return response()->json([
            'department' => $department,
            'hospital' => $hospital,
            'staff_count' => $staffCount
        ]);
    }
}

