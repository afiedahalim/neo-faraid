<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FAQController extends Controller
{
    /**
     * Display the public FAQ page for users
     */
    public function index()
    {
        try {
            // Get only published FAQs for users with pagination
            $faqs = FAQ::where('is_published', true)
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            return view('faq.index', compact('faqs'));
            
        } catch (\Exception $e) {
            \Log::error('FAQ Index Error: ' . $e->getMessage());
            
            // Handle case where column might not exist yet
            if (str_contains($e->getMessage(), 'Unknown column \'is_published\'')) {
                // Fallback to all FAQs if column doesn't exist
                $faqs = FAQ::orderBy('order', 'asc')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                return view('faq.index', compact('faqs'))
                    ->with('warning', 'Please run database migrations to enable filtering.');
            }
            
            return view('faq.index', ['faqs' => FAQ::paginate(10)]);
        }
    }
    
    /**
     * Display admin FAQ management page
     */
    public function adminIndex(Request $request)
    {
        try {
            // Get all FAQs for admin (including unpublished) with pagination
            $perPage = $request->input('per_page', 10);
            
            $faqs = FAQ::orderBy('order', 'asc')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
            
            return view('admin.faq.index', compact('faqs'));
            
        } catch (\Exception $e) {
            Log::error('FAQ Admin Index Error: ' . $e->getMessage());
            
            return redirect()->route('admin.dashboard')
                ->with('error', 'Failed to load FAQ management page. Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for creating a new FAQ
     */
    public function create()
    {
        try {
            // Get next available order number
            $lastFAQ = FAQ::orderBy('order', 'desc')->first();
            $nextOrder = $lastFAQ ? $lastFAQ->order + 1 : 1;
            
            return view('admin.faq.create', compact('nextOrder'));
            
        } catch (\Exception $e) {
            Log::error('FAQ Create Error: ' . $e->getMessage());
            
            return redirect()->route('admin.faq.index')
                ->with('error', 'Failed to load create form. Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Store a newly created FAQ in storage
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'question' => 'required|string|max:500',
                'answer' => 'required|string',
                'category' => 'required|string|in:gettingStarted,calculations,securityPrivacy,others',
                'order' => 'nullable|integer|min:0',
                'is_published' => 'nullable|boolean',
                'meta_description' => 'nullable|string|max:255',
                'read_time' => 'nullable|string|max:50',
                'is_verified' => 'nullable|boolean'
            ]);
            
            // Prepare data with defaults
            $faqData = [
                'question' => $validated['question'],
                'answer' => $validated['answer'],
                'category' => $validated['category'],
                'order' => $validated['order'] ?? 0,
                'is_published' => $request->boolean('is_published', false),
                'is_verified' => $request->boolean('is_verified', false)
            ];
            
            // Add optional fields if provided
            if ($request->filled('meta_description')) {
                $faqData['meta_description'] = $validated['meta_description'];
            }
            
            if ($request->filled('read_time')) {
                $faqData['read_time'] = $validated['read_time'];
            }
            
            FAQ::create($faqData);
            
            return redirect()->route('admin.faq.index')
                ->with('success', 'FAQ created successfully!');
                
        } catch (\Exception $e) {
            Log::error('FAQ Store Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create FAQ. Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Show the form for editing the specified FAQ
     */
    public function edit(FAQ $faq)
    {
        try {
            // Format category for display
            $categoryOptions = [
                'gettingStarted' => 'Getting Started',
                'calculations' => 'Calculations',
                'securityPrivacy' => 'Security & Privacy',
                'others' => 'Other'
            ];
            
            $displayCategory = $categoryOptions[$faq->category] ?? $faq->category;
            
            return view('admin.faq.edit', compact('faq', 'displayCategory', 'categoryOptions'));
            
        } catch (\Exception $e) {
            Log::error('FAQ Edit Error: ' . $e->getMessage());
            
            return redirect()->route('admin.faq.index')
                ->with('error', 'Failed to load edit form. Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Update the specified FAQ in storage
     */
    public function update(Request $request, FAQ $faq)
    {
        try {
            $validated = $request->validate([
                'question' => 'required|string|max:500',
                'answer' => 'required|string',
                'category' => 'required|string|in:gettingStarted,calculations,securityPrivacy,others',
                'order' => 'nullable|integer|min:0',
                'is_published' => 'nullable|boolean',
                'meta_description' => 'nullable|string|max:255',
                'read_time' => 'nullable|string|max:50',
                'is_verified' => 'nullable|boolean'
            ]);
            
            // Prepare update data
            $updateData = [
                'question' => $validated['question'],
                'answer' => $validated['answer'],
                'category' => $validated['category'],
                'order' => $validated['order'] ?? $faq->order,
                'is_published' => $request->boolean('is_published', $faq->is_published),
                'is_verified' => $request->boolean('is_verified', $faq->is_verified ?? false)
            ];
            
            // Handle optional fields
            $updateData['meta_description'] = $request->filled('meta_description') 
                ? $validated['meta_description'] 
                : null;
            
            $updateData['read_time'] = $request->filled('read_time') 
                ? $validated['read_time'] 
                : null;
            
            $faq->update($updateData);
            
            return redirect()->route('admin.faq.index')
                ->with('success', 'FAQ updated successfully!');
                
        } catch (\Exception $e) {
            Log::error('FAQ Update Error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update FAQ. Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Remove the specified FAQ from storage
     */
    public function destroy(FAQ $faq)
    {
        try {
            $faq->delete();
            
            return redirect()->route('admin.faq.index')
                ->with('success', 'FAQ deleted successfully!');
                
        } catch (\Exception $e) {
            Log::error('FAQ Destroy Error: ' . $e->getMessage());
            
            return redirect()->route('admin.faq.index')
                ->with('error', 'Failed to delete FAQ. Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Toggle FAQ publication status
     */
    public function toggleStatus(FAQ $faq)
    {
        try {
            $faq->update([
                'is_published' => !$faq->is_published
            ]);
            
            $status = $faq->is_published ? 'published' : 'unpublished';
            
            return redirect()->route('admin.faq.index')
                ->with('success', "FAQ {$status} successfully!");
                
        } catch (\Exception $e) {
            Log::error('FAQ Toggle Status Error: ' . $e->getMessage());
            
            return redirect()->route('admin.faq.index')
                ->with('error', 'Failed to toggle FAQ status. Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Search FAQs for admin
     */
    public function search(Request $request)
    {
        try {
            $search = $request->input('search', '');
            
            $faqs = FAQ::when($search, function($query) use ($search) {
                    $query->where('question', 'like', "%{$search}%")
                          ->orWhere('answer', 'like', "%{$search}%")
                          ->orWhere('category', 'like', "%{$search}%");
                })
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'html' => view('admin.faq.partials.faq_table', compact('faqs'))->render(),
                    'pagination' => view('admin.faq.partials.pagination', compact('faqs'))->render()
                ]);
            }
            
            return view('admin.faq.index', compact('faqs'));
            
        } catch (\Exception $e) {
            Log::error('FAQ Search Error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search failed. Please try again.'
                ], 500);
            }
            
            return redirect()->route('admin.faq.index')
                ->with('error', 'Search failed. Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Search FAQs for public (user) page
     */
    public function publicSearch(Request $request)
    {
        try {
            $search = $request->input('search', '');
            
            $faqs = FAQ::where('is_published', true)
                ->when($search, function($query) use ($search) {
                    $query->where('question', 'like', "%{$search}%")
                          ->orWhere('answer', 'like', "%{$search}%")
                          ->orWhere('category', 'like', "%{$search}%");
                })
                ->orderBy('order', 'asc')
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'html' => view('faq.partials.faq_list', compact('faqs'))->render(),
                    'pagination' => view('faq.partials.pagination', compact('faqs'))->render(),
                    'count' => $faqs->count(),
                    'total' => $faqs->total()
                ]);
            }
            
            return view('faq.index', compact('faqs'));
            
        } catch (\Exception $e) {
            Log::error('FAQ Public Search Error: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search failed. Please try again.'
                ], 500);
            }
            
            return redirect()->route('faq.index')
                ->with('error', 'Search failed. Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Get FAQ statistics for admin dashboard
     */
    public function stats()
    {
        try {
            $totalFAQs = FAQ::count();
            $publishedFAQs = FAQ::where('is_published', true)->count();
            $unpublishedFAQs = FAQ::where('is_published', false)->count();
            $verifiedFAQs = FAQ::where('is_verified', true)->count();
            
            // Count by category
            $categories = FAQ::selectRaw('category, count(*) as count')
                ->groupBy('category')
                ->orderBy('count', 'desc')
                ->get()
                ->map(function($item) {
                    $categoryLabels = [
                        'gettingStarted' => 'Getting Started',
                        'calculations' => 'Calculations',
                        'securityPrivacy' => 'Security & Privacy',
                        'others' => 'Other'
                    ];
                    
                    return [
                        'category' => $categoryLabels[$item->category] ?? $item->category,
                        'count' => $item->count
                    ];
                });
            
            return response()->json([
                'success' => true,
                'stats' => [
                    'total' => $totalFAQs,
                    'published' => $publishedFAQs,
                    'unpublished' => $unpublishedFAQs,
                    'verified' => $verifiedFAQs,
                    'unverified' => $totalFAQs - $verifiedFAQs,
                    'categories' => $categories
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('FAQ Stats Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load FAQ statistics. Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Export FAQs to CSV
     */
    public function export()
    {
        try {
            $faqs = FAQ::orderBy('order', 'asc')
                ->orderBy('created_at', 'desc')
                ->get();
            
            $filename = 'faqs-export-' . date('Y-m-d-H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            $callback = function() use ($faqs) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fwrite($file, "\xEF\xBB\xBF");
                
                // Headers
                fputcsv($file, [
                    'ID', 'Question', 'Answer', 'Category', 'Order', 
                    'Published', 'Verified', 'Read Time', 'Meta Description', 'Created At', 'Updated At'
                ]);
                
                // Data
                foreach ($faqs as $faq) {
                    $categoryLabels = [
                        'gettingStarted' => 'Getting Started',
                        'calculations' => 'Calculations',
                        'securityPrivacy' => 'Security & Privacy',
                        'others' => 'Other'
                    ];
                    
                    $category = $categoryLabels[$faq->category] ?? $faq->category;
                    
                    fputcsv($file, [
                        $faq->id,
                        $faq->question,
                        strip_tags($faq->answer), // Remove HTML tags for CSV
                        $category,
                        $faq->order,
                        $faq->is_published ? 'Yes' : 'No',
                        $faq->is_verified ? 'Yes' : 'No',
                        $faq->read_time ?? 'N/A',
                        $faq->meta_description ?? 'N/A',
                        $faq->created_at->format('Y-m-d H:i:s'),
                        $faq->updated_at->format('Y-m-d H:i:s')
                    ]);
                }
                
                fclose($file);
            };
            
            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('FAQ Export Error: ' . $e->getMessage());
            
            return redirect()->route('admin.faq.index')
                ->with('error', 'Failed to export FAQs. Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Import FAQs from CSV
     */
    public function import(Request $request)
    {
        try {
            $request->validate([
                'csv_file' => 'required|file|mimes:csv,txt|max:2048'
            ]);
            
            $file = $request->file('csv_file');
            $path = $file->getRealPath();
            
            $imported = 0;
            $failed = 0;
            $errors = [];
            
            if (($handle = fopen($path, 'r')) !== false) {
                // Skip header
                fgetcsv($handle);
                
                $rowNumber = 1;
                while (($data = fgetcsv($handle)) !== false) {
                    $rowNumber++;
                    
                    try {
                        // Validate required fields
                        if (empty($data[1]) || empty($data[2])) {
                            $failed++;
                            $errors[] = "Row {$rowNumber}: Missing question or answer";
                            continue;
                        }
                        
                        // Normalize category
                        $category = $this->normalizeCategory($data[3] ?? 'others');
                        
                        FAQ::create([
                            'question' => trim($data[1]),
                            'answer' => trim($data[2]),
                            'category' => $category,
                            'order' => intval($data[4] ?? 0),
                            'is_published' => strtolower(trim($data[5] ?? 'yes')) === 'yes',
                            'is_verified' => strtolower(trim($data[6] ?? 'no')) === 'yes',
                            'read_time' => !empty($data[7]) ? trim($data[7]) : null,
                            'meta_description' => !empty($data[8]) ? trim($data[8]) : null,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        
                        $imported++;
                    } catch (\Exception $e) {
                        $failed++;
                        $errors[] = "Row {$rowNumber}: " . $e->getMessage();
                        Log::warning('Failed to import FAQ row ' . $rowNumber . ': ' . $e->getMessage());
                    }
                }
                
                fclose($handle);
            }
            
            $message = "Imported {$imported} FAQs successfully. {$failed} failed.";
            
            if (!empty($errors) && $failed > 0) {
                $message .= " Errors: " . implode('; ', array_slice($errors, 0, 5));
                if (count($errors) > 5) {
                    $message .= ' and ' . (count($errors) - 5) . ' more errors';
                }
            }
            
            return redirect()->route('admin.faq.index')
                ->with($failed === 0 ? 'success' : 'warning', $message);
                
        } catch (\Exception $e) {
            Log::error('FAQ Import Error: ' . $e->getMessage());
            
            return redirect()->route('admin.faq.index')
                ->with('error', 'Failed to import FAQs. Please check the CSV format. Error: ' . $e->getMessage());
        }
    }
    
    /**
     * Normalize category string to database format
     */
    private function normalizeCategory(string $category): string
    {
        $category = strtolower(trim($category));
        
        // Remove special characters and spaces
        $category = preg_replace('/[^a-z0-9]/', '', $category);
        
        if (str_contains($category, 'gettingstarted') || $category === 'gettingstarted') {
            return 'gettingStarted';
        }
        
        if (str_contains($category, 'calculations') || $category === 'calculation') {
            return 'calculations';
        }
        
        if (str_contains($category, 'security') || str_contains($category, 'privacy')) {
            return 'securityPrivacy';
        }
        
        return 'others';
    }
    
    /**
     * Show FAQ details modal (AJAX)
     */
    public function show(FAQ $faq)
    {
        try {
            // Check if FAQ is published or user is admin
            if (!$faq->is_published && !auth()->check()) {
                abort(404);
            }
            
            // Format data for modal
            $categoryLabels = [
                'gettingStarted' => 'Getting Started',
                'calculations' => 'Calculations',
                'securityPrivacy' => 'Security & Privacy',
                'others' => 'Other'
            ];
            
            $data = [
                'id' => $faq->id,
                'question' => $faq->question,
                'answer' => $faq->answer,
                'category' => $faq->category,
                'categoryLabel' => $categoryLabels[$faq->category] ?? $faq->category,
                'order' => $faq->order,
                'is_published' => $faq->is_published,
                'is_verified' => $faq->is_verified ?? false,
                'read_time' => $faq->read_time,
                'meta_description' => $faq->meta_description,
                'created_at' => $faq->created_at->format('M d, Y h:i A'),
                'updated_at' => $faq->updated_at->format('M d, Y h:i A')
            ];
            
            return response()->json([
                'success' => true,
                'faq' => $data
            ]);
            
        } catch (\Exception $e) {
            Log::error('FAQ Show Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load FAQ details. Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Bulk actions for FAQs
     */
    public function bulkActions(Request $request)
    {
        try {
            $request->validate([
                'action' => 'required|string|in:delete,publish,unpublish,verify,unverify',
                'ids' => 'required|array',
                'ids.*' => 'exists:faqs,id'
            ]);
            
            $action = $request->action;
            $ids = $request->ids;
            $count = count($ids);
            
            switch ($action) {
                case 'delete':
                    FAQ::whereIn('id', $ids)->delete();
                    $message = "{$count} FAQ(s) deleted successfully!";
                    break;
                    
                case 'publish':
                    FAQ::whereIn('id', $ids)->update(['is_published' => true]);
                    $message = "{$count} FAQ(s) published successfully!";
                    break;
                    
                case 'unpublish':
                    FAQ::whereIn('id', $ids)->update(['is_published' => false]);
                    $message = "{$count} FAQ(s) unpublished successfully!";
                    break;
                    
                case 'verify':
                    FAQ::whereIn('id', $ids)->update(['is_verified' => true]);
                    $message = "{$count} FAQ(s) verified successfully!";
                    break;
                    
                case 'unverify':
                    FAQ::whereIn('id', $ids)->update(['is_verified' => false]);
                    $message = "{$count} FAQ(s) unverified successfully!";
                    break;
                    
                default:
                    throw new \Exception('Invalid bulk action');
            }
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            Log::error('FAQ Bulk Actions Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to perform bulk action. Error: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Reorder FAQs
     */
    public function reorder(Request $request)
    {
        try {
            $request->validate([
                'faqs' => 'required|array',
                'faqs.*.id' => 'required|exists:faqs,id',
                'faqs.*.order' => 'required|integer|min:0'
            ]);
            
            foreach ($request->faqs as $faqData) {
                FAQ::where('id', $faqData['id'])->update(['order' => $faqData['order']]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'FAQs reordered successfully!'
            ]);
            
        } catch (\Exception $e) {
            Log::error('FAQ Reorder Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to reorder FAQs. Error: ' . $e->getMessage()
            ], 500);
        }
    }
}