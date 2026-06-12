<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstantEstateSession;
use App\Models\NotificationRequest;
use App\Services\PdfGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class InstantEstateAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Display a list of ALL instant estate sessions with filtering and stats.
     */
    public function index(Request $request)
    {
        $query = InstantEstateSession::query();

        // Search by session ID, deceased name, guest email, or original filename
        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('session_id', 'like', "%{$search}%")
                  ->orWhere('deceased_name', 'like', "%{$search}%")
                  ->orWhere('guest_email', 'like', "%{$search}%")
                  ->orWhere('original_filename', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        // Filter by quality check
        if ($request->has('quality')) {
            $quality = $request->get('quality');
            if ($quality === '1') {
                $query->where('quality_check_passed', true);
            } elseif ($quality === '0') {
                $query->where('quality_check_passed', false);
            }
        }

        // Filter by notification status (using relationship)
        if ($notifStatus = $request->get('notification_status')) {
            switch ($notifStatus) {
                case 'pending':
                    $query->where('notification_requested', true)
                          ->whereDoesntHave('notificationRequest', function($q) {
                              $q->whereIn('status', ['approved', 'rejected', 'sent']);
                          });
                    break;
                case 'approved':
                    $query->whereHas('notificationRequest', function($q) {
                        $q->where('status', 'approved');
                    });
                    break;
                case 'rejected':
                    $query->whereHas('notificationRequest', function($q) {
                        $q->where('status', 'rejected');
                    });
                    break;
                case 'sent':
                    $query->whereHas('notificationRequest', function($q) {
                        $q->where('status', 'sent');
                    });
                    break;
                case 'none':
                    $query->where('notification_requested', false);
                    break;
            }
        }

        $sessions = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Stats for dashboard cards
        $stats = [
            'total' => InstantEstateSession::count(),
            'pending_reviews' => InstantEstateSession::where('admin_status', 'pending_review')->count(),
            'pending_approval' => InstantEstateSession::where('admin_status', 'pending_approval')->count(),
            'approved' => InstantEstateSession::where('admin_status', 'approved')->count(),
            'rejected' => InstantEstateSession::where('admin_status', 'rejected')->count(),
            'notifications_sent' => NotificationRequest::where('status', 'sent')->count(),
        ];

        return view('admin.instant-estate.index', compact('sessions', 'stats'));
    }

    /**
     * Show a single session details.
     */
    public function show(string $sessionId)
    {
        // Validate that the session ID is a valid UUID
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $sessionId)) {
            abort(404, 'Invalid session ID format.');
        }

        $session = InstantEstateSession::where('session_id', $sessionId)->firstOrFail();
        return view('admin.instant-estate.show', compact('session'));
    }

    /**
     * Delete a session and its associated files.
     */
    public function destroy(string $sessionId)
    {
        // Validate that the session ID is a valid UUID
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $sessionId)) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Invalid session ID format.'], 400);
            }
            return redirect()->back()->with('error', 'Invalid session ID format.');
        }

        $session = InstantEstateSession::where('session_id', $sessionId)->firstOrFail();
        
        // Delete associated files
        if ($session->file_path && Storage::disk('private')->exists($session->file_path)) {
            Storage::disk('private')->delete($session->file_path);
        }
        if ($session->report_pdf_path && Storage::disk('private')->exists($session->report_pdf_path)) {
            Storage::disk('private')->delete($session->report_pdf_path);
        }
        
        $session->delete();
        
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Session deleted successfully.']);
        }
        return redirect()->back()->with('success', 'Session deleted successfully.');
    }

    /**
     * Alternative delete method (for routes using deleteSession).
     */
    public function deleteSession(string $sessionId)
    {
        // Delegates to destroy to keep logic in one place
        return $this->destroy($sessionId);
    }

    /**
     * Download the original uploaded file.
     */
    public function downloadFile(string $sessionId)
    {
        // Validate UUID
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $sessionId)) {
            abort(404, 'Invalid session ID.');
        }

        $session = InstantEstateSession::where('session_id', $sessionId)->firstOrFail();
        
        if (!$session->file_path || !Storage::disk('private')->exists($session->file_path)) {
            abort(404, 'File not found.');
        }
        
        $filename = $session->original_filename ?? 'death_certificate_' . $session->session_id . '.' . pathinfo($session->file_path, PATHINFO_EXTENSION);
        return Storage::disk('private')->download($session->file_path, $filename);
    }

    /**
     * Export all sessions to CSV.
     */
    public function export(Request $request)
    {
        $sessions = InstantEstateSession::all();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="instant-estate-sessions.csv"',
        ];
        
        $callback = function() use ($sessions) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Session ID', 'Deceased Name', 'NRIC', 'Status', 'Admin Status', 'Uploaded At', 'Guest Email', 'Notification Requested', 'Email Sent At']);
            
            foreach ($sessions as $session) {
                fputcsv($file, [
                    $session->session_id,
                    $session->deceased_name,
                    $session->deceased_nric,
                    $session->status,
                    $session->admin_status,
                    $session->created_at,
                    $session->guest_email,
                    $session->notification_requested ? 'Yes' : 'No',
                    $session->email_sent_at,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}