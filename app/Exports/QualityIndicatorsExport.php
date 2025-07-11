<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Http\Controllers\QualityInspectionController; // Import the controller
use Carbon\Carbon;
use Carbon\CarbonInterface; // Optional: If you want to fix the PHP6606 warning from previous steps

class QualityIndicatorsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $qualityController = new QualityInspectionController();
        // Call the method that gets all combined quality data
        return $qualityController->getAllQualityFormDataForReport();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Slug Formulir',
            'Nama Formulir',
            'Tanggal Aktivitas',
            'Pasien/Entitas',
            'Skor/Kepatuhan',
            'Catatan',
            'Detail Formulir', // New column for combined specific details
            'Waktu Input',
        ];
    }

    /**
     * @param mixed $entry
     * @return array
     */
    public function map($entry): array
    {
        return [
            (isset($entry['form_type_slug']) ? $entry['form_type_slug'] : 'N/A'), // Changed ?? to isset() ? :
            (isset($entry['form_name']) ? $entry['form_name'] : 'N/A'), // Changed ?? to isset() ? :
            (isset($entry['activity_date']) ? Carbon::parse($entry['activity_date'])->format('Y-m-d') : '-'), // Changed ?? to isset() ? :
            (isset($entry['patient_entity']) ? $entry['patient_entity'] : 'N/A'), // Changed ?? to isset() ? :
            (isset($entry['score']) ? $entry['score'] : 'N/A'), // Changed ?? to isset() ? :
            (isset($entry['notes']) ? $entry['notes'] : 'Tidak ada'), // Changed ?? to isset() ? :
            (isset($entry['details_summary']) ? $entry['details_summary'] : 'Tidak ada detail'), // Changed ?? to isset() ? :
            (isset($entry['submitted_at']) ? Carbon::parse($entry['submitted_at'])->format('Y-m-d H:i:s') : '-'), // Changed ?? to isset() ? :
        ];
    }
}