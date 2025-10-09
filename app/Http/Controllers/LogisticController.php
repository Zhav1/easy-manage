<?php

namespace App\Http\Controllers;

use App\Models\Logistic;
use App\Models\Department;
use App\Models\UsageLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogisticController extends Controller
{
    const STATUS_AVAILABLE = 'Tersedia';
    const STATUS_LIMITED = 'Terbatas';
    const STATUS_LOW = 'Menipis';

    const CATEGORY_MEDICAL_EQUIPMENT = 'Alat Kesehatan';
    const CATEGORY_CONSUMABLES = 'Barang Habis Pakai';

    public function processTransaction(Request $request)
    {
        return response()->json(['message' => 'Transaction processed.']);
    }

    public function getItems()
    {
        $departmentId = Auth::user()->department_id;

        $items = [
            self::CATEGORY_MEDICAL_EQUIPMENT => Logistic::where('category', self::CATEGORY_MEDICAL_EQUIPMENT)
                ->where('department_id', $departmentId)
                ->distinct('item_name')
                ->pluck('item_name')
                ->toArray(),

            self::CATEGORY_CONSUMABLES => Logistic::where('category', self::CATEGORY_CONSUMABLES)
                ->where('department_id', $departmentId)
                ->distinct('item_name')
                ->pluck('item_name')
                ->toArray()
        ];

        return response()->json([
            'success' => true,
            'items' => $items
        ]);
    }

    public function storeItem(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:' . self::CATEGORY_MEDICAL_EQUIPMENT . ',' . self::CATEGORY_CONSUMABLES,
            'item_name' => 'required|string|max:255'
        ]);

        $exists = Logistic::where('category', $validated['category'])
            ->where('item_name', $validated['item_name'])
            ->where('department_id', Auth::user()->department_id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Item sudah ada dalam database'
            ], 409);
        }

        Logistic::create([
            'department_id' => Auth::user()->department_id,
            'category' => $validated['category'],
            'item_name' => $validated['item_name'],
            'stock' => 0,
            'unit_of_measure' => 'unit',
            'condition' => 'Baik',
            'status' => self::STATUS_LOW
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil ditambahkan'
        ], 201);
    }

    public function dashboard()
    {
        $user = Auth::user();

        if (!$user->department_id) {
            return redirect()->back()->with('error', 'Anda belum terdaftar di departemen manapun');
        }

        $departmentId = $user->department_id;

        $totalStock = Logistic::where('department_id', $departmentId)->sum('stock');
        $limitedStock = Logistic::where('department_id', $departmentId)
            ->where('stock', '<', 10)
            ->where('stock', '>=', 5)
            ->count();
        $lowStock = Logistic::where('department_id', $departmentId)
            ->where('stock', '<=', 5)
            ->count();

        return view('manajemenlogistik', compact('totalStock', 'limitedStock', 'lowStock'));
    }

    public function index()
    {
        $user = Auth::user();

        if (!$user->department_id) {
            return redirect()->back()->with('error', 'Anda belum terdaftar di departemen manapun');
        }

        $logistics = Logistic::with('department')
            ->where('department_id', $user->department_id)
            ->get();

        return view('mltable', compact('logistics'));
    }

    public function create()
    {
        $departments = Department::all();
        $categories = [
            self::CATEGORY_MEDICAL_EQUIPMENT,
            self::CATEGORY_CONSUMABLES
        ];

        return view('logistics.create', compact('departments', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'category' => 'required|in:' . self::CATEGORY_MEDICAL_EQUIPMENT . ',' . self::CATEGORY_CONSUMABLES,
            'item_name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'item_code' => 'nullable|string|max:100|unique:logistics,item_code',
            'calibration_date' => 'nullable|date',
            'calibration_expiry_date' => 'nullable|date|after_or_equal:calibration_date',
            'stock' => 'required|integer|min:0',
            'unit_of_measure' => 'required|string|max:50',
            'condition' => 'nullable|string|max:100'
        ]);

        $status = $this->determineStatus($validated['stock']);

        Logistic::create(array_merge($validated, ['status' => $status]));

        return redirect()->route('logistics.index')
            ->with('success', 'Barang berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $logistic = Logistic::findOrFail($id);
        $departments = Department::all();
        $categories = [
            self::CATEGORY_MEDICAL_EQUIPMENT,
            self::CATEGORY_CONSUMABLES
        ];

        return view('editml', compact('logistic', 'departments', 'categories'));
    }

    public function show(Logistic $logistic)
    {
        return view('logistics.show', compact('logistic'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'category' => 'required|in:' . self::CATEGORY_MEDICAL_EQUIPMENT . ',' . self::CATEGORY_CONSUMABLES,
            'item_name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'item_code' => 'nullable|string|max:100|unique:logistics,item_code,' . $id,
            'calibration_date' => 'nullable|date',
            'calibration_expiry_date' => 'nullable|date|after_or_equal:calibration_date',
            'stock' => 'required|integer|min:0',
            'unit_of_measure' => 'required|string|max:50',
            'condition' => 'nullable|string|max:100'
        ]);

        $status = $this->determineStatus($validated['stock']);

        $logistic = Logistic::findOrFail($id);
        $logistic->update(array_merge($validated, ['status' => $status]));

        return redirect()->route('logistics.index')
            ->with('success', 'Data berhasil diperbarui!');
    }

    public function useItem(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'usage_notes' => 'nullable|string|max:500',
        ]);

        $item = Logistic::findOrFail($id);

        if ($validated['quantity'] > $item->stock) {
            return back()->with('error', 'Jumlah yang digunakan melebihi stok yang tersedia!');
        }

        $item->stock -= $validated['quantity'];
        $item->used += $validated['quantity'];
        $item->status = $this->determineStatus($item->stock);
        $item->save();

        UsageLog::create([
            'logistic_id' => $item->id,
            'quantity' => $validated['quantity'],
            'notes' => $validated['usage_notes'] ?? null,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('logistics.index')
            ->with('success', 'Penggunaan barang berhasil dicatat!');
    }

    public function destroy($id)
    {
        $logistic = Logistic::findOrFail($id);
        $logistic->delete();

        return redirect()->route('logistics.index')
            ->with('success', 'Data berhasil dihapus!');
    }

    private function determineStatus($stock)
    {
        if ($stock < 5) {
            return self::STATUS_LOW;
        } elseif ($stock < 10) {
            return self::STATUS_LIMITED;
        }
        return self::STATUS_AVAILABLE;
    }
}
