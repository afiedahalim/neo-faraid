<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstantEstateSession;
use App\Services\PdfGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstantEstateApprovalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a list of sessions pending admin review/approval.
     */
    public function index(Request $request)
    {
        $query = InstantEstateSession::whereIn('admin_status', ['pending_review', 'pending_approval'])
            ->orderBy('created_at', 'asc');

        // Use $sessions as the variable name
        $sessions = $query->paginate(20);

        return view('admin.instant-estate.pending-reviews', compact('sessions'));
    }

    /**
     * Approve a session, and optionally send the email.
     * (PDF generation is now handled elsewhere / removed.)
     */
    public function approve(string $sessionId, Request $request)
    {
        $session = InstantEstateSession::where('session_id', $sessionId)->firstOrFail();
        $session->admin_status = 'approved';
        $session->reviewed_by_admin_id = auth()->id();
        $session->reviewed_at = now();
        $session->save();

        // PDF generation block removed / commented out as requested
        /*
        if ($session->matched_record_id && $session->matched_record_type) {
            $pdfGen = app(PdfGeneratorService::class);
            $pdfContent = $pdfGen->generateInheritanceDistributionPdfForSession($session);
            $pdfPath = 'estates/' . $session->session_id . '/report_' . now()->format('Ymd_His') . '.pdf';
            Storage::disk('private')->put($pdfPath, $pdfContent);
            $session->report_pdf_path = $pdfPath;
            $session->save();
        }
        */

        // If the user requested email notification, send it now
        if ($session->notification_requested && $session->recipient_email) {
            app(\App\Http\Controllers\InstantEstateController::class)
                ->sendApprovedReportEmail($session);
        }

        return redirect()->back()->with(
            'success',
            'Document approved. Report is now available to the user.'
        );
    }

    /**
     * Reject a session with a mandatory reason.
     */
    public function reject(string $sessionId, Request $request)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $session = InstantEstateSession::where('session_id', $sessionId)->firstOrFail();
        $session->admin_status = 'rejected';
        $session->rejection_reason = $request->reason;
        $session->reviewed_by_admin_id = auth()->id();
        $session->reviewed_at = now();
        $session->save();

        return redirect()->back()->with(
            'success',
            'Document rejected. User will be notified.'
        );
    }
}