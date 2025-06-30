<?php

namespace App\Http\Controllers;

use App\Models\Logistic;
use App\Models\Department;
use Illuminate\Http\Request;

class LogisticController extends Controller
{
    // Menampilkan semua data
    public function index()
    {
        $logistics = Logistic::all();
        return view('mltable', compact('logistics'));
    }

    // Menampilkan form tambah data (CREATE)
    public function create()
    {
        return view('logistics.create'); // Buat view create.blade.php
    }

    // Menyimpan data baru (STORE)
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

    // Menampilkan form edit (EDIT)
   public function edit($id)
{
    $logistic = Logistic::findOrFail($id);
    return view('editml', compact('logistic'));
}
public function show(Logistic $logistic)
{
    // Tampilkan detail single item
    return view('logistics.show', compact('logistic'));
}
    // Update data (UPDATE)
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

    // Hapus data (DELETE)
    public function destroy($id)
    {
        $logistic = Logistic::findOrFail($id);
        $logistic->delete();

        return redirect()->route('logistics.index')->with('success', 'Data berhasil dihapus!');
    }
}