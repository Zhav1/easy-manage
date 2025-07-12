<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;
use App\Models\PerformanceEvaluation;
use Carbon\Carbon;

class StaffPerformanceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = Auth::user();

        // Fetch all performance evaluations for staff managed by the authenticated user
        return PerformanceEvaluation::with('staff.position') // Eager load staff and its position
            ->whereHas('staff', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('department_id', $user->department_id)
                    ->where('hospital_id', $user->hospital_id);
            })
            ->orderBy('created_at', 'asc') // Order chronologically
            ->get();
    }

    /**
     * Define the column headings for the Excel file.
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID Evaluasi',
            'Nama Staff',
            'Jabatan',
            'Kedisiplinan (%)',
            'Komunikasi (%)',
            'Komplain (Skor Konversi)', // Clarify heading since it's a score
            'Kepatuhan (%)',
            'Pencapaian Target (%)',
            'Skor Rata-rata Akhir (%)',
            'Status Kinerja',
            'Catatan',
            'Tanggal Evaluasi',
        ];
    }

    /**
     * Map the data row to the columns.
     * @param mixed $evaluation
     * @return array
     */
    public function map($evaluation): array
    {
        // Replicate the logic from your PerformanceEvaluationController to get status
        $getPerformanceStatus = function ($averageRating) {
            if ($averageRating >= 90) return 'Sangat Baik';
            if ($averageRating >= 70) return 'Baik';
            if ($averageRating >= 50) return 'Cukup';
            if ($averageRating >= 30) return 'Kurang';
            return 'Sangat Kurang';
        };

        return [
            $evaluation->id,
            $evaluation->staff->name ?? 'N/A', // Access staff name
            $evaluation->staff->position->name ?? 'N/A', // Access staff position name
            $evaluation->kedisiplinan,
            $evaluation->komunikasi,
            $evaluation->komplain, // This is the score from the table
            $evaluation->kepatuhan,
            $evaluation->target_kerja,
            $evaluation->overall_score, // This is already calculated on the model via accessor or mutator
            $getPerformanceStatus($evaluation->overall_score), // Use the determined status
            $evaluation->notes ?? '-',
            Carbon::parse($evaluation->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}