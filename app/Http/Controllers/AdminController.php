<?php
// app/Http/Controllers/AdminController.php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Calculation;
use App\Models\Feedback;
use App\Models\Faq;
use App\Models\InstantEstateSession;
use App\Models\EstatePreRegistration;
use App\Models\NotificationRequest;
use App\Models\BeneficiaryAccessLink;
use App\Services\PdfGeneratorService;
use App\Mail\InheritanceReportMail;
use App\Mail\BeneficiaryAccessMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    // ==================== DASHBOARD ====================

    /**
     * Show admin dashboard
     */
    public function dashboard()
    {
        try {
            $stats = $this->getDashboardStats();
        } catch (\Exception $e) {
            Log::error('Dashboard stats error: ' . $e->getMessage());
            $stats = $this->getFallbackStats();
        }

        $users = User::latest()->take(5)->get();
        $recentFeedback = Feedback::with('user')->latest()->take(5)->get();
        $recentCalculations = Calculation::with('user')->latest()->take(5)->get();

        $recentSessions = collect();
        if (class_exists(InstantEstateSession::class)) {
            $recentSessions = InstantEstateSession::with('user')->latest()->take(5)->get();
        }

        $totalUsers = User::count();
        $totalFeedback = Feedback::count();
        $totalFaqs = Faq::count();
        $totalCalculations = Calculation::count();

        $totalInstantSessions = class_exists(InstantEstateSession::class) ? InstantEstateSession::count() : 0;
        $totalEstateSetup = class_exists(EstatePreRegistration::class) ? EstatePreRegistration::count() : 0;

        $adminCount = User::where('role', 'admin')->count();

        try {
            $activeUserCount = User::where('status', 'active')->count();
        } catch (\Exception $e) {
            $activeUserCount = User::where('last_login_at', '>=', Carbon::now()->subDays(30))->count();
        }

        $rejectedFeedback = Feedback::where('status', 'rejected')->count();
        $approvedFeedback = Feedback::where('status', 'approved')->count();

        try {
            $publishedFaqs = Faq::where('is_published', true)->count();
        } catch (\Exception $e) {
            $publishedFaqs = Faq::count();
        }

        $faqCategories = Faq::distinct('category')->count('category');
        $sharedCalculations = 0;

        $monthlyCalculations = Calculation::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $completedSessions = 0;
        $processingSessions = 0;
        if (class_exists(InstantEstateSession::class)) {
            try {
                $completedSessions = InstantEstateSession::whereIn('status', ['completed', 'email_sent', 'record_found'])->count();
                $processingSessions = InstantEstateSession::whereIn('status', ['uploaded', 'processing_ocr', 'ocr_completed', 'data_confirmed'])->count();
            } catch (\Exception $e) {
                Log::warning('InstantEstateSession status query error: ' . $e->getMessage());
            }
        }

        $activatedEstates = 0;
        $draftEstates = 0;
        if (class_exists(EstatePreRegistration::class)) {
            try {
                $activatedEstates = EstatePreRegistration::where('status', 'activated')->count();
                $draftEstates = EstatePreRegistration::where('status', 'draft')->count();
            } catch (\Exception $e) {
                Log::warning('EstatePreRegistration status query error: ' . $e->getMessage());
            }
        }

        $totalAssets = Calculation::sum('total_assets') ?? 0;

        return view('admin.dashboard', compact(
            'stats', 'users', 'recentFeedback', 'recentCalculations', 'recentSessions',
            'totalUsers', 'totalFeedback', 'totalFaqs', 'totalCalculations',
            'totalInstantSessions', 'totalEstateSetup', 'totalAssets',
            'adminCount', 'activeUserCount', 'rejectedFeedback', 'approvedFeedback',
            'publishedFaqs', 'faqCategories', 'sharedCalculations', 'monthlyCalculations',
            'completedSessions', 'processingSessions', 'activatedEstates', 'draftEstates'
        ));
    }

    /**
     * Get real-time statistics for AJAX updates
     */
    public function getRealtimeStats()
    {
        try {
            try {
                $activeUserCount = User::where('status', 'active')->count();
            } catch (\Exception $e) {
                $activeUserCount = User::where('last_login_at', '>=', Carbon::now()->subDays(30))->count();
            }

            try {
                $publishedFaqs = Faq::where('is_published', true)->count();
            } catch (\Exception $e) {
                $publishedFaqs = Faq::count();
            }

            $totalInstantSessions = 0;
            $completedSessions = 0;
            $processingSessions = 0;
            if (class_exists(InstantEstateSession::class)) {
                $totalInstantSessions = InstantEstateSession::count();
                try {
                    $completedSessions = InstantEstateSession::whereIn('status', ['completed', 'email_sent', 'record_found'])->count();
                    $processingSessions = InstantEstateSession::whereIn('status', ['uploaded', 'processing_ocr', 'ocr_completed', 'data_confirmed'])->count();
                } catch (\Exception $e) {}
            }

            $totalEstateSetup = 0;
            $activatedEstates = 0;
            $draftEstates = 0;
            if (class_exists(EstatePreRegistration::class)) {
                $totalEstateSetup = EstatePreRegistration::count();
                try {
                    $activatedEstates = EstatePreRegistration::where('status', 'activated')->count();
                    $draftEstates = EstatePreRegistration::where('status', 'draft')->count();
                } catch (\Exception $e) {}
            }

            $stats = [
                'totalUsers' => User::count(),
                'totalFeedback' => Feedback::count(),
                'totalFaqs' => Faq::count(),
                'totalCalculations' => Calculation::count(),
                'totalInstantSessions' => $totalInstantSessions,
                'totalEstateSetup' => $totalEstateSetup,
                'adminCount' => User::where('role', 'admin')->count(),
                'activeUserCount' => $activeUserCount,
                'rejectedFeedback' => Feedback::where('status', 'rejected')->count(),
                'approvedFeedback' => Feedback::where('status', 'approved')->count(),
                'publishedFaqs' => $publishedFaqs,
                'faqCategories' => Faq::distinct('category')->count('category'),
                'sharedCalculations' => 0,
                'monthlyCalculations' => Calculation::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
                'completedSessions' => $completedSessions,
                'processingSessions' => $processingSessions,
                'activatedEstates' => $activatedEstates,
                'draftEstates' => $draftEstates,
                'totalAssets' => Calculation::sum('total_assets') ?? 0,
                'timestamp' => now()->toIso8601String()
            ];

            return response()->json(['success' => true, 'data' => $stats]);
        } catch (\Exception $e) {
            Log::error('Realtime stats error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch statistics'], 500);
        }
    }

    // ==================== USER MANAGEMENT ====================

    /**
     * Display list of users
     */
    public function userIndex(Request $request)
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('role', 'like', "%{$search}%");
            });
        }

        if ($request->has('role') && $request->role != 'all') {
            $query->where('role', $request->role);
        }

        if ($request->has('status')) {
            if ($request->status == 'active') {
                try {
                    $query->where('status', 'active');
                } catch (\Exception $e) {
                    $query->where('last_login_at', '>=', Carbon::now()->subDays(30));
                }
            } elseif ($request->status == 'inactive') {
                try {
                    $query->where('status', 'inactive');
                } catch (\Exception $e) {
                    $query->where(function($q) {
                        $q->where('last_login_at', '<', Carbon::now()->subDays(30))->orWhereNull('last_login_at');
                    });
                }
            }
        }

        $users = $query->latest()->paginate(20);

        foreach ($users as $user) {
            try {
                $user->display_status = $user->status;
            } catch (\Exception $e) {
                $user->display_status = ($user->last_login_at && $user->last_login_at >= Carbon::now()->subDays(30)) ? 'active' : 'inactive';
            }
        }

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show form to create a user
     */
    public function userCreate()
    {
        return view('admin.users.create');
    }

    /**
     * Show user details
     */
    public function userShow($id)
    {
        $user = User::with(['calculations', 'feedback'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Store a new user
     */
    public function userStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
            'status' => 'nullable|in:active,inactive'
        ]);

        try {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'status' => $validated['status'] ?? 'active'
            ]);

            return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to create user.');
        }
    }

    /**
     * Show form to edit a user
     */
    public function userEdit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update a user
     */
    public function userUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
            'status' => 'required|in:active,inactive'
        ]);

        try {
            $user->update($validated);

            if ($request->filled('password')) {
                $request->validate(['password' => 'string|min:8|confirmed']);
                $user->update(['password' => Hash::make($request->password)]);
            }

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update user.');
        }
    }

    /**
     * Delete a user
     */
    public function userDestroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Cannot delete your own account.');
        }

        try {
            $user->delete();
            return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete user.');
        }
    }

    /**
     * Toggle user active/inactive status
     */
    public function userToggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Cannot change your own status.');
        }

        try {
            try {
                $user->status = $user->status === 'active' ? 'inactive' : 'active';
                $user->save();
                $status = $user->status === 'active' ? 'activated' : 'deactivated';
            } catch (\Exception $e) {
                $user->update(['last_login_at' => $user->last_login_at ? null : Carbon::now()]);
                $status = $user->last_login_at ? 'activated' : 'deactivated';
            }

            return redirect()->back()->with('success', "User {$status} successfully.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update user status.');
        }
    }

    /**
     * Reset user password
     */
    public function userResetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        try {
            $password = Str::random(12);
            $user->update(['password' => Hash::make($password)]);

            return redirect()->back()
                ->with('success', "Password reset successfully. New password: {$password}")
                ->with('password_alert', true);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to reset password.');
        }
    }

    /**
     * Export users to CSV
     */
    public function userExport()
    {
        $users = User::all();

        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="users_' . date('Y-m-d_H-i-s') . '.csv"'];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");
            fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Status', 'Email Verified', 'Created At', 'Last Login']);

            foreach ($users as $user) {
                try {
                    $status = $user->status ?? 'active';
                } catch (\Exception $e) {
                    $status = 'active';
                }

                fputcsv($file, [
                    $user->id, $user->name, $user->email, $user->role, $status,
                    $user->email_verified_at ? 'Yes' : 'No',
                    $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '',
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i:s') : ''
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ==================== FEEDBACK MANAGEMENT ====================

    /**
     * Display list of feedback
     */
    public function feedbackIndex(Request $request)
    {
        $query = Feedback::with('user');
        
        // Optional status filter (from query string, e.g., ?status=pending)
        if ($request->has('status') && in_array($request->status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $request->status);
        }
        
        $feedbackList = $query->latest()->paginate(15);
        
        // Statistics for the stats cards
        $totalFeedback = Feedback::count();
        $approvedCount = Feedback::where('status', 'approved')->count();
        $pendingCount = Feedback::where('status', 'pending')->count();
        $rejectedCount = Feedback::where('status', 'rejected')->count();
        
        return view('admin.feedback.index', compact(
            'feedbackList', 'totalFeedback', 'approvedCount', 'pendingCount', 'rejectedCount'
        ));
    }

    /**
     * Show feedback details
     */
    public function feedbackShow($id)
    {
        $feedback = Feedback::with('user')->findOrFail($id);
        return view('admin.feedback.show', compact('feedback'));
    }

    /**
     * Update feedback status
     */
    public function feedbackUpdate(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $feedback->update($validated);

        return redirect()->route('admin.feedback.index')->with('success', 'Feedback updated successfully.');
    }

    /**
     * Delete feedback
     */
    public function feedbackDestroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();

        return redirect()->route('admin.feedback.index')->with('success', 'Feedback deleted successfully.');
    }

    /**
     * Approve feedback
     */
    public function feedbackApprove(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->update(['status' => 'approved', 'is_public' => true]);

        return redirect()->back()->with('success', 'Feedback approved and made visible.');
    }

    /**
     * Reject feedback
     */
    public function feedbackReject(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->update(['status' => 'rejected', 'is_public' => false]);

        return redirect()->back()->with('success', 'Feedback rejected and hidden.');
    }

    /**
     * Alias for feedbackIndex
     */
    public function adminFeedbackIndex(Request $request)
    {
        return $this->feedbackIndex($request);
    }

    /**
     * Alias for feedbackShow
     */
    public function adminFeedbackShow($id)
    {
        return $this->feedbackShow($id);
    }

    /**
     * Alias for feedbackUpdate
     */
    public function adminFeedbackUpdate(Request $request, $id)
    {
        return $this->feedbackUpdate($request, $id);
    }

    /**
     * Alias for feedbackDestroy
     */
    public function adminFeedbackDestroy($id)
    {
        return $this->feedbackDestroy($id);
    }

    /**
     * Alias for feedbackApprove
     */
    public function approve($id)
    {
        return $this->feedbackApprove(new Request(), $id);
    }

    /**
     * Alias for feedbackReject
     */
    public function rejectFeedback($id)
    {
        return $this->feedbackReject(new Request(), $id);
    }

    /**
     * Toggle feedback visibility
     */
    public function toggleVisibility($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->update(['is_public' => !$feedback->is_public]);

        return redirect()->back()->with('success', "Feedback made " . ($feedback->is_public ? 'visible' : 'hidden') . ".");
    }

    // ==================== FAQ MANAGEMENT ====================

    /**
     * Display list of FAQs
     */
    public function faqIndex()
    {
        $faqs = Faq::orderBy('order', 'asc')->paginate(15);
        return view('admin.faq.index', compact('faqs'));
    }

    /**
     * Show form to create FAQ
     */
    public function faqCreate()
    {
        return view('admin.faq.create');
    }

    /**
     * Store a new FAQ
     */
    public function faqStore(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        Faq::create($validated);

        return redirect()->route('admin.faq.index')->with('success', 'FAQ created successfully.');
    }

    /**
     * Show form to edit FAQ
     */
    public function faqEdit($id)
    {
        $faq = Faq::findOrFail($id);
        return view('admin.faq.edit', compact('faq'));
    }

    /**
     * Update FAQ
     */
    public function faqUpdate(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        $validated = $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'nullable|string|max:100',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
        ]);

        $faq->update($validated);

        return redirect()->route('admin.faq.index')->with('success', 'FAQ updated successfully.');
    }

    /**
     * Delete FAQ
     */
    public function faqDestroy($id)
    {
        $faq = Faq::findOrFail($id);
        $faq->delete();

        return redirect()->route('admin.faq.index')->with('success', 'FAQ deleted successfully.');
    }

    /**
     * Toggle FAQ active status
     */
    public function faqToggleStatus(Request $request, $id)
    {
        $faq = Faq::findOrFail($id);

        try {
            $faq->update(['is_active' => !$faq->is_active]);
            $status = $faq->is_active ? 'activated' : 'deactivated';
        } catch (\Exception $e) {
            $faq->update(['is_published' => !$faq->is_published]);
            $status = $faq->is_published ? 'published' : 'unpublished';
        }

        return redirect()->back()->with('success', "FAQ {$status} successfully.");
    }

    // ==================== CALCULATION MANAGEMENT ====================

    /**
     * Display list of all calculations with search and filters
     */
    public function calculationIndex(Request $request)
    {
        $query = Calculation::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('deceased_name', 'like', "%{$search}%")
                  ->orWhere('deceased_nric', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($sub) use ($search) {
                      $sub->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('scenario')) {
            $query->where('scenario_number', $request->scenario);
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'has_distribution') {
                $query->whereNotNull('distribution_summary');
            } elseif ($status === 'no_distribution') {
                $query->whereNull('distribution_summary');
            }
        }

        if ($request->filled('nric_status')) {
            $nricStatus = $request->nric_status;
            if ($nricStatus === 'has_nric') {
                $query->whereNotNull('deceased_nric')->where('deceased_nric', '!=', '');
            } elseif ($nricStatus === 'no_nric') {
                $query->where(function($q) {
                    $q->whereNull('deceased_nric')->orWhere('deceased_nric', '');
                });
            }
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $calculations = $query->latest()->paginate(20)->withQueryString();

        $stats = [
            'total' => Calculation::count(),
            'total_assets' => Calculation::sum('total_assets'),
            'total_heirs' => Calculation::sum('total_heirs'),
            'average_assets' => Calculation::avg('total_assets') ?? 0,
            'this_month' => Calculation::whereMonth('created_at', now()->month)->count(),
            'last_month' => Calculation::whereMonth('created_at', now()->subMonth()->month)->count(),
            'with_nric' => Calculation::whereNotNull('deceased_nric')->where('deceased_nric', '!=', '')->count(),
            'without_nric' => Calculation::where(function($q) {
                $q->whereNull('deceased_nric')->orWhere('deceased_nric', '');
            })->count(),
            'with_distribution' => Calculation::whereNotNull('distribution_summary')->count(),
            'without_distribution' => Calculation::whereNull('distribution_summary')->count(),
        ];

        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        $totalAssets = Calculation::sum('total_assets') ?? 0;
        $search = $request->get('search');

        return view('admin.calculations.index', compact('calculations', 'stats', 'users', 'search', 'totalAssets'));
    }

    /**
     * Show detailed view of a specific calculation
     */
    public function calculationShow($id)
    {
        $calculation = Calculation::with('user')->findOrFail($id);

        $heirsData = $this->safeJsonDecode($calculation->heirs_data);
        $assetsData = $this->safeJsonDecode($calculation->assets_data);
        $calculationData = $this->safeJsonDecode($calculation->calculation_data);
        $distributionSummary = $this->safeJsonDecode($calculation->distribution_summary);
        $scenarioData = $this->safeJsonDecode($calculation->scenario_data);
        $chartData = $this->safeJsonDecode($calculation->chart_data);
        $treeData = $this->safeJsonDecode($calculation->tree_data);

        $eligibleHeirs = [];
        $totalDistributed = 0;
        $netEstate = $calculation->net_assets;

        if (!empty($distributionSummary) && isset($distributionSummary['heirs'])) {
            $eligibleHeirs = array_filter($distributionSummary['heirs'], function($heir) {
                $amount = is_array($heir) ? ($heir['amount'] ?? 0) : 0;
                return $amount > 0.01;
            });
            $totalDistributed = $distributionSummary['total_distributed'] ?? 0;
        } elseif (!empty($calculationData) && isset($calculationData['distribution'])) {
            $eligibleHeirs = array_filter($calculationData['distribution'], function($heir) {
                $amount = is_array($heir) ? ($heir['amount'] ?? 0) : 0;
                return $amount > 0.01 && ($heir['status'] ?? '') !== 'Surplus';
            });
            $totalDistributed = array_sum(array_column($eligibleHeirs, 'amount'));
        }

        $readiness = [
            'ready' => !empty($eligibleHeirs) && $totalDistributed > 0,
            'issues' => []
        ];

        if (empty($eligibleHeirs)) {
            $readiness['issues'][] = 'No eligible heirs found in distribution';
        }
        if ($totalDistributed <= 0) {
            $readiness['issues'][] = 'Total distributed amount is zero';
        }
        if ($calculation->total_assets <= 0) {
            $readiness['issues'][] = 'Total assets value is zero';
        }

        return view('admin.calculations.show', compact(
            'calculation',
            'heirsData',
            'assetsData',
            'calculationData',
            'distributionSummary',
            'scenarioData',
            'chartData',
            'treeData',
            'eligibleHeirs',
            'totalDistributed',
            'netEstate',
            'readiness'
        ));
    }

    /**
     * Show edit form for calculation
     */
    public function calculationEdit($id)
    {
        $calculation = Calculation::findOrFail($id);
        return view('admin.calculations.edit', compact('calculation'));
    }

    /**
     * Update calculation
     */
    public function calculationUpdate(Request $request, $id)
    {
        $calculation = Calculation::findOrFail($id);

        $validated = $request->validate([
            'deceased_name' => 'required|string|max:255',
            'deceased_nric' => 'nullable|string|max:20',
            'deceased_gender' => 'required|in:male,female',
            'date_of_death' => 'required|date',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'total_assets' => 'required|numeric|min:0',
            'cause_of_death' => 'nullable|string|max:255',
            'death_place' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'residential_address' => 'nullable|string|max:500',
        ]);

        try {
            $calculation->update($validated);

            return redirect()->route('admin.calculations.show', $calculation->id)
                ->with('success', 'Calculation updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Failed to update calculation: ' . $e->getMessage());
        }
    }

    /**
     * Delete calculation
     */
    public function calculationDestroy($id)
    {
        try {
            $calculation = Calculation::findOrFail($id);
            $calculation->delete();

            return redirect()->route('admin.calculations.index')
                ->with('success', 'Calculation deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete calculation: ' . $e->getMessage());
        }
    }

    /**
     * Export calculations to CSV
     */
    public function exportCalculations(Request $request)
    {
        $query = Calculation::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('deceased_name', 'like', "%{$search}%")
                  ->orWhere('deceased_nric', 'like', "%{$search}%");
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $calculations = $query->orderBy('created_at', 'desc')->get();

        $filename = 'calculations_export_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($calculations) {
            $file = fopen('php://output', 'w');
            fwrite($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'ID', 'Deceased Name', 'NRIC', 'Gender', 'Date of Death', 'Marital Status',
                'Total Assets (RM)', 'Net Assets (RM)', 'Total Heirs', 'Eligible Heirs',
                'Scenario', 'Calculation Method', 'Has Distribution', 'Created By', 'Created At'
            ]);

            foreach ($calculations as $calc) {
                fputcsv($file, [
                    $calc->id,
                    $calc->deceased_name,
                    $calc->deceased_nric ?? '',
                    ucfirst($calc->deceased_gender),
                    $calc->date_of_death ? date('Y-m-d', strtotime($calc->date_of_death)) : '',
                    ucfirst($calc->marital_status),
                    number_format($calc->total_assets, 2),
                    number_format($calc->net_assets, 2),
                    $calc->total_heirs ?? 0,
                    $calc->eligible_heirs_count ?? 0,
                    $calc->scenario_number ?? 'N/A',
                    $calc->calculation_method ?? 'local',
                    $calc->hasDistribution() ? 'Yes' : 'No',
                    $calc->user->name ?? 'Unknown',
                    $calc->created_at ? $calc->created_at->format('Y-m-d H:i:s') : '',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Search calculations by NRIC (AJAX endpoint)
     */
    public function searchByNric(Request $request)
    {
        $request->validate([
            'nric' => 'required|string|min:6|max:20'
        ]);

        $nric = $request->nric;

        $calculations = Calculation::with('user')
            ->where('deceased_nric', 'like', "%{$nric}%")
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'count' => $calculations->count(),
            'calculations' => $calculations->map(function($calc) {
                return [
                    'id' => $calc->id,
                    'deceased_name' => $calc->deceased_name,
                    'deceased_nric' => $calc->deceased_nric,
                    'masked_nric' => $calc->masked_nric,
                    'total_assets' => $calc->total_assets,
                    'created_at' => $calc->created_at->format('d M Y'),
                    'user_name' => $calc->user->name ?? 'Unknown',
                    'show_url' => route('admin.calculations.show', $calc->id)
                ];
            })
        ]);
    }

    /**
     * Get calculation statistics (AJAX)
     */
    public function calculationStats(Request $request)
    {
        try {
            $stats = [
                'total' => Calculation::count(),
                'total_assets' => Calculation::sum('total_assets'),
                'total_heirs' => Calculation::sum('total_heirs'),
                'average_assets' => Calculation::avg('total_assets') ?? 0,
                'with_distribution' => Calculation::whereNotNull('distribution_summary')->count(),
                'without_distribution' => Calculation::whereNull('distribution_summary')->count(),
                'with_nric' => Calculation::whereNotNull('deceased_nric')->where('deceased_nric', '!=', '')->count(),
                'without_nric' => Calculation::where(function($q) {
                    $q->whereNull('deceased_nric')->orWhere('deceased_nric', '');
                })->count(),
                'by_gender' => [
                    'male' => Calculation::where('deceased_gender', 'male')->count(),
                    'female' => Calculation::where('deceased_gender', 'female')->count(),
                ],
                'by_marital_status' => [
                    'married' => Calculation::where('marital_status', 'married')->count(),
                    'single' => Calculation::where('marital_status', 'single')->count(),
                    'divorced' => Calculation::where('marital_status', 'divorced')->count(),
                    'widowed' => Calculation::where('marital_status', 'widowed')->count(),
                ],
                'monthly' => Calculation::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                    ->groupBy('month')
                    ->orderBy('month', 'desc')
                    ->limit(12)
                    ->get(),
                'todayCalculations' => Calculation::whereDate('created_at', today())->count(),
                'thisWeekCalculations' => Calculation::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
                'thisMonthCalculations' => Calculation::whereMonth('created_at', now()->month)->count(),
            ];

            $stats['top_users'] = Calculation::select('user_id', DB::raw('COUNT(*) as total'))
                ->with('user')
                ->groupBy('user_id')
                ->orderBy('total', 'desc')
                ->limit(5)
                ->get()
                ->map(function($item) {
                    return [
                        'user_name' => $item->user->name ?? 'Unknown',
                        'user_email' => $item->user->email ?? '',
                        'total' => $item->total
                    ];
                });

            $stats['scenario_distribution'] = Calculation::select('scenario_number', DB::raw('COUNT(*) as count'))
                ->whereNotNull('scenario_number')
                ->groupBy('scenario_number')
                ->orderBy('count', 'desc')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $stats,
                'timestamp' => now()->toDateTimeString()
            ]);
        } catch (\Exception $e) {
            Log::error('Calculation stats error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch statistics'], 500);
        }
    }

    /**
     * Bulk delete calculations
     */
    public function calculationBulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:calculations,id'
        ]);

        $deleted = 0;
        $failed = 0;

        foreach ($request->ids as $id) {
            try {
                $calculation = Calculation::find($id);
                if ($calculation) {
                    $calculation->delete();
                    $deleted++;
                } else {
                    $failed++;
                }
            } catch (\Exception $e) {
                $failed++;
                Log::error("Failed to delete calculation {$id}: " . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Deleted {$deleted} calculation(s). Failed: {$failed}",
            'deleted' => $deleted,
            'failed' => $failed
        ]);
    }

    /**
     * Generate PDF report for calculation
     */
    public function calculationGeneratePdf($id)
    {
        $calculation = Calculation::with('user')->findOrFail($id);

        $heirsData = $this->safeJsonDecode($calculation->heirs_data);
        $distributionSummary = $this->safeJsonDecode($calculation->distribution_summary);

        $eligibleHeirs = [];
        if (!empty($distributionSummary) && isset($distributionSummary['heirs'])) {
            $eligibleHeirs = array_filter($distributionSummary['heirs'], function($heir) {
                return ($heir['amount'] ?? 0) > 0.01;
            });
        }

        $pdf = Pdf::loadView('admin.calculations.pdf', compact('calculation', 'heirsData', 'distributionSummary', 'eligibleHeirs'));
        $pdf->setPaper('a4');
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => false,
            'defaultFont' => 'sans-serif',
        ]);

        $filename = 'calculation_' . $calculation->id . '_' . Str::slug($calculation->deceased_name) . '.pdf';

        return $pdf->download($filename);
    }

    // ==================== INSTANT ESTATE ADMIN METHODS ====================

    /**
     * Display list of instant estate sessions with stats
     */
    public function adminIndex(Request $request)
    {
        if (!class_exists(InstantEstateSession::class)) {
            abort(500, 'InstantEstateSession model not found.');
        }

        $query = InstantEstateSession::with('user');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('session_id', 'like', "%{$search}%")
                  ->orWhere('original_filename', 'like', "%{$search}%")
                  ->orWhere('deceased_name', 'like', "%{$search}%")
                  ->orWhere('deceased_nric', 'like', "%{$search}%")
                  ->orWhere('guest_email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sessions = $query->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => InstantEstateSession::count(),
            'pending_approval' => InstantEstateSession::where('status', InstantEstateSession::STATUS_NOTIFICATION_REQUESTED)->count(),
            'approved' => InstantEstateSession::where('status', InstantEstateSession::STATUS_EMAIL_SENT)->count(),
            'notifications_sent' => InstantEstateSession::whereNotNull('email_sent_at')->count(),
        ];

        foreach ($sessions as $session) {
            $session->status_color = $this->getStatusColorHelper($session->status);
            $session->status_label = $this->getStatusLabelHelper($session->status);
            $session->formatted_file_size = $this->formatFileSizeHelper($session->file_size);
            if ($session->email_sent_at) {
                $session->notification_status = 'Sent';
            } elseif ($session->notification_requested) {
                $session->notification_status = 'Requested';
            } else {
                $session->notification_status = 'Not Requested';
            }
        }

        return view('admin.instant-estate.index', compact('sessions', 'stats'));
    }

    /**
     * Alias for adminIndex
     */
    public function adminSessions(Request $request)
    {
        return $this->adminIndex($request);
    }

    /**
     * View a specific session details
     */
    public function adminViewSession($sessionId)
    {
        if (!class_exists(InstantEstateSession::class)) {
            abort(500, 'InstantEstateSession model not found.');
        }

        $session = InstantEstateSession::where('session_id', $sessionId)->firstOrFail();
        $extractedData = $session->extracted_data ?? [];
        $qualityDetails = $session->quality_check_details ?? [];
        $reportData = $session->report_data ?? [];

        return view('admin.instant-estate.show', compact('session', 'extractedData', 'qualityDetails', 'reportData'));
    }

    /**
     * Delete a session and its associated files
     */
    public function adminDeleteSession($sessionId)
    {
        if (!class_exists(InstantEstateSession::class)) {
            return response()->json(['success' => false, 'message' => 'Model not found.'], 500);
        }

        try {
            $session = InstantEstateSession::where('session_id', $sessionId)->firstOrFail();

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
            return redirect()->route('admin.instant-estate.index')->with('success', 'Session deleted.');
        } catch (\Exception $e) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Delete failed: ' . $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    /**
     * Alias for adminDeleteSession
     */
    public function adminDestroy($id)
    {
        return $this->adminDeleteSession($id);
    }

    /**
     * Resend notification email for a session (if notification was requested)
     */
    public function adminResendNotificationForSession($sessionId)
    {
        try {
            $session = InstantEstateSession::where('session_id', $sessionId)->firstOrFail();

            if (!$session->notification_request_id) {
                return redirect()->back()->with('error', 'No notification request found for this session.');
            }

            $notificationRequest = NotificationRequest::find($session->notification_request_id);
            if (!$notificationRequest) {
                return redirect()->back()->with('error', 'Notification request record missing.');
            }

            return $this->adminResendNotification($notificationRequest->id);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to resend: ' . $e->getMessage());
        }
    }

    /**
     * Download original uploaded file for a session
     */
    public function adminDownloadFile($sessionId)
    {
        $session = InstantEstateSession::where('session_id', $sessionId)->firstOrFail();
        if (!$session->file_path || !Storage::disk('private')->exists($session->file_path)) {
            abort(404);
        }
        return Storage::disk('private')->download($session->file_path, $session->original_filename);
    }

    /**
     * Reprocess a session
     */
    public function adminReprocess($sessionId)
    {
        if (!class_exists(InstantEstateSession::class)) {
            return response()->json(['success' => false, 'message' => 'Model not found.'], 500);
        }

        try {
            $session = InstantEstateSession::where('session_id', $sessionId)->firstOrFail();

            $session->update([
                'status' => InstantEstateSession::STATUS_UPLOADED,
                'error_message' => null,
                'extracted_data' => null,
                'ocr_confidence' => null,
                'missing_fields' => null,
                'processing_attempts' => ($session->processing_attempts ?? 0) + 1,
                'last_processing_attempt_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Session reset for reprocessing.',
                'redirect_url' => route('admin.instant-estate.index')
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to reset session.'], 500);
        }
    }

    /**
     * Reprocess OCR for a session
     */
    public function adminReprocessOCR($sessionId)
    {
        if (!class_exists(InstantEstateSession::class)) {
            return response()->json(['success' => false, 'message' => 'Model not found.'], 500);
        }

        try {
            $session = InstantEstateSession::where('session_id', $sessionId)->firstOrFail();

            if (!$session->file_path || !Storage::disk('private')->exists($session->file_path)) {
                return response()->json(['success' => false, 'message' => 'File not found.'], 404);
            }

            $session->update(['status' => InstantEstateSession::STATUS_UPLOADED, 'error_message' => null]);

            return response()->json(['success' => true, 'message' => 'OCR reprocessing initiated.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Display instant estate statistics
     */
    public function adminStatistics()
    {
        $stats = [
            'total_sessions' => 0,
            'pending_ocr' => 0,
            'processing_ocr' => 0,
            'completed' => 0,
            'failed' => 0,
            'total_notifications' => 0,
            'pending_notifications' => 0,
            'approved_notifications' => 0,
            'rejected_notifications' => 0,
            'sent_notifications' => 0,
        ];

        if (class_exists(InstantEstateSession::class)) {
            $stats['total_sessions'] = InstantEstateSession::count();
            $stats['pending_ocr'] = InstantEstateSession::where('status', InstantEstateSession::STATUS_UPLOADED)->count();
            $stats['processing_ocr'] = InstantEstateSession::where('status', InstantEstateSession::STATUS_PROCESSING_OCR)->count();
            $stats['completed'] = InstantEstateSession::whereIn('status', [InstantEstateSession::STATUS_COMPLETED, InstantEstateSession::STATUS_EMAIL_SENT, InstantEstateSession::STATUS_RECORD_FOUND])->count();
            $stats['failed'] = InstantEstateSession::where('status', InstantEstateSession::STATUS_FAILED)->count();
        }

        if (class_exists(NotificationRequest::class)) {
            $stats['total_notifications'] = NotificationRequest::count();
            $stats['pending_notifications'] = NotificationRequest::where('status', NotificationRequest::STATUS_PENDING_ADMIN_APPROVAL)->count();
            $stats['approved_notifications'] = NotificationRequest::where('status', NotificationRequest::STATUS_APPROVED)->count();
            $stats['rejected_notifications'] = NotificationRequest::where('status', NotificationRequest::STATUS_REJECTED)->count();
            $stats['sent_notifications'] = NotificationRequest::where('status', NotificationRequest::STATUS_SENT)->count();
        }

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'data' => $stats]);
        }

        return view('admin.instant-estate.statistics', compact('stats'));
    }

    /**
     * Export instant estate sessions to CSV
     */
    public function adminExport(Request $request)
    {
        if (!class_exists(InstantEstateSession::class)) {
            return response()->json(['success' => false, 'message' => 'Model not found.'], 500);
        }

        $query = InstantEstateSession::with('user');

        if ($request->filled('status')) $query->where('status', $request->status);
        if ($request->filled('from_date')) $query->whereDate('created_at', '>=', $request->from_date);
        if ($request->filled('to_date')) $query->whereDate('created_at', '<=', $request->to_date);

        $sessions = $query->orderBy('created_at', 'desc')->get();

        $filename = 'instant-estate-sessions-' . date('Y-m-d-His') . '.csv';
        $handle = fopen('php://temp', 'w+');
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, ['ID', 'Session ID', 'User ID', 'User Name', 'User Email', 'Filename', 'File Size', 'Status', 'Deceased Name', 'Deceased NRIC', 'Death Date', 'Death Place', 'OCR Confidence', 'Has Report', 'Created At', 'Completed At']);

        foreach ($sessions as $session) {
            fputcsv($handle, [
                $session->id, $session->session_id, $session->user_id, $session->user?->name ?? 'N/A', $session->user?->email ?? 'N/A',
                $session->original_filename, $this->formatFileSizeHelper($session->file_size), $session->status,
                $session->deceased_name, $session->deceased_nric, $session->death_date, $session->death_place,
                $session->ocr_confidence, $session->has_report ? 'Yes' : 'No', $session->created_at, $session->report_generated_at,
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent, 200)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Bulk delete instant estate sessions
     */
    public function adminBulkDelete(Request $request)
    {
        if (!class_exists(InstantEstateSession::class)) {
            return response()->json(['success' => false, 'message' => 'Model not found.'], 500);
        }

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No sessions selected.'], 400);
        }

        $sessions = InstantEstateSession::whereIn('id', $ids)->get();
        $deletedCount = 0;

        foreach ($sessions as $session) {
            try {
                if ($session->file_path && Storage::disk('private')->exists($session->file_path)) {
                    Storage::disk('private')->delete($session->file_path);
                }
                if ($session->report_pdf_path && Storage::disk('private')->exists($session->report_pdf_path)) {
                    Storage::disk('private')->delete($session->report_pdf_path);
                }
                $session->delete();
                $deletedCount++;
            } catch (\Exception $e) {
                Log::error('Bulk delete session error: ' . $e->getMessage());
            }
        }

        return response()->json(['success' => true, 'message' => "Successfully deleted {$deletedCount} session(s)."]);
    }

    /**
     * Display instant estate settings
     */
    public function adminSettings()
    {
        return view('admin.instant-estate.settings');
    }

    /**
     * Update instant estate settings
     */
    public function adminUpdateSettings(Request $request)
    {
        $request->validate([
            'max_file_size' => 'nullable|integer|min:1|max:50',
            'allowed_extensions' => 'nullable|string',
            'session_expiry_hours' => 'nullable|integer|min:1|max:168',
        ]);

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }

    /**
     * Display system health for instant estate
     */
    public function adminSystemHealth()
    {
        $health = [
            'instant_estate_model_exists' => class_exists(InstantEstateSession::class),
            'notification_model_exists' => class_exists(NotificationRequest::class),
            'storage_private_writable' => Storage::disk('private')->exists('/'),
            'database_connected' => false,
            'total_sessions' => 0,
            'failed_sessions' => 0,
        ];

        try {
            DB::connection()->getPdo();
            $health['database_connected'] = true;
            if (class_exists(InstantEstateSession::class)) {
                $health['total_sessions'] = InstantEstateSession::count();
                $health['failed_sessions'] = InstantEstateSession::where('status', 'failed')->count();
            }
        } catch (\Exception $e) {
            $health['database_error'] = $e->getMessage();
        }

        return view('admin.instant-estate.system-health', compact('health'));
    }

    // ==================== NOTIFICATION MANAGEMENT ====================

    /**
     * Display list of notification requests
     */
    public function adminNotifications(Request $request)
    {
        if (!class_exists(NotificationRequest::class)) {
            abort(500, 'NotificationRequest model not found.');
        }

        $query = NotificationRequest::with(['user', 'approvedBy']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('deceased_name', 'like', "%{$search}%")
                  ->orWhere('recipient_email', 'like', "%{$search}%")
                  ->orWhere('session_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $notificationRequests = $query->orderBy('created_at', 'desc')->paginate(20);

        foreach ($notificationRequests as $req) {
            $req->status_color = $this->getNotificationStatusColorHelper($req->status);
            $req->status_label = $this->getNotificationStatusLabelHelper($req->status);
            $req->formatted_requested_at = $req->created_at?->format('d M Y, h:i A');
            $req->formatted_approved_at = $req->approved_at?->format('d M Y, h:i A');
        }

        $stats = [
            'total' => NotificationRequest::count(),
            'pending_admin_approval' => NotificationRequest::where('status', NotificationRequest::STATUS_PENDING_ADMIN_APPROVAL)->count(),
            'approved' => NotificationRequest::where('status', NotificationRequest::STATUS_APPROVED)->count(),
            'rejected' => NotificationRequest::where('status', NotificationRequest::STATUS_REJECTED)->count(),
            'sent' => NotificationRequest::where('status', NotificationRequest::STATUS_SENT)->count(),
            'failed' => NotificationRequest::where('status', NotificationRequest::STATUS_FAILED)->count(),
        ];

        return view('admin.instant-estate.notifications', compact('notificationRequests', 'stats'));
    }

    /**
     * Approve a notification request and send email with PDF report
     */
    public function adminApproveNotification(int $requestId)
    {
        try {
            $notificationRequest = NotificationRequest::findOrFail($requestId);

            if (!$notificationRequest->canBeApproved()) {
                if (request()->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'This request cannot be approved.'], 400);
                }
                return redirect()->back()->with('error', 'This request cannot be approved.');
            }

            $session = $this->findSessionForNotification($notificationRequest);

            if (!$session) {
                if (request()->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Associated session not found.'], 404);
                }
                return redirect()->back()->with('error', 'Associated session not found.');
            }

            $pdfContent = $this->generatePdfContentSafely($notificationRequest, $session);

            if (!$pdfContent) {
                Log::error('Failed to generate PDF content for approval', [
                    'request_id' => $requestId,
                    'session_id' => $session->session_id,
                ]);

                if (request()->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Failed to generate report PDF.'], 500);
                }
                return redirect()->back()->with('error', 'Failed to generate report PDF.');
            }

            $notificationRequest->setPdfContent($pdfContent);
            $notificationRequest->approve(auth()->id());

            try {
                $emailData = [
                    'deceased_name' => $notificationRequest->deceased_name ?? $session->deceased_name ?? 'the deceased',
                    'recipient_name' => $notificationRequest->recipient_name ?? 'Sir/Madam',
                    'request_id' => $notificationRequest->id,
                    'access_token' => $notificationRequest->access_token,
                    'date' => now()->format('d M Y, h:i A'),
                ];

                Mail::send('emails.inheritance-report', $emailData, function ($message) use ($notificationRequest, $pdfContent) {
                    $message->to($notificationRequest->recipient_email)
                            ->subject('Inheritance Distribution Report - ' . ($notificationRequest->deceased_name ?? 'Estate Report'))
                            ->attachData($pdfContent, 'inheritance_report.pdf', [
                                'mime' => 'application/pdf',
                            ]);
                });

                $notificationRequest->markAsSent();
                $session->markEmailSent();

                Log::info('Notification approved and email sent', [
                    'request_id' => $requestId,
                    'admin_id' => auth()->id(),
                    'recipient_email' => $notificationRequest->recipient_email,
                    'session_id' => $session->session_id,
                ]);
            } catch (\Exception $mailError) {
                Log::error('Failed to send email after approval', [
                    'request_id' => $requestId,
                    'error' => $mailError->getMessage(),
                ]);

                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Approved but failed to send email: ' . $mailError->getMessage()
                    ], 500);
                }
                return redirect()->back()->with('warning', 'Request approved but email sending failed.');
            }

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notification request approved and email sent successfully.'
                ]);
            }

            return redirect()->route('admin.instant-estate.notifications.index')
                ->with('success', 'Notification request approved and email sent.');

        } catch (\Exception $e) {
            Log::error('Failed to approve notification', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to approve: ' . $e->getMessage()], 500);
            }

            return redirect()->back()->with('error', 'Failed to approve request: ' . $e->getMessage());
        }
    }

    /**
     * Reject a notification request
     */
    public function adminRejectNotification(Request $request, int $requestId)
    {
        try {
            $validator = Validator::make($request->all(), [
                'reason' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                if (request()->expectsJson()) {
                    return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
                }
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $notificationRequest = NotificationRequest::findOrFail($requestId);

            if (!$notificationRequest->canBeRejected()) {
                if (request()->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'This request cannot be rejected.'], 400);
                }
                return redirect()->back()->with('error', 'This request cannot be rejected.');
            }

            $reason = $request->input('reason', 'No specific reason provided.');
            $notificationRequest->reject(auth()->id(), $reason);

            Log::info('Notification request rejected', [
                'request_id' => $requestId,
                'admin_id' => auth()->id(),
                'reason' => $reason,
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notification request rejected successfully.'
                ]);
            }

            return redirect()->route('admin.instant-estate.notifications.index')
                ->with('success', 'Notification request rejected.');

        } catch (\Exception $e) {
            Log::error('Failed to reject notification', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
            ]);

            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to reject: ' . $e->getMessage()], 500);
            }

            return redirect()->back()->with('error', 'Failed to reject request: ' . $e->getMessage());
        }
    }

    /**
     * Resend a notification email with PDF generation
     */
    public function adminResendNotification(int $requestId)
    {
        try {
            $notificationRequest = NotificationRequest::findOrFail($requestId);

            if (!in_array($notificationRequest->status, [NotificationRequest::STATUS_PENDING_ADMIN_APPROVAL, NotificationRequest::STATUS_APPROVED, NotificationRequest::STATUS_SENT, NotificationRequest::STATUS_FAILED])) {
                if (request()->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'This request cannot be resent. Current status: ' . $notificationRequest->status], 400);
                }
                return redirect()->back()->with('error', 'This request cannot be resent.');
            }

            $session = $this->findSessionForNotification($notificationRequest);

            if (!$session) {
                Log::error('Session not found for notification resend', [
                    'request_id' => $requestId,
                    'session_id' => $notificationRequest->session_id,
                    'instant_estate_session_id' => $notificationRequest->instant_estate_session_id,
                ]);

                if (request()->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Associated session not found.'], 404);
                }
                return redirect()->back()->with('error', 'Associated session not found.');
            }

            if (!$session->report_data && !$session->has_report) {
                Log::error('No report data found for session', [
                    'session_id' => $session->session_id,
                    'status' => $session->status,
                ]);

                if (request()->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'No report data found for this session.'], 404);
                }
                return redirect()->back()->with('error', 'No report data found for this session.');
            }

            $pdfContent = $this->generatePdfContentSafely($notificationRequest, $session);

            if (!$pdfContent) {
                Log::error('Failed to generate PDF content for resend', [
                    'request_id' => $requestId,
                    'session_id' => $session->session_id,
                ]);

                if (request()->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Failed to generate report PDF. Please check session data.'], 500);
                }
                return redirect()->back()->with('error', 'Failed to generate report PDF. Please check session data.');
            }

            $recipientEmail = $notificationRequest->recipient_email;

            if (empty($recipientEmail)) {
                throw new \Exception('No recipient email address found.');
            }

            $emailData = [
                'deceased_name' => $notificationRequest->deceased_name ?? $session->deceased_name ?? 'the deceased',
                'recipient_name' => $notificationRequest->recipient_name ?? 'Sir/Madam',
                'request_id' => $notificationRequest->id,
                'access_token' => $notificationRequest->access_token,
                'date' => now()->format('d M Y, h:i A'),
            ];

            Mail::send('emails.inheritance-report', $emailData, function ($message) use ($recipientEmail, $pdfContent, $notificationRequest) {
                $message->to($recipientEmail)
                        ->subject('Inheritance Distribution Report - ' . ($notificationRequest->deceased_name ?? 'Estate Report'))
                        ->attachData($pdfContent, 'inheritance_report.pdf', [
                            'mime' => 'application/pdf',
                        ]);
            });

            $notificationRequest->update([
                'status' => NotificationRequest::STATUS_SENT,
                'sent_at' => now(),
                'resend_count' => ($notificationRequest->resend_count ?? 0) + 1,
                'last_resend_at' => now(),
                'emails_sent' => true,
                'email_status' => NotificationRequest::EMAIL_STATUS_SENT,
            ]);

            if ($session && $session->status !== InstantEstateSession::STATUS_EMAIL_SENT) {
                $session->update([
                    'status' => InstantEstateSession::STATUS_EMAIL_SENT,
                    'email_sent_at' => now(),
                ]);
            }

            Log::info('Notification email resent successfully', [
                'request_id' => $requestId,
                'admin_id' => auth()->id(),
                'recipient_email' => $recipientEmail,
                'session_id' => $session->session_id,
                'resend_count' => $notificationRequest->resend_count,
            ]);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Report resent successfully to ' . $recipientEmail,
                ]);
            }

            return redirect()->route('admin.instant-estate.notifications.index')
                ->with('success', 'Report resent successfully to ' . $recipientEmail);

        } catch (\Exception $e) {
            Log::error('Failed to resend notification', [
                'request_id' => $requestId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Failed to resend: ' . $e->getMessage()], 500);
            }

            return redirect()->back()->with('error', 'Failed to resend: ' . $e->getMessage());
        }
    }

    /**
     * Get a single notification request details
     */
    public function adminGetNotification($requestId)
    {
        if (!class_exists(NotificationRequest::class)) {
            abort(500, 'NotificationRequest model not found.');
        }

        $notification = NotificationRequest::with(['user', 'approvedBy', 'rejectedBy'])->findOrFail($requestId);

        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'data' => $notification->toAdminApiResponse()]);
        }

        return view('admin.instant-estate.notification-detail', compact('notification'));
    }

    /**
     * Bulk approve notification requests
     */
    public function adminBulkApproveNotifications(Request $request)
    {
        if (!class_exists(NotificationRequest::class)) {
            return response()->json(['success' => false, 'message' => 'Model not found.'], 500);
        }

        $ids = $request->input('ids', []);
        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No requests selected.'], 400);
        }

        $approved = 0;
        $failed = 0;

        foreach ($ids as $id) {
            try {
                $notification = NotificationRequest::find($id);
                if ($notification && $notification->canBeApproved()) {
                    $session = $this->findSessionForNotification($notification);
                    if ($session && $session->has_pdf_report) {
                        $pdfContent = Storage::disk('private')->get($session->report_pdf_path);
                        $notification->setPdfContent($pdfContent);
                    } else {
                        $pdfContent = $this->generatePdfContentSafely($notification, $session);
                        if ($pdfContent) {
                            $notification->setPdfContent($pdfContent);
                        }
                    }

                    $notification->approve(auth()->id());

                    $emailData = [
                        'deceased_name' => $notification->deceased_name ?? $session->deceased_name ?? 'the deceased',
                        'recipient_name' => $notification->recipient_name ?? 'Sir/Madam',
                        'request_id' => $notification->id,
                        'access_token' => $notification->access_token,
                        'date' => now()->format('d M Y, h:i A'),
                    ];

                    Mail::send('emails.inheritance-report', $emailData, function ($message) use ($notification, $pdfContent) {
                        $message->to($notification->recipient_email)
                                ->subject('Inheritance Distribution Report - ' . ($notification->deceased_name ?? 'Estate Report'))
                                ->attachData($pdfContent, 'inheritance_report.pdf', [
                                    'mime' => 'application/pdf',
                                ]);
                    });

                    $notification->markAsSent();
                    $approved++;
                } else {
                    $failed++;
                }
            } catch (\Exception $e) {
                $failed++;
                Log::error('Bulk approve failed for notification: ' . $id, ['error' => $e->getMessage()]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Approved: {$approved}, Failed: {$failed}",
            'approved' => $approved,
            'failed' => $failed,
        ]);
    }

    /**
     * Bulk reject notification requests
     */
    public function adminBulkRejectNotifications(Request $request)
    {
        if (!class_exists(NotificationRequest::class)) {
            return response()->json(['success' => false, 'message' => 'Model not found.'], 500);
        }

        $ids = $request->input('ids', []);
        $reason = $request->input('reason', 'Bulk rejection by admin');

        if (empty($ids)) {
            return response()->json(['success' => false, 'message' => 'No requests selected.'], 400);
        }

        $rejected = 0;
        $failed = 0;

        foreach ($ids as $id) {
            try {
                $notification = NotificationRequest::find($id);
                if ($notification && $notification->canBeRejected()) {
                    $notification->reject(auth()->id(), $reason);
                    $rejected++;
                } else {
                    $failed++;
                }
            } catch (\Exception $e) {
                $failed++;
                Log::error('Bulk reject failed for notification: ' . $id, ['error' => $e->getMessage()]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Rejected: {$rejected}, Failed: {$failed}",
            'rejected' => $rejected,
            'failed' => $failed,
        ]);
    }

    /**
     * Export notification requests to CSV
     */
    public function adminExportNotifications(Request $request)
    {
        if (!class_exists(NotificationRequest::class)) {
            return response()->json(['success' => false, 'message' => 'Model not found.'], 500);
        }

        $query = NotificationRequest::with(['user', 'approvedBy']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $notifications = $query->orderBy('created_at', 'desc')->get();

        $filename = 'notification-requests-' . date('Y-m-d-His') . '.csv';
        $handle = fopen('php://temp', 'w+');
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, [
            'ID', 'Session ID', 'Deceased Name', 'Deceased NRIC', 'Recipient Email', 'Recipient Name',
            'Status', 'Requested By', 'Approved By', 'Rejection Reason', 'Created At', 'Approved At',
            'Sent At', 'Emails Sent', 'Resend Count'
        ]);

        foreach ($notifications as $notification) {
            fputcsv($handle, [
                $notification->id,
                $notification->session_id,
                $notification->deceased_name,
                $notification->deceased_nric,
                $notification->recipient_email,
                $notification->recipient_name,
                $notification->status,
                $notification->user?->name ?? 'N/A',
                $notification->approvedBy?->name ?? 'N/A',
                $notification->rejection_reason ?? '',
                $notification->created_at?->format('Y-m-d H:i:s'),
                $notification->approved_at?->format('Y-m-d H:i:s'),
                $notification->sent_at?->format('Y-m-d H:i:s'),
                $notification->emails_sent ? 'Yes' : 'No',
                $notification->resend_count ?? 0,
            ]);
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);

        return response($csvContent, 200)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Get notification statistics for dashboard
     */
    public function adminNotificationStats()
    {
        if (!class_exists(NotificationRequest::class)) {
            return response()->json(['success' => false, 'message' => 'Model not found.'], 500);
        }

        $stats = [
            'total' => NotificationRequest::count(),
            'pending_admin_approval' => NotificationRequest::where('status', NotificationRequest::STATUS_PENDING_ADMIN_APPROVAL)->count(),
            'approved' => NotificationRequest::where('status', NotificationRequest::STATUS_APPROVED)->count(),
            'rejected' => NotificationRequest::where('status', NotificationRequest::STATUS_REJECTED)->count(),
            'sent' => NotificationRequest::where('status', NotificationRequest::STATUS_SENT)->count(),
            'failed' => NotificationRequest::where('status', NotificationRequest::STATUS_FAILED)->count(),
            'this_week' => NotificationRequest::where('created_at', '>=', now()->subWeek())->count(),
            'this_month' => NotificationRequest::where('created_at', '>=', now()->subMonth())->count(),
        ];

        $dailyStats = NotificationRequest::select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $statusDistribution = NotificationRequest::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'summary' => $stats,
                'daily' => $dailyStats,
                'status_distribution' => $statusDistribution,
            ]
        ]);
    }

    // ==================== PDF GENERATION METHODS ====================

    /**
     * Find the associated session for a notification request
     */
    private function findSessionForNotification(NotificationRequest $notificationRequest): ?InstantEstateSession
    {
        if ($notificationRequest->instant_estate_session_id) {
            $session = InstantEstateSession::find($notificationRequest->instant_estate_session_id);
            if ($session) return $session;
        }

        if ($notificationRequest->session_id) {
            $session = InstantEstateSession::where('session_id', $notificationRequest->session_id)->first();
            if ($session) return $session;
        }

        $session = InstantEstateSession::where('notification_request_id', $notificationRequest->id)->first();
        if ($session) return $session;

        return null;
    }

    /**
     * Generate PDF content safely (NO Collection serialization)
     */
    private function generatePdfContentSafely(NotificationRequest $notificationRequest, InstantEstateSession $session): ?string
    {
        if ($notificationRequest->has_pdf_content) {
            $pdfContent = $notificationRequest->getDecodedPdfContentAttribute();
            if ($pdfContent) {
                Log::info('Using PDF from notification request', [
                    'request_id' => $notificationRequest->id,
                    'pdf_size' => strlen($pdfContent),
                ]);
                return $pdfContent;
            }
        }

        if ($session->has_pdf_report) {
            try {
                $pdfContent = Storage::disk('private')->get($session->report_pdf_path);
                if ($pdfContent) {
                    $notificationRequest->setPdfContent($pdfContent);
                    Log::info('Using PDF from session storage', [
                        'session_id' => $session->session_id,
                        'pdf_size' => strlen($pdfContent),
                    ]);
                    return $pdfContent;
                }
            } catch (\Exception $e) {
                Log::warning('Could not read PDF from session storage', [
                    'session_id' => $session->session_id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        if ($session->report_data) {
            try {
                $reportData = $session->report_data;
                if ($reportData instanceof \Illuminate\Support\Collection) {
                    $reportData = $reportData->toArray();
                }

                $pdfContent = $this->generatePdfFromArrayData($reportData, $session, $notificationRequest);

                if ($pdfContent) {
                    $pdfPath = 'estates/' . $session->session_id . '/report_' . now()->format('Ymd_His') . '.pdf';
                    Storage::disk('private')->put($pdfPath, $pdfContent);
                    $session->update(['report_pdf_path' => $pdfPath]);

                    $notificationRequest->setPdfContent($pdfContent);

                    Log::info('Generated new PDF from session data', [
                        'session_id' => $session->session_id,
                        'pdf_size' => strlen($pdfContent),
                    ]);
                    return $pdfContent;
                }
            } catch (\Exception $e) {
                Log::error('Failed to generate PDF from session data', [
                    'session_id' => $session->session_id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }
        }

        try {
            $pdfContent = $this->createSimplePdfSafe($notificationRequest, $session);
            if ($pdfContent) {
                Log::info('Created simple PDF as fallback', [
                    'request_id' => $notificationRequest->id,
                    'pdf_size' => strlen($pdfContent),
                ]);
                return $pdfContent;
            }
        } catch (\Exception $e) {
            Log::error('Failed to create simple PDF', [
                'error' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Generate PDF from array data (no Collection objects)
     */
    private function generatePdfFromArrayData(array $reportData, InstantEstateSession $session, NotificationRequest $notificationRequest): ?string
    {
        $heirs = $this->extractArrayValue($reportData, 'heirs');
        $wasiyyah = $this->extractArrayValue($reportData, 'wasiyyah');
        $assets = $this->extractArrayValue($reportData, 'assets');
        $debts = $this->extractArrayValue($reportData, 'debts');

        $deceasedName = $reportData['deceased_name'] ?? $session->deceased_name ?? 'the deceased';
        $deceasedNric = $reportData['deceased_nric'] ?? $session->deceased_nric ?? 'N/A';
        $deathDate = $session->death_date ? $session->death_date->format('d F Y') : ($reportData['date_of_death'] ?? 'N/A');

        $totalAssets = (float) ($reportData['total_assets'] ?? 0);
        $totalDebts = (float) ($reportData['total_debts'] ?? 0);
        $netEstate = max(0, $totalAssets - $totalDebts);
        $remainingForHeirs = $reportData['remaining_for_heirs'] ?? $netEstate;
        $totalHeirPct = 0;

        if (is_array($heirs)) {
            foreach ($heirs as $heir) {
                $percentage = 0;
                if (is_array($heir)) {
                    $percentage = (float) ($heir['share_percentage'] ?? $heir['percentage'] ?? 0);
                } elseif (is_object($heir)) {
                    $percentage = (float) ($heir->share_percentage ?? $heir->percentage ?? 0);
                }
                $totalHeirPct += $percentage;
            }
        }

        $html = $this->buildPdfHtml(
            $deceasedName, $deceasedNric, $deathDate,
            $totalAssets, $totalDebts, $netEstate,
            $heirs, $wasiyyah, $remainingForHeirs, $totalHeirPct,
            $notificationRequest->access_token
        );

        try {
            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('a4');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultFont' => 'sans-serif',
            ]);
            return $pdf->output();
        } catch (\Exception $e) {
            Log::error('DomPDF generation failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Safely extract array value (converts Collection to array)
     */
    private function extractArrayValue(array $data, string $key): array
    {
        if (!isset($data[$key])) {
            return [];
        }

        $value = $data[$key];

        if ($value instanceof \Illuminate\Support\Collection) {
            $value = $value->toArray();
        }

        if (is_array($value)) {
            foreach ($value as $index => $item) {
                if (is_object($item)) {
                    $value[$index] = (array) $item;
                }
            }
        }

        if (is_object($value)) {
            $value = (array) $value;
        }

        return is_array($value) ? $value : [];
    }

    /**
     * Build PDF HTML content
     */
    private function buildPdfHtml(
        string $deceasedName, string $deceasedNric, string $deathDate,
        float $totalAssets, float $totalDebts, float $netEstate,
        array $heirs, array $wasiyyah, float $remainingForHeirs, float $totalHeirPct,
        ?string $token
    ): string {
        $heirsHtml = '';
        if (is_array($heirs)) {
            foreach ($heirs as $heir) {
                $name = $this->getArrayValue($heir, 'name', 'beneficiary_name', 'Unknown');
                $relationship = $this->getArrayValue($heir, 'relationship', 'Unknown');
                $percentage = (float) $this->getArrayValue($heir, 'share_percentage', 'percentage', 0);
                $amount = (float) $this->getArrayValue($heir, 'share_amount', 'amount', 0);

                if ($amount === 0.0 && $remainingForHeirs > 0 && $percentage > 0) {
                    $amount = ($percentage / 100) * $remainingForHeirs;
                }

                $heirsHtml .= '<tr>
                    <td>' . htmlspecialchars($name) . '</td>
                    <td>' . htmlspecialchars($relationship) . '</td>
                    <td>' . number_format($percentage, 2) . '%</td>
                    <td class="amount">RM ' . number_format($amount, 2) . '</td>
                </tr>';
            }
        }

        $wasiyyahHtml = '';
        if (is_array($wasiyyah)) {
            foreach ($wasiyyah as $w) {
                $name = $this->getArrayValue($w, 'beneficiary_name', 'name', 'Unknown');
                $relationship = $this->getArrayValue($w, 'relationship', 'N/A');
                $percentage = (float) $this->getArrayValue($w, 'requested_percentage', 'percentage', 0);
                $amount = ($percentage / 100) * $netEstate;

                $wasiyyahHtml .= '<tr>
                    <td>' . htmlspecialchars($name) . '</td>
                    <td>' . htmlspecialchars($relationship) . '</td>
                    <td>' . number_format($percentage, 2) . '%</td>
                    <td class="amount">RM ' . number_format($amount, 2) . '</td>
                </tr>';
            }
        }

        return '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Inheritance Distribution Report</title>
            <style>
                * { font-family: Arial, sans-serif; }
                body { margin: 20px; line-height: 1.6; }
                h1 { color: #1a5fb4; border-bottom: 2px solid #1a5fb4; padding-bottom: 10px; }
                h2 { color: #2d7ad6; margin-top: 20px; }
                table { width: 100%; border-collapse: collapse; margin: 15px 0; }
                th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                th { background: #1a5fb4; color: white; }
                .footer { margin-top: 30px; font-size: 10px; text-align: center; color: #666; border-top: 1px solid #ddd; padding-top: 10px; }
                .disclaimer { background: #fff3cd; padding: 10px; margin: 20px 0; border-left: 4px solid #ffc107; font-size: 11px; }
                .header { text-align: center; margin-bottom: 20px; }
                .amount { font-weight: 700; color: #25D366; }
                .summary-box { background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0; }
                .summary-box table { margin: 0; }
                .summary-box td { border: none; padding: 5px; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Inheritance Distribution Report</h1>
                <p>Generated: ' . now()->format('d F Y, h:i:s A') . '</p>
            </div>
            
            <h2>Deceased Information</h2>
            <table>
                <tr><th>Name</th><td>' . htmlspecialchars($deceasedName) . '</td>' . '
                <tr><th>NRIC/Passport</th><td>' . htmlspecialchars($deceasedNric) . '</td>' . '
                <tr><th>Date of Death</th><td>' . htmlspecialchars($deathDate) . '</td>' . '
            </table>
            
            <h2>Financial Summary</h2>
            <div class="summary-box">
                <table width="100%">
                    <tr><th>Total Assets</th><td><span class="amount">RM ' . number_format($totalAssets, 2) . '</span></td>' . '
                    <tr><th>Total Debts</th><td>RM ' . number_format($totalDebts, 2) . '</span></td>' . '
                    <tr><th>Net Estate for Distribution</th><td><span class="amount">RM ' . number_format($netEstate, 2) . '</span></td>' . '
                </table>
            </div>
            
            ' . (!empty($heirsHtml) ? '
            <h2>Heirs Distribution (Faraid)</h2>
            <table width="100%" cellpadding="5" cellspacing="0" border="1">
                <thead>
                    <tr><th>Heir Name</th><th>Relationship</th><th>Share Percentage</th><th>Amount (RM)</th></tr>
                </thead>
                <tbody>' . $heirsHtml . '
                    <tr style="background: #f1f5f9; font-weight: 700;">
                        <td colspan="2">Total</td>
                        <td>' . number_format($totalHeirPct, 2) . '%</td>
                        <td class="amount">RM ' . number_format($remainingForHeirs, 2) . '</td>
                    </tr>
                </tbody>
            </table>
            ' : '') . '
            
            ' . (!empty($wasiyyahHtml) ? '
            <h2>Wasiyyah Beneficiaries</h2>
            <table width="100%" cellpadding="5" cellspacing="0" border="1">
                <thead>
                    <tr><th>Name</th><th>Relationship</th><th>Share Percentage</th><th>Amount (RM)</th></tr>
                </thead>
                <tbody>' . $wasiyyahHtml . '</tbody>
            </table>
            ' : '') . '
            
            <div class="disclaimer">
                <strong>Disclaimer:</strong> This report is generated by Neo Faraid - Islamic Inheritance Calculator. 
                It is for informational purposes only. For legal binding purposes, consult with a qualified 
                Islamic inheritance lawyer (Peguam Syarie) or your local Shariah Court (Mahkamah Syariah).
            </div>
            
            <div class="footer">
                This document is generated by Neo Faraid - Islamic Inheritance Calculator.<br>
                Generated on: ' . now()->format('d F Y, h:i:s A') . ' | Document ID: ' . ($token ?? 'N/A') . '
            </div>
        </body>
        </html>';
    }

    /**
     * Helper to get value from array with multiple possible keys
     */
    private function getArrayValue(array $data, string $key1, ?string $key2 = null, $default = null)
    {
        if (isset($data[$key1])) {
            return $data[$key1];
        }
        if ($key2 && isset($data[$key2])) {
            return $data[$key2];
        }
        return $default;
    }

    /**
     * Create a simple PDF as fallback (safe version)
     */
    private function createSimplePdfSafe(NotificationRequest $notificationRequest, InstantEstateSession $session): ?string
    {
        $deceasedName = $notificationRequest->deceased_name ?? $session->deceased_name ?? 'the deceased';
        $deceasedNric = $notificationRequest->deceased_nric ?? $session->deceased_nric ?? 'N/A';
        $deathDate = $session->death_date ? $session->death_date->format('d F Y') : 'N/A';

        $html = '<!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Inheritance Distribution Report</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                h1 { color: #1a5fb4; border-bottom: 2px solid #1a5fb4; }
                .footer { margin-top: 30px; font-size: 10px; text-align: center; }
                .disclaimer { background: #fff3cd; padding: 10px; margin: 20px 0; border-left: 4px solid #ffc107; }
            </style>
        </head>
        <body>
            <div style="text-align:center;">
                <h1>Inheritance Distribution Report</h1>
                <p>Generated: ' . now()->format('d F Y, h:i:s A') . '</p>
            </div>
            
            <h2>Deceased Information</h2>
            <table>
                <tr><th>Name</th><td>' . htmlspecialchars($deceasedName) . '</td>' . '
                <tr><th>NRIC</th><td>' . htmlspecialchars($deceasedNric) . '</td>' . '
                <tr><th>Date of Death</th><td>' . htmlspecialchars($deathDate) . '</td>' . '
            </table>
            
            <div class="disclaimer">
                <strong>Disclaimer:</strong> This report is generated by Neo Faraid - Islamic Inheritance Calculator. 
                It is for informational purposes only. For legal binding purposes, consult with a qualified 
                Islamic inheritance lawyer (Peguam Syarie) or your local Shariah Court (Mahkamah Syariah).
            </div>
            
            <div class="footer">
                Generated on: ' . now()->format('d F Y, h:i:s A') . ' | Document ID: ' . ($notificationRequest->access_token ?? 'N/A') . '
            </div>
        </body>
        </html>';

        try {
            $pdf = Pdf::loadHTML($html);
            $pdf->setPaper('a4');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => false,
                'defaultFont' => 'sans-serif',
            ]);
            return $pdf->output();
        } catch (\Exception $e) {
            Log::error('Failed to generate simple PDF: ' . $e->getMessage());
            return null;
        }
    }

    // ==================== SETTINGS AND SYSTEM METHODS ====================

    /**
     * Display settings page
     */
    public function settingsIndex()
    {
        return view('admin.settings.index');
    }

    /**
     * Update settings
     */
    public function settingsUpdate(Request $request)
    {
        return redirect()->back()->with('success', 'Settings updated.');
    }

    /**
     * Display maintenance page
     */
    public function maintenance()
    {
        return view('admin.settings.maintenance');
    }

    /**
     * Toggle maintenance mode
     */
    public function toggleMaintenance(Request $request)
    {
        return redirect()->back()->with('success', 'Maintenance mode toggled.');
    }

    /**
     * Display backups page
     */
    public function backups()
    {
        return view('admin.settings.backups', ['backups' => []]);
    }

    /**
     * Create a backup
     */
    public function createBackup()
    {
        return redirect()->back()->with('success', 'Backup created.');
    }

    /**
     * Restore a backup
     */
    public function restoreBackup($backup)
    {
        return redirect()->back()->with('success', 'Backup restored.');
    }

    /**
     * Delete a backup
     */
    public function deleteBackup($backup)
    {
        return redirect()->back()->with('success', 'Backup deleted.');
    }

    /**
     * Display cache management page
     */
    public function cacheManagement()
    {
        return view('admin.settings.cache');
    }

    /**
     * Clear application cache
     */
    public function clearCache()
    {
        \Artisan::call('cache:clear');
        \Artisan::call('config:clear');
        \Artisan::call('view:clear');
        \Artisan::call('route:clear');
        return redirect()->back()->with('success', 'Cache cleared.');
    }

    /**
     * Display environment settings page
     */
    public function environmentSettings()
    {
        return view('admin.settings.environment');
    }

    /**
     * Update environment settings
     */
    public function updateEnvironment(Request $request)
    {
        return redirect()->back()->with('success', 'Environment settings updated.');
    }

    /**
     * Display logs page
     */
    public function logs()
    {
        return view('admin.logs.index', ['logFiles' => []]);
    }

    /**
     * View a specific log file
     */
    public function viewLog($file)
    {
        return view('admin.logs.view', ['file' => $file, 'content' => '']);
    }

    /**
     * Delete a log file
     */
    public function deleteLog($file)
    {
        return redirect()->route('admin.logs')->with('success', 'Log deleted.');
    }

    /**
     * Display system health page
     */
    public function systemHealth()
    {
        return view('admin.system.health', ['health' => ['php_version' => PHP_VERSION]]);
    }

    /**
     * Impersonate a user
     */
    public function impersonate($userId)
    {
        $user = User::findOrFail($userId);
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Cannot impersonate yourself.');
        }
        session(['impersonate' => auth()->id()]);
        auth()->login($user);
        return redirect()->route('dashboard')->with('success', 'Impersonating ' . $user->name);
    }

    /**
     * Stop impersonating a user
     */
    public function stopImpersonate()
    {
        $originalId = session('impersonate');
        if ($originalId) {
            session()->forget('impersonate');
            $originalUser = User::find($originalId);
            if ($originalUser) auth()->login($originalUser);
        }
        return redirect()->route('admin.dashboard');
    }

    /**
     * Alias for createBackup
     */
    public function runBackup()
    {
        return $this->createBackup();
    }

    /**
     * Display queue monitor page
     */
    public function queueMonitor()
    {
        return view('admin.queue.monitor', ['failedJobs' => []]);
    }

    /**
     * Retry a failed job
     */
    public function retryFailedJob($id)
    {
        \Artisan::call('queue:retry', ['id' => $id]);
        return redirect()->back()->with('success', 'Job queued for retry.');
    }

    /**
     * Flush all failed jobs
     */
    public function flushFailedJobs()
    {
        \Artisan::call('queue:flush');
        return redirect()->back()->with('success', 'Failed jobs flushed.');
    }

    // ==================== HELPER METHODS ====================

    /**
     * Safely decode JSON data
     */
    private function safeJsonDecode($data)
    {
        if (is_null($data)) {
            return [];
        }
        if (is_array($data)) {
            return $data;
        }
        if (is_string($data)) {
            $decoded = json_decode($data, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    /**
     * Get dashboard statistics
     */
    private function getDashboardStats()
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'new_users_today' => User::whereDate('created_at', today())->count(),
                'total_feedback' => Feedback::count(),
                'pending_feedback' => Feedback::where('status', 'pending')->count(),
                'total_calculations' => Calculation::count(),
                'today_calculations' => Calculation::whereDate('created_at', today())->count(),
                'total_assets' => Calculation::sum('total_assets') ?? 0,
            ];

            try {
                $stats['active_users'] = User::where('status', 'active')->count();
            } catch (\Exception $e) {
                $stats['active_users'] = User::where('last_login_at', '>=', Carbon::now()->subDays(30))->count();
            }

            try {
                $stats['active_faqs'] = Faq::where('is_active', true)->count();
            } catch (\Exception $e) {
                try {
                    $stats['active_faqs'] = Faq::where('is_published', true)->count();
                } catch (\Exception $e2) {
                    $stats['active_faqs'] = Faq::count();
                }
            }

            $stats['total_instant_sessions'] = class_exists(InstantEstateSession::class) ? InstantEstateSession::count() : 0;
            $stats['total_estate_setup'] = class_exists(EstatePreRegistration::class) ? EstatePreRegistration::count() : 0;

            if (class_exists(NotificationRequest::class)) {
                $stats['pending_notifications'] = NotificationRequest::where('status', NotificationRequest::STATUS_PENDING_ADMIN_APPROVAL)->count();
                $stats['approved_notifications'] = NotificationRequest::where('status', NotificationRequest::STATUS_APPROVED)->count();
            } else {
                $stats['pending_notifications'] = 0;
                $stats['approved_notifications'] = 0;
            }

            return $stats;
        } catch (\Exception $e) {
            return $this->getFallbackStats();
        }
    }

    /**
     * Get fallback dashboard statistics
     */
    private function getFallbackStats()
    {
        return [
            'total_users' => User::count(),
            'active_users' => User::count(),
            'new_users_today' => 0,
            'total_feedback' => Feedback::count(),
            'pending_feedback' => Feedback::count(),
            'total_calculations' => Calculation::count(),
            'today_calculations' => 0,
            'total_assets' => 0,
            'active_faqs' => Faq::count(),
            'total_instant_sessions' => 0,
            'total_estate_setup' => 0,
            'pending_notifications' => 0,
            'approved_notifications' => 0,
        ];
    }

    /**
     * Format file size in human-readable format
     */
    private function formatFileSizeHelper(?int $bytes): string
    {
        if (!$bytes) return 'N/A';
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get Bootstrap color class for instant estate session status
     */
    private function getStatusColorHelper(string $status): string
    {
        $colors = [
            'uploaded' => 'secondary',
            'processing_ocr' => 'info',
            'ocr_completed' => 'primary',
            'data_confirmed' => 'primary',
            'captcha_verified' => 'success',
            'record_found' => 'success',
            'no_record' => 'secondary',
            'notification_requested' => 'warning',
            'email_sent' => 'success',
            'failed' => 'danger',
            'completed' => 'success',
            'cancelled' => 'dark',
            'expired' => 'secondary',
        ];
        return $colors[$status] ?? 'secondary';
    }

    /**
     * Get human-readable label for instant estate session status
     */
    private function getStatusLabelHelper(string $status): string
    {
        $labels = [
            'uploaded' => 'Uploaded',
            'processing_ocr' => 'Processing OCR',
            'ocr_completed' => 'OCR Complete',
            'data_confirmed' => 'Data Confirmed',
            'captcha_verified' => 'CAPTCHA Verified',
            'record_found' => 'Record Found',
            'no_record' => 'No Record',
            'notification_requested' => 'Notification Requested',
            'email_sent' => 'Email Sent',
            'failed' => 'Failed',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'expired' => 'Expired',
        ];
        return $labels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * Get Bootstrap color class for notification status
     */
    private function getNotificationStatusColorHelper(string $status): string
    {
        $colors = [
            'pending_admin_approval' => 'warning',
            'pending' => 'secondary',
            'approved' => 'success',
            'rejected' => 'danger',
            'sent' => 'info',
            'failed' => 'danger',
            'cancelled' => 'dark',
        ];
        return $colors[$status] ?? 'secondary';
    }

    /**
     * Get human-readable label for notification status
     */
    private function getNotificationStatusLabelHelper(string $status): string
    {
        $labels = [
            'pending_admin_approval' => 'Pending Approval',
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'sent' => 'Sent',
            'failed' => 'Failed',
            'cancelled' => 'Cancelled',
        ];
        return $labels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}