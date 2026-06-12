<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;

class PublicFeedbackController extends Controller
{
    public function index()
    {
        // Get only approved and public feedback
        $feedback = Feedback::with('user')
            ->where('status', 'approved')
            ->where('is_public', true)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Calculate stats
        $stats = $this->calculateStats();
        
        return view('feedback.index', compact('feedback', 'stats'));
    }
    
    public function show($id)
    {
        $feedback = Feedback::with('user')
            ->where('status', 'approved')
            ->where('is_public', true)
            ->findOrFail($id);
        
        return view('feedback.show', compact('feedback'));
    }
    
    private function calculateStats()
    {
        $totalReviews = Feedback::where('status', 'approved')
            ->where('is_public', true)
            ->count();
        
        $averageRating = Feedback::where('status', 'approved')
            ->where('is_public', true)
            ->whereNotNull('rating')
            ->avg('rating') ?? 0;
        
        // Calculate satisfaction rate (percentage of 4-5 star reviews)
        $positiveReviews = Feedback::where('status', 'approved')
            ->where('is_public', true)
            ->whereIn('rating', [4, 5])
            ->count();
        
        $satisfactionRate = $totalReviews > 0 
            ? round(($positiveReviews / $totalReviews) * 100) 
            : 0;
        
        return [
            'total_reviews' => $totalReviews,
            'average_rating' => number_format($averageRating, 1),
            'satisfaction_rate' => $satisfactionRate
        ];
    }
}