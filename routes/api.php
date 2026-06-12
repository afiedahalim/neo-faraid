<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Calculation;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Api\FaraidCalculationController;
use App\Models\FaraidCalculationRule;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// PUBLIC API ROUTES
Route::post('/calculate-faraid', [FaraidCalculationController::class, 'calculate']);

// NEW: Get all active Faraid scenarios/rules from database
Route::get('/faraid-scenarios', function () {
    try {
        $scenarios = FaraidCalculationRule::where('is_active', 1)
            ->orderBy('priority')
            ->get()
            ->map(function ($scenario) {
                return [
                    'id' => $scenario->id,
                    'scenario_number' => $scenario->scenario_number,
                    'scenario_name' => $scenario->scenario_name,
                    'scenario_description' => $scenario->scenario_description,
                    'priority' => $scenario->priority,
                    'conditions' => $scenario->conditions,
                    'distribution_rules' => $scenario->distribution_rules,
                    'calculation_logic' => $scenario->calculation_logic,
                    'created_at' => $scenario->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $scenario->updated_at->format('Y-m-d H:i:s')
                ];
            });
        
        return response()->json([
            'success' => true,
            'data' => $scenarios,
            'count' => $scenarios->count(),
            'timestamp' => now()->toDateTimeString()
        ]);
    } catch (\Exception $e) {
        \Log::error('Error fetching faraid scenarios: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'error' => 'Failed to fetch scenarios',
            'message' => $e->getMessage()
        ], 500);
    }
});

// NEW: Advanced calculation with scenario detection
Route::post('/calculate-advanced', [FaraidCalculationController::class, 'calculateAdvanced']);

Route::get('/health', function () {
    return response()->json([
        'status' => 'healthy',
        'timestamp' => now()->toDateTimeString(),
        'environment' => app()->environment(),
    ]);
});

// AUTHENTICATED ROUTES
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/calculations/{id}', function ($id) {
        try {
            $calculation = Calculation::with('user')->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'calculation' => $calculation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Calculation not found'
            ], 404);
        }
    });
    
    // NEW: Get calculation history for authenticated user
    Route::get('/my-calculations', function (Request $request) {
        try {
            $calculations = Calculation::where('user_id', $request->user()->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $calculations,
                'count' => $calculations->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch calculations'
            ], 500);
        }
    });
    
    // NEW: Save calculation with scenario data
    Route::post('/save-calculation', function (Request $request) {
        try {
            $request->validate([
                'deceased_name' => 'required|string|max:255',
                'deceased_gender' => 'required|in:male,female',
                'date_of_death' => 'required|date',
                'marital_status' => 'required|string',
                'heirs_data' => 'required|json',
                'assets_data' => 'required|json',
                'calculation_data' => 'required|json',
                'scenario_data' => 'nullable|json',
                'chart_data' => 'nullable|json',
                'tree_data' => 'nullable|json',
                'total_assets' => 'required|numeric|min:0'
            ]);
            
            $calculation = Calculation::create([
                'user_id' => $request->user()->id,
                'deceased_name' => $request->deceased_name,
                'deceased_gender' => $request->deceased_gender,
                'date_of_death' => $request->date_of_death,
                'marital_status' => $request->marital_status,
                'heirs_data' => $request->heirs_data,
                'assets_data' => $request->assets_data,
                'calculation_data' => $request->calculation_data,
                'scenario_data' => $request->scenario_data,
                'chart_data' => $request->chart_data,
                'tree_data' => $request->tree_data,
                'total_assets' => $request->total_assets,
                'calculation_date' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Calculation saved successfully',
                'calculation_id' => $calculation->id,
                'calculation' => $calculation
            ]);
        } catch (\Exception $e) {
            \Log::error('Save calculation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to save calculation',
                'message' => $e->getMessage()
            ], 500);
        }
    });
});

// Admin API Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'getUsers']);
    Route::get('/feedback', [AdminController::class, 'getFeedback']);
    Route::get('/faqs', [AdminController::class, 'getFAQs']);
    Route::get('/faqs/{id}', [AdminController::class, 'getFAQ']);
    Route::post('/faqs', [AdminController::class, 'createFAQ']);
    Route::put('/faqs/{id}', [AdminController::class, 'updateFAQ']);
    Route::delete('/faqs/{id}', [AdminController::class, 'deleteFAQ']);
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser']);
    Route::delete('/feedback/{id}', [AdminController::class, 'deleteFeedback']);
    Route::put('/feedback/{id}/status', [AdminController::class, 'updateFeedbackStatus']);
    
    // NEW: Admin management of Faraid scenarios
    Route::get('/faraid-scenarios', function () {
        try {
            $scenarios = FaraidCalculationRule::orderBy('priority')
                ->orderBy('scenario_number')
                ->get();
            
            return response()->json([
                'success' => true,
                'data' => $scenarios,
                'count' => $scenarios->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch scenarios'
            ], 500);
        }
    });
    
    Route::get('/faraid-scenarios/{id}', function ($id) {
        try {
            $scenario = FaraidCalculationRule::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $scenario
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Scenario not found'
            ], 404);
        }
    });
    
    Route::post('/faraid-scenarios', function (Request $request) {
        try {
            $request->validate([
                'scenario_number' => 'required|integer|unique:faraid_calculation_rules,scenario_number',
                'scenario_name' => 'required|string|max:100',
                'scenario_description' => 'required|string',
                'priority' => 'required|integer|min:1',
                'conditions' => 'required|json',
                'distribution_rules' => 'required|json',
                'calculation_logic' => 'nullable|string',
                'is_active' => 'boolean'
            ]);
            
            $scenario = FaraidCalculationRule::create([
                'scenario_number' => $request->scenario_number,
                'scenario_name' => $request->scenario_name,
                'scenario_description' => $request->scenario_description,
                'priority' => $request->priority,
                'conditions' => $request->conditions,
                'distribution_rules' => $request->distribution_rules,
                'calculation_logic' => $request->calculation_logic,
                'is_active' => $request->is_active ?? true
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Scenario created successfully',
                'data' => $scenario
            ]);
        } catch (\Exception $e) {
            \Log::error('Create scenario error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to create scenario'
            ], 500);
        }
    });
    
    Route::put('/faraid-scenarios/{id}', function (Request $request, $id) {
        try {
            $scenario = FaraidCalculationRule::findOrFail($id);
            
            $request->validate([
                'scenario_number' => 'sometimes|integer|unique:faraid_calculation_rules,scenario_number,' . $id,
                'scenario_name' => 'sometimes|string|max:100',
                'scenario_description' => 'sometimes|string',
                'priority' => 'sometimes|integer|min:1',
                'conditions' => 'sometimes|json',
                'distribution_rules' => 'sometimes|json',
                'calculation_logic' => 'nullable|string',
                'is_active' => 'sometimes|boolean'
            ]);
            
            $scenario->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Scenario updated successfully',
                'data' => $scenario
            ]);
        } catch (\Exception $e) {
            \Log::error('Update scenario error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to update scenario'
            ], 500);
        }
    });
    
    Route::delete('/faraid-scenarios/{id}', function ($id) {
        try {
            $scenario = FaraidCalculationRule::findOrFail($id);
            $scenario->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Scenario deleted successfully'
            ]);
        } catch (\Exception $e) {
            \Log::error('Delete scenario error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to delete scenario'
            ], 500);
        }
    });
});