<?php

namespace App\Http\Controllers;

use App\Models\Calculation;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Display user dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get user's recent calculations
        $recentCalculations = Calculation::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        // Get user's documents - handle if model doesn't exist
        try {
            $recentDocuments = Document::where('user_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
        } catch (\Exception $e) {
            $recentDocuments = collect(); // Empty collection if model doesn't exist
        }
        
        // Get calculation stats
        $totalCalculations = Calculation::where('user_id', $user->id)->count();
        
        // Get document stats - handle if model doesn't exist
        try {
            $totalDocuments = Document::where('user_id', $user->id)->count();
        } catch (\Exception $e) {
            $totalDocuments = 0;
        }
        
        // Get notifications count (if using Laravel's notification system)
        try {
            $unreadNotificationsCount = $user->unreadNotifications()->count();
        } catch (\Exception $e) {
            $unreadNotificationsCount = 0;
        }
        
        return view('dashboard', compact(
            'recentCalculations',
            'recentDocuments',
            'totalCalculations',
            'totalDocuments',
            'unreadNotificationsCount'
        ));
    }
}