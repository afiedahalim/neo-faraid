<?php

namespace App\Http\Controllers;

use App\Models\BeneficiaryAccessLink;
use App\Models\EstatePreRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BeneficiaryAccessController extends Controller
{
    /**
     * Constructor - no auth middleware for public access via token
     */
    public function __construct()
    {
        // No auth middleware - public access via token
        // This allows beneficiaries to view their inheritance without logging in
    }

    // =========================================================================
    // MAIN ACCESS METHODS
    // =========================================================================

    /**
     * Show the estate distribution view for a beneficiary.
     */
    public function show(Request $request, string $token)
    {
        $link = BeneficiaryAccessLink::where('access_token', $token)
            ->where('status', 'active')
            ->first();

        if (!$link) {
            Log::warning('Beneficiary access: Invalid token', [
                'token_prefix' => substr($token, 0, 10) . '...',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
            abort(404, 'Invalid or expired access link.');
        }

        // Check if link has expired
        if ($link->isExpired()) {
            Log::warning('Beneficiary access: Expired link', [
                'link_id' => $link->id,
                'expires_at' => $link->expires_at,
                'ip' => $request->ip(),
            ]);
            abort(410, 'This access link has expired. Please contact the estate administrator.');
        }

        // Get the estate record
        $estate = $link->estate;
        
        if (!$estate) {
            Log::error('Beneficiary access: Estate not found', [
                'link_id' => $link->id,
                'estate_id' => $link->estate_pre_registration_id,
            ]);
            abort(404, 'Estate information not found.');
        }
        
        // Record access attempt
        $link->recordAccess($request->ip(), $request->userAgent());

        // Check debt settlement status
        $debtSettlementStatus = $this->checkDebtSettlementStatus($estate);
        
        // If debts are not fully settled, show pending page
        if (!$debtSettlementStatus['all_settled']) {
            Log::info('Beneficiary access - debts not settled', [
                'estate_id' => $estate->id,
                'beneficiary_email' => $link->beneficiary_email,
                'beneficiary_type' => $link->beneficiary_type,
                'remaining_debt' => $debtSettlementStatus['formatted_remaining'],
                'unsettled_count' => $debtSettlementStatus['unsettled_count'],
            ]);
            
            return view('beneficiary.debt-pending', [
                'estate' => $estate,
                'link' => $link,
                'debtSummary' => $debtSettlementStatus,
                'unsettledDebts' => $debtSettlementStatus['unsettled_debts'],
                'distributionDetails' => $this->getBeneficiaryDistribution($link, $estate),
            ]);
        }

        // All debts are settled - show full distribution
        Log::info('Beneficiary access granted - all debts settled', [
            'estate_id' => $estate->id,
            'beneficiary_email' => $link->beneficiary_email,
            'beneficiary_type' => $link->beneficiary_type,
        ]);

        // Load related data for the view
        $estate->load(['heirs', 'assets', 'debts', 'wasiyyah']);
        
        // Calculate financial summaries
        $totalAssets = $estate->assets->sum('value');
        $totalDebts = $estate->debts->sum('amount');
        $netEstate = max(0, $totalAssets - $totalDebts);
        
        $totalWasiyyahPct = $estate->wasiyyah->sum('requested_percentage');
        $maxWasiyyahPct = 33.33;
        $effectiveWasiyyahPct = min($totalWasiyyahPct, $maxWasiyyahPct);
        $wasiyyahAmount = ($effectiveWasiyyahPct / 100) * $netEstate;
        $remainingForHeirs = max(0, $netEstate - $wasiyyahAmount);
        
        $totalHeirPct = $estate->heirs->sum('share_percentage');
        
        // Prepare distribution data
        $distribution = $this->getBeneficiaryDistribution($link, $estate);
        
        // Prepare heirs list for display
        $heirsList = [];
        foreach ($estate->heirs as $heir) {
            $amount = ((float) $heir->share_percentage / 100) * $remainingForHeirs;
            $heirsList[] = [
                'id' => $heir->id,
                'name' => $heir->name,
                'relationship' => $heir->relationship_label ?? $heir->relationship,
                'percentage' => (float) $heir->share_percentage,
                'amount' => round($amount, 2),
                'formatted_amount' => 'RM ' . number_format($amount, 2),
            ];
        }
        
        // Prepare wasiyyah list for display
        $wasiyyahList = [];
        foreach ($estate->wasiyyah as $wasiyyah) {
            $amount = ((float) $wasiyyah->requested_percentage / 100) * $netEstate;
            $wasiyyahList[] = [
                'id' => $wasiyyah->id,
                'name' => $wasiyyah->beneficiary_name,
                'relationship' => $wasiyyah->relationship,
                'percentage' => (float) $wasiyyah->requested_percentage,
                'amount' => round($amount, 2),
                'formatted_amount' => 'RM ' . number_format($amount, 2),
                'description' => $wasiyyah->description,
            ];
        }
        
        // Prepare assets list
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
        
        // Prepare debts list
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
                'remaining' => $remaining,
                'formatted_remaining' => 'RM ' . number_format($remaining, 2),
                'status' => $remaining <= 0 ? 'settled' : ($debt->status ?? 'pending'),
                'description' => $debt->description ?? '',
                'due_date' => $debt->due_date?->format('d M Y'),
            ];
        }

        return view('beneficiary.access-view', [
            // Link and estate data
            'link' => $link,
            'estate' => $estate,
            
            // Beneficiary information
            'beneficiaryName' => $link->beneficiary_name,
            'beneficiaryType' => $link->beneficiary_type,
            'beneficiaryShare' => $distribution,
            
            // Deceased information
            'deceasedName' => $estate->deceased_name,
            'deceasedNric' => $this->maskNric($estate->deceased_nric),
            'deceasedEmail' => $estate->contact_email,
            'deceasedPhone' => $estate->contact_phone,
            'deceasedAddress' => $estate->address,
            'deceasedDob' => $estate->date_of_birth?->format('d F Y'),
            'deceasedGender' => $estate->gender === 'male' ? 'Male' : 'Female',
            
            // Trustee information
            'trusteeName' => $estate->trustee_name,
            'trusteeEmail' => $estate->trustee_email,
            'trusteePhone' => $estate->trustee_phone,
            'trusteeRelationship' => $estate->trustee_relationship,
            
            // Financial summaries
            'totalAssets' => $totalAssets,
            'formattedTotalAssets' => 'RM ' . number_format($totalAssets, 2),
            'totalDebts' => $totalDebts,
            'formattedTotalDebts' => 'RM ' . number_format($totalDebts, 2),
            'netEstate' => $netEstate,
            'formattedNetEstate' => 'RM ' . number_format($netEstate, 2),
            
            // Wasiyyah calculations
            'totalWasiyyahPct' => $totalWasiyyahPct,
            'maxWasiyyahPct' => $maxWasiyyahPct,
            'effectiveWasiyyahPct' => $effectiveWasiyyahPct,
            'wasiyyahAmount' => $wasiyyahAmount,
            'formattedWasiyyahAmount' => 'RM ' . number_format($wasiyyahAmount, 2),
            'remainingForHeirs' => $remainingForHeirs,
            'formattedRemainingForHeirs' => 'RM ' . number_format($remainingForHeirs, 2),
            'totalHeirPct' => $totalHeirPct,
            
            // Lists for display
            'distribution' => $distribution,
            'debtSummary' => $debtSettlementStatus,
            'heirsList' => $heirsList,
            'wasiyyahList' => $wasiyyahList,
            'assetsList' => $assetsList,
            'debtsList' => $debtsList,
            
            // Raw collections for flexibility
            'heirs' => $estate->heirs,
            'assets' => $estate->assets,
            'debts' => $estate->debts,
            'wasiyyah' => $estate->wasiyyah,
            
            // Beneficiary's specific share
            'sharePercentage' => $distribution['share_percentage'] ?? ($distribution['requested_percentage'] ?? 0),
            'formattedAmount' => $distribution['formatted_amount'] ?? ($distribution['formatted_amount'] ?? 'RM 0.00'),
            'relationship' => $distribution['relationship'] ?? null,
            
            // Additional data
            'expiryDate' => $link->expires_at->format('d F Y, h:i A'),
            'accessUrl' => route('beneficiary.access', ['token' => $link->access_token]),
            'hasVideo' => !empty($estate->will_video_url),
            'willVideoUrl' => $estate->will_video_url,
            'willTextContent' => $estate->will_text_content,
            'isShariahCompliant' => $estate->is_shariah_compliant ?? true,
            'generatedAt' => now()->format('d F Y, h:i A'),
            'documentId' => 'EST-' . $estate->id . '-' . now()->format('Ymd'),
        ]);
    }

    // =========================================================================
    // HELPER METHODS
    // =========================================================================

    /**
     * Get comprehensive debt settlement status.
     */
    private function checkDebtSettlementStatus(EstatePreRegistration $estate): array
    {
        $totalDebts = $estate->debts()->sum('amount');
        $debtsPaid = $estate->debts()->sum('amount_paid');
        $remaining = max(0, $totalDebts - $debtsPaid);
        
        $unsettledDebts = $estate->debts()
            ->where(function ($query) {
                $query->where('status', '!=', 'settled')
                    ->orWhereRaw('amount > amount_paid');
            })
            ->get()
            ->map(function ($debt) {
                $remaining = (float) $debt->amount - (float) ($debt->amount_paid ?? 0);
                return [
                    'id' => $debt->id,
                    'creditor_name' => $debt->creditor_name,
                    'amount' => (float) $debt->amount,
                    'amount_paid' => (float) ($debt->amount_paid ?? 0),
                    'remaining' => $remaining,
                    'formatted_remaining' => 'RM ' . number_format($remaining, 2),
                    'status' => $debt->status,
                    'due_date' => $debt->due_date?->format('d M Y'),
                    'description' => $debt->description,
                ];
            })
            ->toArray();

        return [
            'all_settled' => $remaining <= 0,
            'total_debts' => $totalDebts,
            'debts_paid' => $debtsPaid,
            'remaining' => $remaining,
            'formatted_total' => 'RM ' . number_format($totalDebts, 2),
            'formatted_paid' => 'RM ' . number_format($debtsPaid, 2),
            'formatted_remaining' => 'RM ' . number_format($remaining, 2),
            'unsettled_debts' => $unsettledDebts,
            'unsettled_count' => count($unsettledDebts),
            'settlement_percentage' => $totalDebts > 0 ? round(($debtsPaid / $totalDebts) * 100, 2) : 100,
        ];
    }

    /**
     * Get the distribution details for the specific beneficiary.
     */
    protected function getBeneficiaryDistribution(BeneficiaryAccessLink $link, $estate): array
    {
        $totalAssets = $estate->assets()->sum('value');
        $totalDebts = $estate->debts()->sum('amount');
        $netEstate = max(0, $totalAssets - $totalDebts);
        
        $totalWasiyyahPct = $estate->wasiyyah()->sum('requested_percentage');
        $maxWasiyyahPct = 33.33;
        $effectiveWasiyyahPct = min($totalWasiyyahPct, $maxWasiyyahPct);
        $wasiyyahAmount = ($effectiveWasiyyahPct / 100) * $netEstate;
        $remainingForHeirs = max(0, $netEstate - $wasiyyahAmount);
        
        switch ($link->beneficiary_type) {
            case 'heir':
                $heir = $estate->heirs()->find($link->beneficiary_id);
                if ($heir) {
                    $amount = ((float) $heir->share_percentage / 100) * $remainingForHeirs;
                    return [
                        'type' => 'heir',
                        'name' => $heir->name,
                        'relationship' => $heir->relationship_label ?? $heir->relationship,
                        'share_percentage' => (float) $heir->share_percentage,
                        'amount' => round($amount, 2),
                        'formatted_amount' => 'RM ' . number_format($amount, 2),
                        'share_fraction' => $this->percentageToFraction($heir->share_percentage),
                    ];
                }
                break;

            case 'trustee':
            case 'alternate_trustee':
                return [
                    'type' => $link->beneficiary_type,
                    'name' => $link->beneficiary_name,
                    'role' => $link->beneficiary_type === 'trustee' ? 'Primary Trustee' : 'Alternate Trustee',
                    'responsibilities' => [
                        'Oversee and manage estate distribution',
                        'Ensure all debts are properly settled',
                        'Distribute inheritance to heirs according to Faraid principles',
                        'Distribute Wasiyyah to nominated beneficiaries',
                        'Maintain compliance with Shariah law',
                        'Submit final distribution report',
                    ],
                ];

            case 'wasiyyah':
                $wasiyyah = $estate->wasiyyah()->find($link->beneficiary_id);
                if ($wasiyyah) {
                    // Calculate effective amount based on total wasiyyah percentage
                    $effectiveAmount = $totalWasiyyahPct > 0
                        ? ((float) $wasiyyah->requested_percentage / $totalWasiyyahPct) * $wasiyyahAmount
                        : 0;
                    
                    return [
                        'type' => 'wasiyyah',
                        'name' => $wasiyyah->beneficiary_name,
                        'relationship' => $wasiyyah->relationship,
                        'requested_percentage' => (float) $wasiyyah->requested_percentage,
                        'effective_percentage' => round(($effectiveAmount / max($netEstate, 1)) * 100, 2),
                        'amount' => round($effectiveAmount, 2),
                        'formatted_amount' => 'RM ' . number_format($effectiveAmount, 2),
                        'description' => $wasiyyah->description,
                        'is_charity' => $wasiyyah->is_charity ?? false,
                        'organization_name' => $wasiyyah->beneficiary_organization_name ?? null,
                    ];
                }
                break;
        }

        // Default fallback for viewer access
        return [
            'type' => 'viewer',
            'name' => $link->beneficiary_name,
            'message' => 'You have been granted access to view the estate distribution plan.',
            'can_view' => true,
        ];
    }

    // =========================================================================
    // STATIC HELPER METHODS
    // =========================================================================

    /**
     * Create an access link for a beneficiary (static method)
     */
    public static function createAccessLink($estate, string $email, string $type, string $name): ?BeneficiaryAccessLink
    {
        // If estate is passed as string (unique_id), find the record
        if (is_string($estate)) {
            $estate = EstatePreRegistration::where('unique_id', $estate)->first();
        }
        
        if (!$estate) {
            Log::error('createAccessLink: Estate not found', [
                'estate_input' => $estate,
            ]);
            return null;
        }
        
        return BeneficiaryAccessLink::create([
            'estate_pre_registration_id' => $estate->id,
            'estate_id' => $estate->id,
            'beneficiary_type' => $type,
            'beneficiary_name' => $name,
            'beneficiary_email' => $email,
            'access_token' => BeneficiaryAccessLink::generateToken(),
            'expires_at' => now()->addDays(30),
            'status' => 'active',
            'is_active' => true,
            'access_count' => 0,
            'notification_count' => 0,
        ]);
    }

    /**
     * Create an access link for a beneficiary (instance method)
     */
    public function generateAccessLink($estate, string $email, string $type, string $name): ?BeneficiaryAccessLink
    {
        return self::createAccessLink($estate, $email, $type, $name);
    }

    // =========================================================================
    // API METHODS
    // =========================================================================

    /**
     * Verify a token via AJAX.
     */
    public function verifyToken(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $link = BeneficiaryAccessLink::where('access_token', $request->token)
            ->where('status', 'active')
            ->first();

        if (!$link) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid access token.',
            ], 404);
        }

        if ($link->isExpired()) {
            return response()->json([
                'valid' => false,
                'message' => 'This access link has expired.',
                'expires_at' => $link->expires_at->format('Y-m-d H:i:s'),
            ], 410);
        }

        // Check if estate still exists
        $estate = $link->estate;
        if (!$estate) {
            return response()->json([
                'valid' => false,
                'message' => 'Estate record no longer exists.',
            ], 404);
        }

        return response()->json([
            'valid' => true,
            'beneficiary_name' => $link->beneficiary_name,
            'beneficiary_type' => $link->beneficiary_type,
            'beneficiary_type_label' => $this->getBeneficiaryTypeLabel($link->beneficiary_type),
            'estate_name' => $estate->deceased_name,
            'expires_at' => $link->expires_at->format('Y-m-d H:i:s'),
            'remaining_days' => $link->getRemainingDays(),
        ]);
    }

    /**
     * Get beneficiary type label
     */
    protected function getBeneficiaryTypeLabel(string $type): string
    {
        $labels = [
            'heir' => 'Heir',
            'wasiyyah' => 'Wasiyyah Beneficiary',
            'trustee' => 'Trustee',
            'alternate_trustee' => 'Alternate Trustee',
            'viewer' => 'Viewer',
        ];
        return $labels[$type] ?? ucfirst($type);
    }

    // =========================================================================
    // PRIVATE HELPER METHODS
    // =========================================================================

    /**
     * Mask NRIC for privacy
     */
    private function maskNric(?string $nric): string
    {
        if (empty($nric)) {
            return 'N/A';
        }
        
        $clean = preg_replace('/[^0-9]/', '', $nric);
        
        if (strlen($clean) >= 8) {
            return '******-' . substr($clean, -4, 2) . '-' . substr($clean, -2);
        }
        
        return '******';
    }

    /**
     * Convert percentage to simplified fraction
     */
    private function percentageToFraction(float $percentage): string
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

    // =========================================================================
    // ADDITIONAL UTILITY METHODS
    // =========================================================================

    /**
     * Regenerate token for an existing link
     */
    public function regenerateToken(Request $request, int $linkId)
    {
        $link = BeneficiaryAccessLink::find($linkId);
        
        if (!$link) {
            return response()->json([
                'success' => false,
                'message' => 'Access link not found.',
            ], 404);
        }
        
        $newToken = BeneficiaryAccessLink::generateToken();
        $link->update(['access_token' => $newToken]);
        
        Log::info('Beneficiary access token regenerated', [
            'link_id' => $linkId,
            'beneficiary_email' => $link->beneficiary_email,
            'ip' => $request->ip(),
        ]);
        
        return response()->json([
            'success' => true,
            'access_token' => $newToken,
            'access_url' => route('beneficiary.access', ['token' => $newToken]),
        ]);
    }

    /**
     * Get access link status (for admin monitoring)
     */
    public function getLinkStatus(int $linkId)
    {
        $link = BeneficiaryAccessLink::with('estate')->find($linkId);
        
        if (!$link) {
            return response()->json([
                'success' => false,
                'message' => 'Access link not found.',
            ], 404);
        }
        
        return response()->json([
            'success' => true,
            'link' => [
                'id' => $link->id,
                'beneficiary_name' => $link->beneficiary_name,
                'beneficiary_email' => $link->beneficiary_email,
                'beneficiary_type' => $link->beneficiary_type,
                'status' => $link->status,
                'is_active' => $link->is_active,
                'is_expired' => $link->isExpired(),
                'is_valid' => $link->isValid(),
                'access_count' => $link->access_count,
                'last_accessed_at' => $link->last_accessed_at?->toIso8601String(),
                'expires_at' => $link->expires_at?->toIso8601String(),
                'remaining_days' => $link->getRemainingDays(),
                'created_at' => $link->created_at?->toIso8601String(),
                'estate_name' => $link->estate?->deceased_name,
            ],
        ]);
    }
}