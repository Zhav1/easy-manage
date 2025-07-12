<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;
use App\Models\CvcInsertion;
use App\Models\CvcMaintenance;
use App\Models\CvcInfection;
use App\Models\NeedlestickReport;
use Carbon\Carbon;

class PpiReportsExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = Auth::user();
        $allPpiActivities = collect();

        // Fetch and map CVC Insertion forms
        $insertions = CvcInsertion::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'form_type' => 'Bundle Insersi CVC',
                    'activity_date' => $item->insertion_date,
                    'patient_name' => $item->patient_name,
                    'medical_record_number' => $item->medical_record_number,
                    'details' => "Lokasi: {$item->insertion_location}, Operator: {$item->operator_name}, Kepatuhan: {$item->compliance_percentage}%",
                    'submitted_at' => $item->created_at,
                ];
            });
        $allPpiActivities = $allPpiActivities->concat($insertions);

        // Fetch and map CVC Maintenance forms
        $maintenances = CvcMaintenance::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'form_type' => 'Bundle Maintenance CVC',
                    'activity_date' => $item->maintenance_date,
                    'patient_name' => $item->patient_name,
                    'medical_record_number' => $item->medical_record_number,
                    'details' => "Lokasi: {$item->maintenance_location}, Perawat: {$item->nurse_name}, Hari Terpasang: {$item->days_inserted}, Kepatuhan: {$item->compliance_percentage}%",
                    'submitted_at' => $item->created_at,
                ];
            });
        $allPpiActivities = $allPpiActivities->concat($maintenances);

        // Fetch and map CVC Infection reports
        $infections = CvcInfection::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'form_type' => 'Laporan Infeksi CVC',
                    'activity_date' => $item->infection_diagnosis_date,
                    'patient_name' => $item->patient_name,
                    'medical_record_number' => $item->medical_record_number,
                    'details' => "Jenis Infeksi: {$item->infection_type}, Mikroorganisme: {$item->microorganism}, Gejala: {$item->clinical_symptoms}",
                    'submitted_at' => $item->created_at,
                ];
            });
        $allPpiActivities = $allPpiActivities->concat($infections);

        // Fetch and map Needlestick Reports
        $needlesticks = NeedlestickReport::where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($item) {
                // Combine immediate actions array into a string
                $immediateActionsStr = implode('; ', $item->immediate_actions ?? []);
                return [
                    'form_type' => 'Laporan Tertusuk Jarum',
                    'activity_date' => $item->incident_date,
                    'patient_name' => $item->injured_person_name, // Name of the injured person
                    'medical_record_number' => 'N/A', // Not typically in needlestick reports
                    'details' => "Lokasi: {$item->location}, Jabatan: {$item->injured_person_position}, Usia: {$item->injured_person_age}, Gender: {$item->injured_person_gender}, Deskripsi: {$item->incident_description}, Tindakan: {$immediateActionsStr}",
                    'submitted_at' => $item->created_at,
                ];
            });
        $allPpiActivities = $allPpiActivities->concat($needlesticks);

        return $allPpiActivities->sortBy('submitted_at')->values(); // Sort all combined activities chronologically
    }

    /**
     * Define the column headings for the Excel file.
     * @return array
     */
    public function headings(): array
    {
        return [
            'Jenis Form',
            'Tanggal Aktivitas',
            'Pasien/Nama Terluka',
            'No. Rekam Medis',
            'Detail Singkat',
            'Waktu Input',
        ];
    }

    /**
     * Map the data row to the columns.
     * @param mixed $activity
     * @return array
     */
    public function map($activity): array
    {
        return [
            $activity['form_type'],
            Carbon::parse($activity['activity_date'])->format('d-m-Y'),
            $activity['patient_name'],
            $activity['medical_record_number'],
            $activity['details'],
            Carbon::parse($activity['submitted_at'])->format('d-m-Y H:i:s'),
        ];
    }
}