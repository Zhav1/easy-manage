<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle; // For sheet title

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class MonthlyStaffSchedulesExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $schedulesData;
    protected $monthName;
    protected $startOfMonth;
    protected $daysInMonth;
    protected $staffCount;

    /**
     * @param array $schedulesData - This should be the 'monthly_schedules' array from getMonthlyStaffSchedules
     * @param string $monthName - Formatted month name like "Juli 2025"
     * @param string $startMonthDate - Start date of the month (Y-m-d)
     * @param int $daysInMonth - Number of days in the month
     * @param int $staffCount - Number of staff rows
     */
    public function __construct(array $schedulesData, string $monthName, string $startMonthDate, int $daysInMonth, int $staffCount)
    {
        $this->schedulesData = $schedulesData;
        $this->monthName = $monthName;
        $this->startOfMonth = Carbon::parse($startMonthDate);
        $this->daysInMonth = $daysInMonth;
        $this->staffCount = $staffCount;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $exportCollection = collect();

        // Row 1: Main Title (e.g., "Jadwal Dinas Staff Juli 2025")
        $exportCollection->push([
            'Jadwal Dinas Staff ' . $this->monthName
        ]);

        // Row 2: Blank Row for spacing
        $exportCollection->push([]);

        // Row 3: Date Headers (e.g., "1", "2", "3"...)
        $dateHeaderRow = ['Nama Staff']; // First cell for staff name
        $currentDate = $this->startOfMonth->copy();
        while ($currentDate->lte($this->startOfMonth->copy()->endOfMonth())) {
            $dateHeaderRow[] = $currentDate->day; // Day number
            $dateHeaderRow[] = ''; // Empty for 2nd shift column
            $dateHeaderRow[] = ''; // Empty for 3rd shift column
            $currentDate->addDay();
        }
        $exportCollection->push($dateHeaderRow);

        // Row 4: Shift Type Headers (e.g., "P", "S", "M", "P", "S", "M"...)
        $shiftHeaderRow = ['']; // First cell blank under "Nama Staff"
        for ($i = 0; $i < $this->daysInMonth; $i++) {
            $shiftHeaderRow[] = 'P';
            $shiftHeaderRow[] = 'S';
            $shiftHeaderRow[] = 'M';
        }
        $exportCollection->push($shiftHeaderRow);

        // Populate data rows for each staff member
        foreach ($this->schedulesData as $staffEntry) {
            $rowData = [$staffEntry['staff_name']];
            $currentDate = $this->startOfMonth->copy();
            while ($currentDate->lte($this->startOfMonth->copy()->endOfMonth())) {
                $dateKey = $currentDate->format('Y-m-d');
                // Ensure schedules_by_date entry exists, use empty array as fallback
                $shifts = $staffEntry['schedules_by_date'][$dateKey] ?? [
                    'Pagi' => false, 'Siang' => false, 'Malam' => false, 'is_sunday' => false
                ];

                $rowData[] = $shifts['Pagi'] ? 'P' : '';
                $rowData[] = $shifts['Siang'] ? 'S' : '';
                $rowData[] = $shifts['Malam'] ? 'M' : '';

                $currentDate->addDay();
            }
            $exportCollection->push($rowData);
        }

        return $exportCollection;
    }

    public function headings(): array
    {
        // Headings are handled dynamically within the collection method.
        // This method must still return an array, so return a placeholder.
        return [''];
    }

    public function map($row): array
    {
        // The collection method builds the rows directly, so this acts as a passthrough.
        return $row;
    }

    public function columnWidths(): array
    {
        $widths = ['A' => 15]; // Width for "Nama Staff" column

        // Each day has 3 shift columns
        for ($i = 0; $i < $this->daysInMonth; $i++) {
            $colIndex = chr(ord('B') + ($i * 3));
            $widths[$colIndex] = 3;     // Pagi
            $widths[chr(ord($colIndex) + 1)] = 3; // Siang
            $widths[chr(ord($colIndex) + 2)] = 3; // Malam
        }
        return $widths;
    }

    public function styles(Worksheet $sheet)
    {
        // Apply styles for the main title (Row 1)
        $lastColChar = $sheet->getCellByColumnAndRow(1 + ($this->daysInMonth * 3), 1)->getColumn();
        $sheet->mergeCells('A1:' . $lastColChar . '1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => [Alignment::HORIZONTAL_CENTER, Alignment::VERTICAL_CENTER],
        ]);

        // Merge cells for Date Headers (Row 3, e.g., '1' over 'P S M')
        for ($i = 0; $i < $this->daysInMonth; $i++) {
            $startCol = chr(ord('B') + ($i * 3));
            $endCol = chr(ord($startCol) + 2);
            $sheet->mergeCells($startCol . '3:' . $endCol . '3'); // Row 3 for date numbers
        }

        // Apply style to the header block (rows 3 and 4)
        $headerRange = 'A3:' . $lastColChar . '4';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => [Alignment::HORIZONTAL_CENTER, Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']]],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFE0E0E0']] // Light grey background
        ]);
        // Ensure no extra borders on A3/A4 if they were auto-applied to whole range
        $sheet->getStyle('A3')->getBorders()->getRight()->setBorderStyle(Border::BORDER_NONE);
        $sheet->getStyle('A4')->getBorders()->getRight()->setBorderStyle(Border::BORDER_NONE);

        // Apply specific styling for shifts and Sundays within the data rows
        $startDataRow = 5; // Data starts from row 5 in Excel
        foreach ($this->schedulesData as $staffRowIndex => $staffEntry) {
            $rowNum = $startDataRow + $staffRowIndex; // Current Excel row number for staff data

            $currentDate = $this->startOfMonth->copy();
            for ($dayIndex = 0; $dayIndex < $this->daysInMonth; $dayIndex++) {
                $dateKey = $currentDate->format('Y-m-d');
                $shifts = $staffEntry['shifts'][$dateKey] ?? [ // Ensure shifts exist for this dateKey
                    'Pagi' => false, 'Siang' => false, 'Malam' => false, 'is_sunday' => false
                ];

                $colPagi = chr(ord('B') + ($dayIndex * 3));
                $colSiang = chr(ord($colPagi) + 1);
                $colMalam = chr(ord($colPagi) + 2);

                // Apply shift colors based on boolean true/false
                if ($shifts['Pagi']) {
                    $sheet->getStyle($colPagi . $rowNum)->getFill()
                          ->setFillType(Fill::FILL_SOLID)
                          ->getStartColor()->setARGB('FFD0E0FF'); // Light Blue (Pagi)
                }
                if ($shifts['Siang']) {
                    $sheet->getStyle($colSiang . $rowNum)->getFill()
                          ->setFillType(Fill::FILL_SOLID)
                          ->getStartColor()->setARGB('FFFFE0B2'); // Light Orange (Siang)
                }
                if ($shifts['Malam']) {
                    $sheet->getStyle($colMalam . $rowNum)->getFill()
                          ->setFillType(Fill::FILL_SOLID)
                          ->getStartColor()->setARGB('FFE1BEE7'); // Light Purple (Malam)
                }

                // Apply red font to Sunday day numbers in the header (Row 3)
                if ($shifts['is_sunday']) {
                    $sheet->getStyle(chr(ord('B') + ($dayIndex * 3)) . '3')->getFont()->getColor()->setARGB('FFFF0000'); // Date number in header
                }
                $currentDate->addDay();
            }
        }

        // Apply borders to all data cells below headers
        $dataRange = 'A' . $startDataRow . ':' . $lastColChar . ($startDataRow + $this->staffCount -1);
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']]],
        ]);

        // Ensure Staff Name column wraps text and aligns left
        $sheet->getStyle('A:A')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A:A')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
    }

    public function title(): string
    {
        return $this->monthName;
    }
}