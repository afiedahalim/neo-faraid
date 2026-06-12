<?php

namespace App\Http\Controllers;

use App\Models\InstantEstateSession;
use App\Models\EstatePreRegistration;
use App\Models\Calculation;
use App\Models\NotificationRequest;
use App\Models\BeneficiaryAccessLink;
use App\Models\DocumentHash;
use App\Services\OCRService;
use App\Services\ImageQualityService;
use App\Services\PdfGeneratorService;
use App\Services\WatermarkService;
use App\Services\DocumentAuthenticityService;
use App\Mail\InheritanceReportMail;
use App\Mail\BeneficiaryAccessMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class InstantEstateController extends Controller
{
    protected OCRService $ocrService;
    protected ImageQualityService $qualityService;
    protected PdfGeneratorService $pdfGenerator;
    protected WatermarkService $watermarkService;
    protected DocumentAuthenticityService $authenticityService;

    public function __construct(
        OCRService $ocrService,
        ImageQualityService $qualityService,
        PdfGeneratorService $pdfGenerator,
        WatermarkService $watermarkService,
        DocumentAuthenticityService $authenticityService
    ) {
        $this->ocrService = $ocrService;
        $this->qualityService = $qualityService;
        $this->pdfGenerator = $pdfGenerator;
        $this->watermarkService = $watermarkService;
        $this->authenticityService = $authenticityService;
        
        // NO AUTHENTICATION MIDDLEWARE - All routes are public for upload
        // Admin approval required for report access
    }

    // =========================================================================
    // STEP 1: UPLOAD & QUALITY CHECK WITH DUPLICATE DETECTION
    // =========================================================================

    /**
     * Display the instant estate upload page
     */
    public function index()
    {
        // No login required - public access
        return view('instant-estate.index');
    }

    /**
     * Upload death certificate file with Level 1 validation and duplicate detection
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'death_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png,webp|max:10240',
            'email' => 'nullable|email|max:255',
            'name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $file = $request->file('death_certificate');
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Generate unique session ID
        $sessionId = (string) Str::uuid();
        
        // Store file securely
        $fileName = $sessionId . '.' . $extension;
        $filePath = $file->storeAs('instant-estate/uploads', $fileName, 'private');
        
        // Get full path for hash calculation and quality check
        $fullPath = Storage::disk('private')->path($filePath);
        
        // LEVEL 1: Compute SHA-256 hash for duplicate detection
        $fileHash = hash_file('sha256', $fullPath);
        
        // LEVEL 1: Check for duplicate documents
        $existingHash = DocumentHash::where('file_hash', $fileHash)->first();
        $adminStatus = 'pending_review';
        $adminNotes = null;
        
        if ($existingHash) {
            // Same document previously rejected - provide clear message with options
            if ($existingHash->admin_action === 'rejected') {
                // Clean up the uploaded file
                Storage::disk('private')->delete($filePath);
                
                Log::warning('Duplicate rejected document upload attempted', [
                    'file_hash' => $fileHash,
                    'previous_session_id' => $existingHash->first_session_id,
                    'rejection_reason' => $existingHash->admin_notes,
                    'ip' => $request->ip(),
                ]);
                
                $errorMessage = 'This document was previously rejected.';
                $errorMessage .= ' Reason: ' . ($existingHash->admin_notes ?? 'Document did not meet authenticity requirements.');
                $errorMessage .= ' Please contact support if you believe this is an error, or upload a different, clearer original death certificate.';
                
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => $errorMessage,
                        'code' => 'document_rejected',
                        'rejection_reason' => $existingHash->admin_notes,
                    ], 400);
                }
                return redirect()->back()->with('error', $errorMessage);
            }
            
            // Duplicate found but not rejected - flag for manual review
            $adminStatus = 'pending_review';
            $adminNotes = 'Duplicate document detected. Previous upload: ' . ($existingHash->first_session_id ?? 'unknown');
            
            Log::info('Duplicate document detected', [
                'session_id' => $sessionId,
                'file_hash' => $fileHash,
                'previous_upload_count' => $existingHash->upload_count,
            ]);
        }
        
        // LEVEL 1: Check image quality (for images only, not PDFs)
        $qualityCheck = ['passed' => true, 'score' => 100, 'issues' => []];
        if ($extension !== 'pdf') {
            $qualityCheck = $this->qualityService->checkQuality($fullPath, $extension);
        }
        
        // Apply watermark to the uploaded file
        $watermarkedPath = $this->watermarkService->applyWatermarkToDeathCertificate($filePath, $sessionId);
        
        // Get authenticated user if logged in
        $authUser = auth()->user();
        
        // Get user info - prioritize authenticated user data if available and no explicit input
        if ($authUser && !$request->input('email') && !$request->session()->get('instant_estate_email')) {
            $userEmail = $authUser->email;
            $userName = $authUser->name;
        } else {
            $userEmail = $request->input('email') ?? $request->session()->get('instant_estate_email');
            $userName = $request->input('name') ?? $request->session()->get('instant_estate_name');
        }
        
        // Store user info in session for later use
        if ($userEmail) {
            $request->session()->put('instant_estate_email', $userEmail);
        }
        if ($userName) {
            $request->session()->put('instant_estate_name', $userName);
        }
        
        // Create session record - associate with authenticated user if logged in
        $session = InstantEstateSession::create([
            'session_id' => $sessionId,
            'user_id' => auth()->id(), // Will be null if guest
            'guest_email' => $userEmail,
            'guest_name' => $userName,
            'file_path' => $watermarkedPath,
            'original_filename' => $file->getClientOriginalName(),
            'file_size' => $file->getSize(),
            'file_mime' => $file->getMimeType(),
            'file_hash' => $fileHash,
            'status' => InstantEstateSession::STATUS_UPLOADED,
            'admin_status' => $adminStatus,
            'admin_notes' => $adminNotes,
            'quality_check_passed' => $qualityCheck['passed'],
            'quality_check_details' => $qualityCheck,
            'quality_issues' => $qualityCheck['issues'],
            'expires_at' => now()->addHours(48),
            'processing_attempts' => 0,
        ]);
        
        // Store session ID in user's browser session for later retrieval
        $request->session()->put('instant_estate_session_id', $sessionId);
        
        // Update or create document hash record for duplicate detection
        // Only create/update if not rejected or if this is a new attempt
        if (!$existingHash || $existingHash->admin_action !== 'rejected') {
            $hashRecord = DocumentHash::firstOrNew(['file_hash' => $fileHash]);
            $hashRecord->deceased_nric = null;
            $hashRecord->death_date = null;
            $hashRecord->registration_number = null;
            $hashRecord->first_session_id = $hashRecord->first_session_id ?? $sessionId;
            $hashRecord->last_session_id = $sessionId;
            $hashRecord->upload_count = ($hashRecord->upload_count ?? 0) + 1;
            $hashRecord->save();
        }
        
        Log::info('Death certificate uploaded (public)', [
            'session_id' => $sessionId,
            'user_id' => auth()->id(),
            'guest_email' => $userEmail,
            'quality_passed' => $qualityCheck['passed'],
            'file_size' => $file->getSize(),
            'file_hash' => substr($fileHash, 0, 16),
            'admin_status' => $adminStatus,
            'ip' => $request->ip(),
        ]);
        
        // Start OCR processing automatically
        $this->processOCR($sessionId);
        
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'session_id' => $sessionId,
                'admin_status' => $adminStatus,
                'message' => $adminStatus === 'pending_review' 
                    ? 'File uploaded. Duplicate detected - document requires review.' 
                    : 'File uploaded successfully. Processing OCR...',
            ]);
        }
        
        return redirect()->route('instant-estate.view-session', $sessionId)
            ->with('success', 'File uploaded successfully. Processing OCR...');
    }

    // =========================================================================
    // STEP 2: PROCESS OCR WITH LEVEL 2 VALIDATION (WARNINGS FOR NON-CRITICAL)
    // =========================================================================

    /**
     * Process OCR on uploaded death certificate
     */
    public function processOCR(string $sessionId)
    {
        try {
            $session = $this->getSession($sessionId);
            
            // Update status to processing
            $session->update([
                'status' => InstantEstateSession::STATUS_PROCESSING_OCR,
                'processing_attempts' => $session->processing_attempts + 1,
                'last_processing_attempt_at' => now(),
            ]);
            
            $startTime = microtime(true);
            
            // Perform OCR
            $extractedData = $this->ocrService->extractFromDeathCertificate($session->file_path);
            
            $processingTime = round((microtime(true) - $startTime) * 1000);
            
            // Calculate confidence based on extracted fields
            $confidence = $extractedData['_confidence'] ?? $this->calculateConfidence($extractedData);
            
            // Store raw text for authenticity scoring
            $rawText = $extractedData['raw_text'] ?? '';
            unset($extractedData['_confidence']);
            unset($extractedData['raw_text']);
            
            // LEVEL 2: Perform mandatory field validation (returns critical errors only)
            $qualityDetails = $session->quality_check_details ?? ['score' => 100];
            list($level2Errors, $level2Warnings) = $this->performLevel2Validation($extractedData, $qualityDetails, $rawText);
            
            // Identify missing required fields
            $missingFields = $this->identifyMissingFields($extractedData);
            
            // If Level 2 validation has critical errors, mark as failed
            $hasCriticalErrors = !empty($level2Errors);
            
            // Update session with OCR results
            $session->markOcrCompleted($extractedData, $confidence, $missingFields);
            $session->update([
                'processing_time_ms' => $processingTime,
                'ocr_raw_text' => $rawText,
                'level2_validation_errors' => $level2Errors,
                'level2_validation_warnings' => $level2Warnings,
            ]);
            
            // Update document hash with OCR data (only if not rejected)
            if ($session->file_hash && $session->admin_status !== 'rejected') {
                $hashRecord = DocumentHash::firstOrNew(['file_hash' => $session->file_hash]);
                $hashRecord->deceased_nric = $extractedData['deceased_nric'] ?? $hashRecord->deceased_nric;
                $hashRecord->death_date = isset($extractedData['death_date']) ? Carbon::parse($extractedData['death_date']) : $hashRecord->death_date;
                $hashRecord->registration_number = $extractedData['registration_number'] ?? $extractedData['certificate_no'] ?? $hashRecord->registration_number;
                $hashRecord->first_session_id = $hashRecord->first_session_id ?? $session->session_id;
                $hashRecord->last_session_id = $session->session_id;
                // Don't increment upload_count again here
                $hashRecord->save();
            }
            
            // If critical validation errors, mark as failed
            if ($hasCriticalErrors) {
                $session->markFailed('Level 2 validation failed: ' . implode('; ', $level2Errors));
                $session->update(['admin_status' => 'rejected']);
                
                // Update document hash with rejection info
                if ($session->file_hash) {
                    $hashRecord = DocumentHash::firstOrNew(['file_hash' => $session->file_hash]);
                    $hashRecord->admin_action = 'rejected';
                    $hashRecord->admin_notes = implode('; ', $level2Errors);
                    $hashRecord->save();
                }
                
                Log::warning('Level 2 validation failed', [
                    'session_id' => $sessionId,
                    'errors' => $level2Errors,
                ]);
                
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Document validation failed: ' . implode('; ', $level2Errors),
                        'level2_errors' => $level2Errors,
                        'level2_warnings' => $level2Warnings,
                    ], 400);
                }
                
                return redirect()->route('instant-estate.view-session', $sessionId)
                    ->with('error', 'Document validation failed: ' . implode('; ', $level2Errors));
            }
            
            Log::info('OCR processing completed (public)', [
                'session_id' => $sessionId,
                'confidence' => $confidence,
                'missing_fields' => $missingFields,
                'processing_time_ms' => $processingTime,
                'level2_errors_count' => count($level2Errors),
                'level2_warnings_count' => count($level2Warnings),
            ]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'extracted_data' => $extractedData,
                    'confidence' => $confidence,
                    'missing_fields' => $missingFields,
                    'level2_errors' => $level2Errors,
                    'level2_warnings' => $level2Warnings,
                    'message' => $level2Warnings ? 'OCR completed with warnings. Please review.' : 'OCR completed successfully',
                ]);
            }
            
            return redirect()->route('instant-estate.view-session', $sessionId)
                ->with('success', $level2Warnings ? 'OCR completed with warnings. Please review the extracted data.' : 'OCR completed. Please review the extracted data.');
            
        } catch (\Exception $e) {
            Log::error('OCR processing failed (public)', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            $session = InstantEstateSession::where('session_id', $sessionId)->first();
            if ($session) {
                $session->markFailed($e->getMessage());
            }
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'OCR processing failed: ' . $e->getMessage(),
                ], 500);
            }
            
            return redirect()->route('instant-estate.view-session', $sessionId)
                ->with('error', 'OCR processing failed: ' . $e->getMessage());
        }
    }

    /**
     * LEVEL 2 VALIDATION: Perform mandatory field validation rules A-F
     * 
     * Rules implemented with WARNINGS instead of ERRORS for non-critical fields:
     * A. Document header missing → WARNING (not error)
     * B. Registration number missing → WARNING (not error)
     * C. Only reject if BOTH name AND IC are missing
     * D. IC format validation → WARNING (not error)
     * E. Date consistency → WARNING (not error)
     * F. Age consistency → WARNING (not error)
     * 
     * Returns: [array $criticalErrors, array $warnings]
     */
    protected function performLevel2Validation(array $ocrData, array $qualityDetails, string $rawText = ''): array
    {
        $criticalErrors = [];
        $warnings = [];
        
        // Get raw text for header validation (case-insensitive)
        $textUpper = strtoupper($rawText ?: ($ocrData['raw_text'] ?? ''));
        if (empty($textUpper) && !empty($ocrData['full_text'])) {
            $textUpper = strtoupper($ocrData['full_text']);
        }
        
        // RULE A: Certificate header missing → WARNING (not critical)
        $hasSijilKematian = strpos($textUpper, 'SIJIL KEMATIAN') !== false;
        $hasDeathCertificate = strpos($textUpper, 'DEATH CERTIFICATE') !== false;
        
        if (!$hasSijilKematian && !$hasDeathCertificate) {
            $warnings[] = 'Certificate header not detected by OCR. Please verify this is a valid death certificate.';
        }
        
        // RULE B: Registration number missing → WARNING (not critical)
        $registrationNumber = $ocrData['registration_number'] ?? $ocrData['certificate_no'] ?? $ocrData['no_daftar'] ?? null;
        if (empty($registrationNumber)) {
            $warnings[] = 'Registration number not detected. Please enter it manually if available.';
        }
        
        // RULE C: Only reject if BOTH deceased name AND IC are missing
        $hasName = !empty($ocrData['deceased_name']);
        $hasNric = !empty($ocrData['deceased_nric']);
        
        if (!$hasName && !$hasNric) {
            $criticalErrors[] = 'Could not identify deceased name or NRIC. Please upload a clearer image or enter manually.';
        }
        
        // Death date and place are now warnings, not errors
        if (empty($ocrData['death_date'])) {
            $warnings[] = 'Death date not detected. Please enter it manually.';
        }
        if (empty($ocrData['death_place'])) {
            $warnings[] = 'Place of death not detected. Please enter it manually.';
        }
        
        // RULE D: IC format validation → WARNING (not error)
        $nric = $ocrData['deceased_nric'] ?? '';
        if (!empty($nric)) {
            // Remove spaces and convert to standard format
            $cleanNric = preg_replace('/[^0-9]/', '', $nric);
            
            // Check if it matches Malaysian IC format (12 digits)
            if (strlen($cleanNric) !== 12) {
                $warnings[] = 'NRIC format may be incorrect. Expected 12 digits (format: YYMMDD-XX-XXXX).';
            } elseif (!preg_match('/^\d{6}-\d{2}-\d{4}$/', $nric) && !preg_match('/^\d{12}$/', $cleanNric)) {
                $warnings[] = 'Invalid IC format. Expected format: XXXXXX-XX-XXXX or 12 digits.';
            } else {
                // Extract date of birth from IC (first 6 digits: YYMMDD)
                $icDobYear = (int) substr($cleanNric, 0, 2);
                $icDobMonth = (int) substr($cleanNric, 2, 2);
                $icDobDay = (int) substr($cleanNric, 4, 2);
                
                // Validate if it's a real date
                if (!checkdate($icDobMonth, $icDobDay, 2000 + $icDobYear) && !checkdate($icDobMonth, $icDobDay, 1900 + $icDobYear)) {
                    $warnings[] = 'IC contains invalid date of birth.';
                }
            }
        }
        
        // RULE E: Date consistency – warn, don't reject
        $deathDate = $ocrData['death_date'] ?? null;
        $registrationDate = $ocrData['registration_date'] ?? $ocrData['date_registered'] ?? null;
        
        if (!empty($deathDate) && !empty($registrationDate)) {
            try {
                $deathDateObj = Carbon::parse($deathDate);
                $registrationDateObj = Carbon::parse($registrationDate);
                
                if ($deathDateObj->gt($registrationDateObj)) {
                    $warnings[] = 'Death date appears after registration date. Please verify.';
                }
                
                // Also check that death date is not in the future
                if ($deathDateObj->isFuture()) {
                    $warnings[] = 'Death date is in the future. Please correct.';
                }
            } catch (\Exception $e) {
                $warnings[] = 'Could not validate date format. Please check manually.';
            }
        } elseif (!empty($deathDate)) {
            // If only death date exists, check if it's valid
            try {
                $deathDateObj = Carbon::parse($deathDate);
                if ($deathDateObj->isFuture()) {
                    $warnings[] = 'Death date is in the future. Please correct.';
                }
            } catch (\Exception $e) {
                $warnings[] = 'Death date format appears invalid. Please correct.';
            }
        }
        
        // RULE F: Age consistency – warn only, with improved parsing
        if (!empty($nric) && !empty($deathDate)) {
            try {
                $cleanNric = preg_replace('/[^0-9]/', '', $nric);
                if (strlen($cleanNric) >= 6) {
                    $icYear = (int) substr($cleanNric, 0, 2);
                    $icMonth = (int) substr($cleanNric, 2, 2);
                    $icDay = (int) substr($cleanNric, 4, 2);
                    
                    $deathDateObj = Carbon::parse($deathDate);
                    $deathYear = $deathDateObj->year;
                    
                    // Better century detection
                    $birthYear = ($icYear > $deathYear % 100) ? (1900 + $icYear) : (2000 + $icYear);
                    
                    // Adjust if birth year still after death year
                    if ($birthYear > $deathYear) {
                        $birthYear -= 100;
                    }
                    
                    if (checkdate($icMonth, $icDay, $birthYear)) {
                        $birthDateObj = Carbon::create($birthYear, $icMonth, $icDay);
                        $calculatedAge = $birthDateObj->diffInYears($deathDateObj);
                        
                        // Age should be between 0 and 120 - only warn
                        if ($calculatedAge < 0 || $calculatedAge > 120) {
                            $warnings[] = "Calculated age ({$calculatedAge} years) seems incorrect. Please verify the dates.";
                        } elseif ($calculatedAge > 100) {
                            $warnings[] = "Deceased age ({$calculatedAge} years) is unusually high. Please verify.";
                        }
                    } else {
                        $warnings[] = 'IC contains invalid date of birth.';
                    }
                }
            } catch (\Exception $e) {
                $warnings[] = 'Unable to calculate age from provided data.';
            }
        }
        
        // Quality check – only warn, don't reject
        $qualityScore = $qualityDetails['score'] ?? 100;
        if ($qualityScore < 50) {
            $warnings[] = 'Document image quality is poor. Information may not be extracted accurately.';
        } elseif ($qualityScore < 70) {
            $warnings[] = 'Document image quality is moderate. Please review extracted data carefully.';
        }
        
        // Return critical errors (blocking) and warnings (non-blocking)
        return [$criticalErrors, $warnings];
    }

    /**
     * Get OCR status for a session
     */
    public function getOCRStatus(string $sessionId)
    {
        try {
            $session = $this->getSession($sessionId);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'status' => $session->status,
                    'confidence' => $session->ocr_confidence,
                    'extracted_data' => $session->extracted_data,
                    'missing_fields' => $session->missing_fields,
                    'level2_errors' => $session->level2_validation_errors,
                    'level2_warnings' => $session->level2_validation_warnings,
                    'error_message' => $session->error_message,
                ]);
            }
            
            return redirect()->route('instant-estate.view-session', $sessionId);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Validate OCR extracted data
     */
    public function validateOCRData(Request $request, string $sessionId)
    {
        $validator = Validator::make($request->all(), [
            'field' => 'required|string',
            'value' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }
            return redirect()->back()->withErrors($validator);
        }

        try {
            $session = $this->getSession($sessionId);
            $field = $request->input('field');
            $value = $request->input('value');
            
            // Update the specific field in extracted data
            $extractedData = $session->extracted_data ?? [];
            $extractedData[$field] = $value;
            
            $session->update(['extracted_data' => $extractedData]);
            
            // Update missing fields list
            $missingFields = $this->identifyMissingFields($extractedData);
            $session->update(['missing_fields' => $missingFields]);
            
            // Re-run Level 2 validation if necessary (returns both errors and warnings)
            if ($field === 'deceased_nric' || $field === 'death_date' || $field === 'registration_number' || $field === 'deceased_name') {
                $qualityDetails = $session->quality_check_details ?? ['score' => 100];
                $rawText = $session->ocr_raw_text ?? '';
                list($level2Errors, $level2Warnings) = $this->performLevel2Validation($extractedData, $qualityDetails, $rawText);
                $session->update([
                    'level2_validation_errors' => $level2Errors,
                    'level2_validation_warnings' => $level2Warnings,
                ]);
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Field validated successfully',
                    'missing_fields' => $missingFields,
                    'level2_errors' => $session->level2_validation_errors,
                    'level2_warnings' => $session->level2_validation_warnings,
                ]);
            }
            
            return redirect()->route('instant-estate.view-session', $sessionId)
                ->with('success', 'Field updated successfully');
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to validate field: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================================
    // STEP 3: EDIT & CONTINUE (SAVE EDITED DATA + DATABASE MATCHING)
    // =========================================================================

    /**
     * Save edited data and continue to database matching
     * Only blocks on critical errors, not warnings
     */
    public function editAndContinue(Request $request, string $sessionId)
    {
        $validator = Validator::make($request->all(), [
            'extracted_data' => 'required|array',
            'extracted_data.deceased_name' => 'nullable|string|max:255',
            'extracted_data.deceased_nric' => 'nullable|string|max:20',
            'extracted_data.date_of_birth' => 'nullable|date',
            'extracted_data.gender' => 'nullable|in:male,female',
            'extracted_data.death_date' => 'nullable|date',
            'extracted_data.death_place' => 'nullable|string|max:500',
            'extracted_data.contact_email' => 'nullable|email|max:255',
            'extracted_data.contact_phone' => 'nullable|string|max:20',
            'extracted_data.residential_address' => 'nullable|string|max:1000',
            'extracted_data.marital_status' => 'nullable|string|max:50',
            'extracted_data.cause_of_death' => 'nullable|string|max:500',
            'extracted_data.father_name' => 'nullable|string|max:255',
            'extracted_data.mother_name' => 'nullable|string|max:255',
            'extracted_data.spouse_name' => 'nullable|string|max:255',
            'extracted_data.registration_number' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $session = $this->getSession($sessionId);
            $editedData = $request->input('extracted_data');
            
            // Merge with existing extracted data
            $existingData = $session->extracted_data ?? [];
            $mergedData = array_merge($existingData, $editedData);
            
            // Store registration number separately for tracking
            if (!empty($mergedData['registration_number'])) {
                $session->registration_number = $mergedData['registration_number'];
            } elseif (!empty($mergedData['certificate_no'])) {
                $session->registration_number = $mergedData['certificate_no'];
            }
            
            // Re-run Level 2 validation with edited data (returns critical errors only)
            $qualityDetails = $session->quality_check_details ?? ['score' => 100];
            $rawText = $session->ocr_raw_text ?? '';
            list($level2Errors, $level2Warnings) = $this->performLevel2Validation($mergedData, $qualityDetails, $rawText);
            
            // Only block on critical errors
            if (!empty($level2Errors)) {
                $session->update([
                    'level2_validation_errors' => $level2Errors,
                    'level2_validation_warnings' => $level2Warnings,
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed: ' . implode('; ', $level2Errors),
                        'level2_errors' => $level2Errors,
                        'level2_warnings' => $level2Warnings,
                    ], 422);
                }
                
                return redirect()->back()
                    ->with('error', 'Validation failed: ' . implode('; ', $level2Errors))
                    ->withInput();
            }
            
            // Store warnings (non-blocking)
            $session->update(['level2_validation_warnings' => $level2Warnings]);
            
            // Confirm the data
            $session->confirmData($mergedData);
            $session->save();
            
            Log::info('Data confirmed by user (public)', [
                'session_id' => $sessionId,
                'guest_email' => $session->guest_email,
                'registration_number' => $session->registration_number,
                'warnings_count' => count($level2Warnings),
            ]);
            
            // Update document hash with registration number (don't increment upload count)
            if ($session->file_hash && $session->admin_status !== 'rejected') {
                $hashRecord = DocumentHash::firstOrNew(['file_hash' => $session->file_hash]);
                $hashRecord->deceased_nric = $mergedData['deceased_nric'] ?? $hashRecord->deceased_nric;
                $hashRecord->death_date = isset($mergedData['death_date']) ? Carbon::parse($mergedData['death_date']) : $hashRecord->death_date;
                $hashRecord->registration_number = $session->registration_number ?? $hashRecord->registration_number;
                $hashRecord->last_session_id = $session->session_id;
                // Do NOT increment upload_count here - this is just editing, not a new upload
                $hashRecord->save();
            }
            
            // SEARCH DATABASE FOR MATCHING RECORDS
            $matchingResult = $this->searchDatabaseForMatch($mergedData);
            
            if ($matchingResult['found']) {
                // Record found - mark as record_found
                $session->markMatchedRecord(
                    $matchingResult['record_id'],
                    $matchingResult['record_type'],
                    $matchingResult['record']
                );
                
                // Generate report data from the matched record
                $reportData = $this->generateReportFromMatch($matchingResult['record'], $matchingResult['record_type'], $session);
                $session->generateReport($reportData);
                
                Log::info('Matching record found in database (public)', [
                    'session_id' => $sessionId,
                    'record_type' => $matchingResult['record_type'],
                    'record_id' => $matchingResult['record_id'],
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'record_found' => true,
                        'needs_captcha' => true,
                        'message' => 'Matching record found! Please complete CAPTCHA verification to proceed.',
                    ]);
                }
                
                return redirect()->route('instant-estate.view-session', $sessionId)
                    ->with('success', 'Matching record found! Please complete CAPTCHA verification.');
            } else {
                // No record found - mark as no_record
                $session->markNoRecord();
                
                Log::info('No matching record found (public)', [
                    'session_id' => $sessionId,
                ]);
                
                // Generate calculator URL with pre-filled data
                $calculatorUrl = route('calculator.create') . '?' . http_build_query([
                    'source' => 'instant_estate',
                    'session_id' => $sessionId,
                    'deceased_name' => $mergedData['deceased_name'] ?? '',
                    'deceased_nric' => $mergedData['deceased_nric'] ?? '',
                    'death_date' => $mergedData['death_date'] ?? '',
                    'gender' => $mergedData['gender'] ?? '',
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'record_found' => false,
                        'redirect_url' => $calculatorUrl,
                        'message' => 'No matching record found. Redirecting to calculator.',
                    ]);
                }
                
                return redirect()->away($calculatorUrl);
            }
            
        } catch (\Exception $e) {
            Log::error('Edit and continue failed (public)', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to save data: ' . $e->getMessage(),
                ], 500);
            }
            
            return redirect()->route('instant-estate.view-session', $sessionId)
                ->with('error', 'Failed to save data: ' . $e->getMessage());
        }
    }

    /**
     * Save edited data (alternative endpoint)
     */
    public function saveEditedData(Request $request, string $sessionId)
    {
        return $this->editAndContinue($request, $sessionId);
    }

    // =========================================================================
    // STEP 4: DATABASE MATCHING LOGIC
    // =========================================================================

    /**
     * Search for matching records in estate_pre_registrations and calculations tables
     */
    protected function searchDatabaseForMatch(array $data): array
    {
        $deceasedName = $data['deceased_name'] ?? null;
        $deceasedNric = $data['deceased_nric'] ?? null;
        $deathDate = $data['death_date'] ?? null;
        $gender = $data['gender'] ?? null;
        
        if (empty($deceasedName) && empty($deceasedNric)) {
            return ['found' => false];
        }
        
        // Clean NRIC for matching
        $cleanedNric = null;
        if (!empty($deceasedNric)) {
            $cleanedNric = preg_replace('/[^0-9]/', '', $deceasedNric);
        }
        
        // SEARCH IN ESTATE_PRE_REGISTRATIONS TABLE
        $estateQuery = EstatePreRegistration::query();
        
        // Match by NRIC (most reliable)
        if (!empty($cleanedNric)) {
            $estateQuery->where(function($q) use ($cleanedNric, $deceasedNric) {
                $q->where('deceased_nric', $cleanedNric)
                  ->orWhere('deceased_nric', $deceasedNric)
                  ->orWhereRaw("REPLACE(deceased_nric, '-', '') = ?", [$cleanedNric]);
            });
        }
        
        // Match by name if NRIC not provided or as additional criteria
        if (!empty($deceasedName)) {
            if (!empty($cleanedNric)) {
                $estateQuery->orWhere(function($q) use ($deceasedName) {
                    $q->where('deceased_name', 'LIKE', '%' . $deceasedName . '%');
                });
            } else {
                $estateQuery->where('deceased_name', 'LIKE', '%' . $deceasedName . '%');
            }
        }
        
        // Match by gender if provided
        if (!empty($gender)) {
            $estateQuery->where('gender', $gender);
        }
        
        // Only active/approved estates
        $estateQuery->whereIn('status', ['activated', 'completed', 'executed'])
                    ->where('admin_approved', true);
        
        $matchingEstate = $estateQuery->first();
        
        if ($matchingEstate) {
            return [
                'found' => true,
                'record_type' => InstantEstateSession::RECORD_TYPE_ESTATE_PLAN,
                'record_id' => $matchingEstate->id,
                'record' => $matchingEstate,
            ];
        }
        
        // SEARCH IN CALCULATIONS TABLE
        $calcQuery = Calculation::query();
        
        // Match by name
        if (!empty($deceasedName)) {
            $calcQuery->where('deceased_name', 'LIKE', '%' . $deceasedName . '%');
        }
        
        // Match by gender if provided
        if (!empty($gender)) {
            $calcQuery->where('deceased_gender', $gender);
        }
        
        // Match by death date if available
        if (!empty($deathDate)) {
            $calcQuery->whereDate('date_of_death', $deathDate);
        }
        
        $matchingCalculation = $calcQuery->orderBy('created_at', 'desc')->first();
        
        if ($matchingCalculation) {
            return [
                'found' => true,
                'record_type' => InstantEstateSession::RECORD_TYPE_CALCULATION,
                'record_id' => $matchingCalculation->id,
                'record' => $matchingCalculation,
            ];
        }
        
        return ['found' => false];
    }

    /**
     * Search database endpoint for AJAX calls
     */
    public function searchDatabase(Request $request, string $sessionId)
    {
        try {
            $session = $this->getSession($sessionId);
            $data = $session->extracted_data ?? [];
            
            $result = $this->searchDatabaseForMatch($data);
            
            if ($result['found']) {
                $session->markMatchedRecord($result['record_id'], $result['record_type'], $result['record']);
                $reportData = $this->generateReportFromMatch($result['record'], $result['record_type'], $session);
                $session->generateReport($reportData);
            } else {
                $session->markNoRecord();
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'record_found' => $result['found'],
                    'record_type' => $result['record_type'] ?? null,
                    'message' => $result['found'] ? 'Matching record found' : 'No matching record found',
                ]);
            }
            
            return redirect()->route('instant-estate.view-session', $sessionId);
            
        } catch (\Exception $e) {
            Log::error('Database search failed (public)', ['session_id' => $sessionId, 'error' => $e->getMessage()]);
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Database search failed: ' . $e->getMessage(),
                ], 500);
            }
            
            return redirect()->route('instant-estate.view-session', $sessionId)
                ->with('error', 'Database search failed');
        }
    }

    // =========================================================================
    // STEP 5: CAPTCHA VERIFICATION WITH AUTHENTICITY SCORING (LEVEL 3)
    // =========================================================================

    /**
     * Verify CAPTCHA and run authenticity scoring (Level 3)
     * After scoring, determine admin status based on score
     */
    public function verifyCaptcha(Request $request, string $sessionId)
    {
        $validator = Validator::make($request->all(), [
            'captcha_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $session = $this->getSession($sessionId);
            
            // Verify CAPTCHA token - simplified, use proper service in production
            $captchaToken = $request->input('captcha_token');
            if (!$captchaToken || !str_starts_with($captchaToken, 'verified_')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid CAPTCHA verification code',
                ], 400);
            }
            
            // Run authenticity scoring (Level 3)
            $ocrData = $session->extracted_data ?? [];
            $ocrData['raw_text'] = $session->ocr_raw_text ?? '';
            
            $scoreResult = $this->authenticityService->computeScore(
                $session->file_path,
                $session->file_hash,
                $ocrData
            );
            
            $score = $scoreResult['score'];
            $scoreDetails = $scoreResult['details'] ?? [];
            
            $session->authenticity_score = $score;
            $session->authenticity_score_details = $scoreDetails;
            $session->save();
            
            // Record or update document hash with scoring results
            if ($session->file_hash && $session->admin_status !== 'rejected') {
                $hashRecord = DocumentHash::firstOrNew(['file_hash' => $session->file_hash]);
                $hashRecord->deceased_nric = $session->deceased_nric ?? $hashRecord->deceased_nric;
                $hashRecord->death_date = $session->death_date ?? $hashRecord->death_date;
                $hashRecord->registration_number = $session->registration_number ?? $hashRecord->registration_number;
                $hashRecord->first_session_id = $hashRecord->first_session_id ?? $session->session_id;
                $hashRecord->last_session_id = $session->session_id;
                $hashRecord->authenticity_score = $score;
                $hashRecord->authenticity_details = $scoreDetails;
                // Don't increment upload_count here - this is just scoring
                $hashRecord->save();
            }
            
            // Determine admin status based on authenticity score
            // Score thresholds: 0-59 = Rejected, 60-79 = Pending Review, 80-100 = Pending Approval
            if ($score < 60) {
                $session->admin_status = 'rejected';
                $session->rejection_reason = 'Document authenticity score too low (' . $score . '/100). The document appears to be forged, altered, or of poor quality. Please upload a clear, original death certificate.';
                $session->save();
                
                // Update document hash with rejection info
                if ($session->file_hash) {
                    $hashRecord = DocumentHash::firstOrNew(['file_hash' => $session->file_hash]);
                    $hashRecord->admin_action = 'rejected';
                    $hashRecord->admin_notes = $session->rejection_reason;
                    $hashRecord->save();
                }
                
                Log::warning('Document rejected due to low authenticity score', [
                    'session_id' => $sessionId,
                    'score' => $score,
                    'rejection_reason' => $session->rejection_reason,
                ]);
                
                return response()->json([
                    'success' => false,
                    'admin_status' => 'rejected',
                    'authenticity_score' => $score,
                    'rejection_reason' => $session->rejection_reason,
                    'message' => 'Document does not meet authenticity requirements. Please upload a clear, original death certificate.',
                ], 400);
                
            } elseif ($score < 80) {
                $session->admin_status = 'pending_review';
                $session->save();
                
                Log::info('Document requires manual review', [
                    'session_id' => $sessionId,
                    'score' => $score,
                    'admin_status' => 'pending_review',
                ]);
                
                return response()->json([
                    'success' => true,
                    'admin_status' => 'pending_review',
                    'authenticity_score' => $score,
                    'message' => 'Document requires manual review by an administrator. You will be notified when the review is complete (typically within 24 hours).',
                ], 200);
                
            } else {
                $session->admin_status = 'pending_approval';
                $session->save();
                
                Log::info('Document pending admin approval', [
                    'session_id' => $sessionId,
                    'score' => $score,
                    'admin_status' => 'pending_approval',
                ]);
                
                return response()->json([
                    'success' => true,
                    'admin_status' => 'pending_approval',
                    'authenticity_score' => $score,
                    'message' => 'Document verification successful. Your report is pending admin approval. You will receive the report within 24 hours.',
                ], 200);
            }
            
        } catch (\Exception $e) {
            Log::error('CAPTCHA verification failed (public)', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'CAPTCHA verification failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get admin status for polling (frontend uses this to check approval status)
     */
    public function getAdminStatus(string $sessionId)
    {
        try {
            $session = $this->getSession($sessionId);
            
            $response = [
                'success' => true,
                'session_id' => $session->session_id,
                'admin_status' => $session->admin_status,
                'authenticity_score' => $session->authenticity_score,
                'rejection_reason' => $session->rejection_reason,
                'is_approved' => $session->admin_status === 'approved',
                'is_rejected' => $session->admin_status === 'rejected',
                'is_pending' => in_array($session->admin_status, ['pending_review', 'pending_approval']),
                'can_view_report' => $session->admin_status === 'approved' && $session->has_report,
                'status_message' => $this->getAdminStatusMessage($session->admin_status),
            ];
            
            // Include report data if approved
            if ($session->admin_status === 'approved' && $session->has_report) {
                $response['has_report'] = true;
                $response['report_ready'] = true;
            }
            
            return response()->json($response);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }
    
    /**
     * Get human-readable message for admin status
     */
    protected function getAdminStatusMessage(?string $status): string
    {
        return match($status) {
            'pending_review' => 'Your document is under manual review. Our team will verify the document within 24 hours.',
            'pending_approval' => 'Your document has been verified and is pending final approval. You will receive the report within 24 hours.',
            'approved' => 'Your document has been approved. Your inheritance report is ready to view.',
            'rejected' => 'Your document could not be verified. Please contact support for assistance.',
            default => 'Your document is being processed.'
        };
    }

    // =========================================================================
    // STEP 6: VIEW REPORT (WITH ADMIN APPROVAL CHECK)
    // =========================================================================

    /**
     * View the generated inheritance report (only if admin approved)
     */
    public function viewReport(string $sessionId)
    {
        $session = $this->getSession($sessionId);
        
        // Verify that admin has approved the document
        if ($session->admin_status !== 'approved') {
            if ($session->admin_status === 'rejected') {
                abort(403, 'Your document has been rejected. Reason: ' . ($session->rejection_reason ?? 'Document does not meet authenticity requirements.'));
            }
            if ($session->admin_status === 'pending_review') {
                abort(403, 'Your document is under manual review. Please check back later.');
            }
            if ($session->admin_status === 'pending_approval') {
                abort(403, 'Your document is pending admin approval. You will receive an email once approved.');
            }
            abort(403, 'Report not yet available. Please wait for admin approval.');
        }
        
        // Check if report data exists
        if (!$session->report_data) {
            abort(404, 'Report not found. Please complete the process first.');
        }
        
        $reportData = $session->report_data;
        
        // Check if this is an estate plan report - redirect to beneficiary access view if needed
        if (isset($reportData['report_type']) && $reportData['report_type'] === 'estate_plan') {
            $estate = EstatePreRegistration::find($reportData['estate_id'] ?? null);
            if ($estate) {
                $token = $estate->beneficiaryAccessLinks->first()->access_token ?? null;
                if ($token) {
                    return redirect()->route('beneficiary.access', ['token' => $token]);
                }
            }
        }
        
        // Prepare view data
        $viewData = $this->prepareReportViewData($session, $reportData);
        
        // Return view based on report type
        if (isset($reportData['report_type']) && $reportData['report_type'] === 'estate_plan') {
            return view('instant-estate.report-estate-plan', $viewData);
        } elseif (isset($reportData['report_type']) && $reportData['report_type'] === 'calculation') {
            return view('instant-estate.report-calculation', $viewData);
        } else {
            return view('instant-estate.generic-report', $viewData);
        }
    }

    /**
     * Display the result page for public access (beneficiary view)
     */
    public function result(string $sessionId)
    {
        $session = InstantEstateSession::where('session_id', $sessionId)->firstOrFail();
        
        if (!$session->report_data) {
            abort(404, 'Report not found');
        }
        
        $reportData = $session->report_data;
        
        if (isset($reportData['report_type']) && $reportData['report_type'] === 'estate_plan') {
            $estate = EstatePreRegistration::find($reportData['estate_id'] ?? null);
            if ($estate && $estate->beneficiaryAccessLinks()->first()) {
                $token = $estate->beneficiaryAccessLinks()->first()->access_token;
                if ($token) {
                    return redirect()->route('beneficiary.access', ['token' => $token]);
                }
            }
        }
        
        $viewData = $this->prepareReportViewData($session, $reportData);
        $viewData['is_public'] = true;
        
        return view('instant-estate.generic-report', $viewData);
    }

    /**
     * Prepare view data for report
     */
    protected function prepareReportViewData(InstantEstateSession $session, array $reportData): array
    {
        return [
            'session' => $session,
            'report' => $reportData,
            'session_id' => $session->session_id,
            'document_id' => 'NFR-' . now()->format('Ymd') . '-' . substr($session->session_id, 0, 8),
            'generated_at' => now()->format('d F Y, h:i:s A'),
            'date_of_death' => $session->death_date ? $session->death_date->format('d F Y') : ($reportData['date_of_death'] ?? 'N/A'),
            'deceased_name' => $session->deceased_name ?? $reportData['deceased_name'] ?? 'N/A',
            'deceased_nric' => $this->maskNric($session->deceased_nric ?? $reportData['deceased_nric'] ?? ''),
            'deceased_gender' => $session->gender ?? $reportData['deceased_gender'] ?? 'N/A',
            'deceased_dob' => $session->date_of_birth ? $session->date_of_birth->format('d F Y') : ($reportData['deceased_dob'] ?? 'N/A'),
            'deceased_address' => $session->residential_address ?? $reportData['deceased_address'] ?? 'N/A',
            'can_view_inheritance' => true,
            'calculation' => null,
            'debts' => collect($reportData['debts'] ?? []),
            'willContent' => $reportData['wasiyyah'] ?? null,
            'total_assets' => $reportData['total_assets'] ?? 0,
            'formatted_total_assets' => 'RM ' . number_format($reportData['total_assets'] ?? 0, 2),
            'total_debts' => $reportData['total_debts'] ?? 0,
            'formatted_total_debts' => 'RM ' . number_format($reportData['total_debts'] ?? 0, 2),
            'net_estate' => $reportData['net_estate'] ?? $reportData['net_assets'] ?? 0,
            'formatted_net_estate' => 'RM ' . number_format($reportData['net_estate'] ?? $reportData['net_assets'] ?? 0, 2),
            'remaining_for_heirs' => $reportData['remaining_for_heirs'] ?? $reportData['net_assets'] ?? 0,
            'formatted_remaining_for_heirs' => 'RM ' . number_format($reportData['remaining_for_heirs'] ?? $reportData['net_assets'] ?? 0, 2),
            'wasiyyah_amount' => $reportData['wasiyyah_amount'] ?? 0,
            'formatted_wasiyyah_amount' => 'RM ' . number_format($reportData['wasiyyah_amount'] ?? 0, 2),
            'effective_wasiyyah_pct' => $reportData['effective_wasiyyah_pct'] ?? 0,
            'trustee_name' => $reportData['trustee_name'] ?? null,
            'trustee_relationship' => $reportData['trustee_relationship'] ?? null,
            'trustee_email' => $reportData['trustee_email'] ?? null,
            'trustee_phone' => $reportData['trustee_phone'] ?? null,
            'assets' => $reportData['assets'] ?? [],
            'heirs' => $reportData['heirs'] ?? [],
            'wasiyyah' => $reportData['wasiyyah'] ?? [],
            'calculation_data' => $reportData['calculation_data'] ?? [],
            'family_composition' => $reportData['family_composition'] ?? [],
            'heirs_summary' => $reportData['heirs_summary'] ?? [],
            'estate_unique_id' => $reportData['estate_unique_id'] ?? null,
            'calculation_id' => $reportData['calculation_id'] ?? null,
            'report_data' => $reportData,
        ];
    }

    /**
     * Download report as PDF (only if admin approved)
     */
    public function downloadReport(string $sessionId)
    {
        $session = $this->getSession($sessionId);
        
        // Verify admin approval
        if ($session->admin_status !== 'approved') {
            abort(403, 'Report not yet approved. Please wait for admin approval.');
        }
        
        // Verify access
        if ($session->status !== InstantEstateSession::STATUS_CAPTCHA_VERIFIED &&
            $session->status !== InstantEstateSession::STATUS_COMPLETED &&
            $session->status !== InstantEstateSession::STATUS_EMAIL_SENT &&
            $session->status !== InstantEstateSession::STATUS_RECORD_FOUND) {
            abort(403, 'You do not have permission to download this report.');
        }
        
        // Generate PDF if not exists
        if (!$session->has_pdf_report) {
            if (!$session->report_data) {
                abort(404, 'Report data not found.');
            }
            
            $pdfContent = $this->pdfGenerator->generateInheritanceDistributionPdfForSession($session);
            $pdfPath = 'estates/' . $session->session_id . '/report_' . now()->format('Ymd_His') . '.pdf';
            Storage::disk('private')->put($pdfPath, $pdfContent);
            $session->update(['report_pdf_path' => $pdfPath]);
        }
        
        if (!$session->has_pdf_report) {
            abort(404, 'Report PDF not found.');
        }
        
        $filename = 'inheritance_report_' . ($session->deceased_name ?? 'unknown') . '_' . now()->format('Ymd') . '.pdf';
        $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
        
        return Storage::disk('private')->download($session->report_pdf_path, $filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    // =========================================================================
    // STEP 7: REQUEST EMAIL NOTIFICATION (ONLY AFTER ADMIN APPROVAL & LOGIN)
    // =========================================================================

    /**
     * Request email notification for report (requires login and admin approval)
     */
    public function requestNotification(Request $request, string $sessionId)
    {
        // Check authentication - must be logged in
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Login required to request email report. Please create an account or login.',
                'requires_login' => true,
            ], 401);
        }
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $session = $this->getSession($sessionId);
            
            // Verify admin approval
            if ($session->admin_status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Report must be approved by admin before email can be sent.',
                    'admin_status' => $session->admin_status,
                ], 403);
            }
            
            $email = $request->input('email');
            
            // Check if notification already requested
            if ($session->notification_requested) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification already requested for this session.',
                ], 400);
            }
            
            // Generate PDF report if not already generated
            if (!$session->has_pdf_report && $session->has_report) {
                try {
                    $pdfContent = $this->pdfGenerator->generateInheritanceDistributionPdfForSession($session);
                    $pdfPath = 'estates/' . $session->session_id . '/report_' . now()->format('Ymd_His') . '.pdf';
                    Storage::disk('private')->put($pdfPath, $pdfContent);
                    $session->update(['report_pdf_path' => $pdfPath]);
                    
                    Log::info('PDF report generated for notification request', [
                        'session_id' => $sessionId,
                        'pdf_path' => $pdfPath,
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to generate PDF for notification', [
                        'session_id' => $sessionId,
                        'error' => $e->getMessage(),
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Unable to generate report. Please try again later.',
                    ], 500);
                }
            }
            
            // Get the PDF content as base64 for storage
            $pdfBase64 = null;
            if ($session->has_pdf_report) {
                $pdfContent = Storage::disk('private')->get($session->report_pdf_path);
                $pdfBase64 = base64_encode($pdfContent);
            }
            
            // Generate a unique access token for viewing
            $accessToken = Str::random(64) . '-' . time();
            
            // Get user name from authenticated user or session
            $userName = Auth::user()->name ?? $session->guest_name ?? $request->input('name') ?? 'Valued User';
            
            // Create notification request record
            $notificationRequest = NotificationRequest::create([
                'session_id' => $session->session_id,
                'instant_estate_session_id' => $session->id,
                'user_id' => Auth::id(),
                'guest_email' => $email,
                'guest_name' => $userName,
                'deceased_name' => $session->deceased_name,
                'deceased_nric' => $session->deceased_nric,
                'death_date' => $session->death_date,
                'death_place' => $session->death_place,
                'recipient_email' => $email,
                'recipient_name' => $userName,
                'access_token' => $accessToken,
                'status' => NotificationRequest::STATUS_APPROVED, // Auto-approve since admin already approved
                'request_metadata' => [
                    'report_type' => $session->matched_record_type,
                    'report_generated_at' => $session->report_generated_at,
                    'session_created_at' => $session->created_at,
                    'has_pdf_report' => $session->has_pdf_report,
                    'is_instant_estate' => true,
                    'admin_approved' => true,
                ],
                'pdf_content' => $pdfBase64,
            ]);
            
            // Mark session as notification requested
            $session->markNotificationRequested($email, $notificationRequest->id);
            
            // Send email immediately since admin already approved
            $this->sendInstantEstateNotificationEmail($notificationRequest, $session, $email);
            
            Log::info('Notification requested and email sent (authenticated user)', [
                'session_id' => $sessionId,
                'notification_request_id' => $notificationRequest->id,
                'recipient_email' => $email,
                'user_id' => Auth::id(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Report has been sent to your email address!',
                'request_id' => $notificationRequest->id,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Notification request failed', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to submit request: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Send instant-estate notification email
     */
    protected function sendInstantEstateNotificationEmail(NotificationRequest $notificationRequest, InstantEstateSession $session, string $email): void
    {
        $accessUrl = route('instant-estate.public-view', ['token' => $notificationRequest->access_token]);
        
        $data = [
            'deceased_name' => $session->deceased_name ?? 'the deceased',
            'recipient_name' => $notificationRequest->recipient_name ?? 'Valued User',
            'access_token' => $notificationRequest->access_token,
            'access_url' => $accessUrl,
            'expiry_days' => 30,
            'has_pdf_attached' => $notificationRequest->has_pdf_content,
            'report_type' => $session->matched_record_type === InstantEstateSession::RECORD_TYPE_ESTATE_PLAN ? 'estate_plan' : 'calculation',
            'session_id' => $session->session_id,
            'request_id' => $notificationRequest->id,
            'date' => now()->format('d M Y, h:i A'),
        ];
        
        try {
            Mail::send('emails.inheritance-report', $data, function ($message) use ($email, $notificationRequest, $session) {
                $message->to($email)
                        ->subject('Your Inheritance Report - ' . ($session->deceased_name ?? 'Estate Report'));
                
                if ($notificationRequest->has_pdf_content) {
                    $pdfContent = $notificationRequest->getDecodedPdfContentAttribute();
                    if ($pdfContent) {
                        $message->attachData($pdfContent, 'inheritance_report.pdf', [
                            'mime' => 'application/pdf',
                        ]);
                    }
                }
            });
            
            $notificationRequest->update([
                'notification_sent' => true,
                'notification_sent_at' => now(),
                'email_status' => NotificationRequest::EMAIL_STATUS_SENT,
            ]);
            
            Log::info('Instant estate notification email sent', [
                'notification_id' => $notificationRequest->id,
                'session_id' => $session->session_id,
                'recipient_email' => $email,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send instant-estate notification email', [
                'session_id' => $session->session_id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
            
            $notificationRequest->update([
                'email_status' => NotificationRequest::EMAIL_STATUS_FAILED,
                'error_message' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    /**
     * Public view for instant-estate report (accessed via token from email)
     */
    public function publicView(string $token)
    {
        $notificationRequest = NotificationRequest::where('access_token', $token)
            ->where('status', NotificationRequest::STATUS_APPROVED)
            ->first();
        
        if (!$notificationRequest) {
            $notificationRequest = NotificationRequest::where('access_token', $token)
                ->whereIn('status', [
                    NotificationRequest::STATUS_PENDING_ADMIN_APPROVAL,
                    NotificationRequest::STATUS_SENT,
                ])
                ->first();
        }
        
        if (!$notificationRequest) {
            $hashedToken = hash('sha256', $token);
            $notificationRequest = NotificationRequest::where('access_token', $hashedToken)
                ->where('status', NotificationRequest::STATUS_APPROVED)
                ->first();
        }
        
        Log::info('Public view access attempt', [
            'token' => substr($token, 0, 20) . '...',
            'found' => !is_null($notificationRequest),
            'status' => $notificationRequest?->status,
            'notification_id' => $notificationRequest?->id,
            'ip' => request()->ip(),
        ]);
        
        if (!$notificationRequest) {
            abort(404, 'Invalid or expired access link. Please contact the estate administrator.');
        }
        
        if ($notificationRequest->status === NotificationRequest::STATUS_PENDING_ADMIN_APPROVAL) {
            return view('instant-estate.pending-approval', [
                'deceased_name' => $notificationRequest->deceased_name ?? 'the deceased',
                'request_id' => $notificationRequest->id,
                'requested_at' => $notificationRequest->created_at,
                'recipient_email' => $notificationRequest->recipient_email,
                'support_email' => config('mail.support.address', 'neofaraidadmin@gmail.com'),
                'access_token' => $token,
            ]);
        }
        
        $session = $notificationRequest->instantEstateSession;
        
        if (!$session || !$session->report_data) {
            if ($notificationRequest->session_id) {
                $session = InstantEstateSession::where('session_id', $notificationRequest->session_id)->first();
            }
            
            if (!$session || !$session->report_data) {
                abort(404, 'Report data not found. Please complete the process first.');
            }
        }
        
        Log::info('Instant estate report accessed via public link', [
            'notification_id' => $notificationRequest->id,
            'session_id' => $session->session_id,
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
        
        $reportData = $session->report_data;
        
        $metadata = $notificationRequest->request_metadata ?? [];
        $accessCount = ($metadata['access_count'] ?? 0) + 1;
        $metadata['access_count'] = $accessCount;
        $metadata['last_accessed_at'] = now()->toIso8601String();
        $metadata['last_accessed_ip'] = request()->ip();
        $notificationRequest->update(['request_metadata' => $metadata]);
        
        $viewData = $this->prepareReportViewData($session, $reportData);
        $viewData['is_instant_estate'] = true;
        $viewData['access_token'] = $token;
        $viewData['notification_id'] = $notificationRequest->id;
        $viewData['is_pending'] = false;
        
        if (isset($reportData['report_type']) && $reportData['report_type'] === 'estate_plan') {
            return view('instant-estate.report-estate-plan', $viewData);
        } elseif (isset($reportData['report_type']) && $reportData['report_type'] === 'calculation') {
            return view('instant-estate.report-calculation', $viewData);
        } else {
            return view('instant-estate.generic-report', $viewData);
        }
    }
    
    /**
     * Download report from notification token
     */
    public function downloadReportFromToken(string $token)
    {
        $notificationRequest = NotificationRequest::where('access_token', $token)
            ->where('status', NotificationRequest::STATUS_APPROVED)
            ->first();
        
        if (!$notificationRequest) {
            abort(403, 'Invalid or expired link.');
        }
        
        if (!$notificationRequest->has_pdf_content) {
            abort(404, 'PDF report not found.');
        }
        
        $pdfContent = $notificationRequest->getDecodedPdfContentAttribute();
        $filename = 'inheritance_report_' . ($notificationRequest->deceased_name ?? 'unknown') . '_' . now()->format('Ymd') . '.pdf';
        $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
        
        return response($pdfContent, 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Get notification status for a session (for polling)
     */
    public function getNotificationStatus(string $sessionId)
    {
        try {
            $session = $this->getSession($sessionId);
            
            $notificationStatus = null;
            if ($session->notification_request_id) {
                $notificationRequest = NotificationRequest::find($session->notification_request_id);
                if ($notificationRequest) {
                    $notificationStatus = [
                        'status' => $notificationRequest->status,
                        'requested_at' => $notificationRequest->created_at,
                        'approved_at' => $notificationRequest->approved_at,
                        'rejected_at' => $notificationRequest->rejected_at,
                        'rejection_reason' => $notificationRequest->rejection_reason,
                        'sent_at' => $notificationRequest->sent_at,
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'notification_requested' => $session->notification_requested,
                'notification_status' => $notificationStatus,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }
    
    /**
     * Send approved report email (called after admin approval)
     * This method sends the email notification to the user when their report is approved
     */
    public function sendApprovedReportEmail(InstantEstateSession $session)
    {
        if (!$session->recipient_email) {
            return;
        }
        
        $notificationRequest = NotificationRequest::find($session->notification_request_id);
        if (!$notificationRequest) {
            return;
        }
        
        // Send email with access link
        $accessUrl = route('instant-estate.public-view', ['token' => $notificationRequest->access_token]);
        
        $data = [
            'deceased_name' => $session->deceased_name ?? 'the deceased',
            'recipient_name' => $notificationRequest->recipient_name ?? 'Valued User',
            'access_token' => $notificationRequest->access_token,
            'access_url' => $accessUrl,
            'expiry_days' => 30,
            'has_pdf_attached' => $notificationRequest->has_pdf_content,
            'report_type' => $session->matched_record_type === InstantEstateSession::RECORD_TYPE_ESTATE_PLAN ? 'estate_plan' : 'calculation',
            'session_id' => $session->session_id,
            'request_id' => $notificationRequest->id,
            'date' => now()->format('d M Y, h:i A'),
        ];
        
        try {
            Mail::send('emails.inheritance-report', $data, function ($message) use ($session, $notificationRequest) {
                $message->to($session->recipient_email)
                        ->subject('Your Inheritance Report - ' . ($session->deceased_name ?? 'Estate Report'));
                
                if ($notificationRequest->has_pdf_content) {
                    $pdfContent = $notificationRequest->getDecodedPdfContentAttribute();
                    if ($pdfContent) {
                        $message->attachData($pdfContent, 'inheritance_report.pdf', [
                            'mime' => 'application/pdf',
                        ]);
                    }
                }
            });
            
            Log::info('Approved report email sent', [
                'session_id' => $session->session_id,
                'recipient_email' => $session->recipient_email,
                'notification_id' => $notificationRequest->id,
            ]);
            
            $notificationRequest->update([
                'email_status' => NotificationRequest::EMAIL_STATUS_SENT,
                'notification_sent_at' => now(),
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to send approved report email', [
                'session_id' => $session->session_id,
                'recipient_email' => $session->recipient_email,
                'error' => $e->getMessage(),
            ]);
            
            $notificationRequest->update([
                'email_status' => NotificationRequest::EMAIL_STATUS_FAILED,
                'error_message' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send the report link via email (for approved sessions only).
     */
    public function sendReportLink(Request $request, string $sessionId = null)
    {
        // 1) Get session ID from route or from request body
        if (empty($sessionId) || $sessionId === 'null') {
            $sessionId = $request->input('session_id');
        }

        // 2) Validate session ID
        if (empty($sessionId) || $sessionId === 'null') {
            return response()->json([
                'success' => false,
                'message' => 'Invalid session identifier.',
            ], 400);
        }

        // 3) Validate email from request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $email = $request->input('email');

        // 4) Find the session
        try {
            $session = $this->getSession($sessionId);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Session not found or expired. Please refresh and try again.',
            ], 404);
        }

        // 5) Only approved sessions can send the report link
        if ($session->admin_status !== 'approved') {
            return response()->json([
                'success' => false,
                'message' => 'Report not approved yet. Please wait for admin approval.',
            ], 403);
        }

        // 6) Get or create access token
        $accessToken = $session->notificationRequest?->access_token;

        if (!$accessToken) {
            $notificationRequest = NotificationRequest::create([
                'session_id' => $session->session_id,
                'instant_estate_session_id' => $session->id,
                'user_id' => Auth::id(),
                'guest_email' => $email,
                'guest_name' => $session->guest_name ?? Auth::user()->name ?? 'Valued User',
                'deceased_name' => $session->deceased_name,
                'recipient_email' => $email,
                'recipient_name' => $session->guest_name ?? Auth::user()->name ?? 'Valued User',
                'access_token' => Str::random(64) . '-' . time(),
                'status' => NotificationRequest::STATUS_APPROVED,
                'request_metadata' => ['is_instant_estate' => true],
            ]);
            $accessToken = $notificationRequest->access_token;
            $session->update(['notification_request_id' => $notificationRequest->id]);
        }

        $reportUrl = route('instant-estate.public-view', ['token' => $accessToken]);

        // 7) Send email
        try {
            Mail::send('emails.report-link', [
                'deceased_name' => $session->deceased_name ?? 'the deceased',
                'report_url' => $reportUrl,
                'expiry_days' => 30,
            ], function ($message) use ($email) {
                $message->to($email)
                        ->subject('Inheritance Report');
            });

            return response()->json([
                'success' => true,
                'message' => "Report link has been sent to {$email}.",
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send report link email: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email. Please try again later.',
            ], 500);
        }
    }

    // =========================================================================
    // BENEFICIARY ACCESS METHODS (ALIGNED WITH ESTATE-SETUP)
    // =========================================================================

    /**
     * Secure view for beneficiaries (public access with token)
     */
    public function secureBeneficiaryView(string $token)
    {
        $accessLink = BeneficiaryAccessLink::where('access_token', $token)
            ->where('expires_at', '>', now())
            ->where('is_active', true)
            ->where('status', 'active')
            ->first();

        if (!$accessLink) {
            return $this->beneficiaryAccessDenied('Invalid or expired access link.', 404);
        }

        $accessLink->increment('access_count');
        $accessLink->update(['last_accessed_at' => now()]);

        $estate = EstatePreRegistration::where('id', $accessLink->estate_pre_registration_id)
            ->orWhere('unique_id', $accessLink->estate_pre_registration_id)
            ->first();

        if (!$estate) {
            return $this->beneficiaryAccessDenied('Estate record not found.', 404);
        }

        $estate->load(['heirs', 'assets', 'debts', 'wasiyyah']);

        $totalAssets = $estate->assets->sum('value');
        $totalDebts = $estate->debts->sum('amount');
        $netEstate = max(0, $totalAssets - $totalDebts);

        $totalWasiyyahPct = $estate->wasiyyah->sum('requested_percentage');
        $maxWasiyyahPct = 33.33;
        $effectiveWasiyyahPct = min($totalWasiyyahPct, $maxWasiyyahPct);
        $wasiyyahAmount = ($effectiveWasiyyahPct / 100) * $netEstate;
        $remainingForHeirs = max(0, $netEstate - $wasiyyahAmount);

        $unsettledDebts = $estate->debts->filter(function($debt) {
            return ($debt->amount_paid ?? 0) < $debt->amount;
        });
        $allDebtsSettled = $unsettledDebts->count() === 0;

        $debtSummary = [
            'total_count' => $estate->debts->count(),
            'settled_count' => $estate->debts->count() - $unsettledDebts->count(),
            'formatted_total_remaining' => 'RM ' . number_format($unsettledDebts->sum(function($debt) {
                return $debt->amount - ($debt->amount_paid ?? 0);
            }), 2),
            'formatted_total_debts' => 'RM ' . number_format($totalDebts, 2),
            'formatted_total_paid' => 'RM ' . number_format($totalDebts - $unsettledDebts->sum(function($debt) {
                return $debt->amount - ($debt->amount_paid ?? 0);
            }), 2),
            'all_settled' => $allDebtsSettled,
            'unsettled_debts' => $unsettledDebts->map(function($debt) {
                return [
                    'id' => $debt->id,
                    'creditor_name' => $debt->creditor_name,
                    'amount' => (float) $debt->amount,
                    'remaining' => (float) $debt->amount - (float) ($debt->amount_paid ?? 0),
                    'formatted_remaining' => 'RM ' . number_format($debt->amount - ($debt->amount_paid ?? 0), 2),
                    'due_date' => $debt->due_date?->format('d M Y'),
                    'description' => $debt->description ?? '',
                ];
            })->toArray(),
        ];

        $beneficiaryShare = null;
        $relationship = null;
        $sharePercentage = 0;
        $formattedAmount = 'RM 0.00';
        $heir = null;
        $wasiyyah = null;

        if ($accessLink->beneficiary_type === 'heir') {
            $heir = $estate->heirs->where('email', $accessLink->beneficiary_email)
                ->orWhere('id', $accessLink->beneficiary_id)
                ->first();
            if ($heir) {
                $sharePercentage = (float) $heir->share_percentage;
                $beneficiaryAmount = ($sharePercentage / 100) * $remainingForHeirs;
                $formattedAmount = 'RM ' . number_format($beneficiaryAmount, 2);
                $relationship = $heir->relationship_label ?? $heir->relationship;
                
                $beneficiaryShare = [
                    'type' => 'heir',
                    'name' => $heir->name,
                    'relationship' => $relationship,
                    'share_percentage' => $sharePercentage,
                    'amount' => $beneficiaryAmount,
                    'formatted_amount' => $formattedAmount,
                    'share_fraction' => $this->percentageToFraction($sharePercentage),
                ];
            }
        } elseif ($accessLink->beneficiary_type === 'wasiyyah') {
            $wasiyyah = $estate->wasiyyah->where('beneficiary_email', $accessLink->beneficiary_email)
                ->orWhere('id', $accessLink->beneficiary_id)
                ->first();
            if ($wasiyyah) {
                $sharePercentage = (float) $wasiyyah->requested_percentage;
                $beneficiaryAmount = ($sharePercentage / 100) * $netEstate;
                $formattedAmount = 'RM ' . number_format($beneficiaryAmount, 2);
                $relationship = $wasiyyah->relationship;
                
                $beneficiaryShare = [
                    'type' => 'wasiyyah',
                    'name' => $wasiyyah->beneficiary_name,
                    'relationship' => $relationship,
                    'requested_percentage' => $sharePercentage,
                    'amount' => $beneficiaryAmount,
                    'formatted_amount' => $formattedAmount,
                    'description' => $wasiyyah->description,
                    'is_charity' => $wasiyyah->is_charity ?? false,
                ];
            }
        } elseif ($accessLink->beneficiary_type === 'trustee') {
            $beneficiaryShare = [
                'type' => 'trustee',
                'name' => $accessLink->beneficiary_name,
                'role' => 'Primary Trustee',
                'responsibilities' => [
                    'Oversee and manage estate distribution',
                    'Ensure all debts are properly settled',
                    'Distribute inheritance to heirs according to Faraid principles',
                    'Distribute Wasiyyah to nominated beneficiaries',
                    'Maintain compliance with Shariah law',
                    'Submit final distribution report',
                ],
            ];
            $relationship = 'Trustee';
        }

        $heirsList = [];
        foreach ($estate->heirs as $h) {
            $amount = ((float) $h->share_percentage / 100) * $remainingForHeirs;
            $heirsList[] = [
                'id' => $h->id,
                'name' => $h->name,
                'relationship' => $h->relationship_label ?? $h->relationship,
                'percentage' => (float) $h->share_percentage,
                'amount' => round($amount, 2),
                'formatted_amount' => 'RM ' . number_format($amount, 2),
                'nric' => $this->maskNric($h->nric ?? ''),
                'email' => $h->email,
                'phone' => $h->phone,
            ];
        }

        $wasiyyahList = [];
        foreach ($estate->wasiyyah as $w) {
            $amount = ((float) $w->requested_percentage / 100) * $netEstate;
            $wasiyyahList[] = [
                'id' => $w->id,
                'name' => $w->beneficiary_name,
                'relationship' => $w->relationship,
                'percentage' => (float) $w->requested_percentage,
                'amount' => round($amount, 2),
                'formatted_amount' => 'RM ' . number_format($amount, 2),
                'description' => $w->description,
                'is_charity' => $w->is_charity ?? false,
                'email' => $w->beneficiary_email,
                'phone' => $w->beneficiary_phone,
            ];
        }

        $assetsList = [];
        foreach ($estate->assets as $asset) {
            $ownedValue = (float) $asset->value * (($asset->ownership_percentage ?? 100) / 100);
            $assetsList[] = [
                'id' => $asset->id,
                'name' => $asset->name,
                'type' => $asset->type ?? 'Other',
                'category' => $asset->category ?? 'Other',
                'value' => (float) $asset->value,
                'formatted_value' => 'RM ' . number_format($asset->value, 2),
                'ownership_percentage' => (float) ($asset->ownership_percentage ?? 100),
                'owned_value' => $ownedValue,
                'formatted_owned_value' => 'RM ' . number_format($ownedValue, 2),
                'description' => $asset->description ?? '',
                'location' => $asset->location ?? '',
            ];
        }

        $debtsList = [];
        foreach ($estate->debts as $debt) {
            $remaining = (float) $debt->amount - (float) ($debt->amount_paid ?? 0);
            $debtsList[] = [
                'id' => $debt->id,
                'creditor_name' => $debt->creditor_name,
                'type' => $debt->type ?? $debt->debt_type ?? 'Other',
                'amount' => (float) $debt->amount,
                'formatted_amount' => 'RM ' . number_format($debt->amount, 2),
                'amount_paid' => (float) ($debt->amount_paid ?? 0),
                'formatted_paid' => 'RM ' . number_format($debt->amount_paid ?? 0, 2),
                'remaining' => max(0, $remaining),
                'formatted_remaining' => 'RM ' . number_format(max(0, $remaining), 2),
                'status' => $remaining <= 0 ? 'settled' : ($debt->status ?? 'pending'),
                'description' => $debt->description ?? '',
                'due_date' => $debt->due_date?->format('d M Y'),
            ];
        }

        $hasVideo = !empty($estate->will_video_url);
        $willVideoUrl = $estate->will_video_url;
        $willTextContent = $estate->will_text_content;

        return view('beneficiary.access-view', [
            'link' => $accessLink,
            'estate' => $estate,
            'beneficiaryName' => $accessLink->beneficiary_name,
            'beneficiaryType' => $accessLink->beneficiary_type,
            'beneficiaryShare' => $beneficiaryShare,
            'sharePercentage' => $sharePercentage,
            'formattedAmount' => $formattedAmount,
            'relationship' => $relationship,
            'deceasedName' => $estate->deceased_name,
            'deceasedNric' => $this->maskNric($estate->deceased_nric),
            'deceasedEmail' => $estate->contact_email,
            'deceasedPhone' => $estate->contact_phone,
            'deceasedAddress' => $estate->address,
            'deceasedDob' => $estate->date_of_birth?->format('d F Y'),
            'deceasedGender' => $estate->gender === 'male' ? 'Male' : 'Female',
            'trusteeName' => $estate->trustee_name,
            'trusteeEmail' => $estate->trustee_email,
            'trusteePhone' => $estate->trustee_phone,
            'trusteeRelationship' => $estate->trustee_relationship,
            'totalAssets' => $totalAssets,
            'formattedTotalAssets' => 'RM ' . number_format($totalAssets, 2),
            'totalDebts' => $totalDebts,
            'formattedTotalDebts' => 'RM ' . number_format($totalDebts, 2),
            'netEstate' => $netEstate,
            'formattedNetEstate' => 'RM ' . number_format($netEstate, 2),
            'totalWasiyyahPct' => $totalWasiyyahPct,
            'maxWasiyyahPct' => $maxWasiyyahPct,
            'effectiveWasiyyahPct' => $effectiveWasiyyahPct,
            'wasiyyahAmount' => $wasiyyahAmount,
            'formattedWasiyyahAmount' => 'RM ' . number_format($wasiyyahAmount, 2),
            'remainingForHeirs' => $remainingForHeirs,
            'formattedRemainingForHeirs' => 'RM ' . number_format($remainingForHeirs, 2),
            'totalHeirPct' => $estate->heirs->sum('share_percentage'),
            'debtSummary' => $debtSummary,
            'heirsList' => $heirsList,
            'wasiyyahList' => $wasiyyahList,
            'assetsList' => $assetsList,
            'debtsList' => $debtsList,
            'heirs' => $estate->heirs,
            'assets' => $estate->assets,
            'debts' => $estate->debts,
            'wasiyyah' => $estate->wasiyyah,
            'expiryDate' => $accessLink->expires_at->format('d F Y, h:i A'),
            'accessUrl' => route('beneficiary.access', ['token' => $accessLink->access_token]),
            'hasVideo' => $hasVideo,
            'willVideoUrl' => $willVideoUrl,
            'willTextContent' => $willTextContent,
            'isShariahCompliant' => $estate->is_shariah_compliant ?? true,
            'generatedAt' => now()->format('d F Y, h:i A'),
            'documentId' => 'EST-' . $estate->id . '-' . now()->format('Ymd'),
            'heir' => $heir,
            'wasiyyah' => $wasiyyah,
        ]);
    }

    /**
     * Handle beneficiary access denied responses
     */
    protected function beneficiaryAccessDenied(string $message, int $statusCode = 403)
    {
        if (request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], $statusCode);
        }
        
        return response()->view('errors.access-denied', [
            'message' => $message,
            'support_email' => config('mail.support.address', 'support@neofaraid.com'),
        ], $statusCode);
    }

    /**
     * Verify beneficiary token
     */
    public function verifyBeneficiaryToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $token = $request->input('token');
        
        $accessLink = BeneficiaryAccessLink::where('access_token', $token)
            ->where('expires_at', '>', now())
            ->where('is_active', true)
            ->first();
        
        if (!$accessLink) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired access token.',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'redirect_url' => route('instant-estate.public-view', $token),
        ]);
    }

    /**
     * View will with token
     */
    public function viewWill(string $sessionId)
    {
        $session = $this->getSession($sessionId);
        
        $willContent = null;
        if ($session->matched_record_type === InstantEstateSession::RECORD_TYPE_ESTATE_PLAN && $session->matched_record_id) {
            $estate = EstatePreRegistration::find($session->matched_record_id);
            if ($estate) {
                $willContent = $estate->will_text_content;
            }
        }
        
        return view('instant-estate.beneficiary-view', [
            'session' => $session,
            'willContent' => $willContent,
            'debts' => collect([]),
            'calculation' => null,
            'canViewInheritance' => $session->admin_status === 'approved',
        ]);
    }

    /**
     * Record will access
     */
    public function recordWillAccess(Request $request, string $sessionId)
    {
        try {
            $session = $this->getSession($sessionId);
            
            Log::info('Will accessed (public)', [
                'session_id' => $sessionId,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Access recorded successfully',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================================
    // REPORT GENERATION METHODS
    // =========================================================================

    /**
     * Generate report data from matched record
     */
    protected function generateReportFromMatch($record, string $recordType, InstantEstateSession $session): array
    {
        if ($recordType === InstantEstateSession::RECORD_TYPE_ESTATE_PLAN) {
            return $this->generateReportFromEstatePlan($record, $session);
        } else {
            return $this->generateReportFromCalculation($record, $session);
        }
    }

    /**
     * Generate report from EstatePreRegistration record
     */
    protected function generateReportFromEstatePlan(EstatePreRegistration $estate, InstantEstateSession $session): array
    {
        $estate->load(['heirs', 'assets', 'debts', 'wasiyyah']);
        
        $totalAssets = $estate->assets->sum('value');
        $totalDebts = $estate->debts->sum('amount');
        $netEstate = max(0, $totalAssets - $totalDebts);
        
        $totalWasiyyahPct = $estate->wasiyyah->sum('requested_percentage');
        $maxWasiyyahPct = 33.33;
        $effectiveWasiyyahPct = min($totalWasiyyahPct, $maxWasiyyahPct);
        $wasiyyahAmount = ($effectiveWasiyyahPct / 100) * $netEstate;
        $remainingForHeirs = $netEstate - $wasiyyahAmount;
        
        $heirs = $estate->heirs->map(function($heir) use ($remainingForHeirs) {
            $amount = ($heir->share_percentage / 100) * $remainingForHeirs;
            return [
                'id' => $heir->id,
                'name' => $heir->name,
                'nric' => $this->maskNric($heir->nric ?? ''),
                'relationship' => $heir->relationship,
                'relationship_type' => $heir->relationship_type ?? 'primary',
                'share_percentage' => (float) $heir->share_percentage,
                'share_amount' => round($amount, 2),
                'formatted_amount' => 'RM ' . number_format($amount, 2),
                'share_fraction' => $this->percentageToFraction($heir->share_percentage),
                'email' => $this->maskEmail($heir->email ?? ''),
                'phone' => $this->maskPhone($heir->phone ?? ''),
            ];
        })->toArray();
        
        $assets = $estate->assets->map(function($asset) {
            $ownedValue = (float) $asset->value * (($asset->ownership_percentage ?? 100) / 100);
            return [
                'id' => $asset->id,
                'name' => $asset->name,
                'type' => $asset->type ?? 'Other',
                'category' => $asset->category ?? 'Other',
                'value' => (float) $asset->value,
                'formatted_value' => 'RM ' . number_format($asset->value, 2),
                'ownership_percentage' => (float) ($asset->ownership_percentage ?? 100),
                'owned_value' => $ownedValue,
                'formatted_owned_value' => 'RM ' . number_format($ownedValue, 2),
                'description' => $asset->description ?? '',
                'location' => $asset->location ?? '',
                'reference_number' => $asset->reference_number ?? '',
            ];
        })->toArray();
        
        $debts = $estate->debts->map(function($debt) {
            return [
                'id' => $debt->id,
                'creditor_name' => $debt->creditor_name,
                'type' => $debt->type ?? $debt->debt_type ?? 'Other',
                'amount' => (float) $debt->amount,
                'formatted_amount' => 'RM ' . number_format($debt->amount, 2),
                'description' => $debt->description ?? '',
                'due_date' => $debt->due_date ? $debt->due_date->format('Y-m-d') : null,
                'reference_number' => $debt->reference_number ?? '',
            ];
        })->toArray();
        
        $wasiyyah = $estate->wasiyyah->map(function($was) use ($wasiyyahAmount, $totalWasiyyahPct) {
            $effectivePct = $totalWasiyyahPct > 0 
                ? ($was->requested_percentage / $totalWasiyyahPct) * min($totalWasiyyahPct, 33.33)
                : 0;
            $amount = ($was->requested_percentage / 100) * $wasiyyahAmount;
            
            return [
                'id' => $was->id,
                'beneficiary_name' => $was->beneficiary_name,
                'nric' => $this->maskNric($was->beneficiary_nric ?? ''),
                'relationship' => $was->relationship,
                'requested_percentage' => (float) $was->requested_percentage,
                'effective_percentage' => round($effectivePct, 2),
                'amount' => round($amount, 2),
                'formatted_amount' => 'RM ' . number_format($amount, 2),
                'is_charity' => $was->is_charity ?? false,
                'organization_name' => $was->beneficiary_organization_name ?? null,
                'email' => $this->maskEmail($was->beneficiary_email ?? ''),
                'phone' => $this->maskPhone($was->beneficiary_phone ?? ''),
            ];
        })->toArray();
        
        return [
            'report_type' => 'estate_plan',
            'estate_id' => $estate->id,
            'estate_unique_id' => $estate->unique_id,
            'deceased_name' => $estate->deceased_name,
            'deceased_nric' => $this->maskNric($estate->deceased_nric),
            'deceased_gender' => $estate->gender,
            'deceased_dob' => $estate->date_of_birth ? $estate->date_of_birth->format('Y-m-d') : null,
            'deceased_address' => $estate->address,
            'date_of_death' => $session->death_date ? $session->death_date->format('Y-m-d') : null,
            'total_assets' => $totalAssets,
            'formatted_total_assets' => 'RM ' . number_format($totalAssets, 2),
            'total_debts' => $totalDebts,
            'formatted_total_debts' => 'RM ' . number_format($totalDebts, 2),
            'net_estate' => $netEstate,
            'formatted_net_estate' => 'RM ' . number_format($netEstate, 2),
            'total_wasiyyah_pct' => $totalWasiyyahPct,
            'effective_wasiyyah_pct' => $effectiveWasiyyahPct,
            'wasiyyah_amount' => $wasiyyahAmount,
            'formatted_wasiyyah_amount' => 'RM ' . number_format($wasiyyahAmount, 2),
            'remaining_for_heirs' => $remainingForHeirs,
            'formatted_remaining_for_heirs' => 'RM ' . number_format($remainingForHeirs, 2),
            'heirs' => $heirs,
            'assets' => $assets,
            'debts' => $debts,
            'wasiyyah' => $wasiyyah,
            'trustee_name' => $estate->trustee_name,
            'trustee_nric' => $this->maskNric($estate->trustee_nric ?? ''),
            'trustee_email' => $this->maskEmail($estate->trustee_email ?? ''),
            'trustee_phone' => $this->maskPhone($estate->trustee_phone ?? ''),
            'trustee_relationship' => $estate->trustee_relationship,
            'generated_at' => now()->toISOString(),
            'status' => $estate->status,
            'admin_approved' => $estate->admin_approved,
            'is_shariah_compliant' => $estate->is_shariah_compliant ?? true,
        ];
    }

    /**
     * Generate report from Calculation record
     */
    protected function generateReportFromCalculation(Calculation $calculation, InstantEstateSession $session): array
    {
        $heirsData = $calculation->heirs_data ?? [];
        $calculationData = $calculation->calculation_data ?? [];
        
        $heirs = [];
        if (isset($heirsData['heirs']) && is_array($heirsData['heirs'])) {
            foreach ($heirsData['heirs'] as $heir) {
                $heirs[] = [
                    'name' => $heir['name'] ?? 'Unknown',
                    'relationship' => $heir['relationship'] ?? 'Unknown',
                    'share' => $heir['share'] ?? '0',
                    'amount' => $heir['amount'] ?? 0,
                    'formatted_amount' => 'RM ' . number_format($heir['amount'] ?? 0, 2),
                    'percentage' => $heir['percentage'] ?? 0,
                ];
            }
        }
        
        return [
            'report_type' => 'calculation',
            'calculation_id' => $calculation->id,
            'deceased_name' => $calculation->deceased_name,
            'deceased_gender' => $calculation->deceased_gender,
            'date_of_death' => $calculation->date_of_death ? $calculation->date_of_death->format('Y-m-d') : null,
            'marital_status' => $calculation->marital_status,
            'total_assets' => (float) $calculation->total_assets,
            'formatted_total_assets' => 'RM ' . number_format($calculation->total_assets, 2),
            'net_assets' => (float) $calculation->net_assets,
            'formatted_net_assets' => 'RM ' . number_format($calculation->net_assets, 2),
            'heirs_summary' => $heirsData,
            'calculation_data' => $calculationData,
            'heirs' => $heirs,
            'total_heirs' => $calculation->total_heirs,
            'family_composition' => [
                'wife_count' => $calculation->wife_count,
                'husband_count' => $calculation->husband_count,
                'father_status' => $calculation->father_status,
                'mother_status' => $calculation->mother_status,
                'son_count' => $calculation->son_count,
                'daughter_count' => $calculation->daughter_count,
                'full_brother_count' => $calculation->full_brother_count,
                'full_sister_count' => $calculation->full_sister_count,
                'paternal_brother_count' => $calculation->paternal_brother_count,
                'paternal_sister_count' => $calculation->paternal_sister_count,
                'maternal_sibling_count' => $calculation->maternal_sibling_count,
            ],
            'generated_at' => now()->toISOString(),
        ];
    }

    /**
     * Create beneficiary access links for matched estate plan
     */
    protected function createBeneficiaryAccessLinks(InstantEstateSession $session): void
    {
        if ($session->matched_record_type !== InstantEstateSession::RECORD_TYPE_ESTATE_PLAN || !$session->matched_record_id) {
            return;
        }
        
        $estate = EstatePreRegistration::find($session->matched_record_id);
        if (!$estate || !$estate->admin_approved) {
            return;
        }
        
        foreach ($estate->heirs as $heir) {
            if ($heir->email && !BeneficiaryAccessLink::where('beneficiary_email', $heir->email)->where('estate_pre_registration_id', $estate->id)->exists()) {
                BeneficiaryAccessLink::create([
                    'estate_pre_registration_id' => $estate->id,
                    'beneficiary_type' => 'heir',
                    'beneficiary_id' => $heir->id,
                    'beneficiary_name' => $heir->name,
                    'beneficiary_email' => $heir->email,
                    'access_token' => BeneficiaryAccessLink::generateToken(),
                    'expires_at' => now()->addDays(30),
                    'status' => 'active',
                    'is_active' => true,
                    'access_count' => 0,
                ]);
            }
        }
        
        foreach ($estate->wasiyyah as $wasiyyah) {
            if ($wasiyyah->beneficiary_email && !BeneficiaryAccessLink::where('beneficiary_email', $wasiyyah->beneficiary_email)->where('estate_pre_registration_id', $estate->id)->exists()) {
                BeneficiaryAccessLink::create([
                    'estate_pre_registration_id' => $estate->id,
                    'beneficiary_type' => 'wasiyyah',
                    'beneficiary_id' => $wasiyyah->id,
                    'beneficiary_name' => $wasiyyah->beneficiary_name,
                    'beneficiary_email' => $wasiyyah->beneficiary_email,
                    'access_token' => BeneficiaryAccessLink::generateToken(),
                    'expires_at' => now()->addDays(30),
                    'status' => 'active',
                    'is_active' => true,
                    'access_count' => 0,
                ]);
            }
        }
        
        if ($estate->trustee_email && !BeneficiaryAccessLink::where('beneficiary_email', $estate->trustee_email)->where('estate_pre_registration_id', $estate->id)->exists()) {
            BeneficiaryAccessLink::create([
                'estate_pre_registration_id' => $estate->id,
                'beneficiary_type' => 'trustee',
                'beneficiary_name' => $estate->trustee_name,
                'beneficiary_email' => $estate->trustee_email,
                'access_token' => BeneficiaryAccessLink::generateToken(),
                'expires_at' => now()->addDays(30),
                'status' => 'active',
                'is_active' => true,
                'access_count' => 0,
            ]);
        }
        
        Log::info('Beneficiary access links created', [
            'session_id' => $session->session_id,
            'estate_id' => $estate->id,
        ]);
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    /**
     * Get session by ID with optional user validation (public access)
     */
    protected function getSession(string $sessionId): InstantEstateSession
    {
        $query = InstantEstateSession::where('session_id', $sessionId);
        
        $session = $query->first();
        
        if (!$session) {
            abort(404, 'Session not found');
        }
        
        if ($session->expires_at && $session->expires_at->isPast()) {
            abort(410, 'Session has expired');
        }
        
        return $session;
    }

    /**
     * Get session by session ID (alias for getSession)
     */
    public function getSessionByToken(string $sessionId): ?InstantEstateSession
    {
        try {
            return $this->getSession($sessionId);
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Calculate confidence score based on extracted fields
     */
    protected function calculateConfidence(array $extractedData): int
    {
        $score = 0;
        
        if (!empty($extractedData['deceased_name'])) $score += 30;
        if (!empty($extractedData['deceased_nric'])) $score += 30;
        if (!empty($extractedData['death_date'])) $score += 15;
        if (!empty($extractedData['death_place'])) $score += 10;
        if (!empty($extractedData['cause_of_death'])) $score += 10;
        if (!empty($extractedData['gender'])) $score += 5;
        
        return min(100, $score);
    }

    /**
     * Identify missing required fields
     */
    protected function identifyMissingFields(array $data): array
    {
        $missing = [];
        $requiredFields = InstantEstateSession::REQUIRED_FIELDS;
        
        foreach ($requiredFields as $field => $label) {
            if (empty($data[$field])) {
                $missing[] = $field;
            }
        }
        
        return $missing;
    }

    /**
     * Mask NRIC for privacy
     */
    protected function maskNric(?string $nric): string
    {
        if (empty($nric)) return 'N/A';
        
        $clean = preg_replace('/[^0-9]/', '', $nric);
        
        if (strlen($clean) >= 8) {
            return '******-' . substr($clean, -4, 2) . '-' . substr($clean, -2);
        }
        
        return '******';
    }

    /**
     * Mask email for privacy
     */
    protected function maskEmail(?string $email): string
    {
        if (empty($email)) return 'N/A';
        
        $parts = explode('@', $email);
        if (count($parts) !== 2) return '***@***.***';
        
        $name = $parts[0];
        $domain = $parts[1];
        
        $maskedName = substr($name, 0, 1) . str_repeat('*', max(0, strlen($name) - 1));
        
        return $maskedName . '@' . $domain;
    }

    /**
     * Mask phone number for privacy
     */
    protected function maskPhone(?string $phone): string
    {
        if (empty($phone)) return 'N/A';
        
        $clean = preg_replace('/[^0-9]/', '', $phone);
        
        if (strlen($clean) >= 7) {
            return substr($clean, 0, 3) . '-****' . substr($clean, -3);
        }
        
        return '***-****';
    }

    /**
     * Convert percentage to simplified fraction
     */
    protected function percentageToFraction(float $percentage): string
    {
        $fractions = [
            50 => '1/2',
            33.33 => '1/3',
            25 => '1/4',
            20 => '1/5',
            16.67 => '1/6',
            12.5 => '1/8',
            10 => '1/10',
            66.67 => '2/3',
            75 => '3/4',
            100 => 'Full Share',
        ];
        
        $closest = null;
        $closestDiff = PHP_FLOAT_MAX;
        
        foreach ($fractions as $pct => $frac) {
            $diff = abs($percentage - $pct);
            if ($diff < $closestDiff && $diff < 2) {
                $closestDiff = $diff;
                $closest = $frac;
            }
        }
        
        return $closest ?? number_format($percentage, 2) . '%';
    }

    /**
     * Map marital status for calculator
     */
    protected function mapMaritalStatus(?string $status): string
    {
        $mapping = [
            'married' => 'married',
            'berkahwin' => 'married',
            'single' => 'single',
            'bujang' => 'single',
            'divorced' => 'divorced',
            'bercerai' => 'divorced',
            'widowed' => 'widowed',
            'janda' => 'widowed',
            'duda' => 'widowed',
        ];
        
        return $mapping[strtolower($status ?? '')] ?? 'single';
    }

    // =========================================================================
    // HISTORY & LISTING METHODS
    // =========================================================================

    /**
     * Display user's processing history (based on session or logged-in user)
     */
    public function history()
    {
        $query = InstantEstateSession::query();

        // If user is logged in, show all their sessions
        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } 
        // Otherwise, use guest identifiers stored in session
        else {
            $guestEmail = session()->get('instant_estate_email');
            $currentSessionId = session()->get('instant_estate_session_id');
            
            $query->where(function ($q) use ($guestEmail, $currentSessionId) {
                if ($currentSessionId) {
                    $q->where('session_id', $currentSessionId);
                }
                if ($guestEmail) {
                    $q->orWhere('guest_email', $guestEmail);
                }
                // If neither exists, force no results
                if (!$currentSessionId && !$guestEmail) {
                    $q->whereRaw('1 = 0');
                }
            });
        }

        $sessions = $query->whereNotNull('session_id')
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);

        return view('instant-estate.history', compact('sessions'));
    }

    /**
     * View a specific session
     */
    public function viewSession(string $sessionId)
    {
        $session = $this->getSession($sessionId);
        
        if ($session->matched_record_type === InstantEstateSession::RECORD_TYPE_ESTATE_PLAN && $session->matched_record_id) {
            try {
                $session->load('matchedRecord');
            } catch (\Exception $e) {
                Log::warning('Could not load matchedRecord relationship', [
                    'session_id' => $sessionId,
                    'error' => $e->getMessage(),
                ]);
            }
        }
        
        session()->put('instant_estate_session_id', $sessionId);
        if ($session->guest_email) {
            session()->put('instant_estate_email', $session->guest_email);
        }
        
        return view('instant-estate.session', compact('session'));
    }

    /**
     * Delete a session
     */
    public function deleteSession(string $sessionId)
    {
        // Validate that the session ID is a valid UUID
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $sessionId)) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid session ID format.',
                ], 400);
            }
            return redirect()->route('instant-estate.history')
                ->with('error', 'Invalid session ID.');
        }

        try {
            $session = $this->getSession($sessionId);
            
            $session->deleteFile();
            $session->deletePdfReport();
            $session->delete();
            
            if (session()->get('instant_estate_session_id') === $sessionId) {
                session()->forget('instant_estate_session_id');
            }
            
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Session deleted successfully',
                    'redirect_url' => route('instant-estate.history'),
                ]);
            }
            
            return redirect()->route('instant-estate.history')
                ->with('success', 'Session deleted successfully.');
            
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete session: ' . $e->getMessage(),
                ], 500);
            }
            
            return redirect()->route('instant-estate.history')
                ->with('error', 'Failed to delete session: ' . $e->getMessage());
        }
    }

    /**
     * Cancel processing
     */
    public function cancelProcessing(string $sessionId)
    {
        try {
            $session = $this->getSession($sessionId);
            $session->update(['status' => InstantEstateSession::STATUS_CANCELLED]);
            
            return response()->json([
                'success' => true,
                'message' => 'Processing cancelled successfully',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Retry processing a failed session
     */
    public function retryProcessing(string $sessionId)
    {
        try {
            $session = $this->getSession($sessionId);
            
            if ($session->status !== InstantEstateSession::STATUS_FAILED) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only failed sessions can be retried.',
                ], 400);
            }
            
            $session->update([
                'status' => InstantEstateSession::STATUS_UPLOADED,
                'error_message' => null,
            ]);
            
            $this->processOCR($sessionId);
            
            return response()->json([
                'success' => true,
                'message' => 'Session reset. Processing OCR again.',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get extracted data for a session
     */
    public function getExtractedData(string $sessionId)
    {
        try {
            $session = $this->getSession($sessionId);
            
            return response()->json([
                'success' => true,
                'extracted_data' => $session->extracted_data,
                'missing_fields' => $session->missing_fields,
                'ocr_confidence' => $session->ocr_confidence,
                'level2_errors' => $session->level2_validation_errors,
                'level2_warnings' => $session->level2_validation_warnings,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 404);
        }
    }

    /**
     * Auto-fill calculator with extracted data
     */
    public function autoFillCalculator(string $sessionId)
    {
        try {
            $session = $this->getSession($sessionId);
            
            $calculatorData = [
                'deceased_name' => $session->deceased_name,
                'deceased_gender' => $session->gender === 'male' ? 'male' : 'female',
                'date_of_death' => $session->death_date ? $session->death_date->format('Y-m-d') : null,
                'marital_status' => $this->mapMaritalStatus($session->marital_status),
            ];
            
            session(['instant_estate_calculator_data' => $calculatorData]);
            
            return redirect()->route('calculator.create')->with([
                'instant_estate_data' => $calculatorData,
                'session_id' => $sessionId,
            ]);
            
        } catch (\Exception $e) {
            return redirect()->route('calculator.create')
                ->with('error', 'Failed to auto-fill calculator: ' . $e->getMessage());
        }
    }

    /**
     * Redirect to manual calculator
     */
    public function redirectToManualCalculator(string $sessionId)
    {
        try {
            $session = $this->getSession($sessionId);
            
            $data = [
                'source' => 'instant_estate',
                'session_id' => $sessionId,
                'deceased_name' => $session->deceased_name,
                'deceased_nric' => $session->deceased_nric,
                'death_date' => $session->death_date ? $session->death_date->format('Y-m-d') : '',
            ];
            
            return redirect()->route('calculator.create', $data);
            
        } catch (\Exception $e) {
            return redirect()->route('calculator.create')
                ->with('error', 'Failed to redirect to calculator: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // STATISTICS METHODS
    // =========================================================================

    /**
     * Display statistics (based on browser session)
     */
    public function statistics()
    {
        $guestEmail = session()->get('instant_estate_email');
        $currentSessionId = session()->get('instant_estate_session_id');
        
        $query = InstantEstateSession::query();
        
        if ($currentSessionId) {
            $query->where(function($q) use ($currentSessionId, $guestEmail) {
                $q->where('session_id', $currentSessionId);
                if ($guestEmail) {
                    $q->orWhere('guest_email', $guestEmail);
                }
            });
        } elseif ($guestEmail) {
            $query->where('guest_email', $guestEmail);
        } else {
            $query->whereRaw('1 = 0');
        }
        
        $stats = [
            'total' => $query->count(),
            'completed' => (clone $query)->whereIn('status', [
                    InstantEstateSession::STATUS_COMPLETED,
                    InstantEstateSession::STATUS_EMAIL_SENT,
                    InstantEstateSession::STATUS_RECORD_FOUND
                ])->where('admin_status', 'approved')->count(),
            'processing' => (clone $query)->whereIn('status', [
                    InstantEstateSession::STATUS_UPLOADED,
                    InstantEstateSession::STATUS_PROCESSING_OCR,
                    InstantEstateSession::STATUS_OCR_COMPLETED,
                    InstantEstateSession::STATUS_DATA_CONFIRMED,
                    InstantEstateSession::STATUS_CAPTCHA_VERIFIED
                ])->count(),
            'failed' => (clone $query)->where('status', InstantEstateSession::STATUS_FAILED)->count(),
            'pending_review' => (clone $query)->where('admin_status', 'pending_review')->count(),
            'pending_approval' => (clone $query)->where('admin_status', 'pending_approval')->count(),
            'rejected' => (clone $query)->where('admin_status', 'rejected')->count(),
            'approved' => (clone $query)->where('admin_status', 'approved')->count(),
        ];
        
        $stats['saved_time'] = ($stats['completed'] + $stats['processing']) * 5;
        
        return view('instant-estate.statistics', compact('stats'));
    }

    /**
     * Get user statistics for API (based on session)
     */
    public function getUserStatistics()
    {
        $guestEmail = session()->get('instant_estate_email');
        $currentSessionId = session()->get('instant_estate_session_id');
        
        $query = InstantEstateSession::query();
        
        if ($currentSessionId) {
            $query->where(function($q) use ($currentSessionId, $guestEmail) {
                $q->where('session_id', $currentSessionId);
                if ($guestEmail) {
                    $q->orWhere('guest_email', $guestEmail);
                }
            });
        } elseif ($guestEmail) {
            $query->where('guest_email', $guestEmail);
        } else {
            $stats = [
                'total_sessions' => 0,
                'completed_sessions' => 0,
                'average_confidence' => 0,
                'last_upload' => null,
            ];
            return response()->json($stats);
        }
        
        $stats = [
            'total_sessions' => $query->count(),
            'completed_sessions' => (clone $query)->whereIn('status', [InstantEstateSession::STATUS_COMPLETED, InstantEstateSession::STATUS_EMAIL_SENT, InstantEstateSession::STATUS_RECORD_FOUND])->where('admin_status', 'approved')->count(),
            'average_confidence' => (float) (clone $query)->whereNotNull('ocr_confidence')->avg('ocr_confidence') ?? 0,
            'last_upload' => (clone $query)->latest()->first()?->created_at,
            'admin_status' => (clone $query)->latest()->first()?->admin_status,
        ];
        
        return response()->json($stats);
    }

    // =========================================================================
    // DEBT MANAGEMENT METHODS
    // =========================================================================

    /**
     * Get debts for a session
     */
    public function getDebts(string $sessionId)
    {
        try {
            $session = $this->getSession($sessionId);
            
            $debts = [];
            if ($session->matched_record_type === InstantEstateSession::RECORD_TYPE_ESTATE_PLAN && $session->matched_record_id) {
                $estate = EstatePreRegistration::find($session->matched_record_id);
                if ($estate) {
                    $debts = $estate->debts()->get();
                }
            }
            
            return response()->json([
                'success' => true,
                'debts' => $debts,
                'total_debts' => $debts->sum('amount'),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add debt to session
     */
    public function addDebt(Request $request, string $sessionId)
    {
        $validator = Validator::make($request->all(), [
            'creditor_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:1000',
            'due_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $session = $this->getSession($sessionId);
            
            $debts = $session->metadata['debts'] ?? [];
            $debts[] = [
                'id' => Str::uuid(),
                'creditor_name' => $request->input('creditor_name'),
                'amount' => (float) $request->input('amount'),
                'description' => $request->input('description'),
                'due_date' => $request->input('due_date'),
                'created_at' => now(),
            ];
            
            $metadata = $session->metadata ?? [];
            $metadata['debts'] = $debts;
            $session->update(['metadata' => $metadata]);
            
            return response()->json([
                'success' => true,
                'debt' => end($debts),
                'message' => 'Debt added successfully',
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add debt: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Record debt payment
     */
    public function recordDebtPayment(Request $request, string $sessionId, string $debtId)
    {
        $validator = Validator::make($request->all(), [
            'amount_paid' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string|max:100',
            'reference_number' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        try {
            $session = $this->getSession($sessionId);
            
            $debts = $session->metadata['debts'] ?? [];
            $found = false;
            $debt = null;
            
            foreach ($debts as &$dbt) {
                if ($dbt['id'] == $debtId) {
                    $payments = $dbt['payments'] ?? [];
                    $payments[] = [
                        'amount' => (float) $request->input('amount_paid'),
                        'date' => $request->input('payment_date'),
                        'method' => $request->input('payment_method'),
                        'reference' => $request->input('reference_number'),
                        'recorded_at' => now(),
                    ];
                    $dbt['payments'] = $payments;
                    $dbt['total_paid'] = array_sum(array_column($payments, 'amount'));
                    $dbt['remaining'] = $dbt['amount'] - $dbt['total_paid'];
                    $dbt['is_fully_paid'] = $dbt['remaining'] <= 0;
                    $debt = $dbt;
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                return response()->json([
                    'success' => false,
                    'message' => 'Debt not found',
                ], 404);
            }
            
            $metadata = $session->metadata ?? [];
            $metadata['debts'] = $debts;
            $session->update(['metadata' => $metadata]);
            
            return response()->json([
                'success' => true,
                'message' => 'Payment recorded successfully',
                'debt' => $debt,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to record payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================================
    // BENEFICIARY NOTIFICATION METHODS
    // =========================================================================

    /**
     * Send notifications to beneficiaries
     */
    public function sendBeneficiaryNotifications(string $sessionId)
    {
        try {
            $session = $this->getSession($sessionId);
            
            if ($session->matched_record_type !== InstantEstateSession::RECORD_TYPE_ESTATE_PLAN) {
                return response()->json([
                    'success' => false,
                    'message' => 'Beneficiary notifications only available for estate plan records.',
                ], 400);
            }
            
            $estate = EstatePreRegistration::find($session->matched_record_id);
            
            if (!$estate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Estate record not found.',
                ], 404);
            }
            
            $sentCount = 0;
            $errors = [];
            
            foreach ($estate->heirs as $heir) {
                if ($heir->email) {
                    try {
                        $accessToken = BeneficiaryAccessLink::create([
                            'estate_pre_registration_id' => $estate->id,
                            'beneficiary_type' => 'heir',
                            'beneficiary_id' => $heir->id,
                            'beneficiary_name' => $heir->name,
                            'beneficiary_email' => $heir->email,
                            'access_token' => BeneficiaryAccessLink::generateToken(),
                            'expires_at' => now()->addDays(30),
                            'status' => 'active',
                            'is_active' => true,
                            'access_count' => 0,
                        ]);
                        
                        Mail::to($heir->email)->send(new BeneficiaryAccessMail($estate, $accessToken, $heir));
                        $sentCount++;
                    } catch (\Exception $e) {
                        $errors[] = "Failed to send to {$heir->email}: " . $e->getMessage();
                    }
                }
            }
            
            foreach ($estate->wasiyyah as $was) {
                if ($was->beneficiary_email) {
                    try {
                        $accessToken = BeneficiaryAccessLink::create([
                            'estate_pre_registration_id' => $estate->id,
                            'beneficiary_type' => 'wasiyyah',
                            'beneficiary_id' => $was->id,
                            'beneficiary_name' => $was->beneficiary_name,
                            'beneficiary_email' => $was->beneficiary_email,
                            'access_token' => BeneficiaryAccessLink::generateToken(),
                            'expires_at' => now()->addDays(30),
                            'status' => 'active',
                            'is_active' => true,
                            'access_count' => 0,
                        ]);
                        
                        Mail::to($was->beneficiary_email)->send(new BeneficiaryAccessMail($estate, $accessToken, $was));
                        $sentCount++;
                    } catch (\Exception $e) {
                        $errors[] = "Failed to send to {$was->beneficiary_email}: " . $e->getMessage();
                    }
                }
            }
            
            if ($estate->trustee_email) {
                try {
                    $trusteeData = (object) [
                        'name' => $estate->trustee_name,
                        'email' => $estate->trustee_email,
                        'relationship' => $estate->trustee_relationship,
                    ];
                    $accessToken = BeneficiaryAccessLink::create([
                        'estate_pre_registration_id' => $estate->id,
                        'beneficiary_type' => 'trustee',
                        'beneficiary_name' => $estate->trustee_name,
                        'beneficiary_email' => $estate->trustee_email,
                        'access_token' => BeneficiaryAccessLink::generateToken(),
                        'expires_at' => now()->addDays(30),
                        'status' => 'active',
                        'is_active' => true,
                        'access_count' => 0,
                    ]);
                    
                    Mail::to($estate->trustee_email)->send(new BeneficiaryAccessMail($estate, $accessToken, $trusteeData));
                    $sentCount++;
                } catch (\Exception $e) {
                    $errors[] = "Failed to send to trustee {$estate->trustee_email}: " . $e->getMessage();
                }
            }
            
            Log::info('Beneficiary notifications sent', [
                'session_id' => $sessionId,
                'estate_id' => $estate->id,
                'sent_count' => $sentCount,
                'errors' => $errors,
            ]);
            
            return response()->json([
                'success' => true,
                'sent_count' => $sentCount,
                'errors' => $errors,
                'message' => "Notifications sent to {$sentCount} beneficiaries.",
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notifications: ' . $e->getMessage(),
            ], 500);
        }
    }

    // =========================================================================
    // TESTING & DEBUG METHODS
    // =========================================================================

    /**
     * Test session endpoint (for debugging)
     */
    public function testSession(string $sessionId)
    {
        try {
            $session = $this->getSession($sessionId);
            
            return response()->json([
                'session_id' => $session->session_id,
                'status' => $session->status,
                'admin_status' => $session->admin_status,
                'authenticity_score' => $session->authenticity_score,
                'guest_email' => $session->guest_email,
                'guest_name' => $session->guest_name,
                'has_extracted_data' => !is_null($session->extracted_data),
                'has_report_data' => !is_null($session->report_data),
                'matched_record_type' => $session->matched_record_type,
                'matched_record_id' => $session->matched_record_id,
                'notification_requested' => $session->notification_requested,
                'created_at' => $session->created_at,
                'expires_at' => $session->expires_at,
                'level2_errors' => $session->level2_validation_errors,
                'level2_warnings' => $session->level2_validation_warnings,
                'rejection_reason' => $session->rejection_reason,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    // =========================================================================
    // DOWNLOAD METHODS
    // =========================================================================

    /**
     * Download extracted data as JSON
     */
    public function downloadExtractedData(string $sessionId)
    {
        $session = $this->getSession($sessionId);
        
        $data = [
            'session_id' => $session->session_id,
            'original_filename' => $session->original_filename,
            'status' => $session->status,
            'admin_status' => $session->admin_status,
            'authenticity_score' => $session->authenticity_score,
            'ocr_confidence' => $session->ocr_confidence,
            'extracted_data' => $session->extracted_data,
            'missing_fields' => $session->missing_fields,
            'level2_errors' => $session->level2_validation_errors,
            'level2_warnings' => $session->level2_validation_warnings,
            'deceased_name' => $session->deceased_name,
            'deceased_nric' => $session->deceased_nric,
            'death_date' => $session->death_date,
            'death_place' => $session->death_place,
            'registration_number' => $session->registration_number,
            'created_at' => $session->created_at,
        ];
        
        $filename = 'extracted_data_' . ($session->deceased_name ?? 'unknown') . '_' . now()->format('Ymd') . '.json';
        $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
        
        return response()->json($data, 200, [
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Download death certificate
     */
    public function downloadDeathCertificate(string $sessionId)
    {
        $session = $this->getSession($sessionId);
        
        if (!$session->file_path || !Storage::disk('private')->exists($session->file_path)) {
            abort(404, 'Death certificate file not found.');
        }
        
        $extension = pathinfo($session->file_path, PATHINFO_EXTENSION);
        $filename = 'death_certificate_' . ($session->deceased_name ?? 'unknown') . '_' . now()->format('Ymd') . '.' . $extension;
        $filename = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', $filename);
        
        return Storage::disk('private')->download($session->file_path, $filename);
    }
    
    // =========================================================================
    // ADDITIONAL PUBLIC ACCESS METHODS
    // =========================================================================
    
    /**
     * Resume a session by ID (public access)
     */
    public function resumeSession(string $sessionId)
    {
        try {
            $session = $this->getSession($sessionId);
            
            session()->put('instant_estate_session_id', $sessionId);
            if ($session->guest_email) {
                session()->put('instant_estate_email', $session->guest_email);
            }
            if ($session->guest_name) {
                session()->put('instant_estate_name', $session->guest_name);
            }
            
            return redirect()->route('instant-estate.view-session', $sessionId)
                ->with('success', 'Session resumed successfully.');
                
        } catch (\Exception $e) {
            return redirect()->route('instant-estate.index')
                ->with('error', 'Unable to resume session: ' . $e->getMessage());
        }
    }
    
    /**
     * Get all sessions for the current browser (public)
     */
    public function getMySessions()
    {
        $guestEmail = session()->get('instant_estate_email');
        $currentSessionId = session()->get('instant_estate_session_id');
        
        $sessions = InstantEstateSession::where(function($q) use ($currentSessionId, $guestEmail) {
            if ($currentSessionId) {
                $q->where('session_id', $currentSessionId);
            }
            if ($guestEmail) {
                $q->orWhere('guest_email', $guestEmail);
            }
        })
        ->orderBy('created_at', 'desc')
        ->get();
        
        return response()->json([
            'success' => true,
            'sessions' => $sessions->map(function($session) {
                return [
                    'session_id' => $session->session_id,
                    'status' => $session->status,
                    'admin_status' => $session->admin_status,
                    'deceased_name' => $session->deceased_name,
                    'created_at' => $session->created_at,
                    'has_report' => $session->has_report && $session->admin_status === 'approved',
                ];
            }),
        ]);
    }

    /**
     * Get a public access token for a session (e.g., to generate a shareable link)
     */
    public function getPublicAccessToken(string $sessionId)
    {
        try {
            $session = $this->getSession($sessionId);
            
            // Only approved sessions can have a public token
            if ($session->admin_status !== 'approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Session not approved. Public access token cannot be generated.',
                ], 403);
            }
            
            // Check if a notification request already exists for this session
            $notificationRequest = $session->notificationRequest;
            
            if (!$notificationRequest) {
                // Create a new notification request (public token) without sending email
                $accessToken = Str::random(64) . '-' . time();
                $notificationRequest = NotificationRequest::create([
                    'session_id' => $session->session_id,
                    'instant_estate_session_id' => $session->id,
                    'user_id' => null,
                    'guest_email' => $session->guest_email,
                    'guest_name' => $session->guest_name,
                    'deceased_name' => $session->deceased_name,
                    'deceased_nric' => $session->deceased_nric,
                    'death_date' => $session->death_date,
                    'death_place' => $session->death_place,
                    'recipient_email' => $session->guest_email ?? 'guest@example.com',
                    'recipient_name' => $session->guest_name ?? 'Guest',
                    'access_token' => $accessToken,
                    'status' => NotificationRequest::STATUS_APPROVED,
                    'request_metadata' => [
                        'report_type' => $session->matched_record_type,
                        'is_instant_estate' => true,
                        'generated_by' => 'public_token_endpoint',
                    ],
                ]);
                
                $session->update(['notification_request_id' => $notificationRequest->id]);
                
                Log::info('Public access token generated for session', [
                    'session_id' => $sessionId,
                    'token' => substr($accessToken, 0, 20) . '...',
                ]);
            }
            
            return response()->json([
                'success' => true,
                'access_token' => $notificationRequest->access_token,
                'public_url' => route('instant-estate.public-view', ['token' => $notificationRequest->access_token]),
                'expires_in_days' => 30,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to generate public access token', [
                'session_id' => $sessionId,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate public access token: ' . $e->getMessage(),
            ], 500);
        }
    }
}