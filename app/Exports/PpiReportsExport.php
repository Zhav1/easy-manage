<?php

namespace App\Exports;

use App\Models\CvcInsertion;
use App\Models\CvcMaintenance;
use App\Models\CvcInfection;
use App\Models\NeedlestickReport;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PpiReportsExport implements FromView, ShouldAutoSize
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function view(): View
    {
        $user = Auth::user();

        // Fetch and filter all data collections
        $insertionsQuery = CvcInsertion::where('user_id', $user->id);
        if ($this->startDate) $insertionsQuery->where('insertion_date', '>=', $this->startDate);
        if ($this->endDate) $insertionsQuery->where('insertion_date', '<=', $this->endDate);
        
        $maintenancesQuery = CvcMaintenance::where('user_id', $user->id);
        if ($this->startDate) $maintenancesQuery->where('maintenance_date', '>=', $this->startDate);
        if ($this->endDate) $maintenancesQuery->where('maintenance_date', '<=', $this->endDate);

        $infectionsQuery = CvcInfection::where('user_id', $user->id);
        if ($this->startDate) $infectionsQuery->where('infection_diagnosis_date', '>=', $this->startDate);
        if ($this->endDate) $infectionsQuery->where('infection_diagnosis_date', '<=', $this->endDate);

        $needlesticksQuery = NeedlestickReport::where('user_id', $user->id);
        if ($this->startDate) $needlesticksQuery->where('incident_date', '>=', $this->startDate);
        if ($this->endDate) $needlesticksQuery->where('incident_date', '<=', $this->endDate);
        
        // Prepare data for the Blade view
        $data = [
            'report_start_date' => $this->startDate ? Carbon::parse($this->startDate)->format('d F Y') : 'Semua Waktu',
            'report_end_date' => $this->endDate ? Carbon::parse($this->endDate)->format('d F Y') : '',
            'insertions' => $insertionsQuery->orderBy('insertion_date', 'asc')->get(),
            'maintenances' => $maintenancesQuery->orderBy('maintenance_date', 'asc')->get(),
            'infections' => $infectionsQuery->orderBy('infection_diagnosis_date', 'asc')->get(),
            'needlesticks' => $needlesticksQuery->orderBy('incident_date', 'asc')->get(),
        ];
        
        return view('reports.ppi_reports_excel', $data);
    }
}