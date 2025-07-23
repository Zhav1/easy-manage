<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle; // Import WithTitle
use Illuminate\Support\Facades\Auth;
use App\Models\SpecialCase;
use Carbon\Carbon;

class SpecialCasesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::user();
        // Fetch ALL special cases, as requested
        return SpecialCase::where('user_id', $user->id)
                          ->orderBy('case_date', 'asc')
                          ->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Tanggal Kasus',
            'Nama Pasien',
            'Jenis Kasus',
            'Detail',
            'Tindakan',
        ];
    }

    /**
     * @param mixed $case
     * @return array
     */
    public function map($case): array
    {
        return [
            Carbon::parse($case->case_date)->format('d-m-Y H:i'),
            $case->patient_name ?: '-',
            $case->case_type ?: '-',
            $case->details ?: '-',
            $case->action_taken ?: '-',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Kasus Perhatian Khusus';
    }
}