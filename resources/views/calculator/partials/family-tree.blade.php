<!-- Family Tree Partial -->
@if(isset($calculation))
<div class="glass-card">
    <div class="card-header">
        <svg class="card-header-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"/>
        </svg>
        <h2 class="card-title">Family Tree</h2>
        <span class="card-badge">Auto-Generated</span>
    </div>
    
    <div class="card-body">
        <div class="chart-section">
            <div class="chart-section-title">
                <h3>Family Relationship Visualization</h3>
                <p>Showing family hierarchy and inheritance distribution</p>
            </div>
            
            <div id="familyTreeContainer">
                @if($calculation->family_tree_image && $calculation->has_family_tree)
                    @php
                        $isSvg = strpos($calculation->family_tree_image, '.svg') !== false;
                        $isHtml = strpos($calculation->family_tree_image, '.html') !== false;
                        $treeUrl = $calculation->getFamilyTreeUrl();
                    @endphp
                    
                    @if($isSvg)
                        <!-- SVG Tree -->
                        <div class="family-tree-container">
                            <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; min-height: 400px; display: flex; align-items: center; justify-content: center;">
                                @if($calculation->family_tree_exists)
                                    <div style="width: 100%; max-width: 100%; overflow: auto;">
                                        <div style="text-align: center;">
                                            <img src="{{ $treeUrl }}" 
                                                 alt="Family Tree" 
                                                 class="family-tree-image"
                                                 style="max-width: 100%; height: auto; border: 1px solid #e2e8f0; border-radius: 4px;"
                                                 onerror="this.onerror=null; this.src='{{ asset('images/default-family-tree.svg') }}';">
                                        </div>
                                        <p class="text-muted mt-2" style="color: #666; font-size: 14px; text-align: center;">
                                            SVG Family Tree - Drag to pan, scroll to zoom
                                        </p>
                                    </div>
                                @else
                                    <div class="text-center py-8">
                                        <svg width="48" height="48" fill="none" stroke="var(--warning-color)" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        <h4 style="color: var(--warning-color); margin-top: 1rem;">File Not Found</h4>
                                        <p style="color: var(--gray-500);">The family tree file could not be found.</p>
                                        <button onclick="generateFamilyTreeNow({{ $calculation->id }})" 
                                                class="btn btn-primary mt-3" 
                                                style="display: inline-flex; align-items: center; gap: 8px;">
                                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Regenerate Tree
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @elseif($isHtml)
                        <!-- HTML Tree -->
                        <div class="family-tree-container">
                            <div style="text-align: center; padding: 20px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; min-height: 400px;">
                                @if($calculation->family_tree_exists)
                                    <iframe src="{{ $treeUrl }}" 
                                            style="width: 100%; height: 600px; border: none; border-radius: 8px;">
                                    </iframe>
                                    <p class="text-muted mt-2" style="color: #666; font-size: 14px; text-align: center;">
                                        HTML-based family tree visualization
                                    </p>
                                @else
                                    <div class="text-center py-8">
                                        <svg width="48" height="48" fill="none" stroke="var(--warning-color)" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                        </svg>
                                        <h4 style="color: var(--warning-color); margin-top: 1rem;">File Not Found</h4>
                                        <p style="color: var(--gray-500);">The family tree file could not be found.</p>
                                        <button onclick="generateFamilyTreeNow({{ $calculation->id }})" 
                                                class="btn btn-primary mt-3" 
                                                style="display: inline-flex; align-items: center; gap: 8px;">
                                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                            </svg>
                                            Regenerate Tree
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        <!-- Unknown format -->
                        <div class="family-tree-container">
                            <div class="text-center py-8">
                                <svg width="48" height="48" fill="none" stroke="var(--warning-color)" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <h4 style="color: var(--warning-color); margin-top: 1rem;">Unsupported Format</h4>
                                <p style="color: var(--gray-500);">The family tree format is not supported.</p>
                                <button onclick="generateFamilyTreeNow({{ $calculation->id }})" 
                                        class="btn btn-primary mt-3" 
                                        style="display: inline-flex; align-items: center; gap: 8px;">
                                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Regenerate Tree
                                </button>
                            </div>
                        </div>
                    @endif
                    
                    @if($calculation->family_tree_exists)
                    <div class="tree-actions mt-4" style="display: flex; gap: 10px; justify-content: center; flex-wrap: wrap;">
                        <a href="{{ route('calculator.download-tree', $calculation->id) }}" 
                           class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download
                        </a>
                        
                        <button onclick="printFamilyTree('{{ $treeUrl }}')" 
                                class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px;">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2h2"/>
                            </svg>
                            Print
                        </button>
                        
                        @if($calculation->canRegenerateTree())
                        <button onclick="generateFamilyTreeNow({{ $calculation->id }})" 
                                class="btn btn-warning" 
                                id="regenerateTreeBtn"
                                style="display: inline-flex; align-items: center; gap: 8px;">
                            <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Regenerate
                        </button>
                        @endif
                    </div>
                    
                    @if($calculation->tree_generated_at)
                        <div class="text-center mt-3" style="color: #666; font-size: 14px;">
                            Generated: {{ $calculation->tree_generated_date }}
                        </div>
                    @endif
                    @endif
                @else
                    <div class="family-tree-container">
                        <div class="text-center py-8">
                            <svg width="48" height="48" fill="none" stroke="var(--gray-400)" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <h4 style="color: var(--gray-600); margin-top: 1rem;">Family Tree Not Generated</h4>
                            <p style="color: var(--gray-500);">Generate a visual family tree for this calculation.</p>
                            <div style="margin-top: 1.5rem;">
                                <button onclick="generateFamilyTreeNow({{ $calculation->id }})" 
                                        class="btn btn-primary" 
                                        id="generateTreeBtn"
                                        style="display: inline-flex; align-items: center; gap: 8px;">
                                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Generate Family Tree
                                </button>
                                
                                <div class="mt-3" style="color: #666; font-size: 14px;">
                                    <p>Generates a visual representation of family relationships and inheritance distribution.</p>
                                    <p><small>Note: Requires Graphviz for SVG format, otherwise HTML format will be generated.</small></p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Family Tree Information -->
            <div class="info-item" style="margin-top: 2rem; background: var(--primary-light); border-color: var(--primary-color);">
                <div class="info-label">Family Tree Information</div>
                <div class="info-value" style="color: var(--primary-color);">Visual Relationship Map</div>
                <div class="info-description">
                    This family tree visualization shows the hierarchical relationships between the deceased and all heirs. 
                    Each box represents a person, with colors indicating heir type and labels showing inheritance shares. 
                    Lines connect family members to show relationships and inheritance paths.
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Family Tree Functions
function printFamilyTree(treeUrl) {
    if (!treeUrl) {
        showAlert('error', 'No family tree available to print.');
        return;
    }
    
    const win = window.open('', '_blank');
    
    win.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>Family Tree - Print</title>
            <style>
                body { 
                    margin: 0; 
                    padding: 20px; 
                    text-align: center; 
                    font-family: Arial, sans-serif;
                }
                img { 
                    max-width: 100%; 
                    height: auto; 
                    border: 1px solid #ddd;
                }
                .print-header {
                    text-align: center;
                    margin-bottom: 20px;
                    padding-bottom: 10px;
                    border-bottom: 2px solid #333;
                }
                .print-header h1 {
                    color: #1a5fb4;
                    margin-bottom: 5px;
                }
                .print-info {
                    color: #666;
                    font-size: 14px;
                    margin-bottom: 20px;
                }
                @media print {
                    body { padding: 0; }
                    .print-header { border-bottom: 2px solid #000; }
                }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h1>Family Tree - Inheritance Distribution</h1>
                <div class="print-info">
                    Deceased: {{ $calculation->deceased_name ?? 'Not Provided' }} | 
                    Date: {{ $calculation->created_at ? \Carbon\Carbon::parse($calculation->created_at)->format('d M Y') : '' }}
                </div>
            </div>
            <img src="${treeUrl}" alt="Family Tree">
            <script>
                setTimeout(() => {
                    window.print();
                    setTimeout(() => window.close(), 1000);
                }, 500);
            <\/script>
        </body>
        </html>
    `);
    win.document.close();
}

function generateFamilyTreeNow(calculationId) {
    const generateBtn = document.getElementById('generateTreeBtn') || document.getElementById('regenerateTreeBtn');
    const originalText = generateBtn ? generateBtn.innerHTML : '';
    
    // Show loading state
    if (generateBtn) {
        generateBtn.innerHTML = `
            <div style="display: inline-flex; align-items: center; gap: 8px;">
                <div class="spinner" style="width: 16px; height: 16px; border: 2px solid #f3f3f3; border-top: 2px solid #3498db; border-radius: 50%; animation: spin 1s linear infinite;"></div>
                Generating...
            </div>
        `;
        generateBtn.disabled = true;
    }
    
    // Show loading message in container
    const container = document.getElementById('familyTreeContainer');
    if (container) {
        container.innerHTML = `
            <div class="tree-loading" style="text-align: center; padding: 40px;">
                <div class="spinner" style="width: 40px; height: 40px; border: 4px solid #f3f3f3; border-top: 4px solid #1a5fb4; border-radius: 50%; margin: 0 auto; animation: spin 1s linear infinite;"></div>
                <h4 style="color: var(--primary-color); margin-top: 1rem;">Generating Family Tree...</h4>
                <p style="color: var(--gray-500);">Please wait while we create your family tree visualization</p>
                <p style="color: var(--gray-500); font-size: 14px;">This may take a few seconds</p>
            </div>
        `;
    }
    
    // AJAX request to generate family tree
    fetch('/calculator/' + calculationId + '/generate-tree', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(data => {
                throw new Error(data.message || 'Network response was not ok');
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Tree generation response:', data);
        
        if (data.success) {
            showToast('success', 'Family tree generated successfully!');
            
            // Reload page after short delay to show new tree
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || 'Failed to generate family tree');
        }
    })
    .catch(error => {
        console.error('Error generating tree:', error);
        
        showToast('error', error.message || 'Network error. Please try again.');
        
        // Reset button
        if (generateBtn) {
            generateBtn.innerHTML = originalText;
            generateBtn.disabled = false;
        }
        
        // Show error state
        if (container) {
            container.innerHTML = `
                <div class="text-center py-8">
                    <svg width="48" height="48" fill="none" stroke="var(--danger-color)" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <h4 style="color: var(--danger-color); margin-top: 1rem;">Generation Failed</h4>
                    <p style="color: var(--gray-500);">${error.message || 'Could not generate family tree'}</p>
                    <button onclick="generateFamilyTreeNow(${calculationId})" class="btn btn-primary mt-3" style="display: inline-flex; align-items: center; gap: 8px;">
                        <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="width: 16px; height: 16px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Try Again
                    </button>
                </div>
            `;
        }
    });
}

function showToast(type, message) {
    // Create toast element
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        border-radius: 6px;
        color: white;
        font-weight: 500;
        z-index: 9999;
        animation: slideIn 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    if (type === 'success') {
        toast.style.backgroundColor = '#25D366';
        toast.innerHTML = `
            <div style="display: flex; align-items: center; gap: 8px;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                ${message}
            </div>
        `;
    } else if (type === 'error') {
        toast.style.backgroundColor = '#dc3545';
        toast.innerHTML = `
            <div style="display: flex; align-items: center; gap: 8px;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                ${message}
            </div>
        `;
    } else {
        toast.style.backgroundColor = '#1a5fb4';
        toast.textContent = message;
    }
    
    document.body.appendChild(toast);
    
    // Remove toast after 3 seconds
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

function showAlert(type, message) {
    // Use a nicer alert system
    showToast(type, message);
}

// Add CSS for animations
if (!document.getElementById('tree-animation-styles')) {
    const style = document.createElement('style');
    style.id = 'tree-animation-styles';
    style.textContent = `
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
}

// Make functions globally available
window.printFamilyTree = printFamilyTree;
window.generateFamilyTreeNow = generateFamilyTreeNow;
</script>
@endpush
@endif