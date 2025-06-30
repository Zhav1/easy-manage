<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
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

class QualityInspectionController extends Controller
{
    private $formModels = [
        'hand-hygiene' => HandHygieneForm::class,
        'apd' => ApdForm::class,
        'identifikasi' => IdentifikasiPasienForm::class,
        'wtri' => WtriForm::class,
        'kritis-lab' => KritisLabForm::class,
        'fornas' => FornasForm::class,
        'visite' => VisiteForm::class,
        'jatuh' => JatuhForm::class,
        'cp' => CpForm::class,
        'kepuasan' => KepuasanForm::class,
        'krk' => KrkForm::class,
        'poe' => PoeForm::class,
        'sc' => ScForm::class,
    ];

    /**
     * @return string
     */
    private function getCurrentWeekStartDate()
    {
        return Carbon::now()->startOfWeek(Carbon::MONDAY)->format('Y-m-d');
    }

    /**
     * @param string $formType
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentWeekForm(string $formType)
    {
        if (!isset($this->formModels[$formType])) {
            return response()->json(['message' => 'Invalid form type'], 404);
        }

        $model = $this->formModels[$formType];
        $weekStartDate = $this->getCurrentWeekStartDate();

        $formData = $model::where('week_start_date', $weekStartDate)->first();

        if (!$formData) {
            return response()->json([
                'week_start_date' => $weekStartDate,
                'data' => []
            ]);
        }

        return response()->json($formData);
    }

    /**
     * @param Request $request
     * @param string $formType
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitForm(Request $request, string $formType)
    {
        if (!isset($this->formModels[$formType])) {
            return response()->json(['message' => 'Invalid form type'], 404);
        }

        $model = $this->formModels[$formType];

        // Determine the week_start_date from request or default to current week
        $weekStartDate = $request->input('week_start_date', $this->getCurrentWeekStartDate());

        // Basic validation for the 'data' field
        $validator = Validator::make($request->all(), [
            'data' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $formData = $model::updateOrCreate(
                ['week_start_date' => $weekStartDate],
                ['data' => $request->input('data')]
            );

            if (auth()->check()) {
                $formData->user_id = auth()->id();
                $formData->save();
            }

            return response()->json(['message' => 'Form data saved successfully!', 'data' => $formData], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error saving form data: ' . $e->getMessage()], 500);
        }
    }

    /**
     * @param string $formType
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFormHistory(string $formType, Request $request)
    {
        if (!isset($this->formModels[$formType])) {
            return response()->json(['message' => 'Invalid form type'], 404);
        }

        $model = $this->formModels[$formType];

        $query = $model::query();

        // Optional: Filter history by a specific week if requested (used by auto-submit logic)
        if ($request->has('week_start_date')) {
            $query->where('week_start_date', $request->input('week_start_date'));
        }

        $history = $query->orderBy('week_start_date', 'desc')->get();

        return response()->json($history);
    }
}