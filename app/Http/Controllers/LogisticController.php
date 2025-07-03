<?php

namespace App\Http\Controllers;

use App\Models\Logistic;
use App\Models\Department;
use Illuminate\Http\Request;

class LogisticController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        
        if (!$user->department_id) {
            return redirect()->back()->with('error', 'Anda belum terdaftar di departemen manapun');
        }
        
        $totalStock = Logistic::where('department_id', $user->department_id)->sum('stock');
        $limitedStock = Logistic::where('department_id', $user->department_id)
            ->where('stock', '<', 10)
            ->where('stock', '>=', 5)
            ->count();
        $lowStock = Logistic::where('department_id', $user->department_id)
            ->where('stock', '<', 5)
            ->count();
        
        return view('manajemenlogistik', compact('totalStock', 'limitedStock', 'lowStock'));
    }

    // ... method lainnya tetap sama seperti sebelumnya
    public function index()
    {
        $logistics = Logistic::all();
        return view('mltable', compact('logistics'));
    }

    public function create()
    {
        return view('logistics.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|string',
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

        $status = 'Tersedia';
        if ($request->stock < 5) {
            $status = 'Menipis';
        } elseif ($request->stock < 10) {
            $status = 'Terbatas';
        }

        Logistic::create(array_merge($request->all(), ['status' => $status]));

        return redirect()->route('logistics.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $logistic = Logistic::findOrFail($id);
        return view('editml', compact('logistic'));
    }

    public function show(Logistic $logistic)
    {
        return view('logistics.show', compact('logistic'));
    }

    public function update(Request $request, $id)
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

        $status = 'Tersedia';
        if ($request->stock < 5) {
            $status = 'Menipis';
        } elseif ($request->stock < 10) {
            $status = 'Terbatas';
        }

        $logistic = Logistic::findOrFail($id);
        $logistic->update(array_merge($request->all(), ['status' => $status]));

        return redirect()->route('logistics.index')->with('success', 'Data berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $logistic = Logistic::findOrFail($id);
        $logistic->delete();

        return redirect()->route('logistics.index')->with('success', 'Data berhasil dihapus!');
    }
}