<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\CvcInsertion;
use App\Models\CvcMaintenance;
use App\Models\CvcInfection;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\File\UploadedFile; // Required for manual file parsing

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
                // Invalid or missing boundary for multipart data, return empty.
                // This prevents the "Undefined array key 1" error.
                return [];
            }
            $boundary = '--' . $parts[1];

            $allParts = array_slice(explode($boundary, $rawBody), 1);
            foreach ($allParts as $part) {
                if (empty(trim($part)) || trim($part) === '--') continue;

                list($rawHeaders, $content) = explode("\r\n\r\n", $part, 2);
                $rawHeaders = explode("\r\n", $rawHeaders);
                $content = substr($content, 0, strlen($content) - 2); // Remove trailing --\r\n

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

                    // Handle nested array inputs (e.g., elements_data[0][status])
                    if (preg_match('/^(.+)\[(.+)\](?:\[(.+)\])?$/', $name, $matches)) {
                        $baseName = $matches[1];
                        $firstKey = $matches[2];
                        $secondKey = $matches[3] ?? null;

                        if ($filename) { // It's a nested file upload
                            $tmpFilePath = sys_get_temp_dir() . '/' . uniqid('laravel_upload_');
                            file_put_contents($tmpFilePath, $content);

                            $fileInstance = new UploadedFile(
                                $tmpFilePath,
                                $filename,
                                $headers['content-type'] ?? null,
                                UPLOAD_ERR_OK, // Corrected: Removed filesize() as it's auto-calculated/not needed
                                true // Test mode means the file will not be moved out of the temp dir
                            );

                            if ($secondKey !== null) {
                                $files[$baseName][$firstKey][$secondKey] = $fileInstance;
                            } else {
                                $files[$baseName][$firstKey] = $fileInstance;
                            }
                        } else { // It's a nested form field (non-file)
                            if ($secondKey !== null) {
                                // PHP will convert string content to correct types later (e.g. "true" to bool true)
                                $data[$baseName][$firstKey][$secondKey] = $content;
                            } else {
                                $data[$baseName][$firstKey] = $content;
                            }
                        }
                    } else { // Top-level inputs
                        if ($filename) { // Top-level file upload
                            $tmpFilePath = sys_get_temp_dir() . '/' . uniqid('laravel_upload_');
                            file_put_contents($tmpFilePath, $content);
                            $files[$name] = new UploadedFile(
                                $tmpFilePath,
                                $filename,
                                $headers['content-type'] ?? null,
                                UPLOAD_ERR_OK, // Corrected: Removed filesize()
                                true
                            );
                        } else { // Top-level form field (non-file)
                            $data[$name] = $content;
                        }
                    }
                }
            }
        }
        // Handle application/x-www-form-urlencoded (for PUT/PATCH requests without files)
        // This is often the default if FormData is sent with no actual file objects.
        elseif ($rawBody && str_contains($contentType, 'application/x-www-form-urlencoded')) {
            parse_str($rawBody, $data);
        }
        // Handle application/json (less common for forms, but good to include for completeness)
        elseif ($rawBody && str_contains($contentType, 'application/json')) {
            $data = json_decode($rawBody, true);
        }

        // Handle the _method spoofing field. This needs to be done before merging data.
        if (isset($data['_method'])) {
            $request->setMethod($data['_method']);
            unset($data['_method']); // Remove it from the parsed data so it's not validated or saved
        }

        // Merge manually parsed data into the request object's parameter bag
        // and file bag for validation and later access.
        $request->request->add($data);
        $request->files->add($files);

        return $data; // Return parsed data (useful for internal debugging if needed)
    }

    // --- CVC Insertion Form Methods ---

    /**
     * Get CVC Insertion Forms (for history display), sorted by creation date.
     */
    public function getInsertionForms(Request $request)
    {
        $user = Auth::user();
        $forms = CvcInsertion::where('user_id', $user->id)
                               ->orderBy('created_at', 'desc')
                               ->paginate(10);
        return response()->json($forms);
    }

    /**
     * Show a specific CVC Insertion Form.
     */
    public function showInsertionForm(CvcInsertion $form)
    {
        if ($form->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($form);
    }

    /**
     * Store a new CVC Insertion Form.
     */
    public function storeInsertionForm(Request $request)
    {
        // For POST requests, PHP automatically populates $_POST and $_FILES,
        // so no manual parsing is needed here.
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
                'elements_data.*.photo' => 'nullable|image|max:2048', // File object
                // photo_path and photo_path_removed are typically not sent for new forms
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

            // Handle new file upload for this specific element
            if ($request->hasFile("elements_data.{$index}.photo")) {
                $path = $request->file("elements_data.{$index}.photo")->store('public/insertion_photos');
                $currentElementData['photo_path'] = Storage::url($path);
            } else {
                // Ensure photo_path is null if no photo is uploaded for this element
                $currentElementData['photo_path'] = null;
            }

            // Remove the temporary file object from the array before saving to DB
            unset($currentElementData['photo']);

            $elementsToSave[] = $currentElementData;
        }
        $validated['elements_data'] = $elementsToSave;

        $form = CvcInsertion::create($validated);
        return response()->json(['message' => 'Insertion form submitted successfully', 'form' => $form], 201);
    }

    /**
     * Update an existing CVC Insertion Form.
     */
    public function updateInsertionForm(Request $request, CvcInsertion $form)
    {
        // CRITICAL FIX: Manually parse incoming data for PUT/PATCH requests
        // This ensures $request->all() and $request->files are populated.
        $this->parseAndPopulatePutPatchRequest($request);

        // dd($request->all()); // UNCOMMENT THIS TO INSPECT THE INCOMING DATA AFTER PARSING

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
                'elements_data.*.photo' => 'nullable|image|max:2048', // New photo file
                'elements_data.*.photo_path' => 'nullable|string', // Existing photo path from frontend
                'elements_data.*.photo_path_removed' => 'nullable|boolean', // Frontend flag for removal
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }

        // dd($validated); // UNCOMMENT THIS TO INSPECT THE VALIDATED DATA BEFORE UPDATE

        if (isset($validated['elements_data'])) {
            $compliancePercentage = $this->calculateCompliance($validated['elements_data']);
            $validated['compliance_percentage'] = $compliancePercentage;

            $existingElementsData = $form->elements_data; // Get current elements data from the database model

            $elementsToUpdate = [];
            foreach ($validated['elements_data'] as $index => $element) {
                $currentElementData = $element;

                // Ensure description and detail are always present
                $currentElementData['description'] = $element['description'] ?? ($existingElementsData[$index]['description'] ?? null);
                $currentElementData['detail'] = $element['detail'] ?? ($existingElementsData[$index]['detail'] ?? null);

                // Handle new photo upload
                if ($request->hasFile("elements_data.{$index}.photo")) {
                    // Delete old photo if it exists and a new one is uploaded
                    if (isset($existingElementsData[$index]['photo_path']) && $existingElementsData[$index]['photo_path']) {
                        Storage::delete(str_replace('/storage', 'public', $existingElementsData[$index]['photo_path']));
                    }
                    $path = $request->file("elements_data.{$index}.photo")->store('public/insertion_photos');
                    $currentElementData['photo_path'] = Storage::url($path);
                }
                // Handle explicit photo removal from frontend
                else if (isset($element['photo_path_removed']) && $element['photo_path_removed'] === true) {
                    if (isset($existingElementsData[$index]['photo_path']) && $existingElementsData[$index]['photo_path']) {
                        Storage::delete(str_replace('/storage', 'public', $existingElementsData[$index]['photo_path']));
                    }
                    $currentElementData['photo_path'] = null; // Explicitly set to null in JSON
                }
                // If no new photo and not explicitly marked for removal, retain existing photo path from DB
                else if (isset($existingElementsData[$index]['photo_path'])) {
                    $currentElementData['photo_path'] = $existingElementsData[$index]['photo_path'];
                } else {
                    // Ensure 'photo_path' key exists in the array, even if null
                    $currentElementData['photo_path'] = null;
                }

                // Clean up temporary flags/file objects from the array before saving to DB
                unset($currentElementData['photo_path_removed']);
                unset($currentElementData['photo']);

                $elementsToUpdate[] = $currentElementData;
            }
            $validated['elements_data'] = $elementsToUpdate;
        }

        $form->fill($validated); // Fill the model with validated data
        $form->save(); // Persist changes to the database
        $form->refresh(); // Reload the model instance to ensure the response is fresh

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

    /**
     * Show a specific CVC Maintenance Form.
     */
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
                'nurse_name' => 'nullable|string|max:255',
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
                $path = $request->file("elements_data.{$index}.photo")->store('public/maintenance_photos');
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
        // CRITICAL FIX: Manually parse multipart/form-data for PUT/PATCH
        $this->parseAndPopulatePutPatchRequest($request);

        // dd($request->all()); // UNCOMMENT THIS TO INSPECT THE INCOMING DATA AFTER PARSING

        if ($form->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $validated = $request->validate([
                'patient_name' => 'sometimes|string|max:255',
                'medical_record_number' => 'nullable|string|max:255',
                'maintenance_date' => 'sometimes|date',
                'nurse_name' => 'sometimes|string|max:255',
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

        // dd($validated); // UNCOMMENT THIS TO INSPECT THE VALIDATED DATA AFTER VALIDATION

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
                    $path = $request->file("elements_data.{$index}.photo")->store('public/maintenance_photos');
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

    /**
     * Show a specific CVC Infection Report.
     */
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
            $path = $request->file('photo')->store('public/infection_reports');
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
        // CRITICAL FIX: Manually parse multipart/form-data for PUT/PATCH
        $this->parseAndPopulatePutPatchRequest($request);

        // dd($request->all()); // UNCOMMENT THIS TO INSPECT THE INCOMING DATA AFTER PARSING

        if ($report->user_id !== Auth::id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        try {
            $validated = $request->validate([
                'patient_name' => 'sometimes|string|max:255',
                'medical_record_number' => 'nullable|string|max:255',
                'insertion_date' => 'nullable|date',
                'insertion_location' => 'nullable|string|max:255',
                'infection_diagnosis_date' => 'sometimes|date',
                'infection_type' => 'sometimes|in:CLABSI (Central Line Associated Bloodstream Infection),Exit Site Infection,Tunnel Infection,Pocket Infection',
                'clinical_symptoms' => 'nullable|string|max:1000',
                'microorganism' => 'nullable|string|max:255',
                'management' => 'nullable|string|max:1000',
                'photo' => 'nullable|image|max:2048', // New photo file
                'status' => 'sometimes|in:Aktif,Selesai',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors()
            ], 422);
        }

        // dd($validated); // UNCOMMENT THIS TO INSPECT THE VALIDATED DATA BEFORE UPDATE

        // Handle photo updates
        if ($request->hasFile('photo')) {
            if (isset($report->photo_path) && $report->photo_path) {
                Storage::delete(str_replace('/storage', 'public', $report->photo_path));
            }
            $path = $request->file('photo')->store('public/infection_reports');
            $validated['photo_path'] = Storage::url($path);
        }
        // Check if frontend explicitly sent an empty string for the 'photo' input to signal removal
        // If the 'photo' field is in the request but its value is an empty string, it means it was cleared.
        else if ($request->has('photo') && $request->input('photo') === '') {
             if (isset($report->photo_path) && $report->photo_path) {
                Storage::delete(str_replace('/storage', 'public', $report->photo_path));
            }
            $validated['photo_path'] = null; // Explicitly set to null
        }
        // If 'photo' input was not present in the request (i.e., not changed),
        // the existing 'photo_path' on $report will be retained by fill() automatically.
        unset($validated['photo']); // Remove the file object from validation array if it exists

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

        $totalInsertionsToday = CvcInsertion::where('user_id', $user->id)
                                            ->whereDate('insertion_date', $today)
                                            ->count();
        $totalMaintenancesToday = CvcMaintenance::where('user_id', $user->id)
                                                 ->whereDate('maintenance_date', $today)
                                                 ->count();
        $totalActiveInfections = CvcInfection::where('user_id', $user->id)
                                             ->where('status', 'Aktif')
                                             ->count();

        $infectionTrend = CvcInfection::where('user_id', $user->id)
            ->where('infection_diagnosis_date', '>=', Carbon::now()->subMonths(6))
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

        return response()->json([
            'total_insertions_today' => $totalInsertionsToday,
            'total_maintenances_today' => $totalMaintenancesToday,
            'total_active_infections_overall' => $totalActiveInfections,
            'infection_trend' => $infectionTrend,
            'infection_by_location' => $infectionByLocation,
            'infection_by_microorganism' => $infectionByMicroorganism,
        ]);
    }
}