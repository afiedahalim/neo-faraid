<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Calculation;

class ShareController extends Controller
{
    /**
     * Share via WhatsApp (for existing calculations)
     */
    public function whatsapp($id)
    {
        // Get the calculation by ID
        $calculation = Calculation::findOrFail($id);
        
        // Check if user is authorized to view this calculation
        if (auth()->id() !== $calculation->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Construct message with calculation details
        $message = "I just used the Faraid Calculator to plan my inheritance. ";
        $message .= "Check out this tool for accurate Islamic inheritance planning: ";
        $message .= url('/calculator/' . $calculation->id);
        
        // Redirect to WhatsApp with the message
        $whatsappUrl = "https://wa.me/?text=" . urlencode($message);
        
        return redirect()->away($whatsappUrl);
    }

    /**
     * Share via WhatsApp from home page (no calculation ID needed)
     */
    public function shareWhatsApp(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'country_code' => 'required|string|max:10',
            'whatsapp_number' => 'required|string|max:20',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Clean phone number (remove non-digits)
        $phoneNumber = preg_replace('/[^0-9]/', '', $request->whatsapp_number);
        $countryCode = preg_replace('/[^0-9+]/', '', $request->country_code);
        
        // Validate phone number format
        if (empty($phoneNumber) || strlen($phoneNumber) < 5) {
            return redirect()->back()
                ->with('error', 'Please enter a valid phone number')
                ->withInput();
        }

        // Construct message
        $message = "Hi! I found this amazing Faraid calculator that helps with Islamic inheritance planning. It's accurate, easy to use, and free! Check it out: " . url('/');
        
        // Construct WhatsApp URL
        $whatsappUrl = "https://wa.me/" . $countryCode . $phoneNumber . "?text=" . urlencode($message);
        
        // Log the share activity (optional)
        if (auth()->check()) {
            \Log::info('User shared via WhatsApp', [
                'user_id' => auth()->id(),
                'phone_number' => $countryCode . $phoneNumber,
                'timestamp' => now(),
            ]);
        }

        // Redirect to WhatsApp
        return redirect()->away($whatsappUrl);
    }

    /**
     * Share via email (optional future feature)
     */
    public function email(Request $request, $id = null)
    {
        // If ID is provided, share specific calculation
        if ($id) {
            $calculation = Calculation::findOrFail($id);
            
            // Check if user is authorized
            if (auth()->id() !== $calculation->user_id) {
                abort(403, 'Unauthorized action.');
            }
            
            return view('share.email', compact('calculation'));
        }
        
        // Otherwise, share general website
        return view('share.email');
    }

    /**
     * Send email sharing
     */
    public function sendEmail(Request $request, $id = null)
    {
        // This can be implemented later if needed
        return redirect()->back()
            ->with('error', 'Email sharing is not available yet.');
    }

    /**
     * Download calculation as PDF
     */
    public function download($id)
    {
        $calculation = Calculation::findOrFail($id);
        
        // Check if user is authorized
        if (auth()->id() !== $calculation->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // This can be implemented later if needed
        return redirect()->back()
            ->with('error', 'PDF download is not available yet.');
    }

    /**
     * Print calculation report
     */
    public function printReport($id)
    {
        $calculation = Calculation::findOrFail($id);
        
        // Check if user is authorized
        if (auth()->id() !== $calculation->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Generate PDF (you'll need to install barryvdh/laravel-dompdf)
        // $pdf = PDF::loadView('calculator.print', compact('calculation'));
        // return $pdf->download('calculation-' . $calculation->id . '.pdf');
        
        return redirect()->back()
            ->with('error', 'PDF generation is not available yet.');
    }
}