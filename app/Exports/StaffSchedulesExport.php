<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\Staff;
use Carbon\Carbon;

class StaffSchedulesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = Auth::user();
        
        // Get the current week's start and end dates (Monday to Sunday)
        $startOfWeek = Carbon::now()->startOfWeek(Carbon::MONDAY);
        $endOfWeek = Carbon::now()->endOfWeek(Carbon::SUNDAY);

        // Fetch all schedules for staff managed by the authenticated user within this week
        $schedules = Schedule::with(['staff', 'shift'])
            ->whereHas('staff', function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->where('department_id', $user->department_id)
                    ->where('hospital_id', $user->hospital_id);
            })
            ->whereBetween('start', [$startOfWeek->toDateString(), $endOfWeek->toDateString()])
            ->orderBy('staff_id')
            ->orderBy('start')
            ->get();

        // Get all relevant staff names for the rows
        $allStaff = Staff::where('user_id', $user->id)
                        ->where('department_id', $user->department_id)
                        ->where('hospital_id', $user->hospital_id)
                        ->orderBy('name')
                        ->get();

        $groupedSchedules = [];
        // Initialize the structure with all staff and empty days
        foreach ($allStaff as $staff) {
            $groupedSchedules[$staff->id] = ['staff_name' => $staff->name];
            for ($i = 0; $i < 7; $i++) {
                $date = (clone $startOfWeek)->addDays($i);
                $dayKey = strtolower($date->isoFormat('dddd')); // e.g., 'senin', 'selasa'
                $groupedSchedules[$staff->id][$dayKey] = []; // To store multiple shifts for a day
            }
        }

        // Populate the grouped schedules with actual shift data
        foreach ($schedules as $schedule) {
            $staffId = $schedule->staff_id;
            $shiftCode = $schedule->shift->code ?? 'N/A';
            // Map 'Sore' to 'Siang' for display consistency as per your JS
            if ($shiftCode === 'Sore') {
                $shiftCode = 'Siang';
            }
            $scheduleDate = Carbon::parse($schedule->start);
            $dayKey = strtolower($scheduleDate->isoFormat('dddd'));

            if (isset($groupedSchedules[$staffId][$dayKey])) {
                $groupedSchedules[$staffId][$dayKey][] = $shiftCode;
            }
        }

        // Transform the grouped data into a flat collection suitable for export
        $exportData = collect();
        foreach ($groupedSchedules as $staffRow) {
            $row = [$staffRow['staff_name']]; // First column is staff name
            for ($i = 0; $i < 7; $i++) {
                $date = (clone $startOfWeek)->addDays($i);
                $dayKey = strtolower($date->isoFormat('dddd'));
                $shifts = $staffRow[$dayKey];
                // Join multiple shifts for a day, if any
                $row[] = empty($shifts) ? '-' : implode(', ', $shifts);
            }
            $exportData->push($row);
        }

        return $exportData;
    }

    /**
     * Define the column headings for the Excel file.
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama Staff',
            'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu',
        ];
    }

    /**
     * Map the data row to the columns.
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        // The collection method already prepares rows as arrays
        return $row;
    }
}