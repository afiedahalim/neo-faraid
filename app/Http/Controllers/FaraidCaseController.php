<?php

namespace App\Http\Controllers;

use App\Models\FaraidCase;
use App\Models\Calculation; // Your existing calculation model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaraidCaseController extends Controller
{
    public function index(Request $request)
    {
        // For viewing Telegram bot calculations (admin only)
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $cases = FaraidCase::orderBy('id', 'desc')
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('deceased_name', 'like', '%' . $request->search . '%')
                      ->orWhere('chat_id', 'like', '%' . $request->search . '%');
            })
            ->paginate(10);

        return view('calculator.history', [
            'telegramCases' => $cases,
            'userCalculations' => collect(), // Empty collection for this view
            'isTelegramView' => true
        ]);
    }

    public function show($id)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized access.');
        }

        $case = FaraidCase::with(['heirs', 'results'])->findOrFail($id);

        return view('calculator.show', [
            'calculation' => null,
            'telegramCase' => $case,
            'isTelegramCase' => true
        ]);
    }

    public function history(Request $request)
    {
        // Combined history view for both user calculations and Telegram cases (if admin)
        $userCalculations = collect();
        $telegramCases = collect();
        
        // Get user's own calculations
        if (Auth::check()) {
            $userCalculations = Auth::user()->calculations()
                ->orderBy('created_at', 'desc')
                ->paginate(5, ['*'], 'user_page');
        }

        // Get Telegram cases if user is admin
        if (Auth::user()->isAdmin()) {
            $telegramCases = FaraidCase::orderBy('id', 'desc')
                ->paginate(5, ['*'], 'telegram_page');
        }

        return view('calculator.history', compact('userCalculations', 'telegramCases'));
    }
}