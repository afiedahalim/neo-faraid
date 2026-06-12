<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EstatePreRegistration;
use App\Models\NotificationRequest;
use App\Models\InheritanceNotification;
use App\Models\BeneficiaryAccessLink;
use App\Models\PreRegisteredDebt;
use App\Models\PreRegisteredHeir;
use App\Models\PreRegisteredAsset;
use App\Models\PreRegisteredWasiyyah;
use App\Models\DigitalCredential;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class EstateSetupController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    // =========================================================================
    // MAIN ADMIN PAGES
    // =========================================================================

    /**
     * Display the estate setup admin index page.
     */
    public function index(Request $request)
    {
        $query = EstatePreRegistration::with(['user', 'heirs', 'assets', 'debts', 'wasiyyah']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('deceased_name', 'LIKE', "%{$search}%")
                  ->orWhere('deceased_nric', 'LIKE', "%{$search}%")
                  ->orWhere('unique_id', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('approved')) {
            $query->where('admin_approved', $request->approved == '1');
        }

        $estates = $query->orderBy('created_at', 'desc')->paginate(20);

        $debtPendingCount = EstatePreRegistration::whereHas('debts', function ($q) {
            $q->where('status', '!=', 'settled')
              ->orWhereRaw('amount > amount_paid');
        })->count();

        $stats = [
            'total' => EstatePreRegistration::count(),
            'draft' => EstatePreRegistration::where('status', 'draft')->count(),
            'completed' => EstatePreRegistration::where('status', 'completed')->count(),
            'activated' => EstatePreRegistration::where('status', 'activated')->count(),
            'executed' => EstatePreRegistration::where('status', 'executed')->count(),
            'approved' => EstatePreRegistration::where('admin_approved', true)->count(),
            'pending_approval' => EstatePreRegistration::where('admin_approved', false)
                ->whereIn('status', ['completed', 'activated'])
                ->count(),
            'debt_settlement_pending' => $debtPendingCount,
        ];

        return view('admin.estate-setup.index', compact('estates', 'stats'));
    }

    /**
     * Display the specified estate with full details.
     */
    public function show(string $uniqueId)
    {
        $estate = EstatePreRegistration::with([
            'user', 
            'heirs', 
            'assets', 
            'debts', 
            'wasiyyah',
            'digitalCredentials',
            'beneficiaryAccessLinks'
        ])->where('unique_id', $uniqueId)->firstOrFail();

        $debtSettlementStatus = $this->checkDebtSettlementStatus($estate);
        $readiness = $this->checkEstateReadiness($estate);
        $faraidCalculation = $estate->calculateDistribution();
        
        $accessLinks = $estate->beneficiaryAccessLinks()
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->get();

        return view('admin.estate-setup.show', compact(
            'estate',
            'debtSettlementStatus',
            'readiness',
            'faraidCalculation',
            'accessLinks'
        ));
    }

    /**
     * Display statistics dashboard.
     */
    public function statistics()
    {
        $stats = [
            'total' => EstatePreRegistration::count(),
            'draft' => EstatePreRegistration::where('status', 'draft')->count(),
            'completed' => EstatePreRegistration::where('status', 'completed')->count(),
            'activated' => EstatePreRegistration::where('status', 'activated')->count(),
            'executed' => EstatePreRegistration::where('status', 'executed')->count(),
            'approved' => EstatePreRegistration::where('admin_approved', true)->count(),
            'pending_approval' => EstatePreRegistration::where('admin_approved', false)
                ->whereIn('status', ['completed', 'activated'])
                ->count(),
            'data_locked' => EstatePreRegistration::where('data_locked', true)->count(),
            'pdf_generated' => EstatePreRegistration::whereNotNull('distribution_pdf_path')->count(),
            'pdf_released' => EstatePreRegistration::where('distribution_pdf_status', 'released')->count(),
            'video_processed' => EstatePreRegistration::where('will_video_processed_status', '!=', 'pending')->count(),
            'documents_released' => EstatePreRegistration::where('documents_released', true)->count(),
            'pending_notifications' => NotificationRequest::where('status', 'pending_admin_approval')->count(),
            'approved_notifications' => NotificationRequest::where('status', 'approved')->count(),
            'sent_notifications' => NotificationRequest::where('notification_sent', true)->count(),
            'total_notifications' => NotificationRequest::count(),
            'total_heirs' => PreRegisteredHeir::count(),
            'total_wasiyyah' => PreRegisteredWasiyyah::count(),
            'total_assets' => PreRegisteredAsset::count(),
            'total_debts' => PreRegisteredDebt::count(),
            'total_access_links' => BeneficiaryAccessLink::count(),
            'active_access_links' => BeneficiaryAccessLink::where('status', 'active')
                ->where('expires_at', '>', now())
                ->count(),
        ];

        $monthlyApprovals = EstatePreRegistration::selectRaw('DATE_FORMAT(admin_approved_at, "%Y-%m") as month, COUNT(*) as count')
            ->whereNotNull('admin_approved_at')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return view('admin.estate-setup.statistics', compact('stats', 'monthlyApprovals'));
    }

    /**
     * Export estates as CSV.
     */
    public function export(Request $request)
    {
        $query = EstatePreRegistration::with(['user', 'heirs', 'assets', 'debts', 'wasiyyah']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $estates = $query->get();

        $filename = 'estate_export_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function () use ($estates) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID', 'Unique ID', 'Deceased Name', 'NRIC', 'Gender', 'Status',
                'Heirs Count', 'Assets Count', 'Debts Count', 'Net Estate',
                'Debts Settled', 'Trustee Name', 'Trustee Email', 
                'Admin Approved', 'Admin Approved At', 'Created At'
            ]);

            foreach ($estates as $estate) {
                $debtsSettled = $this->areAllDebtsSettled($estate);
                
                fputcsv($file, [
                    $estate->id,
                    $estate->unique_id,
                    $estate->deceased_name,
                    $estate->deceased_nric,
                    $estate->gender,
                    $estate->status,
                    $estate->heirs->count(),
                    $estate->assets->count(),
                    $estate->debts->count(),
                    number_format($estate->net_estate, 2),
                    $debtsSettled ? 'Yes' : 'No',
                    $estate->trustee_name,
                    $estate->trustee_email,
                    $estate->admin_approved ? 'Yes' : 'No',
                    $estate->admin_approved_at?->format('Y-m-d H:i:s'),
                    $estate->created_at?->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // =========================================================================
    // ESTATE ADMIN ACTIONS WITH DEBT SETTLEMENT CHECK
    // =========================================================================

    /**
     * APPROVE ESTATE - Full approval with debt settlement verification
     * 
     * Sends emails directly via SMTP (no queue dependency)
     */
    public function approve(Request $request, string $uniqueId): RedirectResponse
    {
        try {
            $estate = EstatePreRegistration::where('unique_id', $uniqueId)->firstOrFail();

            // STEP 1: CHECK DEBT SETTLEMENT STATUS
            $debtSettlementStatus = $this->checkDebtSettlementStatus($estate);
            
            if (!$debtSettlementStatus['all_settled']) {
                $this->logAuditTrail($estate, 'approval_blocked_debts_unsettled', [
                    'remaining_amount' => $debtSettlementStatus['remaining'],
                    'unsettled_count' => $debtSettlementStatus['unsettled_count'],
                ]);
                
                return redirect()->back()
                    ->with('error', sprintf(
                        '❌ CANNOT APPROVE: Debts are not fully settled. ' .
                        'Total Debts: %s | Paid: %s | Remaining: %s | ' .
                        'Unsettled Debts: %d | ' .
                        'Please settle ALL debts before approving this estate.',
                        $debtSettlementStatus['formatted_total'],
                        $debtSettlementStatus['formatted_paid'],
                        $debtSettlementStatus['formatted_remaining'],
                        $debtSettlementStatus['unsettled_count']
                    ));
            }

            // STEP 2: ALL DEBTS SETTLED - APPROVE ESTATE
            DB::beginTransaction();

            $estate->update([
                'admin_approved' => true,
                'admin_approved_at' => now(),
                'admin_approved_by' => Auth::id(),
                'admin_notes' => $request->input('admin_notes'),
                'status' => 'activated',
                'activated_at' => now(),
                'data_locked' => true,
                'data_locked_at' => now(),
                'data_locked_by' => Auth::id(),
            ]);

            DB::commit();

            // STEP 3: GENERATE SECURE ACCESS LINKS
            $accessLinks = $this->generateSecureAccessLinks($estate);

            // STEP 4: SEND EMAILS DIRECTLY VIA SMTP (NO QUEUE)
            $emailResults = $this->sendBeneficiaryEmailsDirect($estate, $accessLinks);

            // STEP 5: LOG AUDIT TRAIL
            $this->logAuditTrail($estate, 'approved', [
                'debts_settled' => true,
                'access_links_generated' => $this->countAccessLinks($accessLinks),
                'emails_sent' => $emailResults['sent_count'],
                'emails_failed' => $emailResults['failed_count'],
            ]);

            $successMessage = sprintf(
                'Estate approved successfully! | Debts settled: Yes | ' .
                'Access links generated: %d | Emails sent: %d',
                $this->countAccessLinks($accessLinks),
                $emailResults['sent_count']
            );

            if ($emailResults['failed_count'] > 0) {
                $successMessage .= sprintf(' | Failed: %d (check logs)', $emailResults['failed_count']);
            }

            return redirect()->route('admin.estate-setup.index')
                ->with('success', $successMessage)
                ->with('email_results', $emailResults);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Approve estate error: ' . $e->getMessage(), [
                'estate_id' => $uniqueId,
                'admin_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Failed to approve estate: ' . $e->getMessage());
        }
    }

    /**
     * Check if all debts are settled for an estate.
     */
    private function areAllDebtsSettled(EstatePreRegistration $estate): bool
    {
        $unsettledCount = PreRegisteredDebt::where('estate_pre_registration_id', $estate->id)
            ->where(function ($query) {
                $query->where('status', '!=', 'settled')
                    ->orWhereRaw('amount > amount_paid');
            })
            ->count();

        return $unsettledCount === 0;
    }

    /**
     * Get comprehensive debt settlement status.
     */
    private function checkDebtSettlementStatus(EstatePreRegistration $estate): array
    {
        $debts = PreRegisteredDebt::where('estate_pre_registration_id', $estate->id)->get();
        
        $totalDebts = $debts->sum('amount');
        $debtsPaid = $debts->sum('amount_paid');
        $remaining = max(0, $totalDebts - $debtsPaid);
        
        $unsettledDebts = $debts
            ->filter(function ($debt) {
                return $debt->status !== 'settled' || $debt->amount > $debt->amount_paid;
            })
            ->map(function ($debt) {
                return [
                    'id' => $debt->id,
                    'creditor_name' => $debt->creditor_name,
                    'amount' => (float) $debt->amount,
                    'amount_paid' => (float) $debt->amount_paid,
                    'remaining' => (float) $debt->amount - (float) $debt->amount_paid,
                    'status' => $debt->status,
                    'formatted_remaining' => 'RM ' . number_format($debt->amount - $debt->amount_paid, 2),
                    'due_date' => $debt->due_date?->format('d M Y'),
                ];
            })
            ->values()
            ->toArray();

        return [
            'all_settled' => $remaining <= 0 && count($unsettledDebts) === 0,
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
     * Count total access links generated.
     */
    private function countAccessLinks(array $accessLinks): int
    {
        $count = 0;
        if (isset($accessLinks['heirs'])) $count += count($accessLinks['heirs']);
        if (isset($accessLinks['trustee'])) $count++;
        if (isset($accessLinks['alternate_trustee'])) $count++;
        if (isset($accessLinks['wasiyyah'])) $count += count($accessLinks['wasiyyah']);
        return $count;
    }

    /**
     * Reject an estate with reason.
     */
    public function reject(Request $request, string $uniqueId): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:1000',
        ]);

        try {
            $estate = EstatePreRegistration::where('unique_id', $uniqueId)->firstOrFail();

            DB::beginTransaction();

            $estate->update([
                'admin_approved' => false,
                'rejected_at' => now(),
                'rejected_by' => Auth::id(),
                'rejection_reason' => $request->rejection_reason,
                'status' => 'draft',
            ]);

            DB::commit();

            $this->logAuditTrail($estate, 'rejected', [
                'reason' => $request->rejection_reason,
            ]);

            return redirect()->route('admin.estate-setup.index')
                ->with('success', 'Estate rejected successfully. User can edit and resubmit.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reject estate error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to reject estate.');
        }
    }

    /**
     * Toggle estate status.
     */
    public function toggleStatus(string $uniqueId): RedirectResponse
    {
        try {
            $estate = EstatePreRegistration::where('unique_id', $uniqueId)->firstOrFail();

            $statusFlow = [
                'draft' => 'completed',
                'completed' => 'activated',
                'activated' => 'executed',
                'executed' => 'draft',
            ];

            $newStatus = $statusFlow[$estate->status] ?? 'draft';
            
            $updateData = ['status' => $newStatus];
            
            if ($newStatus === 'activated' && !$estate->activated_at) {
                $updateData['activated_at'] = now();
            }
            if ($newStatus === 'executed' && !$estate->executed_at) {
                $updateData['executed_at'] = now();
            }

            $estate->update($updateData);

            return redirect()->back()->with('success', "Estate status changed to: {$newStatus}");

        } catch (\Exception $e) {
            Log::error('Toggle status error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to toggle status.');
        }
    }

    /**
     * Delete an estate permanently.
     */
    public function destroy(string $uniqueId): RedirectResponse
    {
        try {
            $estate = EstatePreRegistration::where('unique_id', $uniqueId)->firstOrFail();

            DB::beginTransaction();

            $estate->heirs()->delete();
            $estate->wasiyyah()->delete();
            $estate->assets()->delete();
            $estate->debts()->delete();
            $estate->notificationRequests()->delete();
            $estate->inheritanceNotifications()->delete();
            $estate->digitalCredentials()->delete();
            
            BeneficiaryAccessLink::where('estate_pre_registration_id', $estate->id)->delete();
            
            $estate->delete();

            DB::commit();

            Log::info('Admin deleted estate', [
                'estate_id' => $estate->id,
                'admin_id' => Auth::id(),
            ]);

            return redirect()->route('admin.estate-setup.index')
                ->with('success', 'Estate deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Delete estate error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete estate.');
        }
    }

    // =========================================================================
    // DEBT MANAGEMENT METHODS - FIXED FOR PERSISTENCE
    // =========================================================================

    /**
     * Mark a specific debt as settled (AJAX endpoint).
     */
    public function markDebtSettled(Request $request, string $uniqueId, int $debtId)
    {
        try {
            $estate = EstatePreRegistration::where('unique_id', $uniqueId)->firstOrFail();
            
            $debt = PreRegisteredDebt::where('id', $debtId)
                ->where('estate_pre_registration_id', $estate->id)
                ->firstOrFail();

            $request->validate([
                'settlement_reference' => 'nullable|string|max:100',
                'notes' => 'nullable|string|max:500',
            ]);

            if ($debt->status === 'settled' && $debt->amount_paid >= $debt->amount) {
                return response()->json([
                    'success' => true,
                    'message' => 'Debt is already settled.',
                    'debt_id' => $debtId,
                    'all_debts_settled' => $this->areAllDebtsSettled($estate),
                    'remaining_debts' => PreRegisteredDebt::where('estate_pre_registration_id', $estate->id)
                        ->where(function ($q) {
                            $q->where('status', '!=', 'settled')
                              ->orWhereRaw('amount > amount_paid');
                        })
                        ->count(),
                ]);
            }

            DB::beginTransaction();

            $remaining = max(0, $debt->amount - $debt->amount_paid);
            
            $paymentHistory = $debt->payment_history ?? [];
            if (is_string($paymentHistory)) {
                $paymentHistory = json_decode($paymentHistory, true) ?? [];
            }
            
            $paymentHistory[] = [
                'amount' => $remaining,
                'method' => 'admin_settlement',
                'reference' => $request->settlement_reference ?? 'N/A',
                'notes' => $request->notes ?? '',
                'date' => now()->toDateTimeString(),
                'timestamp' => now()->timestamp,
                'admin_id' => Auth::id(),
                'admin_name' => Auth::user()->name,
            ];

            // DIRECT UPDATE - Most reliable way
            $updated = PreRegisteredDebt::where('id', $debtId)->update([
                'amount_paid' => $debt->amount,
                'status' => 'settled',
                'is_settled' => true,
                'settled_at' => now(),
                'paid_at' => now(),
                'settlement_reference' => $request->settlement_reference,
                'settlement_notes' => $request->notes,
                'payment_history' => json_encode($paymentHistory),
                'payment_method' => 'admin_settlement',
                'updated_at' => now(),
            ]);

            DB::commit();

            // VERIFY THE UPDATE
            $debt->refresh();
            
            Log::info('Debt settled by admin', [
                'debt_id' => $debtId,
                'amount' => $debt->amount,
                'amount_paid' => $debt->amount_paid,
                'status' => $debt->status,
                'rows_updated' => $updated,
            ]);

            $allSettled = $this->areAllDebtsSettled($estate);
            $settlementStatus = $this->checkDebtSettlementStatus($estate);

            return response()->json([
                'success' => true,
                'message' => 'Debt marked as settled successfully.',
                'debt_id' => $debtId,
                'all_debts_settled' => $allSettled,
                'remaining_debts' => $settlementStatus['unsettled_count'],
                'settlement_percentage' => $settlementStatus['settlement_percentage'],
                'settlement_summary' => [
                    'total_debts' => $settlementStatus['formatted_total'],
                    'total_paid' => $settlementStatus['formatted_paid'],
                    'remaining' => $settlementStatus['formatted_remaining'],
                    'unsettled_count' => $settlementStatus['unsettled_count'],
                ],
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'error' => 'Estate or debt not found.'], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'error' => 'Validation failed.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Mark debt settled error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => 'An unexpected error occurred.'], 500);
        }
    }

    /**
     * Get debt settlement status (AJAX endpoint).
     */
    public function getDebtStatus(string $uniqueId)
    {
        try {
            $estate = EstatePreRegistration::where('unique_id', $uniqueId)->firstOrFail();
            $status = $this->checkDebtSettlementStatus($estate);

            return response()->json([
                'success' => true,
                'all_settled' => $status['all_settled'],
                'settlement_percentage' => $status['settlement_percentage'],
                'summary' => [
                    'total' => $status['formatted_total'],
                    'paid' => $status['formatted_paid'],
                    'remaining' => $status['formatted_remaining'],
                    'unsettled_count' => $status['unsettled_count'],
                ],
                'unsettled_debts' => $status['unsettled_debts'],
            ]);
        } catch (\Exception $e) {
            Log::error('Get debt status error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // =========================================================================
    // SECURE ACCESS LINK GENERATION
    // =========================================================================

    private function generateSecureAccessLinks(EstatePreRegistration $estate): array
    {
        $accessLinks = [];
        $expiryDays = config('estate.access_link_expiry_days', 30);
        $expiresAt = now()->addDays($expiryDays);

        // Generate links for HEIRS
        foreach ($estate->heirs as $heir) {
            if (!empty($heir->email)) {
                $token = $this->generateUniqueToken();
                
                $link = BeneficiaryAccessLink::create([
                    'estate_pre_registration_id' => $estate->id,
                    'beneficiary_type' => 'heir',
                    'beneficiary_id' => $heir->id,
                    'beneficiary_name' => $heir->name,
                    'beneficiary_email' => $heir->email,
                    'access_token' => $token,
                    'expires_at' => $expiresAt,
                    'status' => 'active',
                    'access_count' => 0,
                ]);
                
                $accessLinks['heirs'][] = [
                    'link' => $link,
                    'url' => route('beneficiary.access', ['token' => $token]),
                    'beneficiary' => $heir,
                ];
            }
        }

        // Generate link for PRIMARY TRUSTEE
        if (!empty($estate->trustee_email)) {
            $token = $this->generateUniqueToken();
            
            $link = BeneficiaryAccessLink::create([
                'estate_pre_registration_id' => $estate->id,
                'beneficiary_type' => 'trustee',
                'beneficiary_id' => null,
                'beneficiary_name' => $estate->trustee_name,
                'beneficiary_email' => $estate->trustee_email,
                'access_token' => $token,
                'expires_at' => $expiresAt,
                'status' => 'active',
                'access_count' => 0,
            ]);
            
            $accessLinks['trustee'] = [
                'link' => $link,
                'url' => route('beneficiary.access', ['token' => $token]),
                'beneficiary' => (object) ['name' => $estate->trustee_name, 'email' => $estate->trustee_email],
            ];
        }

        // Generate link for ALTERNATE TRUSTEE
        if (!empty($estate->alternate_trustee_email)) {
            $token = $this->generateUniqueToken();
            
            $link = BeneficiaryAccessLink::create([
                'estate_pre_registration_id' => $estate->id,
                'beneficiary_type' => 'alternate_trustee',
                'beneficiary_id' => null,
                'beneficiary_name' => $estate->alternate_trustee_name,
                'beneficiary_email' => $estate->alternate_trustee_email,
                'access_token' => $token,
                'expires_at' => $expiresAt,
                'status' => 'active',
                'access_count' => 0,
            ]);
            
            $accessLinks['alternate_trustee'] = [
                'link' => $link,
                'url' => route('beneficiary.access', ['token' => $token]),
                'beneficiary' => (object) ['name' => $estate->alternate_trustee_name, 'email' => $estate->alternate_trustee_email],
            ];
        }

        // Generate links for WASIYYAH beneficiaries
        foreach ($estate->wasiyyah as $wasiyyah) {
            if (!empty($wasiyyah->beneficiary_email)) {
                $token = $this->generateUniqueToken();
                
                $link = BeneficiaryAccessLink::create([
                    'estate_pre_registration_id' => $estate->id,
                    'beneficiary_type' => 'wasiyyah',
                    'beneficiary_id' => $wasiyyah->id,
                    'beneficiary_name' => $wasiyyah->beneficiary_name,
                    'beneficiary_email' => $wasiyyah->beneficiary_email,
                    'access_token' => $token,
                    'expires_at' => $expiresAt,
                    'status' => 'active',
                    'access_count' => 0,
                ]);
                
                $accessLinks['wasiyyah'][] = [
                    'link' => $link,
                    'url' => route('beneficiary.access', ['token' => $token]),
                    'beneficiary' => $wasiyyah,
                ];
            }
        }

        $estate->update([
            'secure_access_links_generated' => true,
            'secure_access_links_generated_at' => now(),
            'secure_access_links' => json_encode([
                'generated_at' => now()->toISOString(),
                'generated_by' => Auth::id(),
                'expires_at' => $expiresAt->toISOString(),
                'expiry_days' => $expiryDays,
                'total_links' => $this->countAccessLinks($accessLinks),
            ]),
        ]);

        return $accessLinks;
    }

    private function generateUniqueToken(): string
    {
        $token = Str::random(64) . '-' . time() . '-' . Str::random(16);
        
        while (BeneficiaryAccessLink::where('access_token', $token)->exists()) {
            $token = Str::random(64) . '-' . time() . '-' . Str::random(16);
        }
        
        return $token;
    }

    // =========================================================================
    // DIRECT EMAIL SENDING VIA SMTP (RELIABLE - NO QUEUE DEPENDENCY)
    // =========================================================================

    /**
     * Send emails directly to ALL beneficiaries via SMTP.
     * This method sends emails synchronously without relying on queues.
     */
    private function sendBeneficiaryEmailsDirect(EstatePreRegistration $estate, array $accessLinks): array
    {
        $sent = [];
        $failed = [];
        $emailResults = [];

        // Get estate data for email content
        $deceasedName = $estate->deceased_name ?? 'Unknown';
        $fromAddress = config('mail.from.address', 'noreply@neofaraid.com');
        $fromName = config('mail.from.name', 'Neo Faraid');

        // Send to HEIRS
        if (isset($accessLinks['heirs'])) {
            foreach ($accessLinks['heirs'] as $heirData) {
                $link = $heirData['link'];
                $heir = $heirData['beneficiary'];
                $email = $link->beneficiary_email;
                $name = $heir->name;

                try {
                    // Build email subject
                    $subject = "Inheritance Distribution - {$deceasedName}";

                    // Build email HTML content
                    $htmlContent = $this->buildBeneficiaryEmailHtml(
                        $name, 
                        'heir', 
                        $deceasedName, 
                        $heirData['url'], 
                        $heir->share_percentage ?? null,
                        $estate->will_video_url ?? null
                    );

                    // Send email directly via SMTP
                    Mail::html($htmlContent, function ($message) use ($email, $name, $subject, $fromAddress, $fromName) {
                        $message->to($email, $name)
                                ->subject($subject)
                                ->from($fromAddress, $fromName);
                    });

                    $link->update(['notification_sent_at' => now()]);
                    $sent[] = $email;
                    
                    $emailResults[] = [
                        'beneficiary_type' => 'heir',
                        'beneficiary_name' => $name,
                        'email' => $email,
                        'status' => 'sent',
                    ];

                    Log::info('Email sent to heir', ['email' => $email, 'name' => $name]);
                } catch (\Exception $e) {
                    $failed[] = $email;
                    $emailResults[] = [
                        'beneficiary_type' => 'heir',
                        'beneficiary_name' => $name,
                        'email' => $email,
                        'status' => 'failed',
                        'error' => $e->getMessage(),
                    ];
                    Log::error('Failed to send email to heir: ' . $e->getMessage(), ['email' => $email]);
                }
            }
        }

        // Send to TRUSTEE
        if (isset($accessLinks['trustee'])) {
            $trusteeData = $accessLinks['trustee'];
            $link = $trusteeData['link'];
            $email = $link->beneficiary_email;
            $name = $trusteeData['beneficiary']->name;

            try {
                $subject = "Trustee Appointment - Estate of {$deceasedName}";
                $htmlContent = $this->buildBeneficiaryEmailHtml(
                    $name,
                    'trustee', 
                    $deceasedName, 
                    $trusteeData['url'],
                    null,
                    $estate->will_video_url ?? null
                );

                Mail::html($htmlContent, function ($message) use ($email, $name, $subject, $fromAddress, $fromName) {
                    $message->to($email, $name)
                            ->subject($subject)
                            ->from($fromAddress, $fromName);
                });

                $link->update(['notification_sent_at' => now()]);
                $sent[] = $email;
                
                $emailResults[] = [
                    'beneficiary_type' => 'trustee',
                    'beneficiary_name' => $name,
                    'email' => $email,
                    'status' => 'sent',
                ];

                Log::info('Email sent to trustee', ['email' => $email, 'name' => $name]);
            } catch (\Exception $e) {
                $failed[] = $email;
                $emailResults[] = [
                    'beneficiary_type' => 'trustee',
                    'beneficiary_name' => $name,
                    'email' => $email,
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                ];
                Log::error('Failed to send email to trustee: ' . $e->getMessage(), ['email' => $email]);
            }
        }

        // Send to ALTERNATE TRUSTEE
        if (isset($accessLinks['alternate_trustee'])) {
            $altData = $accessLinks['alternate_trustee'];
            $link = $altData['link'];
            $email = $link->beneficiary_email;
            $name = $altData['beneficiary']->name;

            try {
                $subject = "Alternate Trustee Appointment - Estate of {$deceasedName}";
                $htmlContent = $this->buildBeneficiaryEmailHtml(
                    $name,
                    'alternate_trustee',
                    $deceasedName,
                    $altData['url'],
                    null,
                    $estate->will_video_url ?? null
                );

                Mail::html($htmlContent, function ($message) use ($email, $name, $subject, $fromAddress, $fromName) {
                    $message->to($email, $name)
                            ->subject($subject)
                            ->from($fromAddress, $fromName);
                });

                $link->update(['notification_sent_at' => now()]);
                $sent[] = $email;
                
                $emailResults[] = [
                    'beneficiary_type' => 'alternate_trustee',
                    'beneficiary_name' => $name,
                    'email' => $email,
                    'status' => 'sent',
                ];

                Log::info('Email sent to alternate trustee', ['email' => $email, 'name' => $name]);
            } catch (\Exception $e) {
                $failed[] = $email;
                Log::error('Failed to send email to alternate trustee: ' . $e->getMessage(), ['email' => $email]);
            }
        }

        // Send to WASIYYAH beneficiaries
        if (isset($accessLinks['wasiyyah'])) {
            foreach ($accessLinks['wasiyyah'] as $wasiyyahData) {
                $link = $wasiyyahData['link'];
                $wasiyyah = $wasiyyahData['beneficiary'];
                $email = $link->beneficiary_email;
                $name = $wasiyyah->beneficiary_name;

                try {
                    $subject = "Wasiyyah Notification - {$deceasedName}";
                    $htmlContent = $this->buildBeneficiaryEmailHtml(
                        $name,
                        'wasiyyah',
                        $deceasedName,
                        $wasiyyahData['url'],
                        $wasiyyah->requested_percentage ?? null,
                        $estate->will_video_url ?? null
                    );

                    Mail::html($htmlContent, function ($message) use ($email, $name, $subject, $fromAddress, $fromName) {
                        $message->to($email, $name)
                                ->subject($subject)
                                ->from($fromAddress, $fromName);
                    });

                    $link->update(['notification_sent_at' => now()]);
                    $sent[] = $email;
                    
                    $emailResults[] = [
                        'beneficiary_type' => 'wasiyyah',
                        'beneficiary_name' => $name,
                        'email' => $email,
                        'status' => 'sent',
                    ];

                    Log::info('Email sent to wasiyyah beneficiary', ['email' => $email, 'name' => $name]);
                } catch (\Exception $e) {
                    $failed[] = $email;
                    Log::error('Failed to send email to wasiyyah: ' . $e->getMessage(), ['email' => $email]);
                }
            }
        }

        // Update estate notification status
        $estate->update([
            'notification_sent' => !empty($sent),
            'notification_sent_at' => now(),
            'notification_recipients' => json_encode([
                'total' => count($sent) + count($failed),
                'sent' => count($sent),
                'failed' => count($failed),
                'recipients' => $sent,
                'failed_recipients' => $failed,
            ]),
        ]);

        return [
            'sent' => $sent,
            'failed' => $failed,
            'sent_count' => count($sent),
            'failed_count' => count($failed),
            'total_count' => count($sent) + count($failed),
            'email_results' => $emailResults,
        ];
    }

    /**
     * Build HTML email content for beneficiaries.
     */
    private function buildBeneficiaryEmailHtml(string $name, string $type, string $deceasedName, string $accessUrl, ?float $sharePercentage = null, ?string $videoUrl = null): string
    {
        $typeLabel = ucfirst(str_replace('_', ' ', $type));
        $expiryDate = now()->addDays(30)->format('d F Y');
        $appName = config('app.name', 'Neo Faraid');
        $currentYear = date('Y');

        $shareInfo = '';
        if ($sharePercentage) {
            $shareInfo = "
                <div style='background-color: #f0f7ff; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #1a5fb4;'>
                    <p style='margin: 0;'><strong>Your Share:</strong> {$sharePercentage}% of the distributable estate</p>
                </div>";
        }

        $videoSection = '';
        if ($videoUrl) {
            $videoSection = "
                <div style='background-color: #fff3cd; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #ffc107;'>
                    <p style='margin: 0;'><strong>📹 Will Video Available</strong></p>
                    <p style='margin: 5px 0 0;'>The deceased has recorded a video will. You can view it by clicking the access link below.</p>
                </div>";
        }

        $roleDescription = match($type) {
            'heir' => "as a legal heir (Faraid beneficiary) in the estate of",
            'trustee' => "as the appointed Trustee (Wasi) for the estate of",
            'alternate_trustee' => "as the Alternate Trustee for the estate of",
            'wasiyyah' => "as a Wasiyyah beneficiary in the estate of",
            default => "as a beneficiary in the estate of",
        };

        return <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Inheritance Notification - {$deceasedName}</title>
        </head>
        <body style="font-family: Poppins, sans-serif; line-height: 1.6; color: #333333; background-color: #f5f5f5; margin: 0; padding: 0;">
            <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
                <!-- Header -->
                <div style="background: linear-gradient(135deg, #1a5fb4, #2d7ad6); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
                    <h1 style="margin: 0; font-size: 22px;">{$appName}</h1>
                    <p style="margin: 10px 0 0; font-size: 16px; opacity: 0.95;">Inheritance Distribution Notification</p>
                </div>

                <!-- Body -->
                <div style="background-color: white; padding: 30px; border-left: 1px solid #dddddd; border-right: 1px solid #dddddd;">
                    <p>Dear <strong>{$name}</strong>,</p>
                    
                    <p>You have been named <strong>{$roleDescription} <em>{$deceasedName}</em></strong>.</p>
                    
                    {$shareInfo}
                    
                    <p>To view your complete inheritance details, please click the button below:</p>
                    
                    <div style="text-align: center; margin: 25px 0;">
                        <a href="{$accessUrl}" style="background-color: #003871; color: white; padding: 14px 35px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px; display: inline-block;">
                            View Inheritance Details
                        </a>
                    </div>
                    
                    {$videoSection}
                    
                    <!-- Security Notice -->
                    <div style="background-color: #fff3cd; padding: 15px; border-radius: 8px; margin: 15px 0; border-left: 4px solid #ffc107;">
                        <p style="margin: 0;"><strong>⚠️   Important Security Notes:</strong></p>
                        <ul style="margin: 10px 0 0; padding-left: 20px;">
                            <li>This link is for your personal use only. Do not share it.</li>
                            <li>The link will expire on <strong>{$expiryDate}</strong>.</li>
                            <li>All access to this link is logged for security purposes.</li>
                        </ul>
                    </div>
                    
                    <p>If you have any questions, please contact the estate administrator or the appointed trustee.</p>
                    
                    <p style="margin-top: 25px;">
                        Best regards,<br>
                        <strong>{$appName} Team</strong>
                    </p>
                </div>

                <!-- Footer -->
                <div style="background-color: #f8f9fa; padding: 20px; text-align: center; border: 1px solid #dddddd; border-top: none; border-radius: 0 0 10px 10px;">
                    <p style="margin: 0; font-size: 12px; color: #888888;">
                        This is an automated message from {$appName}. Please do not reply to this email.<br>
                        If you believe you received this email in error, please contact our support team.
                    </p>
                    <p style="margin: 5px 0 0; font-size: 11px; color: #aaaaaa;">
                        &copy; {$currentYear} {$appName}. All rights reserved.
                    </p>
                </div>
            </div>
        </body>
        </html>
        HTML;
    }

    // =========================================================================
    // ACCESS LINK MANAGEMENT
    // =========================================================================

    public function getAccessLinks(string $uniqueId)
    {
        $estate = EstatePreRegistration::where('unique_id', $uniqueId)->firstOrFail();
        
        $accessLinks = BeneficiaryAccessLink::where('estate_pre_registration_id', $estate->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'access_links' => $accessLinks->map(function ($link) {
                return [
                    'id' => $link->id,
                    'beneficiary_type' => $link->beneficiary_type,
                    'beneficiary_name' => $link->beneficiary_name,
                    'beneficiary_email' => $link->beneficiary_email,
                    'expires_at' => $link->expires_at->format('Y-m-d H:i:s'),
                    'status' => $link->status,
                    'access_url' => route('beneficiary.access', ['token' => $link->access_token]),
                ];
            }),
        ]);
    }

    public function resendNotification(Request $request, string $uniqueId, int $linkId)
    {
        try {
            $estate = EstatePreRegistration::where('unique_id', $uniqueId)->firstOrFail();
            $link = BeneficiaryAccessLink::where('id', $linkId)
                ->where('estate_pre_registration_id', $estate->id)
                ->firstOrFail();

            if ($link->expires_at->isPast()) {
                $newToken = $this->generateUniqueToken();
                $link->update([
                    'access_token' => $newToken,
                    'expires_at' => now()->addDays(30),
                    'status' => 'active',
                ]);
            }

            $accessUrl = route('beneficiary.access', ['token' => $link->access_token]);
            $htmlContent = $this->buildBeneficiaryEmailHtml(
                $link->beneficiary_name,
                $link->beneficiary_type,
                $estate->deceased_name ?? 'Unknown',
                $accessUrl,
                null,
                $estate->will_video_url ?? null
            );

            $subject = "Inheritance Distribution - {$estate->deceased_name}";

            Mail::html($htmlContent, function ($message) use ($link, $subject) {
                $message->to($link->beneficiary_email, $link->beneficiary_name)
                        ->subject($subject)
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
            
            $link->update(['notification_sent_at' => now()]);

            return response()->json([
                'success' => true,
                'message' => 'Notification resent successfully to ' . $link->beneficiary_email,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to resend notification: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function revokeAccessLink(string $uniqueId, int $linkId)
    {
        try {
            $estate = EstatePreRegistration::where('unique_id', $uniqueId)->firstOrFail();
            $link = BeneficiaryAccessLink::where('id', $linkId)
                ->where('estate_pre_registration_id', $estate->id)
                ->firstOrFail();

            $link->update(['status' => 'revoked']);

            return response()->json(['success' => true, 'message' => 'Access link revoked.']);

        } catch (\Exception $e) {
            Log::error('Revoke access link error: ' . $e->getMessage());
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    // =========================================================================
    // NOTIFICATION REQUEST MANAGEMENT
    // =========================================================================

    public function approveNotification(Request $request, int $requestId): RedirectResponse
    {
        try {
            $notificationRequest = NotificationRequest::findOrFail($requestId);

            if ($notificationRequest->status !== 'pending_admin_approval') {
                return redirect()->back()->with('error', 'This request is not pending approval.');
            }

            $estate = $notificationRequest->estate;
            
            if (!$estate) {
                return redirect()->back()->with('error', 'Associated estate not found.');
            }

            $debtStatus = $this->checkDebtSettlementStatus($estate);
            
            if (!$debtStatus['all_settled']) {
                return redirect()->back()->with('error', 'Debts not fully settled.');
            }

            $accessLinks = $this->generateSecureAccessLinks($estate);
            $emailResults = $this->sendBeneficiaryEmailsDirect($estate, $accessLinks);

            DB::beginTransaction();

            $notificationRequest->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'admin_notes' => $request->input('admin_notes'),
                'notification_sent' => $emailResults['sent_count'] > 0,
                'notification_sent_at' => now(),
                'emails_sent' => $emailResults['sent_count'] > 0,
                'recipients_count' => $emailResults['total_count'],
                'recipients_list' => json_encode($emailResults['email_results']),
                'email_status' => $emailResults['failed_count'] > 0 ? 'partial' : 'sent',
            ]);

            DB::commit();

            return redirect()->back()->with('success', 
                sprintf('✅ Notification approved! Emails sent: %d', $emailResults['sent_count']));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Admin approve notification error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to approve notification: ' . $e->getMessage());
        }
    }

    public function rejectNotification(Request $request, int $requestId): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ]);

        try {
            $notificationRequest = NotificationRequest::findOrFail($requestId);

            $notificationRequest->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => Auth::id(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            return redirect()->back()->with('success', 'Notification request rejected.');

        } catch (\Exception $e) {
            Log::error('Admin reject notification error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to reject notification.');
        }
    }

    public function notifications(Request $request)
    {
        $query = NotificationRequest::with(['estate', 'requestedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => NotificationRequest::count(),
            'pending' => NotificationRequest::where('status', 'pending_admin_approval')->count(),
            'approved' => NotificationRequest::where('status', 'approved')->count(),
            'rejected' => NotificationRequest::where('status', 'rejected')->count(),
            'sent' => NotificationRequest::where('notification_sent', true)->count(),
        ];

        return view('admin.estate-setup.notifications', compact('requests', 'stats'));
    }

    // =========================================================================
    // INHERITANCE NOTIFICATION MANAGEMENT
    // =========================================================================

    public function inheritanceNotifications(Request $request)
    {
        $query = InheritanceNotification::with(['estate', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => InheritanceNotification::count(),
            'pending' => InheritanceNotification::where('status', 'pending')->count(),
            'approved' => InheritanceNotification::where('status', 'approved')->count(),
            'rejected' => InheritanceNotification::where('status', 'rejected')->count(),
            'sent' => InheritanceNotification::where('notification_sent', true)->count(),
        ];

        return view('admin.estate-setup.inheritance-notifications', compact('notifications', 'stats'));
    }

    public function approveInheritanceNotification(Request $request, int $id): RedirectResponse
    {
        try {
            $notification = InheritanceNotification::findOrFail($id);
            
            if ($notification->status !== 'pending') {
                return redirect()->back()->with('error', 'This notification is not pending.');
            }

            $estate = $notification->estate;
            
            if (!$estate) {
                return redirect()->back()->with('error', 'Associated estate not found.');
            }

            $debtStatus = $this->checkDebtSettlementStatus($estate);
            if (!$debtStatus['all_settled']) {
                return redirect()->back()->with('error', 'Debts not fully settled.');
            }

            $accessToken = Str::random(64);

            DB::beginTransaction();
            $notification->update([
                'status' => 'approved',
                'approved_at' => now(),
                'admin_notes' => $request->input('admin_notes'),
                'access_token' => $accessToken,
                'expires_at' => now()->addDays(30),
            ]);
            DB::commit();

            $sentCount = $this->sendInheritanceEmails($estate, $accessToken);

            $notification->update([
                'notification_sent' => true,
                'notification_sent_at' => now(),
                'recipients' => $sentCount,
            ]);

            return redirect()->back()->with('success', "Notification approved. Emails sent: {$sentCount}.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Approve inheritance notification error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to approve notification.');
        }
    }

    public function rejectInheritanceNotification(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ]);

        try {
            $notification = InheritanceNotification::findOrFail($id);
            
            $notification->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'admin_notes' => $request->rejection_reason,
            ]);

            return redirect()->back()->with('success', 'Notification request rejected.');

        } catch (\Exception $e) {
            Log::error('Reject inheritance notification error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to reject notification.');
        }
    }

    private function sendInheritanceEmails(EstatePreRegistration $estate, string $accessToken): int
    {
        $sentCount = 0;
        $secureLinkBase = route('inheritance.view-result', ['token' => $accessToken]);
        $deceasedName = $estate->deceased_name ?? 'Unknown';

        $beneficiaries = [];

        foreach ($estate->heirs as $heir) {
            if (!empty($heir->email)) {
                $beneficiaries[] = [
                    'email' => $heir->email,
                    'name' => $heir->name,
                    'type' => 'heir',
                ];
            }
        }

        foreach ($estate->wasiyyah as $w) {
            if (!empty($w->beneficiary_email)) {
                $beneficiaries[] = [
                    'email' => $w->beneficiary_email,
                    'name' => $w->beneficiary_name,
                    'type' => 'wasiyyah',
                ];
            }
        }

        if (!empty($estate->trustee_email)) {
            $beneficiaries[] = [
                'email' => $estate->trustee_email,
                'name' => $estate->trustee_name,
                'type' => 'trustee',
            ];
        }

        foreach ($beneficiaries as $b) {
            try {
                $subject = "Inheritance Distribution - {$deceasedName}";
                $htmlContent = $this->buildBeneficiaryEmailHtml(
                    $b['name'],
                    $b['type'],
                    $deceasedName,
                    $secureLinkBase,
                    null,
                    $estate->will_video_url ?? null
                );

                Mail::html($htmlContent, function ($message) use ($b, $subject) {
                    $message->to($b['email'], $b['name'])
                            ->subject($subject)
                            ->from(config('mail.from.address'), config('mail.from.name'));
                });

                $sentCount++;
            } catch (\Exception $e) {
                Log::error("Failed to send inheritance email to {$b['email']}: " . $e->getMessage());
            }
        }

        return $sentCount;
    }

    // =========================================================================
    // SYSTEM MAINTENANCE
    // =========================================================================

    public function systemHealth()
    {
        $validEstateIds = EstatePreRegistration::pluck('id')->toArray();
        
        $health = [
            'total_estates' => EstatePreRegistration::count(),
            'estates_without_admin_review' => EstatePreRegistration::whereNull('admin_approved_at')
                ->whereIn('status', ['completed', 'activated'])
                ->count(),
            'estates_with_unsettled_debts' => EstatePreRegistration::whereHas('debts', function ($q) {
                $q->where('status', '!=', 'settled')->orWhereRaw('amount > amount_paid');
            })->count(),
            'expired_access_links' => BeneficiaryAccessLink::where('expires_at', '<', now())->where('status', 'active')->count(),
            'orphaned_heirs' => PreRegisteredHeir::whereNotIn('estate_pre_registration_id', $validEstateIds)->count(),
            'orphaned_assets' => PreRegisteredAsset::whereNotIn('estate_pre_registration_id', $validEstateIds)->count(),
            'orphaned_debts' => PreRegisteredDebt::whereNotIn('estate_pre_registration_id', $validEstateIds)->count(),
            'orphaned_wasiyyah' => PreRegisteredWasiyyah::whereNotIn('estate_pre_registration_id', $validEstateIds)->count(),
            'locked_estates' => EstatePreRegistration::where('data_locked', true)->count(),
            'pending_pdf' => EstatePreRegistration::where('admin_approved', true)->whereNull('distribution_pdf_path')->count(),
            'pdf_locked' => EstatePreRegistration::where('distribution_pdf_status', 'locked')->count(),
            'pdf_released' => EstatePreRegistration::where('distribution_pdf_status', 'released')->count(),
        ];

        return view('admin.estate-setup.system-health', compact('health'));
    }

    public function cleanOrphanedRecords()
    {
        try {
            DB::beginTransaction();
            
            $validEstateIds = EstatePreRegistration::pluck('id')->toArray();
            
            $orphanedHeirs = PreRegisteredHeir::whereNotIn('estate_pre_registration_id', $validEstateIds)->delete();
            $orphanedAssets = PreRegisteredAsset::whereNotIn('estate_pre_registration_id', $validEstateIds)->delete();
            $orphanedDebts = PreRegisteredDebt::whereNotIn('estate_pre_registration_id', $validEstateIds)->delete();
            $orphanedWasiyyah = PreRegisteredWasiyyah::whereNotIn('estate_pre_registration_id', $validEstateIds)->delete();
            $orphanedAccessLinks = BeneficiaryAccessLink::whereNotIn('estate_pre_registration_id', $validEstateIds)->delete();
            
            DB::commit();

            AuditLog::create([
                'estate_pre_registration_id' => null,
                'action' => 'cleaned_orphaned_records',
                'performed_by' => Auth::id(),
                'performed_by_name' => Auth::user()->name,
                'performed_by_role' => Auth::user()->role ?? 'admin',
                'details' => json_encode([
                    'orphaned_heirs' => $orphanedHeirs,
                    'orphaned_assets' => $orphanedAssets,
                    'orphaned_debts' => $orphanedDebts,
                    'orphaned_wasiyyah' => $orphanedWasiyyah,
                    'orphaned_access_links' => $orphanedAccessLinks,
                ]),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            return redirect()->back()->with('success', 'Cleaned up orphaned records.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to clean orphaned records.');
        }
    }

    public function bulkDelete(Request $request): RedirectResponse
    {
        $request->validate([
            'estate_ids' => 'required|array',
            'estate_ids.*' => 'exists:estate_pre_registrations,id',
        ]);

        $deleted = 0;

        DB::beginTransaction();

        foreach ($request->estate_ids as $estateId) {
            try {
                $estate = EstatePreRegistration::findOrFail($estateId);
                $estate->heirs()->delete();
                $estate->wasiyyah()->delete();
                $estate->assets()->delete();
                $estate->debts()->delete();
                $estate->digitalCredentials()->delete();
                BeneficiaryAccessLink::where('estate_pre_registration_id', $estateId)->delete();
                $estate->delete();
                $deleted++;
            } catch (\Exception $e) {
                Log::error("Failed to delete estate ID {$estateId}: " . $e->getMessage());
            }
        }

        DB::commit();

        return redirect()->route('admin.estate-setup.index')->with('success', "Deleted {$deleted} estate(s).");
    }

    // =========================================================================
    // HELPERS
    // =========================================================================

    private function checkEstateReadiness(EstatePreRegistration $estate): array
    {
        $issues = [];

        if (empty($estate->deceased_name)) $issues[] = 'Deceased name is missing';
        if (empty($estate->deceased_nric)) $issues[] = 'Deceased NRIC is missing';
        if ($estate->heirs()->count() === 0) $issues[] = 'No heirs have been added';
        if ($estate->assets()->count() === 0) $issues[] = 'No assets have been added';
        if ($estate->net_estate <= 0) $issues[] = 'Net estate value must be greater than zero';
        if (empty($estate->trustee_name) || empty($estate->trustee_email)) $issues[] = 'Trustee information is incomplete';

        $totalHeirPercentage = $estate->heirs()->sum('share_percentage');
        if (abs($totalHeirPercentage - 100) > 0.01 && $estate->heirs()->count() > 0) {
            $issues[] = "Heir distribution must equal 100% (currently {$totalHeirPercentage}%)";
        }

        $totalWasiyyah = $estate->wasiyyah()->sum('requested_percentage');
        if ($totalWasiyyah > 33.33) {
            $issues[] = "Total wasiyyah ({$totalWasiyyah}%) exceeds the 1/3 limit (33.33%)";
        }

        return ['ready' => empty($issues), 'issues' => $issues, 'issue_count' => count($issues)];
    }

    private function logAuditTrail(EstatePreRegistration $estate, string $action, array $details = []): void
    {
        try {
            AuditLog::create([
                'estate_pre_registration_id' => $estate->id,
                'action' => $action,
                'performed_by' => Auth::id(),
                'performed_by_name' => Auth::user()->name,
                'performed_by_role' => Auth::user()->role ?? 'admin',
                'details' => json_encode(array_merge($details, [
                    'estate_unique_id' => $estate->unique_id,
                    'deceased_name' => $estate->deceased_name,
                    'timestamp' => now()->toISOString(),
                ])),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            Log::warning('Failed to create audit log: ' . $e->getMessage());
        }
    }
}