<?php

namespace App\Http\Controllers;

use App\Models\EstatePreRegistration;
use App\Models\PreRegisteredHeir;
use App\Models\PreRegisteredWasiyyah;
use App\Models\PreRegisteredAsset;
use App\Models\PreRegisteredDebt;
use App\Models\DigitalCredential;
use App\Models\NotificationRequest;
use App\Services\FaraidCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EstateSetupController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('verified');
    }

    // =========================================================================
    // VALIDATION HELPERS
    // =========================================================================

    /**
     * Validate NRIC format.
     *
     * @param  string|null  $nric
     * @return bool
     */
    protected function validateNRIC(?string $nric): bool
    {
        if (empty($nric)) {
            return false;
        }
        $clean = preg_replace('/[-\s]/', '', $nric);
        return preg_match('/^\d{12}$/', $clean) === 1;
    }

    /**
     * Format NRIC to standard format (000000-00-0000).
     *
     * @param  string|null  $nric
     * @return string
     */
    protected function formatNRIC(?string $nric): string
    {
        if (empty($nric)) {
            return '';
        }
        $clean = preg_replace('/[^0-9]/', '', $nric);
        if (strlen($clean) === 12) {
            return substr($clean, 0, 6) . '-' . substr($clean, 6, 2) . '-' . substr($clean, 8, 4);
        }
        return $nric;
    }

    /**
     * Validate email format.
     *
     * @param  string|null  $email
     * @return bool
     */
    protected function validateEmail(?string $email): bool
    {
        if (empty($email)) {
            return false;
        }
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate phone format (Malaysian).
     *
     * @param  string|null  $phone
     * @return bool
     */
    protected function validatePhone(?string $phone): bool
    {
        if (empty($phone)) {
            return true; // Phone is optional
        }
        $clean = preg_replace('/[^0-9]/', '', $phone);
        return preg_match('/^01[0-9]{8,9}$/', $clean) === 1;
    }

    /**
     * Format phone to standard format (012-3456789).
     *
     * @param  string|null  $phone
     * @return string
     */
    protected function formatPhone(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }
        $clean = preg_replace('/[^0-9]/', '', $phone);
        if (strlen($clean) >= 10 && strlen($clean) <= 11 && str_starts_with($clean, '01')) {
            return substr($clean, 0, 3) . '-' . substr($clean, 3);
        }
        return $phone;
    }

    /**
     * Get estate by unique ID and ensure it belongs to the authenticated user.
     *
     * @param  string  $uniqueId
     * @return EstatePreRegistration
     */
    protected function getEstate(string $uniqueId): EstatePreRegistration
    {
        return EstatePreRegistration::where('unique_id', $uniqueId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
    }

    /**
     * Determine relationship type from relationship value.
     *
     * @param  string  $relationship
     * @return string
     */
    protected function getRelationshipType(string $relationship): string
    {
        $primary = ['husband', 'wife', 'father', 'mother'];
        $substitute = ['grandfather', 'grandmother_paternal', 'grandmother_maternal'];
        $secondary = [
            'half_brother_full', 'half_brother_paternal', 'half_brother_maternal',
            'half_sister_full', 'half_sister_paternal', 'half_sister_maternal'
        ];
        $asabah = ['son', 'daughter'];

        if (in_array($relationship, $primary)) {
            return 'primary';
        }
        if (in_array($relationship, $substitute)) {
            return 'substitute';
        }
        if (in_array($relationship, $secondary)) {
            return 'secondary';
        }
        if (in_array($relationship, $asabah)) {
            return 'asabah';
        }

        return 'other';
    }

    // =========================================================================
    // MAIN PAGES
    // =========================================================================

    /**
     * Display the estate planning index/overview page with all sections.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $estate = EstatePreRegistration::where('user_id', Auth::id())
            ->where('status', '!=', 'executed')
            ->latest()
            ->first();

        if ($estate) {
            $estate->load(['heirs', 'assets', 'debts', 'wasiyyah']);

            $progressSteps = [
                'deceased_name' => !empty($estate->deceased_name),
                'has_heirs' => $estate->heirs()->count() > 0,
                'has_assets' => $estate->assets()->count() > 0,
                'has_debts' => $estate->debts()->count() > 0,
                'has_will' => !is_null($estate->will_video_url) || !is_null($estate->will_text_content),
                'is_activated' => $estate->status === 'activated'
            ];

            $completedSteps = count(array_filter($progressSteps));
            $estate->progress_percentage = round(($completedSteps / 6) * 100);
        }

        return view('estate-setup.index', compact('estate'));
    }

    /**
     * Display the dashboard with all estates.
     *
     * @param  Request  $request
     * @return \Illuminate\View\View
     */
    public function dashboard(Request $request)
    {
        $query = EstatePreRegistration::where('user_id', Auth::id())
            ->with(['heirs', 'assets', 'debts', 'wasiyyah']);

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('deceased_name', 'LIKE', "%{$search}%")
                  ->orWhere('deceased_nric', 'LIKE', "%{$search}%")
                  ->orWhere('unique_id', 'LIKE', "%{$search}%")
                  ->orWhere('contact_email', 'LIKE', "%{$search}%");
            });
        }

        $estates = $query->orderBy('created_at', 'desc')->paginate(12);

        $stats = [
            'total' => EstatePreRegistration::where('user_id', Auth::id())->count(),
            'draft' => EstatePreRegistration::where('user_id', Auth::id())->where('status', 'draft')->count(),
            'completed' => EstatePreRegistration::where('user_id', Auth::id())->where('status', 'completed')->count(),
            'active' => EstatePreRegistration::where('user_id', Auth::id())->where('status', 'activated')->count(),
            'executed' => EstatePreRegistration::where('user_id', Auth::id())->where('status', 'executed')->count(),
        ];

        return view('estate-setup.dashboard', compact('estates', 'stats'));
    }

    /**
     * Display the create estate planning page.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        $user = Auth::user();

        // Check if user already has an active estate
        $existingEstate = EstatePreRegistration::where('user_id', Auth::id())
            ->whereIn('status', ['draft', 'completed', 'activated'])
            ->first();

        if ($existingEstate) {
            return redirect()->route('estate-setup.index')
                ->with('info', 'You already have an active estate setup. You can continue editing it below.');
        }

        return view('estate-setup.create', compact('user'));
    }

    /**
     * Store the estate planning data.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $validator = Validator::make($request->all(), [
            'deceased_name' => 'nullable|string|max:255',
            'deceased_nric' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'contact_phone' => 'nullable|string|max:20',
            'contact_email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'trustee_name' => 'nullable|string|max:255',
            'trustee_nric' => 'nullable|string|max:20',
            'trustee_phone' => 'nullable|string|max:20',
            'trustee_email' => 'nullable|email|max:255',
            'wasiyyah_instructions' => 'nullable|string',
            'assets_data' => 'nullable|json',
            'debts_data' => 'nullable|json',
            'heirs_data' => 'nullable|json',
            'wasiyyah_data' => 'nullable|json',
            'credentials_data' => 'nullable|json',
            'will_video' => 'nullable|file|mimes:mp4,mov,avi,webm|max:524288000',
            'youtube_url' => 'nullable|url|max:500',
            'activate_after_save' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Auto-populate from user's registration data
        $deceasedName = $request->deceased_name ?: $user->name;
        $deceasedNric = $request->deceased_nric ?: ($user->nric ?? null);
        $dateOfBirth = $request->date_of_birth ?: ($user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : null);
        $gender = $request->gender ?: $user->gender;
        $contactPhone = $request->contact_phone ?: ($user->contact_phone ?? null);
        $contactEmail = $request->contact_email ?: $user->email;
        $address = $request->address ?: ($user->address ?? null);

        // Validate required fields
        if (empty($deceasedName)) {
            $message = 'Deceased name is required.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return redirect()->back()->with('error', $message)->withInput();
        }

        // Validate NRIC format
        if ($deceasedNric && !$this->validateNRIC($deceasedNric)) {
            $message = 'Invalid NRIC format. Use: 000000-00-0000 or 12 digits.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return redirect()->back()->with('error', $message)->withInput();
        }

        // Validate phone format
        if ($contactPhone && !$this->validatePhone($contactPhone)) {
            $message = 'Invalid phone format. Use: 012-3456789';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return redirect()->back()->with('error', $message)->withInput();
        }

        // Validate email format
        if ($contactEmail && !$this->validateEmail($contactEmail)) {
            $message = 'Invalid email format.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return redirect()->back()->with('error', $message)->withInput();
        }

        // Validate trustee details if provided
        if ($request->trustee_email && !$this->validateEmail($request->trustee_email)) {
            $message = 'Invalid trustee email format.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return redirect()->back()->with('error', $message)->withInput();
        }

        if ($request->trustee_phone && !$this->validatePhone($request->trustee_phone)) {
            $message = 'Invalid trustee phone format. Use: 012-3456789';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return redirect()->back()->with('error', $message)->withInput();
        }

        if ($request->trustee_nric && !$this->validateNRIC($request->trustee_nric)) {
            $message = 'Invalid trustee NRIC format.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return redirect()->back()->with('error', $message)->withInput();
        }

        DB::beginTransaction();

        try {
            // Create the estate pre-registration
            $estate = EstatePreRegistration::create([
                'user_id' => Auth::id(),
                'unique_id' => (string) Str::uuid(),
                'deceased_name' => $deceasedName,
                'deceased_nric' => $deceasedNric ? $this->formatNRIC($deceasedNric) : null,
                'date_of_birth' => $dateOfBirth,
                'gender' => $gender,
                'contact_phone' => $contactPhone ? $this->formatPhone($contactPhone) : null,
                'contact_email' => $contactEmail,
                'address' => $address,
                'trustee_name' => $request->trustee_name,
                'trustee_nric' => $request->trustee_nric ? $this->formatNRIC($request->trustee_nric) : null,
                'trustee_phone' => $request->trustee_phone ? $this->formatPhone($request->trustee_phone) : null,
                'trustee_email' => $request->trustee_email,
                'wasiyyah_instructions' => $request->wasiyyah_instructions,
                'status' => $request->activate_after_save ? 'activated' : 'draft',
            ]);

            // Process Assets
            if ($request->assets_data) {
                $assets = json_decode($request->assets_data, true);
                if (is_array($assets)) {
                    foreach ($assets as $asset) {
                        $estate->assets()->create([
                            'name' => $asset['name'] ?? 'Unknown Asset',
                            'type' => $asset['category'] ?? 'other',
                            'category' => $asset['label'] ?? 'Other Asset',
                            'value' => $asset['value'] ?? 0,
                            'description' => $asset['description'] ?? null,
                            'location' => $asset['description'] ?? null,
                            'ownership_percentage' => $asset['ownership'] ?? 100,
                        ]);
                    }
                }
            }

            // Process Debts
            if ($request->debts_data) {
                $debts = json_decode($request->debts_data, true);
                if (is_array($debts)) {
                    foreach ($debts as $debt) {
                        $estate->debts()->create([
                            'creditor_name' => $debt['creditor_name'] ?? $debt['name'] ?? 'Unknown Creditor',
                            'amount' => $debt['amount'] ?? 0,
                            'description' => $debt['description'] ?? null,
                            'type' => $debt['type'] ?? 'unsecured',
                            'due_date' => $debt['due_date'] ?? null,
                            'debt_type' => $debt['debt_type'] ?? null,
                            'creditor_contact' => $debt['creditor_contact'] ?? null,
                        ]);
                    }
                }
            }

            // Process Heirs
            if ($request->heirs_data) {
                $heirs = json_decode($request->heirs_data, true);
                if (is_array($heirs)) {
                    foreach ($heirs as $heir) {
                        $estate->heirs()->create([
                            'name' => $heir['name'] ?? 'Unknown Heir',
                            'nric' => isset($heir['nric']) ? $this->formatNRIC($heir['nric']) : null,
                            'email' => $heir['email'] ?? null,
                            'relationship' => $heir['relationship'] ?? 'other',
                            'relationship_type' => $this->getRelationshipType($heir['relationship'] ?? 'other'),
                            'phone' => isset($heir['phone']) ? $this->formatPhone($heir['phone']) : null,
                            'share_percentage' => $heir['share_percentage'] ?? $heir['percentage'] ?? 0,
                        ]);
                    }
                }
            }

            // Process Wasiyyah
            if ($request->wasiyyah_data) {
                $wasiyyahList = json_decode($request->wasiyyah_data, true);
                if (is_array($wasiyyahList)) {
                    foreach ($wasiyyahList as $wasiyyah) {
                        $estate->wasiyyah()->create([
                            'beneficiary_name' => $wasiyyah['beneficiary_name'] ?? $wasiyyah['name'] ?? 'Unknown Beneficiary',
                            'beneficiary_nric' => isset($wasiyyah['beneficiary_nric']) ? $this->formatNRIC($wasiyyah['beneficiary_nric']) : (isset($wasiyyah['nric']) ? $this->formatNRIC($wasiyyah['nric']) : null),
                            'beneficiary_email' => $wasiyyah['beneficiary_email'] ?? $wasiyyah['email'] ?? null,
                            'relationship' => $wasiyyah['relationship'] ?? 'other',
                            'requested_percentage' => $wasiyyah['requested_percentage'] ?? $wasiyyah['percentage'] ?? 0,
                            'description' => $wasiyyah['description'] ?? $wasiyyah['notes'] ?? null,
                        ]);
                    }
                }
            }

            // Process Digital Credentials
            if ($request->credentials_data) {
                $credentials = json_decode($request->credentials_data, true);
                if (is_array($credentials)) {
                    foreach ($credentials as $credential) {
                        DigitalCredential::create([
                            'estate_pre_registration_id' => $estate->id,
                            'platform' => $credential['platform'] ?? 'Unknown',
                            'username' => $credential['username'] ?? '',
                            'encrypted_password' => isset($credential['password']) ? encrypt($credential['password']) : encrypt(''),
                            'security_questions' => $credential['security_questions'] ?? $credential['security'] ?? null,
                            'notes' => $credential['notes'] ?? null,
                        ]);
                    }
                }
            }

            // Process Will Video upload
            if ($request->hasFile('will_video')) {
                $file = $request->file('will_video');
                $filename = 'will_video_' . $estate->unique_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('will-videos', $filename, 'public');
                $estate->will_video_url = Storage::url($path);
                $estate->will_video_type = 'upload';
                $estate->save();
            }

            // Process YouTube URL
            if ($request->youtube_url) {
                $estate->will_video_url = $request->youtube_url;
                $estate->will_video_type = 'youtube';
                $estate->save();
            }

            // Process Will Text Content
            if ($request->will_text_content) {
                $estate->will_text_content = $request->will_text_content;
                $estate->save();
            }

            // If activated, set activation fields and generate token
            if ($request->activate_after_save) {
                $estate->activated_at = now();
                $estate->access_token = Str::random(64);
                $estate->token_expires_at = now()->addYears(10);
                $estate->save();
            }

            DB::commit();

            // Redirect to INDEX (estate-setup.index) which shows the estate plan
            $redirectUrl = route('estate-setup.index');
            
            if ($request->activate_after_save) {
                session()->flash('success', 'Estate plan created and activated successfully!');
                session()->flash('activated_estate_id', $estate->unique_id);
                session()->flash('activated_estate_token', $estate->access_token);
                session()->flash('pending_admin_approval', true);
                
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'redirect' => $redirectUrl,
                        'message' => 'Estate plan activated successfully! Pending admin review.',
                        'estate_id' => $estate->unique_id,
                        'token' => $estate->access_token,
                    ]);
                }
                
                return redirect($redirectUrl)->with('success', 'Estate plan created and activated successfully!');
            }

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'redirect' => $redirectUrl,
                    'message' => 'Estate setup created successfully.',
                ]);
            }

            return redirect($redirectUrl)->with('success', 'Estate setup created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create estate: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create estate setup: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Failed to create estate setup. Please try again.')->withInput();
        }
    }

    // =========================================================================
    // SECTION REDIRECTS (Single Page App Style)
    // =========================================================================

    /**
     * All section methods redirect to index which handles everything in tabs.
     *
     * @param  string  $uniqueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function heirs(string $uniqueId)
    {
        return redirect()->route('estate-setup.index');
    }

    public function assets(string $uniqueId)
    {
        return redirect()->route('estate-setup.index');
    }

    public function debts(string $uniqueId)
    {
        return redirect()->route('estate-setup.index');
    }

    public function wasiyyah(string $uniqueId)
    {
        return redirect()->route('estate-setup.index');
    }

    public function willVideo(string $uniqueId)
    {
        return redirect()->route('estate-setup.index');
    }

    public function trustee(string $uniqueId)
    {
        return redirect()->route('estate-setup.index');
    }

    public function review(string $uniqueId)
    {
        return redirect()->route('estate-setup.index');
    }

    // =========================================================================
    // HEIRS CRUD
    // =========================================================================

    /**
     * Store a new heir for the estate.
     *
     * @param  Request  $request
     * @param  string   $uniqueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeHeir(Request $request, string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nric' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'relationship' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'share_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->route('estate-setup.index')
                ->withErrors($validator)
                ->withInput();
        }

        // Validate email
        if (!$this->validateEmail($request->email)) {
            return redirect()->route('estate-setup.index')
                ->with('error', 'Invalid email format for heir.')
                ->withInput();
        }

        // Validate NRIC if provided
        if ($request->nric && !$this->validateNRIC($request->nric)) {
            return redirect()->route('estate-setup.index')
                ->with('error', 'Invalid NRIC format.')
                ->withInput();
        }

        // Validate phone if provided
        if ($request->phone && !$this->validatePhone($request->phone)) {
            return redirect()->route('estate-setup.index')
                ->with('error', 'Invalid phone format. Use: 012-3456789')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $heirData = $request->all();
            $heirData['nric'] = $request->nric ? $this->formatNRIC($request->nric) : null;
            $heirData['phone'] = $request->phone ? $this->formatPhone($request->phone) : null;
            $heirData['relationship_type'] = $this->getRelationshipType($request->relationship);

            $estate->heirs()->create($heirData);
            DB::commit();

            return redirect()->route('estate-setup.index')
                ->with('success', 'Heir added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to add heir: ' . $e->getMessage(), [
                'estate_id' => $estate->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to add heir. Please try again.');
        }
    }

    /**
     * Update an existing heir.
     *
     * @param  Request  $request
     * @param  string   $uniqueId
     * @param  int      $heirId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateHeir(Request $request, string $uniqueId, int $heirId)
    {
        $estate = $this->getEstate($uniqueId);
        $heir = $estate->heirs()->findOrFail($heirId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'relationship' => 'required|string',
            'email' => 'required|email|max:255',
            'share_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->route('estate-setup.index')
                ->withErrors($validator)
                ->withInput();
        }

        // Validate email
        if (!$this->validateEmail($request->email)) {
            return redirect()->route('estate-setup.index')
                ->with('error', 'Invalid email format.')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $updateData = $request->all();
            if ($request->nric) {
                $updateData['nric'] = $this->formatNRIC($request->nric);
            }
            if ($request->phone) {
                $updateData['phone'] = $this->formatPhone($request->phone);
            }

            $heir->update($updateData);
            DB::commit();

            return redirect()->route('estate-setup.index')
                ->with('success', 'Heir updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update heir: ' . $e->getMessage(), [
                'heir_id' => $heirId,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to update heir. Please try again.');
        }
    }

    /**
     * Delete an heir.
     *
     * @param  string  $uniqueId
     * @param  int     $heirId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteHeir(string $uniqueId, int $heirId)
    {
        $estate = $this->getEstate($uniqueId);
        $heir = $estate->heirs()->findOrFail($heirId);

        DB::beginTransaction();
        try {
            $heir->delete();
            DB::commit();
            return redirect()->route('estate-setup.index')
                ->with('success', 'Heir deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete heir: ' . $e->getMessage(), [
                'heir_id' => $heirId,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to delete heir. Please try again.');
        }
    }

    // =========================================================================
    // WASIYYAH CRUD
    // =========================================================================

    /**
     * Store a new wasiyyah beneficiary.
     *
     * @param  Request  $request
     * @param  string   $uniqueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeWasiyyah(Request $request, string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);

        $validator = Validator::make($request->all(), [
            'beneficiary_name' => 'required|string|max:255',
            'beneficiary_nric' => 'nullable|string|max:20',
            'beneficiary_email' => 'nullable|email|max:255',
            'relationship' => 'required|string',
            'requested_percentage' => 'required|numeric|min:0.01|max:33.33',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('estate-setup.index')
                ->withErrors($validator)
                ->withInput();
        }

        // Validate NRIC if provided
        if ($request->beneficiary_nric && !$this->validateNRIC($request->beneficiary_nric)) {
            return redirect()->route('estate-setup.index')
                ->with('error', 'Invalid NRIC format.')
                ->withInput();
        }

        // Validate email if provided
        if ($request->beneficiary_email && !$this->validateEmail($request->beneficiary_email)) {
            return redirect()->route('estate-setup.index')
                ->with('error', 'Invalid email format.')
                ->withInput();
        }

        // Check wasiyyah limit (max 33.33%)
        $existingTotal = $estate->wasiyyah()->sum('requested_percentage');
        $newTotal = $existingTotal + $request->requested_percentage;
        $maxPercentage = 33.33;

        if ($newTotal > $maxPercentage) {
            return redirect()->route('estate-setup.index')
                ->with('error', "Total wasiyyah ({$newTotal}%) would exceed maximum ({$maxPercentage}%). Current: {$existingTotal}%.")
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $wasiyyahData = $request->all();
            if ($request->beneficiary_nric) {
                $wasiyyahData['beneficiary_nric'] = $this->formatNRIC($request->beneficiary_nric);
            }

            $estate->wasiyyah()->create($wasiyyahData);
            DB::commit();

            return redirect()->route('estate-setup.index')
                ->with('success', 'Wasiyyah beneficiary added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to add wasiyyah: ' . $e->getMessage(), [
                'estate_id' => $estate->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to add wasiyyah. Please try again.');
        }
    }

    /**
     * Update an existing wasiyyah beneficiary.
     *
     * @param  Request  $request
     * @param  string   $uniqueId
     * @param  int      $wasiyyahId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateWasiyyah(Request $request, string $uniqueId, int $wasiyyahId)
    {
        $estate = $this->getEstate($uniqueId);
        $wasiyyah = $estate->wasiyyah()->findOrFail($wasiyyahId);

        $validator = Validator::make($request->all(), [
            'beneficiary_name' => 'required|string|max:255',
            'relationship' => 'required|string',
            'requested_percentage' => 'required|numeric|min:0.01|max:33.33',
        ]);

        if ($validator->fails()) {
            return redirect()->route('estate-setup.index')
                ->withErrors($validator)
                ->withInput();
        }

        // Check wasiyyah limit
        $existingTotal = $estate->wasiyyah()
            ->where('id', '!=', $wasiyyahId)
            ->sum('requested_percentage');
        $newTotal = $existingTotal + $request->requested_percentage;
        $maxPercentage = 33.33;

        if ($newTotal > $maxPercentage) {
            return redirect()->route('estate-setup.index')
                ->with('error', "Total wasiyyah ({$newTotal}%) would exceed maximum ({$maxPercentage}%).")
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $wasiyyah->update($request->all());
            DB::commit();
            return redirect()->route('estate-setup.index')
                ->with('success', 'Wasiyyah beneficiary updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update wasiyyah: ' . $e->getMessage(), [
                'wasiyyah_id' => $wasiyyahId,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to update wasiyyah. Please try again.');
        }
    }

    /**
     * Delete a wasiyyah beneficiary.
     *
     * @param  string  $uniqueId
     * @param  int     $wasiyyahId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteWasiyyah(string $uniqueId, int $wasiyyahId)
    {
        $estate = $this->getEstate($uniqueId);
        $wasiyyah = $estate->wasiyyah()->findOrFail($wasiyyahId);

        DB::beginTransaction();
        try {
            $wasiyyah->delete();
            DB::commit();
            return redirect()->route('estate-setup.index')
                ->with('success', 'Wasiyyah beneficiary deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete wasiyyah: ' . $e->getMessage(), [
                'wasiyyah_id' => $wasiyyahId,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to delete wasiyyah. Please try again.');
        }
    }

    // =========================================================================
    // ASSETS CRUD
    // =========================================================================

    /**
     * Store a new asset for the estate.
     *
     * @param  Request  $request
     * @param  string   $uniqueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeAsset(Request $request, string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'ownership_percentage' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->route('estate-setup.index')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $estate->assets()->create([
                'name' => $request->name,
                'value' => $request->value,
                'description' => $request->description,
                'location' => $request->location ?? null,
                'ownership_percentage' => $request->ownership_percentage ?? 100,
                'type' => $request->type ?? null,
                'category' => $request->category ?? null,
            ]);
            DB::commit();
            return redirect()->route('estate-setup.index')
                ->with('success', 'Asset added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to add asset: ' . $e->getMessage(), [
                'estate_id' => $estate->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to add asset. Please try again.');
        }
    }

    /**
     * Update an existing asset.
     *
     * @param  Request  $request
     * @param  string   $uniqueId
     * @param  int      $assetId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAsset(Request $request, string $uniqueId, int $assetId)
    {
        $estate = $this->getEstate($uniqueId);
        $asset = $estate->assets()->findOrFail($assetId);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('estate-setup.index')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $asset->update([
                'name' => $request->name,
                'value' => $request->value,
                'description' => $request->description,
                'location' => $request->location,
                'ownership_percentage' => $request->ownership_percentage ?? $asset->ownership_percentage,
                'type' => $request->type ?? $asset->type,
                'category' => $request->category ?? $asset->category,
            ]);
            DB::commit();
            return redirect()->route('estate-setup.index')
                ->with('success', 'Asset updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update asset: ' . $e->getMessage(), [
                'asset_id' => $assetId,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to update asset. Please try again.');
        }
    }

    /**
     * Delete an asset.
     *
     * @param  string  $uniqueId
     * @param  int     $assetId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteAsset(string $uniqueId, int $assetId)
    {
        $estate = $this->getEstate($uniqueId);
        $asset = $estate->assets()->findOrFail($assetId);

        DB::beginTransaction();
        try {
            $asset->delete();
            DB::commit();
            return redirect()->route('estate-setup.index')
                ->with('success', 'Asset deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete asset: ' . $e->getMessage(), [
                'asset_id' => $assetId,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to delete asset. Please try again.');
        }
    }

    // =========================================================================
    // DEBTS CRUD
    // =========================================================================

    /**
     * Store a new debt for the estate.
     *
     * @param  Request  $request
     * @param  string   $uniqueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeDebt(Request $request, string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);

        $validator = Validator::make($request->all(), [
            'creditor_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'type' => 'nullable|in:secured,unsecured,personal,religious,government,administrative,other',
        ]);

        if ($validator->fails()) {
            return redirect()->route('estate-setup.index')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $estate->debts()->create([
                'creditor_name' => $request->creditor_name,
                'amount' => $request->amount,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'type' => $request->type,
                'category' => $request->category ?? null,
                'creditor_contact' => $request->creditor_contact ?? null,
                'debt_type' => $request->debt_type ?? null,
            ]);
            DB::commit();
            return redirect()->route('estate-setup.index')
                ->with('success', 'Debt added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to add debt: ' . $e->getMessage(), [
                'estate_id' => $estate->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to add debt. Please try again.');
        }
    }

    /**
     * Update an existing debt.
     *
     * @param  Request  $request
     * @param  string   $uniqueId
     * @param  int      $debtId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateDebt(Request $request, string $uniqueId, int $debtId)
    {
        $estate = $this->getEstate($uniqueId);
        $debt = $estate->debts()->findOrFail($debtId);

        $validator = Validator::make($request->all(), [
            'creditor_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('estate-setup.index')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $debt->update([
                'creditor_name' => $request->creditor_name,
                'amount' => $request->amount,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'type' => $request->type ?? $debt->type,
                'category' => $request->category ?? $debt->category,
                'creditor_contact' => $request->creditor_contact ?? $debt->creditor_contact,
                'debt_type' => $request->debt_type ?? $debt->debt_type,
            ]);
            DB::commit();
            return redirect()->route('estate-setup.index')
                ->with('success', 'Debt updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update debt: ' . $e->getMessage(), [
                'debt_id' => $debtId,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to update debt. Please try again.');
        }
    }

    /**
     * Delete a debt.
     *
     * @param  string  $uniqueId
     * @param  int     $debtId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteDebt(string $uniqueId, int $debtId)
    {
        $estate = $this->getEstate($uniqueId);
        $debt = $estate->debts()->findOrFail($debtId);

        DB::beginTransaction();
        try {
            $debt->delete();
            DB::commit();
            return redirect()->route('estate-setup.index')
                ->with('success', 'Debt deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete debt: ' . $e->getMessage(), [
                'debt_id' => $debtId,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to delete debt. Please try again.');
        }
    }

    // =========================================================================
    // TRUSTEE MANAGEMENT
    // =========================================================================

    /**
     * Store trustee information.
     *
     * @param  Request  $request
     * @param  string   $uniqueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeTrustee(Request $request, string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);

        $validator = Validator::make($request->all(), [
            'trustee_name' => 'required|string|max:255',
            'trustee_nric' => 'nullable|string|max:20',
            'trustee_phone' => 'nullable|string|max:20',
            'trustee_email' => 'required|email|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->route('estate-setup.index')
                ->withErrors($validator)
                ->withInput();
        }

        // Validate trustee email
        if (!$this->validateEmail($request->trustee_email)) {
            return redirect()->route('estate-setup.index')
                ->with('error', 'Invalid trustee email format.')
                ->withInput();
        }

        // Validate trustee phone if provided
        if ($request->trustee_phone && !$this->validatePhone($request->trustee_phone)) {
            return redirect()->route('estate-setup.index')
                ->with('error', 'Invalid phone format. Use: 012-3456789')
                ->withInput();
        }

        // Validate trustee NRIC if provided
        if ($request->trustee_nric && !$this->validateNRIC($request->trustee_nric)) {
            return redirect()->route('estate-setup.index')
                ->with('error', 'Invalid NRIC format.')
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $estate->update([
                'trustee_name' => $request->trustee_name,
                'trustee_nric' => $request->trustee_nric ? $this->formatNRIC($request->trustee_nric) : $estate->trustee_nric,
                'trustee_phone' => $request->trustee_phone ? $this->formatPhone($request->trustee_phone) : $estate->trustee_phone,
                'trustee_email' => $request->trustee_email,
            ]);
            DB::commit();
            return redirect()->route('estate-setup.index')
                ->with('success', 'Trustee information updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update trustee: ' . $e->getMessage(), [
                'estate_id' => $estate->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to update trustee. Please try again.');
        }
    }

    /**
     * Update trustee information (alias for storeTrustee).
     *
     * @param  Request  $request
     * @param  string   $uniqueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTrustee(Request $request, string $uniqueId)
    {
        return $this->storeTrustee($request, $uniqueId);
    }

    // =========================================================================
    // WILL VIDEO MANAGEMENT
    // =========================================================================

    /**
     * Save will video.
     *
     * @param  Request  $request
     * @param  string   $uniqueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveWillVideo(Request $request, string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);

        $validator = Validator::make($request->all(), [
            'video_type' => 'required|in:upload,youtube',
            'video_url' => 'required_if:video_type,youtube|nullable|url',
            'video_file' => 'required_if:video_type,upload|nullable|file|mimes:mp4,mov,avi,webm|max:204800',
            'will_text' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('estate-setup.index')
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $videoUrl = null;
            $videoType = $request->video_type;

            if ($request->video_type === 'youtube') {
                $videoUrl = $request->video_url;
            } elseif ($request->hasFile('video_file')) {
                $file = $request->file('video_file');
                $filename = 'will_video_' . $estate->unique_id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('will-videos', $filename, 'public');
                $videoUrl = Storage::url($path);
            }

            $estate->update([
                'will_video_url' => $videoUrl,
                'will_video_type' => $videoType,
                'will_text_content' => $request->will_text,
            ]);

            DB::commit();
            return redirect()->route('estate-setup.index')
                ->with('success', 'Will video saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to save will video: ' . $e->getMessage(), [
                'estate_id' => $estate->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to save will video. Please try again.');
        }
    }

    /**
     * Delete will video.
     *
     * @param  string  $uniqueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteWillVideo(string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);

        DB::beginTransaction();
        try {
            // Delete the file if it's an uploaded video (not YouTube)
            if ($estate->will_video_url && !Str::contains($estate->will_video_url, 'youtube')) {
                $path = str_replace('/storage/', '', $estate->will_video_url);
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            $estate->update([
                'will_video_url' => null,
                'will_video_type' => null,
            ]);

            DB::commit();
            return redirect()->route('estate-setup.index')
                ->with('success', 'Will video deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete will video: ' . $e->getMessage(), [
                'estate_id' => $estate->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to delete will video. Please try again.');
        }
    }

    // =========================================================================
    // DEATH CERTIFICATE & ACTIVATION
    // =========================================================================

    /**
     * Upload death certificate.
     *
     * @param  Request  $request
     * @param  string   $uniqueId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function uploadDeathCertificate(Request $request, string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);

        $validator = Validator::make($request->all(), [
            'death_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
            }
            return redirect()->route('estate-setup.index')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $file = $request->file('death_certificate');
            $filename = 'death_cert_' . $estate->unique_id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('death-certificates', $filename, 'public');

            $ocrData = [
                'full_name' => null,
                'nric' => null,
                'date_of_death' => null,
                'place_of_death' => null,
                'extracted_at' => now()->toDateTimeString(),
                'confidence_score' => 0,
            ];

            $estate->uploadDeathCertificate(Storage::url($path), $ocrData);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Death certificate uploaded successfully.',
                    'ocr_data' => $ocrData,
                ]);
            }

            return redirect()->route('estate-setup.index')
                ->with('success', 'Death certificate uploaded successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to upload death certificate: ' . $e->getMessage(), [
                'estate_id' => $estate->id,
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to upload death certificate.'], 500);
            }
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to upload death certificate.');
        }
    }

    /**
     * Activate the estate plan.
     *
     * @param  string  $uniqueId
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function activate(string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);

        // Validate activation requirements
        $heirsCount = $estate->heirs()->count();
        $assetsCount = $estate->assets()->count();

        if ($heirsCount === 0) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Please add at least one heir before activating.']);
            }
            return redirect()->route('estate-setup.index')
                ->with('error', 'Please add at least one heir before activating.');
        }

        if ($assetsCount === 0) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Please add at least one asset before activating.']);
            }
            return redirect()->route('estate-setup.index')
                ->with('error', 'Please add at least one asset before activating.');
        }

        $totalAssets = $estate->assets()->sum('value');
        $totalDebts = $estate->debts()->sum('amount');
        $netEstate = max(0, $totalAssets - $totalDebts);

        if ($netEstate <= 0) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Net estate value must be greater than 0.']);
            }
            return redirect()->route('estate-setup.index')
                ->with('error', 'Net estate value must be greater than 0.');
        }

        // Check wasiyyah limit
        $totalWasiyyah = $estate->wasiyyah()->sum('requested_percentage');
        if ($totalWasiyyah > 33.33) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => "Total wasiyyah ({$totalWasiyyah}%) exceeds maximum (33.33%)."]);
            }
            return redirect()->route('estate-setup.index')
                ->with('error', "Total wasiyyah ({$totalWasiyyah}%) exceeds maximum (33.33%).");
        }

        // Check heir distribution
        $totalHeirPercentage = $estate->heirs()->sum('share_percentage');
        if (abs($totalHeirPercentage - 100) > 0.01 && $heirsCount > 0) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => "Heir distribution must equal 100% (currently {$totalHeirPercentage}%)."]);
            }
            return redirect()->route('estate-setup.index')
                ->with('error', "Heir distribution must equal 100% (currently {$totalHeirPercentage}%).");
        }

        // Check trustee
        if (empty($estate->trustee_name) || empty($estate->trustee_email)) {
            if (request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Please appoint a trustee before activating.']);
            }
            return redirect()->route('estate-setup.index')
                ->with('error', 'Please appoint a trustee before activating.');
        }

        DB::beginTransaction();
        try {
            $estate->activate();
            DB::commit();
            
            // Redirect to INDEX (estate-setup.index)
            $redirectUrl = route('estate-setup.index');
            
            session()->flash('success', 'Your estate plan has been activated and submitted for admin approval!');
            session()->flash('activated_estate_id', $estate->unique_id);
            session()->flash('activated_estate_token', $estate->access_token);
            session()->flash('pending_admin_approval', true);
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'redirect' => $redirectUrl,
                    'message' => 'Your estate plan has been activated successfully! Pending admin approval.',
                    'estate_id' => $estate->unique_id,
                    'token' => $estate->access_token,
                ]);
            }
            
            return redirect($redirectUrl)->with('success', 'Your estate plan has been activated and submitted for admin approval!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to activate estate: ' . $e->getMessage(), [
                'estate_id' => $estate->id,
                'trace' => $e->getTraceAsString(),
            ]);
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to activate estate: ' . $e->getMessage()], 500);
            }
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to activate estate. Please try again.');
        }
    }

    /**
     * Deactivate the estate plan.
     *
     * @param  string  $uniqueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deactivate(string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);

        DB::beginTransaction();
        try {
            $estate->update([
                'status' => 'draft',
                'activated_at' => null,
                'access_token' => null,
                'token_expires_at' => null,
            ]);
            DB::commit();
            return redirect()->route('estate-setup.index')
                ->with('success', 'Estate plan deactivated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to deactivate estate: ' . $e->getMessage(), [
                'estate_id' => $estate->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to deactivate estate. Please try again.');
        }
    }

    /**
     * Mark estate as completed.
     *
     * @param  Request      $request
     * @param  string|null  $uniqueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function complete(Request $request, ?string $uniqueId = null)
    {
        if ($uniqueId) {
            $estate = $this->getEstate($uniqueId);
        } else {
            $estate = EstatePreRegistration::where('user_id', Auth::id())
                ->where('status', 'activated')
                ->latest()
                ->first();

            if (!$estate) {
                return redirect()->route('estate-setup.index')
                    ->with('error', 'No active estate setup found.');
            }
        }

        DB::beginTransaction();
        try {
            $estate->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);
            DB::commit();
            return redirect()->route('estate-setup.index')
                ->with('success', 'Estate setup marked as completed.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to complete estate: ' . $e->getMessage(), [
                'estate_id' => $estate->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to complete estate setup.');
        }
    }

    // =========================================================================
    // PUBLIC WILL VIEW
    // =========================================================================

    /**
     * View will via token.
     *
     * @param  string  $token
     * @return \Illuminate\View\View
     */
    public function viewWill(string $token)
    {
        $estate = EstatePreRegistration::where('access_token', $token)
            ->where('token_expires_at', '>', now())
            ->firstOrFail();

        $calculator = new FaraidCalculator($estate);
        $distribution = $calculator->calculate();

        $wasiyyah = $estate->wasiyyah;

        return view('estate-setup.view-will', compact('estate', 'distribution', 'wasiyyah'));
    }

    // =========================================================================
    // SHOW, EDIT, UPDATE, DESTROY, EXPORT, PRINT
    // =========================================================================

    /**
     * Display the specified estate (redirects to index).
     *
     * @param  string  $uniqueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show(string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);
        $estate->load(['heirs', 'assets', 'debts', 'wasiyyah']);

        return redirect()->route('estate-setup.index');
    }

    /**
     * Edit estate (redirects to index).
     *
     * @param  string  $uniqueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(string $uniqueId)
    {
        return redirect()->route('estate-setup.index');
    }

    /**
     * Update estate (redirects to index).
     *
     * @param  Request  $request
     * @param  string   $uniqueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $uniqueId)
    {
        return redirect()->route('estate-setup.index')
            ->with('info', 'Use the sections below to update your estate.');
    }

    /**
     * Delete the estate and all related data.
     *
     * @param  string  $uniqueId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);

        DB::beginTransaction();
        try {
            // Delete related records
            $estate->heirs()->delete();
            $estate->wasiyyah()->delete();
            $estate->assets()->delete();
            $estate->debts()->delete();
            $estate->notificationRequests()->delete();

            // Delete digital credentials
            DigitalCredential::where('estate_pre_registration_id', $estate->id)->delete();

            // Delete will video file if exists
            if ($estate->will_video_url && !Str::contains($estate->will_video_url, 'youtube')) {
                $path = str_replace('/storage/', '', $estate->will_video_url);
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            // Delete death certificate file if exists
            if ($estate->death_certificate_url) {
                $path = str_replace('/storage/', '', $estate->death_certificate_url);
                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->delete($path);
                }
            }

            // Delete the estate
            $estate->delete();
            DB::commit();

            return redirect()->route('estate-setup.create')
                ->with('success', 'Estate plan deleted successfully. You can create a new one.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete estate: ' . $e->getMessage(), [
                'estate_id' => $estate->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('estate-setup.index')
                ->with('error', 'Failed to delete estate. Please try again.');
        }
    }

    /**
     * Export estate data as JSON.
     *
     * @param  string  $uniqueId
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);
        $estate->load(['heirs', 'wasiyyah', 'assets', 'debts']);

        return response()->json([
            'success' => true,
            'data' => [
                'estate' => $estate->toArray(),
                'heirs' => $estate->heirs->toArray(),
                'wasiyyah' => $estate->wasiyyah->toArray(),
                'assets' => $estate->assets->toArray(),
                'debts' => $estate->debts->toArray(),
            ],
        ]);
    }

    /**
     * Print estate data.
     *
     * @param  string  $uniqueId
     * @return \Illuminate\View\View
     */
    public function print(string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);
        $estate->load(['heirs', 'wasiyyah', 'assets', 'debts']);

        $calculator = new FaraidCalculator($estate);
        $distribution = $calculator->calculate();

        return view('estate-setup.print', compact('estate', 'distribution'));
    }

    // =========================================================================
    // API ENDPOINTS
    // =========================================================================

    /**
     * Calculate Faraid distribution via API.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateFaraid(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'unique_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $estate = EstatePreRegistration::where('unique_id', $request->unique_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $calculator = new FaraidCalculator($estate);
            $distribution = $calculator->calculate();

            return response()->json([
                'success' => true,
                'distribution' => $distribution,
                'total_assets' => $estate->assets()->sum('value'),
                'total_debts' => $estate->debts()->sum('amount'),
                'net_estate' => $estate->net_estate,
            ]);
        } catch (\Exception $e) {
            Log::error('Faraid calculation API failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['success' => false, 'message' => 'Calculation failed.'], 500);
        }
    }

    /**
     * Get estate summary via API.
     *
     * @param  string  $uniqueId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSummary(string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);
        $estate->load(['heirs', 'wasiyyah', 'assets', 'debts']);

        $calculator = new FaraidCalculator($estate);
        $distribution = $calculator->calculate();

        return response()->json([
            'success' => true,
            'data' => [
                'estate' => $estate,
                'heirs_count' => $estate->heirs->count(),
                'wasiyyah_count' => $estate->wasiyyah->count(),
                'assets_count' => $estate->assets->count(),
                'debts_count' => $estate->debts->count(),
                'total_assets' => $estate->total_assets,
                'total_debts' => $estate->total_debts,
                'net_estate' => $estate->net_estate,
                'total_wasiyyah_percentage' => $estate->total_wasiyyah_percentage,
                'status' => $estate->status,
                'distribution' => $distribution,
            ],
        ]);
    }

    /**
     * Get estate progress via API.
     *
     * @param  string  $uniqueId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProgress(string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);

        $progressSteps = [
            'personal_info' => !empty($estate->deceased_name),
            'heirs' => $estate->heirs()->count() > 0,
            'wasiyyah' => $estate->wasiyyah()->count() > 0,
            'assets' => $estate->assets()->count() > 0,
            'debts' => $estate->debts()->count() > 0,
            'will_video' => !is_null($estate->will_video_url) || !is_null($estate->will_text_content),
            'activated' => $estate->status === 'activated',
        ];

        $completedSteps = count(array_filter($progressSteps));
        $percentage = round(($completedSteps / 7) * 100);

        return response()->json([
            'success' => true,
            'progress' => [
                'percentage' => $percentage,
                'completed_steps' => $completedSteps,
                'total_steps' => 7,
                'steps' => $progressSteps,
            ],
        ]);
    }

    /**
     * Validate estate data via API.
     *
     * @param  string  $uniqueId
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateData(string $uniqueId)
    {
        $estate = $this->getEstate($uniqueId);
        $estate->load(['heirs', 'wasiyyah', 'assets', 'debts']);

        $errors = [];

        // Check for required data
        if ($estate->heirs->count() === 0) {
            $errors[] = 'No heirs added.';
        }
        if ($estate->assets->count() === 0) {
            $errors[] = 'No assets added.';
        }

        // Check net estate
        $totalAssets = $estate->assets()->sum('value');
        $totalDebts = $estate->debts()->sum('amount');
        $netEstate = max(0, $totalAssets - $totalDebts);

        if ($netEstate <= 0) {
            $errors[] = 'Net estate must be greater than 0.';
        }

        // Check wasiyyah limit
        $totalWasiyyah = $estate->wasiyyah->sum('requested_percentage');
        if ($totalWasiyyah > 33.33) {
            $errors[] = "Wasiyyah ({$totalWasiyyah}%) exceeds maximum (33.33%).";
        }

        // Check heirs with missing email
        $heirsWithoutEmail = $estate->heirs->filter(fn($heir) => empty($heir->email));
        if ($heirsWithoutEmail->count() > 0) {
            $errors[] = "{$heirsWithoutEmail->count()} heir(s) missing email addresses.";
        }

        return response()->json([
            'success' => empty($errors),
            'errors' => $errors,
            'is_valid' => empty($errors),
            'total_assets' => $totalAssets,
            'total_debts' => $totalDebts,
            'net_estate' => $netEstate,
            'total_wasiyyah_percentage' => $totalWasiyyah,
            'heirs_count' => $estate->heirs->count(),
            'assets_count' => $estate->assets->count(),
            'debts_count' => $estate->debts->count(),
        ]);
    }
}