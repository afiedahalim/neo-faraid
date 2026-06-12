<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactFormMail;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        return view('home');
    }
    
    /**
     * Display the about page.
     */
    public function about()
    {
        return view('about');
    }
    
    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('contact');
    }
    
    /**
     * Handle contact form submission.
     */
    public function contactSubmit(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10|max:1000',
        ]);
        
        // Send email
        try {
            Mail::to('info@neofaraid.com')->send(new ContactFormMail($validated));
            
            return redirect()->route('contact')
                ->with('success', 'Thank you for your message! We will get back to you soon.');
        } catch (\Exception $e) {
            return redirect()->route('contact')
                ->with('error', 'Sorry, there was an error sending your message. Please try again later.');
        }
    }
    
    /**
     * Display the dashboard for authenticated users.
     */
    public function dashboard()
    {
        $user = auth()->user();
        
        // Get user statistics
        $calculationsCount = 0;
        $totalAssetValue = 0;
        
        if (method_exists($user, 'calculations')) {
            $calculationsCount = $user->calculations()->count();
            $totalAssetValue = $user->calculations()->sum('total_assets') ?? 0;
        }
        
        // Format asset value for display
        $formattedAssetValue = $totalAssetValue > 1000 ? 
            number_format($totalAssetValue / 1000, 1) . 'K' : 
            number_format($totalAssetValue, 2);
        
        // Get recent calculations
        $recentCalculations = method_exists($user, 'calculations') ? 
            $user->calculations()->latest()->take(5)->get() : 
            collect();
        
        return view('dashboard', compact(
            'calculationsCount',
            'formattedAssetValue',
            'recentCalculations'
        ));
    }
}