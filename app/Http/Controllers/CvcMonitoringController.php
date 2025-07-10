<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\CvcInsertion;
use App\Models\CvcMaintenance;
use App\Models\CvcInfection;
use App\Models\NeedlestickReport;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CvcMonitoringController extends Controller
{
    /**
     * Manually parse raw HTTP request body for PUT/PATCH methods,
     * especially when the Content-Type is multipart/form-data.
     * PHP's $_POST and $_FILES are typically only populated for POST requests.
     * This method populates $request->request (for non-file fields) and $request->files (for files).
     *
     * @param Request $request The current HTTP request instance.
     * @return array The parsed data (excluding files).
     */
    private function parseAndPopulatePutPatchRequest(Request $request): array
    {
        $data = [];
        $files = [];
        $contentType = $request->header('Content-Type', '');
        $rawBody = $request->getContent();

        // Handle multipart/form-data
        if (str_starts_with($contentType, 'multipart/form-data')) {
            $parts = explode('boundary=', $contentType);
            if (count($parts) < 2) {
                return []; // Invalid or missing boundary for multipart data
            }
            $boundary = '--' . $parts[1];

            $allParts = array_slice(explode($boundary, $rawBody), 1);
            foreach ($allParts as $part) {
                if (empty(trim($part)) || trim($part) === '--') continue;

                list($rawHeaders, $content) = explode("\r\n\r\n", $part, 2);
                $rawHeaders = explode("\r\n", $rawHeaders);
                $content = substr($content, 0, strlen($content) - 2);

                $headers = [];
                foreach ($rawHeaders as $header) {
                    if (str_contains($header, ':')) {
                        list($key, $value) = explode(':', $header, 2);
                        $headers[trim(strtolower($key))] = trim($value);
                    }
                }

                if (isset($headers['content-disposition'])) {
                    $disposition = explode(';', $headers['content-disposition']);
                    $name = '';
                    $filename = '';
                    foreach ($disposition as $dispPart) {
                        if (str_contains($dispPart, 'name=')) {
                            $name = trim(explode('name=', $dispPart)[1], '\"');
                        }
                        if (str_contains($dispPart, 'filename=')) {
                            $filename = trim(explode('filename=', $dispPart)[1], '\"');
                        }
                    }

                    if (preg_match('/^(.+)\[(.+)\](?:\[(.+)\])?$/', $name, $matches)) {
                        $baseName = $matches[1];
                        $firstKey = $matches[2];
                        $secondKey = $matches[3] ?? null;

                        if ($filename) {
                            $tmpFilePath = sys_get_temp_dir() . '/' . uniqid('laravel_upload_');
                            file_put_contents($tmpFilePath, $content);

                            $fileInstance = new UploadedFile(
                                $tmpFilePath,
                                $filename,
                                $headers['content-type'] ?? null,
                                UPLOAD_ERR_OK,
                                true
                            );

                            if ($secondKey !== null) {
                                $files[$baseName][$firstKey][$secondKey] = $fileInstance;
                            } else {
                                $files[$baseName][$firstKey] = $fileInstance;
                            }
                        } else {
                            if ($secondKey !== null) {
                                $data[$baseName][$firstKey][$secondKey] = $content;
                            } else {
                                if (str_ends_with($firstKey, '[]')) {
                                    $trueFirstKey = rtrim($firstKey, '[]');
                                    if (!isset($data[$baseName][$trueFirstKey])) {
                                        $data[$baseName][$trueFirstKey] = [];
                                    }
                                    $data[$baseName][$trueFirstKey][] = $content;
                                } else {
                                    $data[$baseName][$firstKey] = $content;
                                }
                            }
                        }
                    } else {
                        if ($filename) {
                            $tmpFilePath = sys_get_temp_dir() . '/' . uniqid('laravel_upload_');
                            file_put_contents($tmpFilePath, $content);
                            $files[$name] = new UploadedFile(
                                $tmpFilePath,
                                $filename,
                                $headers['content-type'] ?? null,
                                UPLOAD_ERR_OK,
                                true
                            );
                        } else {
                            $data[$name] = $content;
                        }
                    }
                }
            }
        } elseif ($rawBody && str_contains($contentType, 'application/x-www-form-urlencoded')) {
            parse_str($rawBody, $data);
        } elseif ($rawBody && str_contains($contentType, 'application/json')) {
            $data = json_decode($rawBody, true);
        }

        if (isset($data['_method'])) {
            $request->setMethod($data['_method']);
            unset($data['_method']);
        }

        $request->request->add($data);
        $request->files->add($files);

        return $data;
    }

    // --- CVC Insertion Form Methods ---

    public function getInsertionForms(Request $request)
    {
        $user = Auth::user();
        $forms = CvcInsertion::where('user_id', $user->id)
                               ->orderBy('created_at', 'desc')
                               ->paginate(10);
        return response()->json($forms);
    }

    public function showInsertionForm(CvcInsertion $form)
    {
        if ($form->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($form);
    }

    public function storeInsertionForm(Request $request)
    {
        try {
            $validated = $request->validate([
                'patient_name' => 'required|string|max:255',
                'medical_record_number' => 'nullable|string|max:255',
                'insertion_date' => 'required|date',
                'insertion_location' => 'required|string|max:255',
                'operator_name' => 'nullable|string|max:255',
                'elements_data' => 'required|array',
                'elements_data.*.description' => 'required|string|max:255',
                'elements_data.*.detail' => 'nullable|string|max:500',
                'elements_data.*.status' => 'required|in:Ya,Tidak,Tidak Dilakukan',
                'elements_data.*.notes' => 'nullable|string|max:500',
                'elements_data.*.photo' => 'nullable|image|max:2048',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }

        $compliancePercentage = $this->calculateCompliance($validated['elements_data']);
        $validated['compliance_percentage'] = $compliancePercentage;
        $validated['user_id'] = Auth::id();

        $elementsToSave = [];
        foreach ($validated['elements_data'] as $index => $element) {
            $currentElementData = $element;
            if ($request->hasFile("elements_data.{$index}.photo")) {
                $path = $request->file("elements_data.{$index}.photo")->store('insertion_photos', 'public');
                $currentElementData['photo_path'] = Storage::url($path);
            } else {
                $currentElementData['photo_path'] = null;
            }
            unset($currentElementData['photo']);
            $elementsToSave[] = $currentElementData;
        }
        $validated['elements_data'] = $elementsToSave;

        $form = CvcInsertion::create($validated);
        return response()->json(['message' => 'Insertion form submitted successfully', 'form' => $form], 201);
    }

    public function updateInsertionForm(Request $request, CvcInsertion $form)
    {
        $this->parseAndPopulatePutPatchRequest($request);

        if ($form->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $validated = $request->validate([
                'patient_name' => 'sometimes|string|max:255',
                'medical_record_number' => 'nullable|string|max:255',
                'insertion_date' => 'sometimes|date',
                'insertion_location' => 'sometimes|string|max:255',
                'operator_name' => 'nullable|string|max:255',
                'elements_data' => 'sometimes|array',
                'elements_data.*.description' => 'required|string|max:255',
                'elements_data.*.detail' => 'nullable|string|max:500',
                'elements_data.*.status' => 'required|in:Ya,Tidak,Tidak Dilakukan',
                'elements_data.*.notes' => 'nullable|string|max:500',
                'elements_data.*.photo' => 'nullable|image|max:2048',
                'elements_data.*.photo_path' => 'nullable|string',
                'elements_data.*.photo_path_removed' => 'nullable|boolean',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }

        if (isset($validated['elements_data'])) {
            $compliancePercentage = $this->calculateCompliance($validated['elements_data']);
            $validated['compliance_percentage'] = $compliancePercentage;

            $existingElementsData = $form->elements_data;

            $elementsToUpdate = [];
            foreach ($validated['elements_data'] as $index => $element) {
                $currentElementData = $element;
                $currentElementData['description'] = $element['description'] ?? ($existingElementsData[$index]['description'] ?? null);
                $currentElementData['detail'] = $element['detail'] ?? ($existingElementsData[$index]['detail'] ?? null);

                if ($request->hasFile("elements_data.{$index}.photo")) {
                    if (isset($existingElementsData[$index]['photo_path']) && $existingElementsData[$index]['photo_path']) {
                        Storage::delete(str_replace('/storage', 'public', $existingElementsData[$index]['photo_path']));
                    }
                    $path = $request->file("elements_data.{$index}.photo")->store('insertion_photos', 'public');
                    $currentElementData['photo_path'] = Storage::url($path);
                } elseif (isset($element['photo_path_removed']) && $element['photo_path_removed'] === true) {
                    if (isset($existingElementsData[$index]['photo_path']) && $existingElementsData[$index]['photo_path']) {
                        Storage::delete(str_replace('/storage', 'public', $existingElementsData[$index]['photo_path']));
                    }
                    $currentElementData['photo_path'] = null;
                } else if (isset($existingElementsData[$index]['photo_path'])) {
                    $currentElementData['photo_path'] = $existingElementsData[$index]['photo_path'];
                } else {
                    $currentElementData['photo_path'] = null;
                }
                unset($currentElementData['photo_path_removed']);
                unset($currentElementData['photo']);
                $elementsToUpdate[] = $currentElementData;
            }
            $validated['elements_data'] = $elementsToUpdate;
        }

        $form->fill($validated);
        $form->save();
        $form->refresh();

        return response()->json(['message' => 'Insertion form updated successfully', 'form' => $form], 200);
    }

    public function deleteInsertionForm(CvcInsertion $form)
    {
        if ($form->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        foreach ($form->elements_data as $element) {
            if (isset($element['photo_path']) && $element['photo_path']) {
                Storage::delete(str_replace('/storage', 'public', $element['photo_path']));
            }
        }
        $form->delete();
        return response()->noContent();
    }

    // --- CVC Maintenance Form Methods ---

    public function getMaintenanceForms(Request $request)
    {
        $user = Auth::user();
        $forms = CvcMaintenance::where('user_id', $user->id)
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(10);
        return response()->json($forms);
    }

    public function showMaintenanceForm(CvcMaintenance $form)
    {
        if ($form->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($form);
    }

    public function storeMaintenanceForm(Request $request)
    {
        try {
            $validated = $request->validate([
                'patient_name' => 'required|string|max:255',
                'medical_record_number' => 'nullable|string|max:255',
                'maintenance_date' => 'required|date',
                'maintenance_location' => 'required|string|max:255', // Added
                'days_inserted' => 'required|integer|min:0',        // Added
                'nurse_name' => 'nullable|string|max:255',           // Added/Confirmed
                'elements_data' => 'required|array',
                'elements_data.*.description' => 'required|string|max:255',
                'elements_data.*.detail' => 'nullable|string|max:500',
                'elements_data.*.status' => 'required|in:Ya,Tidak,Tidak Dilakukan',
                'elements_data.*.notes' => 'nullable|string|max:500',
                'elements_data.*.photo' => 'nullable|image|max:2048',
                'elements_data.*.photo_path' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }

        $compliancePercentage = $this->calculateCompliance($validated['elements_data']);
        $validated['compliance_percentage'] = $compliancePercentage;
        $validated['user_id'] = Auth::id();

        $elementsToSave = [];
        foreach ($validated['elements_data'] as $index => $element) {
            $currentElementData = $element;
            if ($request->hasFile("elements_data.{$index}.photo")) {
                $path = $request->file("elements_data.{$index}.photo")->store('maintenance_photos', 'public');
                $currentElementData['photo_path'] = Storage::url($path);
            } else {
                $currentElementData['photo_path'] = null;
            }
            unset($currentElementData['photo']);
            $elementsToSave[] = $currentElementData;
        }
        $validated['elements_data'] = $elementsToSave;

        $form = CvcMaintenance::create($validated);
        return response()->json(['message' => 'Maintenance form submitted successfully', 'form' => $form], 201);
    }

    public function updateMaintenanceForm(Request $request, CvcMaintenance $form)
    {
        $this->parseAndPopulatePutPatchRequest($request);

        if ($form->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $validated = $request->validate([
                'patient_name' => 'sometimes|string|max:255',
                'medical_record_number' => 'nullable|string|max:255',
                'maintenance_date' => 'sometimes|date',
                'maintenance_location' => 'sometimes|string|max:255', // Added
                'days_inserted' => 'sometimes|integer|min:0',        // Added
                'nurse_name' => 'nullable|string|max:255',           // Added/Confirmed
                'elements_data' => 'sometimes|array',
                'elements_data.*.description' => 'required|string|max:255',
                'elements_data.*.detail' => 'nullable|string|max:500',
                'elements_data.*.status' => 'required|in:Ya,Tidak,Tidak Dilakukan',
                'elements_data.*.notes' => 'nullable|string|max:500',
                'elements_data.*.photo' => 'nullable|image|max:2048',
                'elements_data.*.photo_path' => 'nullable|string',
                'elements_data.*.photo_path_removed' => 'nullable|boolean',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }

        if (isset($validated['elements_data'])) {
            $compliancePercentage = $this->calculateCompliance($validated['elements_data']);
            $validated['compliance_percentage'] = $compliancePercentage;

            $existingElementsData = $form->elements_data;

            $elementsToUpdate = [];
            foreach ($validated['elements_data'] as $index => $element) {
                $currentElementData = $element;
                $currentElementData['description'] = $element['description'] ?? ($existingElementsData[$index]['description'] ?? null);
                $currentElementData['detail'] = $element['detail'] ?? ($existingElementsData[$index]['detail'] ?? null);

                if ($request->hasFile("elements_data.{$index}.photo")) {
                    if (isset($existingElementsData[$index]['photo_path']) && $existingElementsData[$index]['photo_path']) {
                        Storage::delete(str_replace('/storage', 'public', $existingElementsData[$index]['photo_path']));
                    }
                    $path = $request->file("elements_data.{$index}.photo")->store('maintenance_photos', 'public');
                    $currentElementData['photo_path'] = Storage::url($path);
                } elseif (isset($element['photo_path_removed']) && $element['photo_path_removed'] === true) {
                    if (isset($existingElementsData[$index]['photo_path']) && $existingElementsData[$index]['photo_path']) {
                        Storage::delete(str_replace('/storage', 'public', $existingElementsData[$index]['photo_path']));
                    }
                    $currentElementData['photo_path'] = null;
                } else if (isset($existingElementsData[$index]['photo_path'])) {
                    $currentElementData['photo_path'] = $existingElementsData[$index]['photo_path'];
                } else {
                    $currentElementData['photo_path'] = null;
                }
                unset($currentElementData['photo_path_removed']);
                unset($currentElementData['photo']);
                $elementsToUpdate[] = $currentElementData;
            }
            $validated['elements_data'] = $elementsToUpdate;
        }

        $form->fill($validated);
        $form->save();
        $form->refresh();

        return response()->json(['message' => 'Maintenance form updated successfully', 'form' => $form], 200);
    }

    public function deleteMaintenanceForm(CvcMaintenance $form)
    {
        if ($form->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        foreach ($form->elements_data as $element) {
            if (isset($element['photo_path']) && $element['photo_path']) {
                Storage::delete(str_replace('/storage', 'public', $element['photo_path']));
            }
        }
        $form->delete();
        return response()->noContent();
    }

    // --- CVC Infection Report Methods ---

    public function getInfectionReports(Request $request)
    {
        $user = Auth::user();
        $reports = CvcInfection::where('user_id', $user->id)
                                 ->orderBy('created_at', 'desc')
                                 ->paginate(10);
        return response()->json($reports);
    }

    public function showInfectionReport(CvcInfection $report)
    {
        if ($report->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($report);
    }

    public function storeInfectionReport(Request $request)
    {
        try {
            $validated = $request->validate([
                'patient_name' => 'required|string|max:255',
                'medical_record_number' => 'nullable|string|max:255',
                'insertion_date' => 'nullable|date',
                'insertion_location' => 'nullable|string|max:255',
                'days_inserted' => 'nullable|integer|min:0', // Added
                'infection_diagnosis_date' => 'required|date',
                'infection_type' => 'required|in:CLABSI (Central Line Associated Bloodstream Infection),Exit Site Infection,Tunnel Infection,Pocket Infection',
                'clinical_symptoms' => 'nullable|string|max:1000',
                'microorganism' => 'nullable|string|max:255',
                'management' => 'nullable|string|max:1000',
                'photo' => 'nullable|image|max:2048',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }

        $validated['user_id'] = Auth::id();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('infection_reports', 'public');
            $validated['photo_path'] = Storage::url($path);
        } else {
            $validated['photo_path'] = null;
        }
        unset($validated['photo']);

        $report = CvcInfection::create($validated);
        return response()->json(['message' => 'Infection report submitted successfully', 'report' => $report], 201);
    }

    public function updateInfectionReport(Request $request, CvcInfection $report)
    {
        $this->parseAndPopulatePutPatchRequest($request);

        if ($report->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $validated = $request->validate([
                'patient_name' => 'sometimes|string|max:255',
                'medical_record_number' => 'nullable|string|max:255',
                'insertion_date' => 'nullable|date',
                'insertion_location' => 'nullable|string|max:255',
                'days_inserted' => 'sometimes|integer|min:0', // Added
                'infection_diagnosis_date' => 'sometimes|date',
                'infection_type' => 'sometimes|in:CLABSI (Central Line Associated Bloodstream Infection),Exit Site Infection,Tunnel Infection,Pocket Infection',
                'clinical_symptoms' => 'nullable|string|max:1000',
                'microorganism' => 'nullable|string|max:255',
                'management' => 'nullable|string|max:1000',
                'photo' => 'nullable|image|max:2048',
                'status' => 'sometimes|in:Aktif,Selesai',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }

        if ($request->hasFile('photo')) {
            if (isset($report->photo_path) && $report->photo_path) {
                Storage::delete(str_replace('/storage', 'public', $report->photo_path));
            }
            $path = $request->file('photo')->store('infection_reports', 'public');
            $validated['photo_path'] = Storage::url($path);
        } elseif ($request->has('photo') && $request->input('photo') === '') {
            if (isset($report->photo_path) && $report->photo_path) {
                Storage::delete(str_replace('/storage', 'public', $report->photo_path));
            }
            $validated['photo_path'] = null;
        }
        unset($validated['photo']);

        $report->fill($validated);
        $report->save();
        $report->refresh();

        return response()->json(['message' => 'Infection report updated successfully', 'report' => $report], 200);
    }

    public function deleteInfectionReport(CvcInfection $report)
    {
        if ($report->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (isset($report->photo_path) && $report->photo_path) {
            Storage::delete(str_replace('/storage', 'public', $report->photo_path));
        }
        $report->delete();
        return response()->noContent();
    }

    // --- Needlestick Report Methods (No changes needed, as they align with previous logic) ---
    public function getNeedlestickReports(Request $request)
    {
        $user = Auth::user();
        $reports = NeedlestickReport::where('user_id', $user->id)
                                     ->orderBy('created_at', 'desc')
                                     ->paginate(10);
        return response()->json($reports);
    }

    public function showNeedlestickReport(NeedlestickReport $report)
    {
        if ($report->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($report);
    }

    public function storeNeedlestickReport(Request $request)
    {
        try {
            $validated = $request->validate([
                'incident_date' => 'required|date',
                'incident_time' => 'required',
                'location' => 'required|string|max:255',
                'department' => 'required|string|max:255',
                'injured_person_name' => 'required|string|max:255',
                'injured_person_position' => 'required|string|max:255',
                'injured_person_age' => 'required|integer|min:1',
                'injured_person_gender' => 'required|in:Laki-laki,Perempuan',
                'incident_description' => 'required|string|max:1000',
                'source_patient_status' => 'nullable|string|max:1000',
                'immediate_actions' => 'required|array',
                'immediate_actions.*' => 'string|max:255',
                'other_immediate_action' => 'nullable|string|max:255',
                'follow_up_actions' => 'required|string|max:1000',
                'photo' => 'nullable|image|max:2048',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }

        $validated['user_id'] = Auth::id();

        if (isset($validated['immediate_actions']) && in_array('Lainnya', $validated['immediate_actions']) && !empty($validated['other_immediate_action'])) {
            $otherIndex = array_search('Lainnya', $validated['immediate_actions']);
            if ($otherIndex !== false) {
                $validated['immediate_actions'][$otherIndex] = $validated['other_immediate_action'];
            }
        }
        unset($validated['other_immediate_action']);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('needlestick_reports', 'public');
            $validated['photo_path'] = Storage::url($path);
        } else {
            $validated['photo_path'] = null;
        }
        unset($validated['photo']);

        $report = NeedlestickReport::create($validated);
        return response()->json(['message' => 'Needlestick report submitted successfully', 'report' => $report], 201);
    }

    public function updateNeedlestickReport(Request $request, NeedlestickReport $report)
    {
        $this->parseAndPopulatePutPatchRequest($request);

        if ($report->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $validated = $request->validate([
                'incident_date' => 'sometimes|date',
                'incident_time' => 'sometimes',
                'location' => 'sometimes|string|max:255',
                'department' => 'sometimes|string|max:255',
                'injured_person_name' => 'sometimes|string|max:255',
                'injured_person_position' => 'sometimes|string|max:255',
                'injured_person_age' => 'sometimes|integer|min:1',
                'injured_person_gender' => 'sometimes|in:Laki-laki,Perempuan',
                'incident_description' => 'sometimes|string|max:1000',
                'source_patient_status' => 'nullable|string|max:1000',
                'immediate_actions' => 'sometimes|array',
                'immediate_actions.*' => 'string|max:255',
                'other_immediate_action' => 'nullable|string|max:255',
                'follow_up_actions' => 'sometimes|string|max:1000',
                'photo' => 'nullable|image|max:2048',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }

        if (isset($validated['immediate_actions'])) {
            if (in_array('Lainnya', $validated['immediate_actions']) && !empty($validated['other_immediate_action'])) {
                $otherIndex = array_search('Lainnya', $validated['immediate_actions']);
                if ($otherIndex !== false) {
                    $validated['immediate_actions'][$otherIndex] = $validated['other_immediate_action'];
                }
            }
        }
        unset($validated['other_immediate_action']);

        if ($request->hasFile('photo')) {
            if (isset($report->photo_path) && $report->photo_path) {
                Storage::delete(str_replace('/storage', 'public', $report->photo_path));
            }
            $path = $request->file('photo')->store('needlestick_reports', 'public');
            $validated['photo_path'] = Storage::url($path);
        } else if ($request->has('photo') && $request->input('photo') === '') {
            if (isset($report->photo_path) && $report->photo_path) {
                Storage::delete(str_replace('/storage', 'public', $report->photo_path));
            }
            $validated['photo_path'] = null;
        }
        unset($validated['photo']);

        $report->fill($validated);
        $report->save();
        $report->refresh();

        return response()->json(['message' => 'Needlestick report updated successfully', 'report' => $report], 200);
    }

    public function deleteNeedlestickReport(NeedlestickReport $report)
    {
        if ($report->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (isset($report->photo_path) && $report->photo_path) {
            Storage::delete(str_replace('/storage', 'public', $report->photo_path));
        }
        $report->delete();
        return response()->noContent();
    }

    // --- Helper for Compliance Calculation ---
    private function calculateCompliance(array $elementsData): int
    {
        $observedElements = 0;
        $compliantElements = 0;

        foreach ($elementsData as $element) {
            if (isset($element['status'])) {
                if ($element['status'] === 'Ya') {
                    $compliantElements++;
                    $observedElements++;
                } elseif ($element['status'] === 'Tidak') {
                    $observedElements++;
                }
            }
        }

        if ($observedElements === 0) {
            return 0;
        }

        return (int) round(($compliantElements / $observedElements) * 100);
    }

    // --- Analytics Methods ---
    public function getOverallStats()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $last30Days = Carbon::now()->subDays(30);
        $last6Months = Carbon::now()->subMonths(6);

        $totalInsertionsToday = CvcInsertion::where('user_id', $user->id)
                                            ->whereDate('created_at', $today) 
                                            ->count();
        $totalMaintenancesToday = CvcMaintenance::where('user_id', $user->id)
                                            ->whereDate('created_at', $today) 
                                            ->count();
        $totalActiveInfections = CvcInfection::where('user_id', $user->id)
                                            ->where('status', 'Aktif')
                                            ->count();
        $totalInfectionsToday = CvcInfection::where('user_id', $user->id)
                                            ->whereDate('created_at', $today) 
                                            ->count();
        $totalNeedlestickCasesToday = NeedlestickReport::where('user_id', $user->id)
                                            ->whereDate('created_at', $today) 
                                            ->count();

        // Compliance Rates for last 30 days
        $totalInsertionsLast30Days = CvcInsertion::where('user_id', $user->id)
                                                ->where('insertion_date', '>=', $last30Days)
                                                ->count();
        $compliantInsertionsLast30Days = CvcInsertion::where('user_id', $user->id)
                                                    ->where('insertion_date', '>=', $last30Days)
                                                    ->where('compliance_percentage', 100)
                                                    ->count();
        $insertionComplianceRate = ($totalInsertionsLast30Days > 0) ? round(($compliantInsertionsLast30Days / $totalInsertionsLast30Days) * 100, 2) : 0;

        $totalMaintenancesLast30Days = CvcMaintenance::where('user_id', $user->id)
                                                      ->where('maintenance_date', '>=', $last30Days)
                                                      ->count();
        $compliantMaintenancesLast30Days = CvcMaintenance::where('user_id', $user->id)
                                                        ->where('maintenance_date', '>=', $last30Days)
                                                        ->where('compliance_percentage', 100)
                                                        ->count();
        $maintenanceComplianceRate = ($totalMaintenancesLast30Days > 0) ? round(($compliantMaintenancesLast30Days / $totalMaintenancesLast30Days) * 100, 2) : 0;

        $totalNeedlestickLast30Days = NeedlestickReport::where('user_id', $user->id)
                                                      ->where('incident_date', '>=', $last30Days)
                                                      ->count();


        $infectionTrend = CvcInfection::where('user_id', $user->id)
            ->where('infection_diagnosis_date', '>=', $last6Months)
            ->selectRaw('DATE_FORMAT(infection_diagnosis_date, "%Y-%m") as month, count(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $infectionByLocation = CvcInfection::where('user_id', $user->id)
            ->selectRaw('insertion_location, count(*) as count')
            ->groupBy('insertion_location')
            ->get();

        $infectionByMicroorganism = CvcInfection::where('user_id', $user->id)
            ->whereNotNull('microorganism')
            ->selectRaw('microorganism, count(*) as count')
            ->groupBy('microorganism')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        $needlestickTrend = NeedlestickReport::where('user_id', $user->id)
            ->where('incident_date', '>=', $last6Months)
            ->selectRaw('DATE_FORMAT(incident_date, "%Y-%m") as month, count(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $needlestickByDepartment = NeedlestickReport::where('user_id', $user->id)
            ->selectRaw('department, count(*) as count')
            ->groupBy('department')
            ->orderByDesc('count')
            ->get();

        $needlestickByPosition = NeedlestickReport::where('user_id', $user->id)
            ->selectRaw('injured_person_position, count(*) as count')
            ->groupBy('injured_person_position')
            ->orderByDesc('count')
            ->get();


        // Placeholder for CLABSI Rate.
        // To calculate accurately, you'd need CVC device-days data which isn't currently tracked.
        // It's (number of CLABSIs / total CVC device-days) * 1000.
        $clabsiRate = 0.0; // Placeholder

        return response()->json([
            'total_insertions_today' => $totalInsertionsToday,
            'total_maintenances_today' => $totalMaintenancesToday,
            'total_active_infections_overall' => $totalActiveInfections,
            'total_infections_today' => $totalInfectionsToday,
            'total_needlestick_cases_today' => $totalNeedlestickCasesToday,
            'insertion_compliance_rate' => $insertionComplianceRate,
            'maintenance_compliance_rate' => $maintenanceComplianceRate,
            'needlestick_rate_30_days' => $totalNeedlestickLast30Days,
            'clabsi_rate' => $clabsiRate,
            'infection_trend' => $infectionTrend,
            'infection_by_location' => $infectionByLocation,
            'infection_by_microorganism' => $infectionByMicroorganism,
            'needlestick_trend' => $needlestickTrend,
            'needlestick_by_department' => $needlestickByDepartment,
            'needlestick_by_position' => $needlestickByPosition,
        ]);
    }
}