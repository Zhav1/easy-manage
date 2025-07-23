<?php

namespace App\Exports;

use App\Models\PerformanceEvaluation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class StaffPerformanceExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        $user = Auth::user();
        $query = PerformanceEvaluation::with('staff.position')
            ->whereHas('staff', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query->orderBy('created_at', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'ID Evaluasi',
            'Nama Staff',
            'Jabatan',
            'Kedisiplinan (%)',
            'Komunikasi (%)',
            'Komplain (Skor)',
            'Kepatuhan (%)',
            'Pencapaian Target (%)',
            'Skor Rata-rata Akhir (%)',
            'Status Kinerja',
            'Catatan',
            'Tanggal Evaluasi',
        ];
    }

    public function map($evaluation): array
    {
        $getPerformanceStatus = function ($averageRating) {
            if ($averageRating >= 90) return 'Sangat Baik';
            if ($averageRating >= 70) return 'Baik';
            if ($averageRating >= 50) return 'Cukup';
            if ($averageRating >= 30) return 'Kurang';
            return 'Sangat Kurang';
        };

        return [
            $evaluation->id,
            $evaluation->staff->name ?? 'N/A',
            $evaluation->staff->position->name ?? 'N/A',
            $evaluation->kedisiplinan,
            $evaluation->komunikasi,
            $evaluation->komplain,
            $evaluation->kepatuhan,
            $evaluation->target_kerja,
            $evaluation->overall_score, // Now correctly calculated by the model
            $getPerformanceStatus($evaluation->overall_score),
            $evaluation->notes ?? '-',
            Carbon::parse($evaluation->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}