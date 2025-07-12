<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;
use App\Models\Logistic;
use Carbon\Carbon;

class LogisticsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = Auth::user();

        if (!$user->department_id) {
            return collect(); // Return empty if user has no department
        }

        // Fetch all logistic items for the authenticated user's department
        return Logistic::with('department')
            ->where('department_id', $user->department_id)
            ->orderBy('category')
            ->orderBy('item_name')
            ->get();
    }

    /**
     * Define the column headings for the Excel file.
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Barang',
            'Kategori',
            'Merk',
            'Stok',
            'Satuan',
            'Status',
            'Kode Barang',
            'Jadwal Maintenance',
            'Tanggal Kalibrasi',
            'Tanggal Kadaluarsa Kalibrasi',
            'Catatan',
            'Terakhir Diperbarui',
            'Ruangan',
        ];
    }

    /**
     * Map the data row to the columns.
     * @param mixed $logistic
     * @return array
     */
    public function map($logistic): array
    {
        return [
            $logistic->id,
            $logistic->item_name,
            $logistic->category,
            $logistic->brand ?? '-',
            $logistic->stock,
            $logistic->unit_of_measure ?? '-',
            $logistic->status,
            $logistic->item_code ?? '-',
            $logistic->maintenance_schedule ?? '-',
            $logistic->calibration_date ? Carbon::parse($logistic->calibration_date)->format('Y-m-d') : '-',
            $logistic->calibration_expiry_date ? Carbon::parse($logistic->calibration_expiry_date)->format('Y-m-d') : '-',
            $logistic->notes ?? '-',
            $logistic->updated_at ? Carbon::parse($logistic->updated_at)->format('Y-m-d H:i:s') : '-',
            $logistic->department->name ?? 'N/A', // Access department name
        ];
    }
}