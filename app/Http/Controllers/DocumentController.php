<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Calculation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display user documents
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Document::where('user_id', $user->id);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $documents = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('documents.index', compact('documents'));
    }

    /**
     * Save calculation as document
     */
    public function saveCalculation(Request $request, Calculation $calculation)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        // In a real application, you would generate a PDF here
        // For now, we'll create a placeholder document
        
        $document = Document::create([
            'user_id' => Auth::id(),
            'calculation_id' => $calculation->id,
            'title' => $request->title,
            'file_path' => 'documents/placeholder.pdf', // Placeholder path
            'file_type' => 'pdf',
            'file_size' => 1024, // 1KB placeholder
            'description' => $request->description,
            'metadata' => [
                'calculation_id' => $calculation->id,
                'calculation_title' => $calculation->title,
                'saved_at' => now()->toDateTimeString(),
            ],
            'status' => 'active',
        ]);
        
        return redirect()->route('documents.index')
                        ->with('success', 'Calculation saved as document successfully!');
    }

    /**
     * Show document
     */
    public function show(Document $document)
    {
        // Check authorization
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to document.');
        }
        
        return view('documents.show', compact('document'));
    }

    /**
     * Delete document
     */
    public function destroy(Document $document)
    {
        // Check authorization
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to document.');
        }
        
        // In a real application, delete the file from storage
        // Storage::delete($document->file_path);
        
        $document->delete();
        
        return redirect()->route('documents.index')
                        ->with('success', 'Document deleted successfully!');
    }

    /**
     * Download document
     */
    public function download(Document $document)
    {
        // Check authorization
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to document.');
        }
        
        // In a real application, you would return the actual file
        // return Storage::download($document->file_path);
        
        // For now, return a placeholder response
        return response()->json([
            'message' => 'Document download would start here',
            'document' => $document->only(['id', 'title', 'file_path']),
        ]);
    }

    /**
     * Archive document
     */
    public function archive(Document $document)
    {
        // Check authorization
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to document.');
        }
        
        $document->update(['status' => 'archived']);
        
        return redirect()->route('documents.index')
                        ->with('success', 'Document archived successfully!');
    }

    /**
     * Restore archived document
     */
    public function restore(Document $document)
    {
        // Check authorization
        if ($document->user_id !== Auth::id()) {
            abort(403, 'Unauthorized access to document.');
        }
        
        $document->update(['status' => 'active']);
        
        return redirect()->route('documents.index')
                        ->with('success', 'Document restored successfully!');
    }
}