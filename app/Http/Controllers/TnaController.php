<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\TnaRecord;
use Illuminate\Http\Request;

class TnaController extends Controller
{
   

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
}
