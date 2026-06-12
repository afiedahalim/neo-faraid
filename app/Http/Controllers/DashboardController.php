<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        
        // Check if user is admin and redirect to admin dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        // Get dashboard data for regular users
        $dashboardData = $this->getDashboardData($user);
        
        return view('dashboard', $dashboardData);
    }
    
    /**
     * Get all dashboard data for the user
     *
     * @param  \App\Models\User  $user
     * @return array
     */
    private function getDashboardData($user)
    {
        return [
            'totalCalculations' => $this->getTotalCalculations($user),
            'totalEstates' => $this->getTotalEstates($user),
            'totalAssetValue' => $this->getTotalAssetValue($user),
            'calculations' => $this->getRecentCalculations($user),
            'estates' => $this->getRecentEstates($user),
        ];
    }
    
    /**
     * Get total calculations
     *
     * @param  \App\Models\User  $user
     * @return int
     */
    private function getTotalCalculations($user)
    {
        try {
            if (class_exists('\App\Models\Calculation')) {
                return \App\Models\Calculation::where('user_id', $user->id)->count();
            }
        } catch (\Exception $e) {
            Log::error('Dashboard total calculations error: ' . $e->getMessage());
        }
        
        return 0;
    }
    
    /**
     * Get total estates
     *
     * @param  \App\Models\User  $user
     * @return int
     */
    private function getTotalEstates($user)
    {
        try {
            if (class_exists('\App\Models\Estate')) {
                return \App\Models\Estate::where('user_id', $user->id)->count();
            }
        } catch (\Exception $e) {
            Log::error('Dashboard total estates error: ' . $e->getMessage());
        }
        
        return 0;
    }
    
    /**
     * Get total asset value
     *
     * @param  \App\Models\User  $user
     * @return float
     */
    private function getTotalAssetValue($user)
    {
        $totalValue = 0;
        
        try {
            // Calculations asset value
            if (class_exists('\App\Models\Calculation')) {
                $calculations = \App\Models\Calculation::where('user_id', $user->id)->get();
                foreach ($calculations as $calculation) {
                    $totalValue += (float)($calculation->total_assets ?? 0);
                }
            }
            
            // Estates asset value
            if (class_exists('\App\Models\Estate')) {
                $estates = \App\Models\Estate::where('user_id', $user->id)->get();
                foreach ($estates as $estate) {
                    $totalValue += (float)($estate->net_estate ?? 0);
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Dashboard asset value error: ' . $e->getMessage());
        }
        
        return $totalValue;
    }
    
    /**
     * Get recent calculations
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Support\Collection
     */
    private function getRecentCalculations($user, $limit = 5)
    {
        try {
            if (class_exists('\App\Models\Calculation')) {
                return \App\Models\Calculation::where('user_id', $user->id)
                    ->latest()
                    ->limit($limit)
                    ->get();
            }
        } catch (\Exception $e) {
            Log::error('Dashboard recent calculations error: ' . $e->getMessage());
        }
        
        return collect();
    }
    
    /**
     * Get recent estates
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Support\Collection
     */
    private function getRecentEstates($user, $limit = 5)
    {
        try {
            if (class_exists('\App\Models\Estate')) {
                return \App\Models\Estate::where('user_id', $user->id)
                    ->latest()
                    ->limit($limit)
                    ->get();
            }
        } catch (\Exception $e) {
            Log::error('Dashboard recent estates error: ' . $e->getMessage());
        }
        
        return collect();
    }
}