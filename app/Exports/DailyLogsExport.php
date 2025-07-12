<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;
use App\Models\PrivateSchedule;
use App\Models\SpecialCase;
use Carbon\Carbon;

class DailyLogsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = Auth::user();

        // Fetch all private schedules for the authenticated user
        $privateSchedules = PrivateSchedule::where('user_id', $user->id)
            ->orderBy('scheduled_at', 'asc') // Order chronologically for report
            ->get();

        // Fetch all special cases for the authenticated user
        $specialCases = SpecialCase::where('user_id', $user->id)
            ->orderBy('case_date', 'asc') // Order chronologically for report
            ->get();

        // Map and combine them into a single collection
        $combinedLogs = $privateSchedules->map(function ($log) {
            return [
                'type' => 'Catatan Harian Kegiatan',
                'date' => $log->scheduled_at,
                'patient_name' => '', // Not applicable for PrivateSchedule
                'case_type' => '', // Not applicable for PrivateSchedule
                'details' => $log->note, // Using 'note' from PrivateSchedule
                'action_taken' => '', // Not applicable for PrivateSchedule
                'briefing_conducted' => $log->briefing ? 'Ya' : 'Tidak',
                'meeting_held' => $log->meeting ? 'Ya' : 'Tidak',
                'supervision_conducted' => $log->supervision ? 'Ya' : 'Tidak',
                'handover_done' => $log->handover ? 'Ya' : 'Tidak',
                'external_task' => $log->external_task,
            ];
        })->concat($specialCases->map(function ($case) {
            return [
                'type' => 'Kasus Perhatian Khusus',
                'date' => $case->case_date,
                'patient_name' => $case->patient_name,
                'case_type' => $case->case_type,
                'details' => $case->details,
                'action_taken' => $case->action_taken,
                'briefing_conducted' => '', // Not applicable for SpecialCase
                'meeting_held' => '', // Not applicable for SpecialCase
                'supervision_conducted' => '', // Not applicable for SpecialCase
                'handover_done' => '', // Not applicable for SpecialCase
                'external_task' => '', // Not applicable for SpecialCase
            ];
        }))->sortBy('date'); // Sort by date overall

        return $combinedLogs;
    }

    /**
     * Define the column headings for the Excel file.
     * @return array
     */
    public function headings(): array
    {
        return [
            'Tipe Log',
            'Tanggal & Waktu',
            'Nama Pasien',
            'Jenis Kasus',
            'Detail / Catatan',
            'Tindakan',
            'Briefing Dilakukan?',
            'Rapat Diadakan?',
            'Supervisi Dilakukan?',
            'Handover Dilakukan?',
            'Tugas Luar',
        ];
    }

    /**
     * Map the data row to the columns.
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        return [
            $row['type'],
            Carbon::parse($row['date'])->format('d-m-Y H:i'),
            $row['patient_name'],
            $row['case_type'],
            $row['details'],
            $row['action_taken'],
            $row['briefing_conducted'],
            $row['meeting_held'],
            $row['supervision_conducted'],
            $row['handover_done'],
            $row['external_task'],
        ];
    }
}