<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class StaffSchedulesExport implements FromView, ShouldAutoSize, WithColumnWidths
{
    protected $allMonthlySchedulesData;
    protected $overallReportPeriodTitle;

    public function __construct(array $allMonthlySchedulesDataFromController, string $overallReportPeriodTitle)
    {
        $this->allMonthlySchedulesData = $allMonthlySchedulesDataFromController;
        $this->overallReportPeriodTitle = $overallReportPeriodTitle;
    }

    /**
     * This is the core change. The export now gets its content from a dedicated
     * Blade view file, which is much more reliable for complex layouts.
     * This does NOT affect your PDF view file.
     */
    public function view(): View
    {
        return view('reports.staff_schedules_excel', [
            'all_monthly_schedules_data' => $this->allMonthlySchedulesData,
            'report_period_title'      => $this->overallReportPeriodTitle,
        ]);
    }

    /**
     * Defines the column widths for the Excel file.
     */
    public function columnWidths(): array
    {
        // Set a generous width for the staff name column
        $widths = ['A' => 30]; 

        // Set a small, fixed width for all the shift columns
        for ($i = 1; $i <= 31; $i++) {
            $colIndex = 1 + (($i - 1) * 3) + 1;
            $widths[Coordinate::stringFromColumnIndex($colIndex)]     = 4; // P
            $widths[Coordinate::stringFromColumnIndex($colIndex + 1)] = 4; // S
            $widths[Coordinate::stringFromColumnIndex($colIndex + 2)] = 4; // M
        }
        return $widths;
    }
}