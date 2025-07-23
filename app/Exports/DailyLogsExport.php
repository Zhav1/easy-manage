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
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::user();

        // 1. Query for Private Schedules (filtered by date range)
        $privateSchedulesQuery = PrivateSchedule::where('user_id', $user->id);
        if ($this->startDate && $this->endDate) {
            $privateSchedulesQuery->whereBetween('scheduled_at', [$this->startDate, Carbon::parse($this->endDate)->endOfDay()]);
        } elseif ($this->startDate) {
            $privateSchedulesQuery->where('scheduled_at', '>=', $this->startDate);
        } elseif ($this->endDate) {
            $privateSchedulesQuery->where('scheduled_at', '<=', Carbon::parse($this->endDate)->endOfDay());
        }
        $privateSchedules = $privateSchedulesQuery->orderBy('scheduled_at', 'asc')->get();

        // 2. Query for Special Cases (ALL data, NOT filtered by date)
        $specialCases = SpecialCase::where('user_id', $user->id)
                                   ->orderBy('case_date', 'asc')
                                   ->get();

        // Prepare a combined collection
        $combinedData = collect();

        // Add private schedules
        foreach ($privateSchedules as $log) {
            $combinedData->push([
                'type' => 'Catatan Harian Kegiatan',
                'date' => $log->scheduled_at,
                'patient_name' => '', // Not applicable
                'case_type' => '', // Not applicable
                'details' => $log->note,
                'action_taken' => '', // Not applicable
                'briefing_conducted' => $log->briefing ? 'Ya' : 'Tidak',
                'meeting_held' => $log->meeting ? 'Ya' : 'Tidak',
                'supervision_conducted' => $log->supervision ? 'Ya' : 'Tidak',
                'handover_done' => $log->handover ? 'Ya' : 'Tidak',
                'external_task' => $log->external_task,
                'section' => 'Private Schedules' // Internal flag for mapping if needed
            ]);
        }

        // Add a blank row (or multiple) as separator
        // Add as many blank lines as you want, each one is an empty array
        $combinedData->push([
            'type' => '', 'date' => '', 'patient_name' => '', 'case_type' => '',
            'details' => '----------- Separate Data -----------', // A visual marker
            'action_taken' => '', 'briefing_conducted' => '', 'meeting_held' => '',
            'supervision_conducted' => '', 'handover_done' => '', 'external_task' => '',
            'section' => 'Separator'
        ]);
        $combinedData->push([ /* empty row */ ]); // Another blank row

        // Add special cases
        foreach ($specialCases as $case) {
            $combinedData->push([
                'type' => 'Kasus Perhatian Khusus',
                'date' => $case->case_date,
                'patient_name' => $case->patient_name,
                'case_type' => $case->case_type,
                'details' => $case->details,
                'action_taken' => $case->action_taken,
                'briefing_conducted' => '', // Not applicable
                'meeting_held' => '', // Not applicable
                'supervision_conducted' => '', // Not applicable
                'handover_done' => '', // Not applicable
                'external_task' => '', // Not applicable
                'section' => 'Special Cases'
            ]);
        }

        // No overall sorting here if you want sections to stay together
        return $combinedData->values(); // Reset array keys after pushing
    }

    /**
     * Define the column headings for the Excel file.
     * @return array
     */
    public function headings(): array
    {
        // Use a comprehensive set of headings for both types
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
        // Ensure values are safe for Excel and match headings order
        return [
            $row['type'] ?? '',
            ($row['date'] ?? '') ? Carbon::parse($row['date'])->format('d-m-Y H:i') : '',
            $row['patient_name'] ?? '-',
            $row['case_type'] ?? '-',
            $row['details'] ?? '-',
            $row['action_taken'] ?? '-',
            $row['briefing_conducted'] ?? '-',
            $row['meeting_held'] ?? '-',
            $row['supervision_conducted'] ?? '-',
            $row['handover_done'] ?? '-',
            $row['external_task'] ?? '-',
        ];
    }
}