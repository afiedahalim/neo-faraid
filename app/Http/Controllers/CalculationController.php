<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use App\Models\FaraidCalculationRule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Str;

class CalculationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'create', 'preview', 'viewShared', 'sharedPrint']);
    }

    /**
     * Display calculator form
     */
    public function index()
    {
        return view('calculator.index');
    }

    /**
     * Show form for new calculation
     */
    public function create()
    {
        return view('calculator.index');
    }

    /**
     * Save calculation via form submission - FIXED FOR MISSING COLUMNS
     */
    public function store(Request $request)
    {
        try {
            if (!Auth::check()) {
                return redirect()->route('login')
                    ->with('error', 'Please login to save calculations.');
            }

            Log::info('Store calculation request data:', $request->all());

            $validator = Validator::make($request->all(), [
                'deceased_name' => 'required|string|max:255',
                'deceased_gender' => 'required|in:male,female',
                'date_of_death' => 'required|date',
                'marital_status' => 'required|in:single,married,divorced,widowed',
                'total_assets' => 'required|numeric|min:0',
                'deceased_nric' => 'nullable|string|max:20',
                'cause_of_death' => 'required|string|max:255',
                'death_place' => 'required|string|max:255',
                'contact_email' => 'required|email|max:255',
                'contact_phone' => 'required|string|max:20',
                'residential_address' => 'required|string|max:500',
                
                'wife_count' => 'nullable|integer|min:0|max:4',
                'husband_count' => 'nullable|integer|min:0|max:1',
                'father_status' => 'required|in:alive,deceased',
                'mother_status' => 'required|in:alive,deceased',
                'son_count' => 'nullable|integer|min:0',
                'daughter_count' => 'nullable|integer|min:0',
                'full_brother_count' => 'nullable|integer|min:0',
                'full_sister_count' => 'nullable|integer|min:0',
                'paternal_half_brother_count' => 'nullable|integer|min:0',
                'paternal_half_sister_count' => 'nullable|integer|min:0',
                'maternal_half_brother_count' => 'nullable|integer|min:0',
                'maternal_half_sister_count' => 'nullable|integer|min:0',
                'fathers_father_status' => 'nullable|in:alive,deceased',
                'fathers_mother_status' => 'nullable|in:alive,deceased',
                'mothers_mother_status' => 'nullable|in:alive,deceased',
                
                'heirs_data' => 'nullable|string',
                'calculation_data' => 'nullable|string',
                'chart_data' => 'nullable|string',
                'tree_data' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput()
                    ->with('error', 'Please fill in all required fields correctly.');
            }

            DB::beginTransaction();

            try {
                // Extract heirs data from request
                $heirsData = [
                    'husband_count' => (int) ($request->husband_count ?? 0),
                    'wife_count' => (int) ($request->wife_count ?? 0),
                    'father_status' => $request->father_status ?? 'deceased',
                    'mother_status' => $request->mother_status ?? 'deceased',
                    'son_count' => (int) ($request->son_count ?? 0),
                    'daughter_count' => (int) ($request->daughter_count ?? 0),
                    'full_brother_count' => (int) ($request->full_brother_count ?? 0),
                    'full_sister_count' => (int) ($request->full_sister_count ?? 0),
                    'paternal_half_brother_count' => (int) ($request->paternal_half_brother_count ?? 0),
                    'paternal_half_sister_count' => (int) ($request->paternal_half_sister_count ?? 0),
                    'maternal_half_brother_count' => (int) ($request->maternal_half_brother_count ?? 0),
                    'maternal_half_sister_count' => (int) ($request->maternal_half_sister_count ?? 0),
                    'fathers_father_status' => $request->fathers_father_status ?? 'deceased',
                    'fathers_mother_status' => $request->fathers_mother_status ?? 'deceased',
                    'mothers_mother_status' => $request->mothers_mother_status ?? 'deceased',
                ];
                
                $totalAssets = (float) $request->total_assets;
                $netAssets = max($totalAssets, 0);
                
                $calculator = new \App\Services\FaraidCalculator(null, $heirsData, $netAssets);
                $calculationResult = $calculator->calculate();
                
                $distribution = $calculationResult['distribution'] ?? [];
                $scenarioNumber = $calculationResult['scenario_number'] ?? 15;
                $totalDistributed = $calculationResult['total_distributed'] ?? 0;
                $eligibleHeirsCount = $calculationResult['total_eligible'] ?? 0;
                $awlApplied = $calculationResult['awl_applied'] ?? false;
                
                $totalHeirs = $this->calculateTotalHeirs($heirsData);
                
                $distributionSummary = [
                    'heirs' => $distribution,
                    'total_distributed' => $totalDistributed,
                    'total_eligible' => $eligibleHeirsCount,
                    'net_estate' => $netAssets,
                    'total_heirs' => count($distribution),
                    'awl_applied' => $awlApplied,
                    'scenario_number' => $scenarioNumber,
                    'calculation_date' => now()->toISOString()
                ];
                
                // Prepare calculation data
                $calculationData = [];
                foreach ($distribution as $heir) {
                    if (($heir['amount'] ?? 0) > 0) {
                        $calculationData[] = [
                            'heir' => $heir['heir'],
                            'relationship' => $heir['relationship'],
                            'share' => $heir['share'],
                            'amount' => $heir['amount'],
                            'fraction' => $heir['fraction'] ?? ($netAssets > 0 ? $heir['amount'] / $netAssets : 0),
                            'status' => $heir['status'],
                            'type' => $heir['type'] ?? 'Fixed Share',
                            'percentage' => $heir['percentage'] ?? ($netAssets > 0 ? ($heir['amount'] / $netAssets) * 100 : 0)
                        ];
                    }
                }
                
                $chartData = $this->prepareChartDataFromDistribution($distributionSummary);
                
                $treeData = [
                    'deceased' => [
                        'name' => $request->deceased_name,
                        'gender' => $request->deceased_gender,
                        'net_estate' => $netAssets
                    ],
                    'heirs' => $heirsData,
                    'heirAmounts' => $this->extractHeirAmountsFromDistribution($distribution),
                    'distribution' => $distributionSummary,
                    'timestamp' => now()->toISOString(),
                    'generated_by' => 'Faraid Calculator'
                ];
                
                $scenarioDescription = $this->getScenarioDescription($scenarioNumber);
                $faraidScenario = null;
                $calculationMethod = 'local';
                $scenarioRulesApplied = null;
                
                if (class_exists(FaraidCalculationRule::class)) {
                    $faraidScenario = FaraidCalculationRule::where('scenario_number', $scenarioNumber)->first();
                    if ($faraidScenario) {
                        $calculationMethod = 'database';
                        $scenarioRulesApplied = json_decode($faraidScenario->distribution_rules, true);
                    }
                }
                
                // Assets data
                $assetsData = null;
                if ($request->has('assets_data') && $request->assets_data) {
                    $assetsData = json_decode($request->assets_data, true);
                } else {
                    $assetsData = [
                        'properties' => [],
                        'total_assets' => $totalAssets,
                        'net_assets' => $netAssets
                    ];
                }
                
                // Scenario data (extra metadata)
                $scenarioData = [
                    'scenario_number' => $scenarioNumber,
                    'description' => $scenarioDescription,
                    'awl_applied' => $awlApplied,
                    'timestamp' => now()->toISOString()
                ];
                
                // Create calculation – use fillable array to avoid extra columns
                $calculation = Calculation::create([
                    'user_id' => Auth::id(),
                    'deceased_name' => $request->deceased_name,
                    'deceased_nric' => $request->deceased_nric,
                    'deceased_gender' => $request->deceased_gender,
                    'date_of_death' => $request->date_of_death,
                    'marital_status' => $request->marital_status,
                    'cause_of_death' => $request->cause_of_death,
                    'death_place' => $request->death_place,
                    'contact_email' => $request->contact_email,
                    'contact_phone' => $request->contact_phone,
                    'residential_address' => $request->residential_address,
                    'total_assets' => $totalAssets,
                    'net_assets' => $netAssets,
                    'wife_count' => $heirsData['wife_count'],
                    'husband_count' => $heirsData['husband_count'],
                    'father_status' => $heirsData['father_status'],
                    'mother_status' => $heirsData['mother_status'],
                    'son_count' => $heirsData['son_count'],
                    'daughter_count' => $heirsData['daughter_count'],
                    'full_brother_count' => $heirsData['full_brother_count'],
                    'full_sister_count' => $heirsData['full_sister_count'],
                    'paternal_brother_count' => $heirsData['paternal_half_brother_count'],
                    'paternal_sister_count' => $heirsData['paternal_half_sister_count'],
                    'maternal_brother_count' => $heirsData['maternal_half_brother_count'],
                    'maternal_sister_count' => $heirsData['maternal_half_sister_count'],
                    'fathers_father_status' => $heirsData['fathers_father_status'],
                    'fathers_mother_status' => $heirsData['fathers_mother_status'],
                    'mothers_mother_status' => $heirsData['mothers_mother_status'],
                    'total_heirs' => $totalHeirs,
                    'eligible_heirs_count' => $eligibleHeirsCount,
                    'scenario_number' => $scenarioNumber,
                    'faraid_scenario_id' => $faraidScenario ? $faraidScenario->id : null,
                    'scenario_description' => $scenarioDescription,
                    'distribution_summary' => $distributionSummary,
                    'heirs_data' => $heirsData,
                    'assets_data' => $assetsData,
                    'calculation_data' => $calculationData,
                    'chart_data' => $chartData,
                    'tree_data' => $treeData,
                    'scenario_data' => $scenarioData,
                    'calculation_method' => $calculationMethod,
                    'scenario_rules_applied' => $scenarioRulesApplied,
                    'calculation_hash' => Calculation::generateHash(),
                    'tree_generation_status' => 'pending',
                ]);
                
                Log::info('Calculation created successfully', [
                    'id' => $calculation->id,
                    'deceased_name' => $calculation->deceased_name,
                    'eligible_heirs_count' => $eligibleHeirsCount,
                    'calculation_method' => $calculationMethod,
                    'scenario_number' => $scenarioNumber,
                    'awl_applied' => $awlApplied
                ]);
                
                DB::commit();
                
                return redirect()->route('calculator.show', $calculation)
                    ->with('success', 'Calculation for "' . $calculation->deceased_name . '" saved successfully!');
                    
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Calculation creation error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
                throw $e;
            }
            
        } catch (\Exception $e) {
            Log::error('Store calculation error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to save calculation: ' . $e->getMessage());
        }
    }

    /**
     * Calculate and show results (AJAX) - FIXED VERSION
     */
    public function calculate(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'deceased_name' => 'required|string|max:255',
                'deceased_gender' => 'required|in:male,female',
                'date_of_death' => 'required|date',
                'marital_status' => 'required|in:single,married,divorced,widowed',
                'total_assets' => 'required|numeric|min:0',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }

            // Extract heirs data
            $heirsData = $this->extractHeirsDataFromRequest($request);

            // Calculate net assets
            $totalAssets = (float) $request->total_assets;
            $netAssets = max($totalAssets, 0);

            if ($netAssets <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total estate must be greater than 0.'
                ], 400);
            }

            // Use the enhanced FaraidCalculator service
            $calculator = new \App\Services\FaraidCalculator(null, $heirsData, $netAssets);
            $calculationResult = $calculator->calculate();
            
            $distribution = $calculationResult['distribution'] ?? [];
            $scenarioNumber = $calculationResult['scenario_number'] ?? 15;
            $totalDistributed = $calculationResult['total_distributed'] ?? 0;
            $eligibleHeirsCount = $calculationResult['total_eligible'] ?? 0;
            $awlApplied = $calculationResult['awl_applied'] ?? false;
            
            // Prepare distribution summary
            $distributionSummary = [
                'heirs' => $distribution,
                'total_distributed' => $totalDistributed,
                'total_eligible' => $eligibleHeirsCount,
                'net_estate' => $netAssets,
                'total_heirs' => count($distribution),
                'awl_applied' => $awlApplied,
                'scenario_number' => $scenarioNumber,
                'calculation_date' => now()->toISOString()
            ];
            
            // Detect scenario
            $scenarioDescription = $this->getScenarioDescription($scenarioNumber);
            
            // Try to find matching scenario in database
            $faraidScenario = null;
            $calculationMethod = 'local';
            
            if (class_exists(FaraidCalculationRule::class)) {
                $faraidScenario = FaraidCalculationRule::where('scenario_number', $scenarioNumber)->first();
                if ($faraidScenario) {
                    $calculationMethod = 'database';
                }
            }

            // Prepare calculation data
            $calculationData = $this->prepareCalculationData($distributionSummary, $netAssets);

            // Prepare chart data
            $chartData = $this->prepareChartDataFromDistribution($distributionSummary);

            // Generate tree data
            $treeData = $this->generateTreeData($heirsData, $distributionSummary, [
                'deceased_name' => $request->deceased_name,
                'deceased_gender' => $request->deceased_gender,
                'net_assets' => $netAssets
            ]);

            // Prepare response
            $response = [
                'success' => true,
                'data' => [
                    'deceased_name' => $request->deceased_name,
                    'deceased_gender' => $request->deceased_gender,
                    'date_of_death' => $request->date_of_death,
                    'marital_status' => $request->marital_status,
                    'total_assets' => $totalAssets,
                    'net_assets' => $netAssets,
                    'total_heirs' => $this->calculateTotalHeirs($heirsData),
                    'eligible_heirs_count' => $eligibleHeirsCount,
                    'heirs_data' => $heirsData,
                    'distribution_summary' => $distributionSummary,
                    'calculation_data' => $calculationData,
                    'total_distributed' => $totalDistributed,
                    'scenario_number' => $scenarioNumber,
                    'faraid_scenario_id' => $faraidScenario ? $faraidScenario->id : null,
                    'scenario_description' => $scenarioDescription,
                    'calculation_method' => $calculationMethod,
                    'chart_data' => $chartData,
                    'tree_data' => $treeData,
                    'calculation_date' => now()->toDateTimeString(),
                    'calculation_hash' => Calculation::generateHash(),
                    'awl_applied' => $awlApplied
                ],
                'preview_url' => route('calculator.preview')
            ];

            // Store in session for preview
            session(['pending_calculation' => $response['data']]);

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Calculation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Calculation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show calculation details
     */
    public function show(Calculation $calculation)
    {
        $this->authorize('view', $calculation);
        
        // Log for debugging
        Log::info('Showing calculation ID: ' . $calculation->id, [
            'deceased_name' => $calculation->deceased_name,
            'has_distribution_summary' => !empty($calculation->distribution_summary),
            'calculation_method' => $calculation->calculation_method
        ]);
        
        // If distribution_summary is empty or invalid, recalculate it
        if (empty($calculation->distribution_summary) || !is_array($calculation->distribution_summary)) {
            Log::warning('Empty or invalid distribution_summary for calculation ID: ' . $calculation->id);
            
            try {
                // Get heirs data
                $heirsData = $calculation->heirs_data ?? $this->extractHeirsDataFromCalculation($calculation);
                
                // Use FaraidCalculator service
                $calculator = new \App\Services\FaraidCalculator(null, $heirsData, $calculation->net_assets);
                $calculationResult = $calculator->calculate();
                
                $distribution = $calculationResult['distribution'] ?? [];
                $totalDistributed = $calculationResult['total_distributed'] ?? 0;
                $eligibleHeirsCount = $calculationResult['total_eligible'] ?? 0;
                
                $distributionSummary = [
                    'heirs' => $distribution,
                    'total_distributed' => $totalDistributed,
                    'total_eligible' => $eligibleHeirsCount,
                    'net_estate' => $calculation->net_assets,
                    'total_heirs' => count($distribution)
                ];
                
                // Prepare calculation data
                $calculationData = $this->prepareCalculationData($distributionSummary, $calculation->net_assets);
                
                // Update the calculation
                $calculation->update([
                    'distribution_summary' => $distributionSummary,
                    'calculation_data' => $calculationData,
                    'eligible_heirs_count' => $eligibleHeirsCount
                ]);
                
                // Refresh the instance
                $calculation->refresh();
                
                Log::info('Recalculated distribution for calculation ID: ' . $calculation->id);
            } catch (\Exception $e) {
                Log::error('Failed to recalculate distribution: ' . $e->getMessage());
            }
        }
        
        return view('calculator.show', compact('calculation'));
    }

    /**
     * Show calculation history
     */
    public function history(Request $request)
    {
        try {
            // Get all calculations for the authenticated user
            $query = Calculation::where('user_id', Auth::id())->latest();

            // Search by deceased name or ID
            if ($request->filled('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('deceased_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('id', 'like', '%' . $searchTerm . '%');
                });
            }

            // Date range filter
            if ($request->filled('from_date')) {
                $query->whereDate('created_at', '>=', $request->from_date);
            }

            if ($request->filled('to_date')) {
                $query->whereDate('created_at', '<=', $request->to_date);
            }

            // Get paginated results
            $calculations = $query->paginate(15)->withQueryString();

            // Calculate statistics
            $stats = $this->calculateUserStatistics(Auth::id());

            return view('calculator.history', compact('calculations', 'stats'));

        } catch (\Exception $e) {
            Log::error('History page error: ' . $e->getMessage());

            // Return empty data if there's an error
            $calculations = collect([]);
            $stats = $this->getDefaultStatistics();

            return view('calculator.history', compact('calculations', 'stats'))
                ->with('error', 'Failed to load calculation history');
        }
    }

    /**
     * Print calculation report
     */
    public function print(Calculation $calculation)
    {
        $this->authorize('view', $calculation);
        
        try {
            // Ensure distribution_summary exists and is valid
            if (empty($calculation->distribution_summary) || !is_array($calculation->distribution_summary)) {
                Log::warning('Invalid distribution summary for print, recalculating...');
                $heirsData = $calculation->heirs_data ?? $this->extractHeirsDataFromCalculation($calculation);
                
                $calculator = new \App\Services\FaraidCalculator(null, $heirsData, $calculation->net_assets);
                $calculationResult = $calculator->calculate();
                
                $distribution = $calculationResult['distribution'] ?? [];
                $totalDistributed = $calculationResult['total_distributed'] ?? 0;
                $eligibleHeirsCount = $calculationResult['total_eligible'] ?? 0;
                
                $distributionSummary = [
                    'heirs' => $distribution,
                    'total_distributed' => $totalDistributed,
                    'total_eligible' => $eligibleHeirsCount,
                    'net_estate' => $calculation->net_assets,
                    'total_heirs' => count($distribution)
                ];
                
                $calculationData = $this->prepareCalculationData($distributionSummary, $calculation->net_assets);
                
                $calculation->update([
                    'distribution_summary' => $distributionSummary,
                    'calculation_data' => $calculationData
                ]);
                
                $calculation->refresh();
            }
            
            $pdf = Pdf::loadView('calculator.print', compact('calculation'));
            
            $filename = 'inheritance-calculation-' . Str::slug($calculation->deceased_name) . '-' . date('Y-m-d') . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('Print error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }

    /**
     * Preview calculation before saving
     */
    public function preview()
    {
        $calculation = session('pending_calculation');
        
        if (!$calculation) {
            return redirect()->route('calculator.create')
                ->with('error', 'No calculation to preview. Please fill the form first.');
        }
        
        return view('calculator.preview', compact('calculation'));
    }

    /**
     * Show edit form
     */
    public function edit(Calculation $calculation)
    {
        $this->authorize('update', $calculation);
        
        return view('calculator.edit', compact('calculation'));
    }

    /**
     * Update calculation - COMPLETE FIXED VERSION WITH FARAID CALCULATOR
     */
    public function update(Request $request, Calculation $calculation)
    {
        $this->authorize('update', $calculation);

        Log::info('UPDATE REQUEST DATA:', $request->all());
        
        try {
            // Validate the request
            $validated = $request->validate([
                'deceased_name' => 'required|string|max:255',
                'deceased_gender' => 'required|in:male,female',
                'date_of_death' => 'required|date',
                'marital_status' => 'required|in:single,married,divorced,widowed',
                'total_assets' => 'required|numeric|min:0',
                'deceased_nric' => 'nullable|string|max:20',
                'cause_of_death' => 'nullable|string|max:255',
                'death_place' => 'nullable|string|max:255',
                'contact_email' => 'nullable|email|max:255',
                'contact_phone' => 'nullable|string|max:20',
                'residential_address' => 'nullable|string|max:500',
            ]);

            DB::beginTransaction();

            // Prepare heirs data
            $heirsData = [
                'husband_count' => (int) ($request->husband_count ?? 0),
                'wife_count' => (int) ($request->wife_count ?? 0),
                'father_status' => $request->father_status ?? 'deceased',
                'mother_status' => $request->mother_status ?? 'deceased',
                'son_count' => (int) ($request->son_count ?? 0),
                'daughter_count' => (int) ($request->daughter_count ?? 0),
                'full_brother_count' => (int) ($request->full_brother_count ?? 0),
                'full_sister_count' => (int) ($request->full_sister_count ?? 0),
                'paternal_half_brother_count' => (int) ($request->paternal_half_brother_count ?? 0),
                'paternal_half_sister_count' => (int) ($request->paternal_half_sister_count ?? 0),
                'maternal_half_brother_count' => (int) ($request->maternal_half_brother_count ?? 0),
                'maternal_half_sister_count' => (int) ($request->maternal_half_sister_count ?? 0),
                'fathers_father_status' => $request->fathers_father_status ?? 'deceased',
                'fathers_mother_status' => $request->fathers_mother_status ?? 'deceased',
                'mothers_mother_status' => $request->mothers_mother_status ?? 'deceased',
            ];

            Log::info('Heirs Data Prepared for Update:', $heirsData);

            // Calculate financial values
            $totalAssets = (float) $request->total_assets;
            $netAssets = max($totalAssets, 0);

            Log::info('Financial Calculations for Update:', [
                'totalAssets' => $totalAssets,
                'netAssets' => $netAssets
            ]);

            // Use the enhanced FaraidCalculator service
            $calculator = new \App\Services\FaraidCalculator(null, $heirsData, $netAssets);
            $calculationResult = $calculator->calculate();
            
            $distribution = $calculationResult['distribution'] ?? [];
            $scenarioNumber = $calculationResult['scenario_number'] ?? 15;
            $totalDistributed = $calculationResult['total_distributed'] ?? 0;
            $eligibleHeirsCount = $calculationResult['total_eligible'] ?? 0;
            $awlApplied = $calculationResult['awl_applied'] ?? false;
            
            // Calculate total heirs
            $totalHeirs = $this->calculateTotalHeirs($heirsData);

            Log::info('Distribution Summary Calculated for Update:', [
                'has_summary' => !empty($distribution),
                'heirs_count' => count($distribution),
                'total_distributed' => $totalDistributed,
                'total_eligible' => $eligibleHeirsCount,
                'awl_applied' => $awlApplied
            ]);

            // Prepare distribution summary for storage
            $distributionSummary = [
                'heirs' => $distribution,
                'total_distributed' => $totalDistributed,
                'total_eligible' => $eligibleHeirsCount,
                'net_estate' => $netAssets,
                'total_heirs' => count($distribution),
                'awl_applied' => $awlApplied,
                'scenario_number' => $scenarioNumber,
                'calculation_date' => now()->toISOString()
            ];

            // Detect scenario and get from database if available
            $scenarioDescription = $this->getScenarioDescription($scenarioNumber);
            
            // Try to find matching scenario in database
            $faraidScenario = null;
            $calculationMethod = 'local';
            $scenarioRulesApplied = null;
            
            if (class_exists(FaraidCalculationRule::class)) {
                $faraidScenario = FaraidCalculationRule::where('scenario_number', $scenarioNumber)->first();
                if ($faraidScenario) {
                    $calculationMethod = 'database';
                    $scenarioRulesApplied = json_decode($faraidScenario->distribution_rules, true);
                }
            }

            // Prepare calculation data
            $calculationData = $this->prepareCalculationData($distributionSummary, $netAssets);

            // Generate chart data
            $chartData = $this->prepareChartDataFromDistribution($distributionSummary);

            // Generate updated tree data
            $treeData = $this->generateTreeData($heirsData, $distributionSummary, [
                'deceased_name' => $request->deceased_name,
                'deceased_gender' => $request->deceased_gender,
                'net_assets' => $netAssets
            ]);
            $treeData['updated_at'] = now()->toISOString();

            // Update calculation with ALL fields
            $updateData = [
                'deceased_name' => $request->deceased_name,
                'deceased_gender' => $request->deceased_gender,
                'date_of_death' => $request->date_of_death,
                'marital_status' => $request->marital_status,
                'deceased_nric' => $request->deceased_nric,
                'cause_of_death' => $request->cause_of_death,
                'death_place' => $request->death_place,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'residential_address' => $request->residential_address,
                
                // Financial data
                'total_assets' => $totalAssets,
                'net_assets' => $netAssets,
                
                // Heirs counts - individual fields
                'wife_count' => $heirsData['wife_count'],
                'husband_count' => $heirsData['husband_count'],
                'father_status' => $heirsData['father_status'],
                'mother_status' => $heirsData['mother_status'],
                'son_count' => $heirsData['son_count'],
                'daughter_count' => $heirsData['daughter_count'],
                'full_brother_count' => $heirsData['full_brother_count'],
                'full_sister_count' => $heirsData['full_sister_count'],
                'paternal_brother_count' => $heirsData['paternal_half_brother_count'],
                'paternal_sister_count' => $heirsData['paternal_half_sister_count'],
                'maternal_brother_count' => $heirsData['maternal_half_brother_count'],
                'maternal_sister_count' => $heirsData['maternal_half_sister_count'],
                'fathers_father_status' => $heirsData['fathers_father_status'],
                'fathers_mother_status' => $heirsData['fathers_mother_status'],
                'mothers_mother_status' => $heirsData['mothers_mother_status'],
                
                'total_heirs' => $totalHeirs,
                'eligible_heirs_count' => $eligibleHeirsCount,
                
                // Calculation results
                'scenario_number' => $scenarioNumber,
                'faraid_scenario_id' => $faraidScenario ? $faraidScenario->id : null,
                'scenario_description' => $scenarioDescription,
                
                // Distribution summary - MUST be valid array
                'distribution_summary' => $distributionSummary,
                
                // JSON data storage
                'heirs_data' => $heirsData,
                'calculation_data' => $calculationData,
                'chart_data' => $chartData,
                'tree_data' => $treeData,
                
                // CRITICAL: Update the missing field
                'calculation_method' => $calculationMethod,
                
                // Scenario rules if from database
                'scenario_rules_applied' => $scenarioRulesApplied,
                
                'scenario_data' => [
                    'scenario_number' => $scenarioNumber,
                    'description' => $scenarioDescription,
                    'awl_applied' => $awlApplied,
                    'timestamp' => now()->toISOString()
                ],
                
                // Metadata
                'tree_generation_status' => 'updated',
                'updated_at' => now(),
            ];

            // Update the calculation
            $calculation->update($updateData);

            Log::info('Calculation updated successfully', [
                'id' => $calculation->id,
                'deceased_name' => $calculation->deceased_name,
                'eligible_heirs_count' => $eligibleHeirsCount,
                'calculation_method' => $calculationMethod,
                'has_distribution_summary' => !empty($distributionSummary),
                'distribution_heirs_count' => count($distributionSummary['heirs'] ?? []),
                'awl_applied' => $awlApplied
            ]);

            DB::commit();

            return redirect()->route('calculator.show', $calculation)
                ->with('success', 'Calculation updated successfully!')
                ->with('updated', true);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Update calculation error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update calculation: ' . $e->getMessage());
        }
    }

    /**
     * Delete calculation
     */
    public function destroy(Calculation $calculation)
    {
        $this->authorize('delete', $calculation);

        try {
            // Delete associated files
            if ($calculation->family_tree_image) {
                Storage::disk('public')->delete($calculation->family_tree_image);
            }
            
            $calculation->delete();

            return redirect()->route('calculator.history')
                ->with('success', 'Calculation deleted successfully!');

        } catch (\Exception $e) {
            Log::error('Delete calculation error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to delete calculation: ' . $e->getMessage());
        }
    }

    /**
     * Recalculate distribution for existing calculation
     */
    public function recalculate(Calculation $calculation)
    {
        $this->authorize('update', $calculation);

        try {
            DB::beginTransaction();

            // Get current data
            $heirsData = $calculation->heirs_data ?? $this->extractHeirsDataFromCalculation($calculation);
            $netAssets = $calculation->net_assets;
            
            // Use FaraidCalculator service
            $calculator = new \App\Services\FaraidCalculator(null, $heirsData, $netAssets);
            $calculationResult = $calculator->calculate();
            
            $distribution = $calculationResult['distribution'] ?? [];
            $scenarioNumber = $calculationResult['scenario_number'] ?? 15;
            $totalDistributed = $calculationResult['total_distributed'] ?? 0;
            $eligibleHeirsCount = $calculationResult['total_eligible'] ?? 0;
            $awlApplied = $calculationResult['awl_applied'] ?? false;
            
            $distributionSummary = [
                'heirs' => $distribution,
                'total_distributed' => $totalDistributed,
                'total_eligible' => $eligibleHeirsCount,
                'net_estate' => $netAssets,
                'total_heirs' => count($distribution),
                'awl_applied' => $awlApplied,
                'scenario_number' => $scenarioNumber
            ];
            
            // Prepare calculation data
            $calculationData = $this->prepareCalculationData($distributionSummary, $netAssets);
            
            // Generate chart data
            $chartData = $this->prepareChartDataFromDistribution($distributionSummary);
            
            // Generate updated tree data
            $treeData = $this->generateTreeData($heirsData, $distributionSummary, [
                'deceased_name' => $calculation->deceased_name,
                'deceased_gender' => $calculation->deceased_gender,
                'net_assets' => $netAssets
            ]);
            $treeData['recalculated_at'] = now()->toISOString();
            
            // Detect scenario and get from database if available
            $scenarioDescription = $this->getScenarioDescription($scenarioNumber);
            
            // Try to find matching scenario in database
            $faraidScenario = null;
            $calculationMethod = 'local';
            $scenarioRulesApplied = null;
            
            if (class_exists(FaraidCalculationRule::class)) {
                $faraidScenario = FaraidCalculationRule::where('scenario_number', $scenarioNumber)->first();
                if ($faraidScenario) {
                    $calculationMethod = 'database';
                    $scenarioRulesApplied = json_decode($faraidScenario->distribution_rules, true);
                }
            }

            // Update calculation
            $calculation->update([
                'distribution_summary' => $distributionSummary,
                'calculation_data' => $calculationData,
                'eligible_heirs_count' => $eligibleHeirsCount,
                'chart_data' => $chartData,
                'tree_data' => $treeData,
                'scenario_number' => $scenarioNumber,
                'faraid_scenario_id' => $faraidScenario ? $faraidScenario->id : null,
                'scenario_description' => $scenarioDescription,
                'calculation_method' => $calculationMethod,
                'scenario_rules_applied' => $scenarioRulesApplied,
                'updated_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('calculator.show', $calculation)
                ->with('success', 'Distribution recalculated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Recalculate distribution error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to recalculate distribution: ' . $e->getMessage());
        }
    }

    /**
     * Fix existing calculations that have missing distribution data
     */
    public function fixCalculationData(Calculation $calculation)
    {
        try {
            // Get heirs data
            $heirsData = $calculation->heirs_data ?? [];
            if (empty($heirsData)) {
                $heirsData = $this->extractHeirsDataFromCalculation($calculation);
            }

            // Debug log
            Log::info('Fixing calculation data:', [
                'calculation_id' => $calculation->id,
                'heirs_data' => $heirsData,
                'net_assets' => $calculation->net_assets
            ]);

            // Use FaraidCalculator service
            $calculator = new \App\Services\FaraidCalculator(null, $heirsData, $calculation->net_assets);
            $calculationResult = $calculator->calculate();
            
            $distribution = $calculationResult['distribution'] ?? [];
            $scenarioNumber = $calculationResult['scenario_number'] ?? 15;
            $totalDistributed = $calculationResult['total_distributed'] ?? 0;
            $eligibleHeirsCount = $calculationResult['total_eligible'] ?? 0;
            $awlApplied = $calculationResult['awl_applied'] ?? false;
            
            $distributionSummary = [
                'heirs' => $distribution,
                'total_distributed' => $totalDistributed,
                'total_eligible' => $eligibleHeirsCount,
                'net_estate' => $calculation->net_assets,
                'total_heirs' => count($distribution),
                'awl_applied' => $awlApplied,
                'scenario_number' => $scenarioNumber
            ];
            
            // Generate chart data
            $chartData = $this->prepareChartDataFromDistribution($distributionSummary);

            // Prepare calculation data
            $calculationData = $this->prepareCalculationData($distributionSummary, $calculation->net_assets);
            
            // Detect scenario and get from database if available
            $scenarioDescription = $this->getScenarioDescription($scenarioNumber);
            
            // Try to find matching scenario in database
            $faraidScenario = null;
            $calculationMethod = $calculation->calculation_method ?? 'local';
            $scenarioRulesApplied = null;
            
            if (class_exists(FaraidCalculationRule::class)) {
                $faraidScenario = FaraidCalculationRule::where('scenario_number', $scenarioNumber)->first();
                if ($faraidScenario) {
                    $calculationMethod = 'database';
                    $scenarioRulesApplied = json_decode($faraidScenario->distribution_rules, true);
                }
            }

            // Log the update
            Log::info('Updating calculation with:', [
                'eligible_heirs_count' => $eligibleHeirsCount,
                'distribution_summary_count' => count($distributionSummary['heirs'] ?? []),
                'calculation_data_count' => count($calculationData),
                'calculation_method' => $calculationMethod,
                'awl_applied' => $awlApplied
            ]);

            // Update calculation
            $calculation->update([
                'distribution_summary' => $distributionSummary,
                'eligible_heirs_count' => $eligibleHeirsCount,
                'calculation_data' => $calculationData,
                'chart_data' => $chartData,
                'scenario_number' => $scenarioNumber,
                'faraid_scenario_id' => $faraidScenario ? $faraidScenario->id : null,
                'scenario_description' => $scenarioDescription,
                'calculation_method' => $calculationMethod,
                'scenario_rules_applied' => $scenarioRulesApplied,
                'scenario_data' => [
                    'scenario_number' => $scenarioNumber,
                    'description' => $scenarioDescription,
                    'awl_applied' => $awlApplied,
                    'timestamp' => now()->toISOString(),
                    'rules_applied' => 'Data consistency fix'
                ],
                'updated_at' => now(),
            ]);

            Log::info('Successfully fixed calculation ID: ' . $calculation->id);

            return redirect()->route('calculator.show', $calculation)
                ->with('success', 'Calculation data fixed successfully!');

        } catch (\Exception $e) {
            Log::error('Fix calculation data error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            
            return redirect()->back()
                ->with('error', 'Failed to fix calculation data: ' . $e->getMessage());
        }
    }

    /**
     * Share calculation with other users
     */
    public function share(Request $request, Calculation $calculation)
    {
        $this->authorize('view', $calculation);

        try {
            $request->validate([
                'email' => 'required|email',
                'message' => 'nullable|string|max:500'
            ]);

            // Generate share token
            $shareToken = Str::random(32);
            
            // Create share record
            $calculation->shares()->create([
                'user_id' => Auth::id(),
                'email' => $request->email,
                'token' => $shareToken,
                'message' => $request->message,
                'expires_at' => now()->addDays(7)
            ]);

            // Send email notification
            // TODO: Implement email sending logic

            return redirect()->back()
                ->with('success', 'Calculation shared successfully!');
                
        } catch (\Exception $e) {
            Log::error('Share calculation error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to share calculation: ' . $e->getMessage());
        }
    }

    /**
     * View shared calculation
     */
    public function viewShared($token)
    {
        try {
            // Find the share record
            $share = \App\Models\CalculationShare::where('token', $token)
                ->where('expires_at', '>', now())
                ->firstOrFail();

            $calculation = $share->calculation;

            // Check if share is still valid
            if (!$calculation || $share->expires_at < now()) {
                return redirect()->route('calculator.index')
                    ->with('error', 'This shared link has expired or is invalid.');
            }

            // Increment view count
            $share->increment('views');

            return view('calculator.shared', compact('calculation', 'share'));

        } catch (\Exception $e) {
            Log::error('View shared calculation error: ' . $e->getMessage());
            
            return redirect()->route('calculator.index')
                ->with('error', 'Unable to view shared calculation.');
        }
    }

    /**
     * Export calculation to CSV
     */
    public function export(Calculation $calculation)
    {
        $this->authorize('view', $calculation);

        try {
            // Ensure distribution_summary exists
            if (empty($calculation->distribution_summary)) {
                Log::warning('Empty distribution summary for export, recalculating...');
                $heirsData = $calculation->heirs_data ?? $this->extractHeirsDataFromCalculation($calculation);
                
                $calculator = new \App\Services\FaraidCalculator(null, $heirsData, $calculation->net_assets);
                $calculationResult = $calculator->calculate();
                
                $distribution = $calculationResult['distribution'] ?? [];
                $totalDistributed = $calculationResult['total_distributed'] ?? 0;
                $eligibleHeirsCount = $calculationResult['total_eligible'] ?? 0;
                
                $distributionSummary = [
                    'heirs' => $distribution,
                    'total_distributed' => $totalDistributed,
                    'total_eligible' => $eligibleHeirsCount,
                    'net_estate' => $calculation->net_assets,
                    'total_heirs' => count($distribution)
                ];
                
                $calculationData = $this->prepareCalculationData($distributionSummary, $calculation->net_assets);
                
                $calculation->update([
                    'distribution_summary' => $distributionSummary,
                    'calculation_data' => $calculationData
                ]);
                
                $calculation->refresh();
            }

            $filename = 'inheritance-calculation-' . Str::slug($calculation->deceased_name) . '-' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($calculation) {
                $file = fopen('php://output', 'w');
                
                // Write headers
                fputcsv($file, ['Inheritance Calculation Report']);
                fputcsv($file, ['Deceased Name', $calculation->deceased_name]);
                fputcsv($file, ['Date of Death', $calculation->date_of_death]);
                fputcsv($file, ['Total Assets', 'RM ' . number_format($calculation->total_assets, 2)]);
                fputcsv($file, ['Net Assets', 'RM ' . number_format($calculation->net_assets, 2)]);
                fputcsv($file, ['Calculation Method', $calculation->calculation_method ?? 'local']);
                fputcsv($file, ['']); // Empty row
                
                // Write heirs header
                fputcsv($file, ['Heirs Distribution']);
                fputcsv($file, ['Heir', 'Relationship', 'Share', 'Amount', 'Percentage', 'Status']);
                
                // Write heirs data
                $distribution = $calculation->distribution_summary['heirs'] ?? [];
                foreach ($distribution as $heir) {
                    fputcsv($file, [
                        $heir['name'] ?? $heir['heir'] ?? 'Unknown',
                        $heir['relationship'] ?? 'Unknown',
                        $heir['share'] ?? '0',
                        'RM ' . number_format($heir['amount'] ?? 0, 2),
                        number_format($heir['percentage'] ?? 0, 2) . '%',
                        $heir['status'] ?? 'Unknown'
                    ]);
                }
                
                fputcsv($file, ['']); // Empty row
                fputcsv($file, ['Total Distributed', 'RM ' . number_format($calculation->distribution_summary['total_distributed'] ?? 0, 2)]);
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Export calculation error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to export calculation: ' . $e->getMessage());
        }
    }

    /**
     * Bulk delete calculations
     */
    public function bulkDelete(Request $request)
    {
        try {
            $request->validate([
                'calculation_ids' => 'required|array',
                'calculation_ids.*' => 'exists:calculations,id'
            ]);

            $deletedCount = 0;
            
            foreach ($request->calculation_ids as $id) {
                $calculation = Calculation::findOrFail($id);
                
                // Check authorization
                if (Auth::id() !== $calculation->user_id) {
                    continue;
                }
                
                // Delete associated files
                if ($calculation->family_tree_image) {
                    Storage::disk('public')->delete($calculation->family_tree_image);
                }
                
                $calculation->delete();
                $deletedCount++;
            }

            return redirect()->route('calculator.history')
                ->with('success', "{$deletedCount} calculations deleted successfully!");

        } catch (\Exception $e) {
            Log::error('Bulk delete error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to delete calculations: ' . $e->getMessage());
        }
    }

    /**
     * Get calculation statistics for dashboard
     */
    public function getStatistics()
    {
        try {
            $userId = Auth::id();
            $stats = $this->calculateUserStatistics($userId);
            
            // Add more detailed statistics
            $stats['recent_calculations'] = Calculation::where('user_id', $userId)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
            
            $stats['total_net_assets'] = Calculation::where('user_id', $userId)
                ->sum('net_assets') ?? 0;
            
            $stats['average_heirs'] = Calculation::where('user_id', $userId)
                ->avg('eligible_heirs_count') ?? 0;
            
            $stats['most_common_scenario'] = Calculation::where('user_id', $userId)
                ->select('scenario_number', DB::raw('COUNT(*) as count'))
                ->groupBy('scenario_number')
                ->orderBy('count', 'desc')
                ->first();
            
            $stats['calculation_methods'] = Calculation::where('user_id', $userId)
                ->select('calculation_method', DB::raw('COUNT(*) as count'))
                ->groupBy('calculation_method')
                ->get();
            
            return response()->json([
                'success' => true,
                'statistics' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Get statistics error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics'
            ], 500);
        }
    }

    /**
     * Get calculation statistics for admin panel (AJAX)
     */
    public function stats(Request $request)
    {
        try {
            // Check if it's an AJAX request
            if (!$request->ajax()) {
                abort(404);
            }

            // Only accessible by admin
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            // Calculate statistics
            $stats = [
                'totalCalculations' => Calculation::count(),
                'totalAssets' => Calculation::sum('total_assets') ?? 0,
                'totalUsers' => User::count(),
                'todayCalculations' => Calculation::whereDate('created_at', today())->count(),
                'thisMonthCalculations' => Calculation::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->count(),
                'averageAssets' => Calculation::avg('total_assets') ?? 0,
                'telegramCalculations' => Calculation::where('calculation_method', 'telegram_bot')->count(),
                'webCalculations' => Calculation::where('calculation_method', '!=', 'telegram_bot')->count(),
                'scenarioBreakdown' => Calculation::select('scenario_number', DB::raw('COUNT(*) as count'))
                    ->groupBy('scenario_number')
                    ->get()
                    ->pluck('count', 'scenario_number')
                    ->toArray()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'timestamp' => now()->toDateTimeString()
            ]);

        } catch (\Exception $e) {
            Log::error('Stats calculation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate calculation data
     */
    public function validateData(Calculation $calculation)
    {
        try {
            $this->authorize('view', $calculation);
            
            $validationResults = [
                'isValid' => true,
                'errors' => [],
                'warnings' => [],
                'dataSummary' => []
            ];
            
            // Check required fields
            $requiredFields = [
                'deceased_name',
                'deceased_gender',
                'date_of_death',
                'marital_status',
                'total_assets',
                'net_assets'
            ];
            
            foreach ($requiredFields as $field) {
                if (empty($calculation->{$field})) {
                    $validationResults['isValid'] = false;
                    $validationResults['errors'][] = "Required field '{$field}' is empty.";
                }
            }
            
            // Check financial consistency
            if ($calculation->total_assets < $calculation->net_assets) {
                $validationResults['warnings'][] = "Total assets ({$calculation->total_assets}) is less than net assets ({$calculation->net_assets}).";
            }
            
            // Check heirs data
            if (empty($calculation->heirs_data)) {
                $validationResults['warnings'][] = "Heirs data is empty.";
            } else {
                $heirsData = $calculation->heirs_data;
                if (is_array($heirsData)) {
                    $totalHeirs = $this->calculateTotalHeirs($heirsData);
                    if ($totalHeirs == 0) {
                        $validationResults['warnings'][] = "No heirs specified for this calculation.";
                    }
                }
            }
            
            // Check distribution summary
            if (empty($calculation->distribution_summary)) {
                $validationResults['errors'][] = "Distribution summary is missing.";
                $validationResults['isValid'] = false;
            } else {
                $distribution = $calculation->distribution_summary;
                $totalDistributed = $distribution['total_distributed'] ?? 0;
                $expectedTotal = $calculation->net_assets;
                
                if (abs($totalDistributed - $expectedTotal) > 0.01) {
                    $validationResults['warnings'][] = "Total distributed (RM " . number_format($totalDistributed, 2) . 
                                                      ") does not match net assets (RM " . number_format($expectedTotal, 2) . ").";
                }
            }
            
            // Check calculation method
            if (empty($calculation->calculation_method)) {
                $validationResults['warnings'][] = "Calculation method is not specified.";
            }
            
            // Add data summary
            $validationResults['dataSummary'] = [
                'id' => $calculation->id,
                'deceased_name' => $calculation->deceased_name,
                'total_assets' => $calculation->total_assets,
                'net_assets' => $calculation->net_assets,
                'eligible_heirs_count' => $calculation->eligible_heirs_count,
                'calculation_method' => $calculation->calculation_method ?? 'Not specified',
                'scenario_number' => $calculation->scenario_number ?? 'Not specified',
                'created_at' => $calculation->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $calculation->updated_at->format('Y-m-d H:i:s')
            ];
            
            return response()->json([
                'success' => true,
                'validation' => $validationResults
            ]);
            
        } catch (\Exception $e) {
            Log::error('Validate calculation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clone an existing calculation
     */
    public function clone(Calculation $calculation)
    {
        $this->authorize('view', $calculation);
        
        try {
            DB::beginTransaction();
            
            // Create new calculation based on the existing one
            $newCalculation = $calculation->replicate();
            $newCalculation->user_id = Auth::id();
            $newCalculation->deceased_name = 'Copy of ' . $calculation->deceased_name;
            $newCalculation->calculation_hash = Calculation::generateHash();
            $newCalculation->created_at = now();
            $newCalculation->updated_at = now();
            $newCalculation->save();
            
            Log::info('Calculation cloned', [
                'original_id' => $calculation->id,
                'new_id' => $newCalculation->id,
                'user_id' => Auth::id()
            ]);
            
            DB::commit();
            
            return redirect()->route('calculator.show', $newCalculation)
                ->with('success', 'Calculation cloned successfully!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Clone calculation error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to clone calculation: ' . $e->getMessage());
        }
    }

    /**
     * Check calculation data integrity
     */
    public function checkData(Calculation $calculation)
    {
        $this->authorize('view', $calculation);
        
        try {
            $issues = [];
            
            // Check for missing distribution summary
            if (empty($calculation->distribution_summary)) {
                $issues[] = [
                    'type' => 'error',
                    'message' => 'Distribution summary is missing',
                    'fixable' => true,
                    'fix_url' => route('calculator.fix', $calculation)
                ];
            }
            
            // Check for empty heirs data
            if (empty($calculation->heirs_data)) {
                $issues[] = [
                    'type' => 'warning',
                    'message' => 'Heirs data is empty',
                    'fixable' => true,
                    'fix_url' => route('calculator.recalculate', $calculation)
                ];
            }
            
            // Check calculation method
            if (empty($calculation->calculation_method)) {
                $issues[] = [
                    'type' => 'warning',
                    'message' => 'Calculation method is not specified',
                    'fixable' => true,
                    'fix_url' => route('calculator.fix', $calculation)
                ];
            }
            
            // Check financial consistency
            if ($calculation->net_assets <= 0) {
                $issues[] = [
                    'type' => 'error',
                    'message' => 'Net assets is zero or negative',
                    'fixable' => false
                ];
            }
            
            return response()->json([
                'success' => true,
                'issues' => $issues,
                'issue_count' => count($issues),
                'calculation_id' => $calculation->id,
                'deceased_name' => $calculation->deceased_name
            ]);
            
        } catch (\Exception $e) {
            Log::error('Check calculation data error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to check calculation data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Revoke shared calculation link
     */
    public function revokeShare(Request $request, Calculation $calculation)
    {
        $this->authorize('update', $calculation);

        try {
            $calculation->shares()->delete();

            return redirect()->back()
                ->with('success', 'Shared links revoked successfully!');

        } catch (\Exception $e) {
            Log::error('Revoke share error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to revoke shared links: ' . $e->getMessage());
        }
    }

    /**
     * Generate family tree for calculation
     */
    public function generateTree(Request $request, Calculation $calculation)
    {
        $this->authorize('update', $calculation);

        try {
            $calculation->tree_generation_status = 'processing';
            $calculation->save();

            // TODO: Implement tree generation logic
            // This would call an external service or generate locally

            return response()->json([
                'success' => true,
                'message' => 'Tree generation started',
                'status' => 'processing'
            ]);

        } catch (\Exception $e) {
            Log::error('Generate tree error: ' . $e->getMessage());
            
            $calculation->tree_generation_status = 'failed';
            $calculation->tree_generation_error = $e->getMessage();
            $calculation->save();

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate tree: ' . $e->getMessage(),
                'status' => 'failed'
            ], 500);
        }
    }

    /**
     * Download family tree
     */
    public function downloadTree(Calculation $calculation, $format = 'png')
    {
        $this->authorize('view', $calculation);

        try {
            if (!$calculation->family_tree_image) {
                return redirect()->back()
                    ->with('error', 'Family tree not generated yet.');
            }

            $path = Storage::disk('public')->path($calculation->family_tree_image);
            
            return response()->download($path, 'family-tree-' . Str::slug($calculation->deceased_name) . '.' . $format);

        } catch (\Exception $e) {
            Log::error('Download tree error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to download family tree: ' . $e->getMessage());
        }
    }

    /**
     * Generate Graphviz tree
     */
    public function generateGraphvizTree(Request $request, Calculation $calculation)
    {
        $this->authorize('update', $calculation);

        try {
            // Generate Graphviz DOT format
            $graphviz = $this->generateGraphvizData($calculation);
            
            // Save graphviz data
            $calculation->graphviz_data = $graphviz;
            $calculation->save();

            return response()->json([
                'success' => true,
                'message' => 'Graphviz tree generated successfully',
                'graphviz' => $graphviz
            ]);

        } catch (\Exception $e) {
            Log::error('Generate Graphviz tree error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate Graphviz tree: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download Graphviz tree
     */
    public function downloadGraphvizTree(Calculation $calculation, $format = 'dot')
    {
        $this->authorize('view', $calculation);

        try {
            if (!$calculation->graphviz_data) {
                return redirect()->back()
                    ->with('error', 'Graphviz tree not generated yet.');
            }

            $filename = 'family-tree-' . Str::slug($calculation->deceased_name) . '.' . $format;
            $headers = [
                'Content-Type' => 'text/plain',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ];

            return response($calculation->graphviz_data, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Download Graphviz tree error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to download Graphviz tree: ' . $e->getMessage());
        }
    }

    /**
     * Shared calculation print view
     */
    public function sharedPrint($token)
    {
        try {
            // Find the share record
            $share = \App\Models\CalculationShare::where('token', $token)
                ->where('expires_at', '>', now())
                ->firstOrFail();

            $calculation = $share->calculation;

            // Check if share is still valid
            if (!$calculation || $share->expires_at < now()) {
                return redirect()->route('calculator.index')
                    ->with('error', 'This shared link has expired or is invalid.');
            }

            // Ensure distribution_summary exists
            if (empty($calculation->distribution_summary)) {
                $heirsData = $calculation->heirs_data ?? $this->extractHeirsDataFromCalculation($calculation);
                
                $calculator = new \App\Services\FaraidCalculator(null, $heirsData, $calculation->net_assets);
                $calculationResult = $calculator->calculate();
                
                $distribution = $calculationResult['distribution'] ?? [];
                $totalDistributed = $calculationResult['total_distributed'] ?? 0;
                $eligibleHeirsCount = $calculationResult['total_eligible'] ?? 0;
                
                $distributionSummary = [
                    'heirs' => $distribution,
                    'total_distributed' => $totalDistributed,
                    'total_eligible' => $eligibleHeirsCount,
                    'net_estate' => $calculation->net_assets,
                    'total_heirs' => count($distribution)
                ];
                
                $calculationData = $this->prepareCalculationData($distributionSummary, $calculation->net_assets);
                
                $calculation->update([
                    'distribution_summary' => $distributionSummary,
                    'calculation_data' => $calculationData
                ]);
                
                $calculation->refresh();
            }

            $pdf = Pdf::loadView('calculator.shared-print', compact('calculation', 'share'));
            
            $filename = 'inheritance-calculation-' . Str::slug($calculation->deceased_name) . '-' . date('Y-m-d') . '.pdf';
            
            return $pdf->download($filename);

        } catch (\Exception $e) {
            Log::error('Shared print error: ' . $e->getMessage());
            
            return redirect()->route('calculator.index')
                ->with('error', 'Unable to generate print version.');
        }
    }

    // ============================================
    // PRIVATE & PROTECTED HELPER METHODS
    // ============================================

    /**
     * Extract heirs data from request
     */
    private function extractHeirsDataFromRequest(Request $request)
    {
        return [
            'husband_count' => (int) ($request->husband_count ?? 0),
            'wife_count' => (int) ($request->wife_count ?? 0),
            'father_status' => $request->father_status ?? 'deceased',
            'mother_status' => $request->mother_status ?? 'deceased',
            'son_count' => (int) ($request->son_count ?? 0),
            'daughter_count' => (int) ($request->daughter_count ?? 0),
            'full_brother_count' => (int) ($request->full_brother_count ?? 0),
            'full_sister_count' => (int) ($request->full_sister_count ?? 0),
            'paternal_half_brother_count' => (int) ($request->paternal_half_brother_count ?? 0),
            'paternal_half_sister_count' => (int) ($request->paternal_half_sister_count ?? 0),
            'maternal_half_brother_count' => (int) ($request->maternal_half_brother_count ?? 0),
            'maternal_half_sister_count' => (int) ($request->maternal_half_sister_count ?? 0),
            'fathers_father_status' => $request->fathers_father_status ?? 'deceased',
            'fathers_mother_status' => $request->fathers_mother_status ?? 'deceased',
            'mothers_mother_status' => $request->mothers_mother_status ?? 'deceased',
        ];
    }

    /**
     * Extract heirs data from calculation object
     */
    private function extractHeirsDataFromCalculation(Calculation $calculation)
    {
        return [
            'husband_count' => $calculation->husband_count ?? 0,
            'wife_count' => $calculation->wife_count ?? 0,
            'father_status' => $calculation->father_status ?? 'deceased',
            'mother_status' => $calculation->mother_status ?? 'deceased',
            'son_count' => $calculation->son_count ?? 0,
            'daughter_count' => $calculation->daughter_count ?? 0,
            'full_brother_count' => $calculation->full_brother_count ?? 0,
            'full_sister_count' => $calculation->full_sister_count ?? 0,
            'paternal_half_brother_count' => $calculation->paternal_brother_count ?? 0,
            'paternal_half_sister_count' => $calculation->paternal_sister_count ?? 0,
            'maternal_half_brother_count' => $calculation->maternal_brother_count ?? 0,
            'maternal_half_sister_count' => $calculation->maternal_sister_count ?? 0,
            'fathers_father_status' => $calculation->fathers_father_status ?? 'deceased',
            'fathers_mother_status' => $calculation->fathers_mother_status ?? 'deceased',
            'mothers_mother_status' => $calculation->mothers_mother_status ?? 'deceased',
        ];
    }

    /**
     * Calculate total heirs count
     */
    private function calculateTotalHeirs(array $heirsData)
    {
        $total = 0;
        
        $total += $heirsData['husband_count'] ?? 0;
        $total += $heirsData['wife_count'] ?? 0;
        
        if (($heirsData['father_status'] ?? 'deceased') === 'alive') $total++;
        if (($heirsData['mother_status'] ?? 'deceased') === 'alive') $total++;
        
        $total += $heirsData['son_count'] ?? 0;
        $total += $heirsData['daughter_count'] ?? 0;
        $total += $heirsData['full_brother_count'] ?? 0;
        $total += $heirsData['full_sister_count'] ?? 0;
        $total += $heirsData['paternal_half_brother_count'] ?? 0;
        $total += $heirsData['paternal_half_sister_count'] ?? 0;
        $total += $heirsData['maternal_half_brother_count'] ?? 0;
        $total += $heirsData['maternal_half_sister_count'] ?? 0;
        
        if (($heirsData['fathers_father_status'] ?? 'deceased') === 'alive') $total++;
        if (($heirsData['fathers_mother_status'] ?? 'deceased') === 'alive') $total++;
        if (($heirsData['mothers_mother_status'] ?? 'deceased') === 'alive') $total++;
        
        return $total;
    }

    /**
     * Extract heir amounts from distribution
     */
    protected function extractHeirAmountsFromDistribution(array $distribution): array
    {
        $heirAmounts = [];
        foreach ($distribution as $heir) {
            if (($heir['amount'] ?? 0) > 0) {
                $heirAmounts[$heir['heir']] = $heir['amount'];
            }
        }
        return $heirAmounts;
    }

    /**
     * Prepare chart data from distribution
     */
    protected function prepareChartDataFromDistribution(array $distributionSummary): array
    {
        $heirs = $distributionSummary['heirs'] ?? [];
        
        $eligibleHeirs = array_filter($heirs, function($heir) {
            $amount = $heir['amount'] ?? 0;
            $status = $heir['status'] ?? '';
            return $amount > 0.01 && $status !== 'Surplus';
        });
        
        if (empty($eligibleHeirs)) {
            return [];
        }
        
        $totalAmount = array_sum(array_column($eligibleHeirs, 'amount'));
        $colors = ['#7e1ab4', '#2d7ad6', '#25D366', '#ffd700', '#dc3545', '#2eaec2', '#1a5fb4', '#ff6b6b', '#51cf66', '#9d4edd'];
        
        $chartData = [];
        $colorIndex = 0;
        
        foreach ($eligibleHeirs as $heir) {
            $amount = $heir['amount'] ?? 0;
            $percentage = $totalAmount > 0 ? ($amount / $totalAmount) * 100 : 0;
            
            $chartData[] = [
                'heir' => $heir['heir'] ?? $heir['name'] ?? 'Unknown',
                'amount' => $amount,
                'percentage' => round($percentage, 2),
                'share' => $heir['share'] ?? '0',
                'color' => $colors[$colorIndex % count($colors)]
            ];
            $colorIndex++;
        }
        
        return $chartData;
    }

    /**
     * Generate tree data
     */
    private function generateTreeData(array $heirsData, array $distributionSummary, array $deceasedInfo)
    {
        $heirAmounts = [];
        
        if (isset($distributionSummary['heirs']) && is_array($distributionSummary['heirs'])) {
            foreach ($distributionSummary['heirs'] as $heir) {
                if (($heir['amount'] ?? 0) > 0) {
                    $heirAmounts[$heir['heir'] ?? $heir['name']] = $heir['amount'];
                }
            }
        }

        return [
            'deceased' => [
                'name' => $deceasedInfo['deceased_name'] ?? 'Unknown',
                'gender' => $deceasedInfo['deceased_gender'] ?? 'male',
                'net_estate' => $deceasedInfo['net_assets'] ?? 0
            ],
            'heirs' => $heirsData,
            'heirAmounts' => $heirAmounts,
            'distribution' => $distributionSummary,
            'timestamp' => now()->toISOString(),
            'generated_by' => 'Faraid Calculator'
        ];
    }

    /**
     * Detect scenario
     */
    private function detectScenario(array $heirsData)
    {
        $hasSpouse = ($heirsData['husband_count'] ?? 0) > 0 || ($heirsData['wife_count'] ?? 0) > 0;
        $hasChildren = ($heirsData['son_count'] ?? 0) > 0 || ($heirsData['daughter_count'] ?? 0) > 0;
        $hasParents = (($heirsData['father_status'] ?? 'deceased') === 'alive') || 
                     (($heirsData['mother_status'] ?? 'deceased') === 'alive');

        if ($hasSpouse && !$hasChildren && !$hasParents) {
            return 1; // Spouse only
        } elseif ($hasSpouse && $hasChildren && !$hasParents) {
            return 2; // Spouse and children
        } elseif ($hasSpouse && $hasChildren && $hasParents) {
            return 3; // Spouse, children, and parents
        } elseif ($hasChildren && !$hasSpouse && !$hasParents) {
            return 4; // Children only
        } elseif ($hasChildren && !$hasSpouse && $hasParents) {
            return 5; // Children and parents
        } elseif ($hasParents && !$hasSpouse && !$hasChildren) {
            return 6; // Parents only
        } elseif ($hasSpouse && !$hasChildren && $hasParents) {
            return 7; // Spouse and parents
        } else {
            return 15; // Standard/default
        }
    }

    /**
     * Get scenario description
     */
    private function getScenarioDescription($scenario)
    {
        $descriptions = [
            1 => "Spouse Only Inheritance",
            2 => "Spouse and Children Inheritance",
            3 => "Spouse, Children, and Parents Inheritance",
            4 => "Children Only Inheritance",
            5 => "Children and Parents Inheritance",
            6 => "Parents Only Inheritance",
            7 => "Spouse and Parents Inheritance",
            8 => "Siblings Only Inheritance",
            9 => "Maternal and Half Siblings Inheritance",
            10 => "Blocking (Mahjub) Scenario",
            11 => "Extended Heirs (Asabah) Scenario",
            12 => "Awl (Over-Subscription) Scenario",
            13 => "Surplus Estate Distribution",
            14 => "Multiple Wives Scenario",
            15 => "Standard Inheritance Distribution"
        ];
        
        return $descriptions[$scenario] ?? "Standard Inheritance Distribution";
    }

    /**
     * Calculate eligible heirs count
     */
    private function calculateEligibleHeirsCount(array $distributionSummary)
    {
        if (!isset($distributionSummary['heirs']) || !is_array($distributionSummary['heirs'])) {
            return 0;
        }

        $eligibleHeirs = array_filter($distributionSummary['heirs'], function($heir) {
            $amount = $heir['amount'] ?? 0;
            $type = $heir['type'] ?? '';
            
            // Count as eligible if amount > 0 and not Surplus
            return $amount > 0.01 && $type !== 'Surplus';
        });

        return count($eligibleHeirs);
    }

    /**
     * Prepare calculation data for storage
     */
    private function prepareCalculationData(array $distributionSummary, float $netEstate)
    {
        $calculationData = [];
        
        if (isset($distributionSummary['heirs']) && is_array($distributionSummary['heirs'])) {
            foreach ($distributionSummary['heirs'] as $heir) {
                $amount = $heir['amount'] ?? 0;
                if ($amount > 0) {
                    $calculationData[] = [
                        'heir' => $heir['heir'] ?? $heir['name'] ?? 'Unknown',
                        'relationship' => $heir['relationship'] ?? 'Unknown',
                        'share' => $heir['share'] ?? '0',
                        'amount' => $amount,
                        'fraction' => $netEstate > 0 ? $amount / $netEstate : 0,
                        'status' => $heir['status'] ?? 'Eligible',
                        'type' => $heir['type'] ?? 'Unknown',
                        'formatted_amount' => 'RM ' . number_format($amount, 2),
                        'percentage' => $heir['percentage'] ?? ($netEstate > 0 ? ($amount / $netEstate) * 100 : 0)
                    ];
                }
            }
        }
        
        return $calculationData;
    }

    /**
     * Calculate user statistics
     */
    private function calculateUserStatistics($userId)
    {
        $calculations = Calculation::where('user_id', $userId)->get();
        
        return [
            'total_calculations' => $calculations->count(),
            'total_assets' => $calculations->sum('total_assets') ?? 0,
            'total_heirs' => $calculations->sum('total_heirs') ?? 0,
            'average_assets' => $calculations->avg('total_assets') ?? 0,
            'latest_calculation' => $calculations->first(),
            'database_scenarios_used' => $calculations->where('calculation_method', 'database')->count(),
            'local_calculations' => $calculations->where('calculation_method', 'local')->count(),
        ];
    }

    /**
     * Get default statistics
     */
    private function getDefaultStatistics()
    {
        return [
            'total_calculations' => 0,
            'total_assets' => 0,
            'total_heirs' => 0,
            'average_assets' => 0,
            'latest_calculation' => null,
            'database_scenarios_used' => 0,
            'local_calculations' => 0,
        ];
    }

    /**
     * Generate Graphviz data for calculation
     */
    private function generateGraphvizData(Calculation $calculation)
    {
        $deceasedName = $calculation->deceased_name;
        $deceasedGender = $calculation->deceased_gender;
        $deceasedColor = $deceasedGender === 'male' ? 'lightblue' : 'lightpink';
        
        $graphviz = "digraph FamilyTree {\n";
        $graphviz .= "  node [style=filled, fontname=\"Arial\"];\n";
        $graphviz .= "  rankdir=TB;\n\n";
        
        // Add deceased node
        $graphviz .= "  // Deceased person\n";
        $graphviz .= "  deceased [label=\"{$deceasedName}\\n(Deceased)\", fillcolor=\"{$deceasedColor}\", shape=box];\n\n";
        
        // Add heirs
        $graphviz .= "  // Heirs\n";
        $distribution = $calculation->distribution_summary['heirs'] ?? [];
        $heirIndex = 1;
        
        foreach ($distribution as $heir) {
            if (($heir['amount'] ?? 0) > 0) {
                $heirName = $heir['name'] ?? $heir['heir'] ?? 'Unknown';
                $relationship = $heir['relationship'] ?? 'Unknown';
                $amount = number_format($heir['amount'] ?? 0, 2);
                $percentage = number_format($heir['percentage'] ?? 0, 2);
                $share = $heir['share'] ?? '0';
                
                $heirColor = $this->getHeirColor($relationship);
                $graphviz .= "  heir{$heirIndex} [label=\"{$heirName}\\n{$relationship}\\nRM {$amount} ({$percentage}%)\\nShare: {$share}\", fillcolor=\"{$heirColor}\"];\n";
                $graphviz .= "  deceased -> heir{$heirIndex} [label=\"inherits\"];\n\n";
                $heirIndex++;
            }
        }
        
        $graphviz .= "}\n";
        
        return $graphviz;
    }

    /**
     * Get color for heir based on relationship
     */
    private function getHeirColor($relationship)
    {
        $colors = [
            'Husband' => '#FFD700', // Gold
            'Wife' => '#FF69B4',    // Hot pink
            'Father' => '#87CEEB',   // Sky blue
            'Mother' => '#FFB6C1',   // Light pink
            'Son' => '#90EE90',      // Light green
            'Daughter' => '#FFA07A', // Light salmon
            'Full Brother' => '#DDA0DD', // Plum
            'Full Sister' => '#98FB98',  // Pale green
            'Paternal Half Brother' => '#DDA0DD',
            'Paternal Half Sister' => '#98FB98',
            'Maternal Half Brother' => '#E0B0FF',
            'Maternal Half Sister' => '#E0B0FF',
            'Baitulmal' => '#D3D3D3'     // Light gray
        ];
        
        return $colors[$relationship] ?? '#E6E6FA'; // Default: Lavender
    }

    /**
     * Calculate distribution summary - Public version for blade access
     */
    public function calculateDistributionSummaryPublic(array $heirsData, float $netEstate)
    {
        $calculator = new \App\Services\FaraidCalculator(null, $heirsData, $netEstate);
        $calculationResult = $calculator->calculate();
        
        return [
            'heirs' => $calculationResult['distribution'] ?? [],
            'total_distributed' => $calculationResult['total_distributed'] ?? 0,
            'total_eligible' => $calculationResult['total_eligible'] ?? 0,
            'net_estate' => $netEstate,
            'total_heirs' => count($calculationResult['distribution'] ?? []),
            'awl_applied' => $calculationResult['awl_applied'] ?? false
        ];
    }

    /**
     * Save pending calculation
     */
    public function savePending(Request $request)
    {
        try {
            $pendingData = session('pending_calculation');
            
            if (!$pendingData) {
                return response()->json([
                    'success' => false,
                    'message' => 'No pending calculation found.'
                ], 400);
            }

            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please login to save calculations.',
                    'login_url' => route('login')
                ], 401);
            }

            // Create calculation record
            $calculation = Calculation::create([
                'user_id' => Auth::id(),
                'deceased_name' => $pendingData['deceased_name'],
                'deceased_gender' => $pendingData['deceased_gender'],
                'date_of_death' => $pendingData['date_of_death'],
                'marital_status' => $pendingData['marital_status'],
                'total_assets' => $pendingData['total_assets'],
                'net_assets' => $pendingData['net_assets'],
                'heirs_data' => $pendingData['heirs_data'],
                'distribution_summary' => $pendingData['distribution_summary'],
                'calculation_data' => $pendingData['calculation_data'],
                'eligible_heirs_count' => $pendingData['eligible_heirs_count'],
                'total_heirs' => $pendingData['total_heirs'],
                'scenario_number' => $pendingData['scenario_number'],
                'scenario_description' => $pendingData['scenario_description'],
                'calculation_method' => $pendingData['calculation_method'],
                'chart_data' => $pendingData['chart_data'],
                'tree_data' => $pendingData['tree_data'],
                'calculation_hash' => Calculation::generateHash()
            ]);

            // Clear pending calculation from session
            session()->forget('pending_calculation');

            return response()->json([
                'success' => true,
                'message' => 'Calculation saved successfully!',
                'calculation_id' => $calculation->id,
                'redirect_url' => route('calculator.show', $calculation)
            ]);

        } catch (\Exception $e) {
            Log::error('Save pending calculation error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save calculation: ' . $e->getMessage()
            ], 500);
        }
    }
}