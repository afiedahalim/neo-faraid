<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PUBLIC METHODS
    |--------------------------------------------------------------------------
    */
    
    /**
     * Display the public feedback index page
     */
    public function index()
    {
        // Get approved and public feedback with pagination
        $feedback = Feedback::where('status', 'approved')
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->paginate(9);
        
        // Calculate statistics
        $totalApproved = Feedback::where('status', 'approved')
            ->where('is_public', true)
            ->count();
        
        $averageRating = Feedback::where('status', 'approved')
            ->where('is_public', true)
            ->avg('rating') ?? 0;
        
        $satisfactionCount = Feedback::where('status', 'approved')
            ->where('is_public', true)
            ->where('rating', '>=', 4)
            ->count();
        
        $stats = [
            'total_reviews' => $totalApproved,
            'average_rating' => number_format($averageRating, 1),
            'satisfaction_rate' => $totalApproved > 0 ? round(($satisfactionCount / $totalApproved) * 100) : 0
        ];
        
        return view('feedback.index', compact('feedback', 'stats'));
    }
    
    /**
     * Show form for creating new feedback (authenticated users only)
     */
    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('info', 'Please login to submit feedback.');
        }
        
        return view('feedback.create');
    }
    
    /**
     * Store new feedback from authenticated users
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'You must be logged in to submit feedback.');
        }
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|min:10|max:500',
        ]);
        
        // Create feedback
        Feedback::create([
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'message' => $validated['message'],
            'status' => 'pending', // Default status - needs admin approval
            'is_public' => true, // Default to public
        ]);
        
        return redirect()->route('feedback.index')
            ->with('success', 'Thank you for your feedback! It will be reviewed and published soon.');
    }
    
    /**
     * Display user's own feedback
     */
    public function myFeedback()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $feedback = Feedback::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('feedback.my', compact('feedback'));
    }
    
    /**
     * Edit user's own feedback
     */
    public function edit($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $feedback = Feedback::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        // Only allow editing if status is pending
        if ($feedback->status !== 'pending') {
            return redirect()->route('feedback.my')
                ->with('error', 'You can only edit pending feedback.');
        }
        
        return view('feedback.edit', compact('feedback'));
    }
    
    /**
     * Update user's own feedback
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $feedback = Feedback::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        // Only allow editing if status is pending
        if ($feedback->status !== 'pending') {
            return redirect()->route('feedback.my')
                ->with('error', 'You can only edit pending feedback.');
        }
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|min:10|max:500',
        ]);
        
        $feedback->update([
            'rating' => $validated['rating'],
            'message' => $validated['message'],
        ]);
        
        return redirect()->route('feedback.my')
            ->with('success', 'Your feedback has been updated successfully.');
    }
    
    /**
     * Delete user's own feedback
     */
    public function destroy($id)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $feedback = Feedback::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        // Only allow deletion if status is pending
        if ($feedback->status !== 'pending') {
            return redirect()->route('feedback.my')
                ->with('error', 'You can only delete pending feedback.');
        }
        
        $feedback->delete();
        
        return redirect()->route('feedback.my')
            ->with('success', 'Your feedback has been deleted successfully.');
    }
    
    /**
     * Get feedback statistics for AJAX requests (Live Stats - Public)
     */
    public function stats()
    {
        if (!request()->ajax() && !request()->expectsJson()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $total = Feedback::count();
        $approved = Feedback::where('status', 'approved')->count();
        $pending = Feedback::where('status', 'pending')->count();
        $rejected = Feedback::where('status', 'rejected')->count();
        
        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'approved' => $approved,
                'pending' => $pending,
                'rejected' => $rejected,
            ]
        ]);
    }
    
    /*
    |--------------------------------------------------------------------------
    | ADMIN METHODS
    |--------------------------------------------------------------------------
    */
    
    /**
     * Display all feedback for admin
     */
    public function adminIndex()
    {
        // Get all feedback with user relationship
        $feedbackList = Feedback::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        // Calculate stats for initial page load
        $totalFeedback = Feedback::count();
        $approvedCount = Feedback::where('status', 'approved')->count();
        $pendingCount = Feedback::where('status', 'pending')->count();
        $rejectedCount = Feedback::where('status', 'rejected')->count();
        
        return view('admin.feedback.index', compact(
            'feedbackList',
            'totalFeedback',
            'approvedCount',
            'pendingCount',
            'rejectedCount'
        ));
    }
    
    /**
     * Show single feedback for admin
     */
    public function adminShow($id)
    {
        $feedback = Feedback::with('user')->findOrFail($id);
        return view('admin.feedback.show', compact('feedback'));
    }
    
    /**
     * Approve feedback
     */
    public function approve($id)
    {
        $feedback = Feedback::findOrFail($id);
        
        // When approving, make sure it's also public
        $feedback->update([
            'status' => 'approved',
            'is_public' => true
        ]);
        
        // Return JSON for AJAX requests, otherwise redirect
        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Feedback approved successfully.',
                'data' => [
                    'id' => $feedback->id,
                    'status' => $feedback->status,
                    'is_public' => $feedback->is_public
                ]
            ]);
        }
        
        return redirect()->route('admin.feedback.index')
            ->with('success', 'Feedback approved and published successfully.');
    }
    
    /**
     * Reject feedback
     */
    public function reject($id)
    {
        $feedback = Feedback::findOrFail($id);
        
        // When rejecting, make it private
        $feedback->update([
            'status' => 'rejected',
            'is_public' => false
        ]);
        
        // Return JSON for AJAX requests, otherwise redirect
        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Feedback rejected successfully.',
                'data' => [
                    'id' => $feedback->id,
                    'status' => $feedback->status,
                    'is_public' => $feedback->is_public
                ]
            ]);
        }
        
        return redirect()->route('admin.feedback.index')
            ->with('success', 'Feedback rejected and hidden from public view.');
    }
    
    /**
     * Toggle feedback visibility
     */
    public function toggleVisibility($id)
    {
        $feedback = Feedback::findOrFail($id);
        
        // Only allow visibility toggle for approved feedback
        if ($feedback->status === 'approved') {
            $feedback->update(['is_public' => !$feedback->is_public]);
            $status = $feedback->is_public ? 'public' : 'private';
            
            if (request()->ajax() || request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Feedback visibility changed to {$status}.",
                    'data' => [
                        'id' => $feedback->id,
                        'is_public' => $feedback->is_public
                    ]
                ]);
            }
            
            return redirect()->route('admin.feedback.index')
                ->with('success', "Feedback visibility changed to {$status}.");
        }
        
        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Only approved feedback can have visibility toggled.'
            ], 400);
        }
        
        return redirect()->route('admin.feedback.index')
            ->with('error', 'Only approved feedback can have visibility toggled.');
    }
    
    /**
     * Delete feedback (Admin)
     */
    public function adminDestroy($id)
    {
        $feedback = Feedback::findOrFail($id);
        $feedback->delete();
        
        // Return JSON for AJAX requests, otherwise redirect
        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Feedback deleted successfully.',
                'data' => ['id' => $id]
            ]);
        }
        
        return redirect()->route('admin.feedback.index')
            ->with('success', 'Feedback deleted successfully.');
    }
    
    /**
     * Update feedback (admin edit)
     */
    public function adminUpdate(Request $request, $id)
    {
        $feedback = Feedback::findOrFail($id);
        
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'message' => 'required|string|min:10|max:500',
            'status' => 'required|in:pending,approved,rejected',
            'is_public' => 'boolean',
        ]);
        
        $feedback->update($validated);
        
        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Feedback updated successfully.',
                'data' => $feedback
            ]);
        }
        
        return redirect()->route('admin.feedback.show', $feedback->id)
            ->with('success', 'Feedback updated successfully.');
    }
    
    /**
     * Bulk approve feedback
     */
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:feedback,id'
        ]);
        
        $count = Feedback::whereIn('id', $request->ids)
            ->where('status', '!=', 'approved')
            ->update([
                'status' => 'approved',
                'is_public' => true
            ]);
        
        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$count} feedback items approved successfully.",
                'data' => ['count' => $count]
            ]);
        }
        
        return redirect()->route('admin.feedback.index')
            ->with('success', "{$count} feedback items approved successfully.");
    }
    
    /**
     * Bulk reject feedback
     */
    public function bulkReject(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:feedback,id'
        ]);
        
        $count = Feedback::whereIn('id', $request->ids)
            ->where('status', '!=', 'rejected')
            ->update([
                'status' => 'rejected',
                'is_public' => false
            ]);
        
        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$count} feedback items rejected successfully.",
                'data' => ['count' => $count]
            ]);
        }
        
        return redirect()->route('admin.feedback.index')
            ->with('success', "{$count} feedback items rejected successfully.");
    }
    
    /**
     * Bulk delete feedback
     */
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:feedback,id'
        ]);
        
        $count = Feedback::whereIn('id', $request->ids)->delete();
        
        if (request()->ajax() || request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$count} feedback items deleted successfully.",
                'data' => ['count' => $count]
            ]);
        }
        
        return redirect()->route('admin.feedback.index')
            ->with('success', "{$count} feedback items deleted successfully.");
    }
    
    /**
     * Export feedback to CSV/Excel
     */
    public function export(Request $request)
    {
        $query = Feedback::with('user');
        
        // Apply filters if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $feedback = $query->orderBy('created_at', 'desc')->get();
        
        $filename = 'feedback_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
        ];
        
        $callback = function() use ($feedback) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Headers
            fputcsv($file, [
                'ID', 'User Name', 'User Email', 'Rating', 'Message', 
                'Status', 'Public', 'Created At', 'Updated At'
            ]);
            
            // Data rows
            foreach ($feedback as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->user->name ?? 'Anonymous User',
                    $item->user->email ?? '',
                    $item->rating,
                    $item->message,
                    $item->status,
                    $item->is_public ? 'Yes' : 'No',
                    $item->created_at,
                    $item->updated_at,
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
    
    /**
     * Get feedback statistics for admin dashboard (AJAX endpoint for live updates)
     */
    public function adminStats()
    {
        $total = Feedback::count();
        $approved = Feedback::where('status', 'approved')->count();
        $pending = Feedback::where('status', 'pending')->count();
        $rejected = Feedback::where('status', 'rejected')->count();
        
        $averageRating = Feedback::where('status', 'approved')
            ->where('is_public', true)
            ->avg('rating') ?? 0;
        
        $ratingDistribution = [
            '5_stars' => Feedback::where('rating', 5)->count(),
            '4_stars' => Feedback::where('rating', 4)->count(),
            '3_stars' => Feedback::where('rating', 3)->count(),
            '2_stars' => Feedback::where('rating', 2)->count(),
            '1_star' => Feedback::where('rating', 1)->count(),
        ];
        
        $monthlyStats = Feedback::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'total' => $total,
                'approved' => $approved,
                'pending' => $pending,
                'rejected' => $rejected,
                'average_rating' => number_format($averageRating, 1),
                'rating_distribution' => $ratingDistribution,
                'monthly_stats' => $monthlyStats,
            ]
        ]);
    }
    
    /**
     * Search feedback (AJAX)
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);
        
        $query = $request->query;
        
        $feedback = Feedback::with('user')
            ->where(function($q) use ($query) {
                $q->where('message', 'LIKE', "%{$query}%")
                  ->orWhereHas('user', function($userQuery) use ($query) {
                      $userQuery->where('name', 'LIKE', "%{$query}%")
                                ->orWhere('email', 'LIKE', "%{$query}%");
                  });
            })
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $feedback,
            'count' => $feedback->count()
        ]);
    }
    
    /**
     * Get pending feedback count for admin notification
     */
    public function getPendingCount()
    {
        $count = Feedback::where('status', 'pending')->count();
        
        return response()->json([
            'success' => true,
            'pending_count' => $count
        ]);
    }
    
    /**
     * Restore soft-deleted feedback (if using soft deletes)
     */
    public function restore($id)
    {
        $feedback = Feedback::withTrashed()->findOrFail($id);
        $feedback->restore();
        
        return redirect()->route('admin.feedback.index')
            ->with('success', 'Feedback restored successfully.');
    }
    
    /**
     * Permanently delete feedback (force delete)
     */
    public function forceDelete($id)
    {
        $feedback = Feedback::withTrashed()->findOrFail($id);
        $feedback->forceDelete();
        
        return redirect()->route('admin.feedback.index')
            ->with('success', 'Feedback permanently deleted.');
    }
    
    /**
     * Get feedback summary for dashboard widget
     */
    public function summary()
    {
        $recentFeedback = Feedback::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $pendingCount = Feedback::where('status', 'pending')->count();
        $approvedCount = Feedback::where('status', 'approved')->count();
        $todayCount = Feedback::whereDate('created_at', today())->count();
        
        return response()->json([
            'success' => true,
            'data' => [
                'pending_count' => $pendingCount,
                'approved_count' => $approvedCount,
                'today_count' => $todayCount,
                'recent_feedback' => $recentFeedback
            ]
        ]);
    }
}