<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Auth;
use App\Models\HandHygieneForm;
use App\Models\ApdForm;
use App\Models\IdentifikasiPasienForm;
use App\Models\WtriForm;
use App\Models\KritisLabForm;
use App\Models\FornasForm;
use App\Models\VisiteForm;
use App\Models\JatuhForm;
use App\Models\CpForm;
use App\Models\KepuasanForm;
use App\Models\KrkForm;
use App\Models\PoeForm;
use App\Models\ScForm;

class QualityIndicatorsExport implements FromView, ShouldAutoSize
{
    // This array maps slugs to their corresponding Eloquent models
    private $formModels = [
        'hand-hygiene' => HandHygieneForm::class, 'apd' => ApdForm::class, 'identifikasi' => IdentifikasiPasienForm::class, 'wtri' => WtriForm::class, 'kritis-lab' => KritisLabForm::class, 'fornas' => FornasForm::class, 'visite' => VisiteForm::class, 'jatuh' => JatuhForm::class, 'cp' => CpForm::class, 'kepuasan' => KepuasanForm::class, 'krk' => KrkForm::class, 'poe' => PoeForm::class, 'sc' => ScForm::class,
    ];

    /**
    * This function gathers all data from all 13 forms and passes it to the Excel view.
    */
    public function view(): View
    {
        $user = Auth::user();
        $reportData = [];

        foreach ($this->formModels as $formType => $modelClass) {
            $records = $modelClass::where('user_id', $user->id)->get();
            // Aggregate all 'entries' from all historical records for this form type
            $allEntries = $records->pluck('data.entries')->flatten(1)->filter()->values();
            
            // Use the latest record's top-level data (like unit_kerja, judul_cp)
            $latestRecordData = $records->sortByDesc('created_at')->first()->data ?? ['entries' => []];
            $latestRecordData['entries'] = $allEntries->toArray();
            
            $reportData[$formType] = [
                'name' => ucwords(str_replace('-', ' ', $formType)),
                'data' => $latestRecordData,
            ];
        }

        // The key here is passing the correctly named variable: 'reportData'
        return view('reports.quality_indicators_excel', [
            'reportData' => $reportData
        ]);
    }
}