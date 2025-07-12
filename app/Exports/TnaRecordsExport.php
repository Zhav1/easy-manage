<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;
use App\Models\TrainingNeed;
use Carbon\Carbon;

class TnaRecordsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = Auth::user();

        // Fetch all training needs for staff managed by the authenticated user
        return TrainingNeed::with('staff.position') // Eager load staff and its position
            ->whereHas('staff', function ($query) use ($user) {
                $query->where('user_id', $user->id); // Filter by user_id on staff
            })
            ->orderBy('tanggal', 'asc') // Order by the 'tanggal' field
            ->get();
    }

    /**
     * Define the column headings for the Excel file.
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID TNA',
            'Nama Staff',
            'Jabatan',
            'Seminar / Workshop / Webinar',
            'Pelatihan',
            'Pendidikan Lanjutan',
            'Tanggal Input',
        ];
    }

    /**
     * Map the data row to the columns.
     * @param mixed $tna
     * @return array
     */
    public function map($tna): array
    {
        return [
            $tna->id,
            $tna->staff->name ?? 'N/A',
            $tna->staff->position->name ?? 'N/A',
            $tna->seminar_workshop_webinar ?? '-',
            $tna->pelatihan ?? '-',
            $tna->pendidikan_lanjutan ?? '-',
            Carbon::parse($tna->created_at)->format('Y-m-d H:i:s'),
        ];
    }
}