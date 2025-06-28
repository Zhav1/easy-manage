<?php

namespace App\Http\Controllers;

use App\Models\Logistic;
use Illuminate\Http\Request;

class LogisticController extends Controller
{
    public function index()
    {
        $logistics = Logistic::all();
        return view('mltable', compact('logistics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit' => 'required|string',
            'category' => 'required|string',
            'item_name' => 'required|string',
            'brand' => 'nullable|string',
            'item_code' => 'nullable|string',
            'maintenance_schedule' => 'nullable|string',
            'calibration_date' => 'nullable|date',
            'calibration_expiry_date' => 'nullable|date',
            'stock' => 'required|integer|min:0',
            'unit_of_measure' => 'required|string',
        ]);

        // Determine status based on stock
        $status = 'Tersedia';
        if ($request->stock < 5) {
            $status = 'Menipis';
        } elseif ($request->stock < 10) {
            $status = 'Terbatas';
        }

        Logistic::create(array_merge($request->all(), ['status' => $status]));

        return redirect()->route('logistics.index')->with('success', 'Barang berhasil ditambahkan!');
    }
}