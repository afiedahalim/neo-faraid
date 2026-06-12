@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-download me-2"></i>Graphviz Installation Required</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        To generate family tree visualizations, Graphviz needs to be installed on your system.
                    </div>
                    
                    <h5 class="mb-3">Installation Steps for Windows:</h5>
                    
                    <div class="card mb-3">
                        <div class="card-header bg-warning">
                            <h6 class="mb-0">Windows Installation</h6>
                        </div>
                        <div class="card-body">
                            <ol>
                                <li>Download Graphviz from <a href="https://graphviz.org/download/" target="_blank" class="fw-bold">graphviz.org</a></li>
                                <li>Run the installer (graphviz-x.xx.x.msi)</li>
                                <li>During installation, <strong>check "Add Graphviz to the system PATH for all users"</strong></li>
                                <li>Complete the installation</li>
                                <li>Restart your computer or Laragon</li>
                                <li>Verify installation by running in Command Prompt:
                                    <pre class="bg-dark text-light p-2 mt-2">where dot</pre>
                                </li>
                                <li>Or test in Laravel:
                                    <pre class="bg-dark text-light p-2 mt-2">php artisan graphviz:test</pre>
                                </li>
                            </ol>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <h5>Current Status</h5>
                        <div class="alert alert-secondary">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Note:</strong> If Graphviz is not installed, the system will automatically generate an HTML version of the family tree instead.
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Go Back
                        </a>
                        <a href="https://graphviz.org/download/" target="_blank" class="btn btn-success">
                            <i class="fas fa-download me-2"></i>Download Graphviz Now
                        </a>
                        <a href="{{ route('graphviz.test') }}" class="btn btn-info">
                            <i class="fas fa-check-circle me-2"></i>Test Installation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection