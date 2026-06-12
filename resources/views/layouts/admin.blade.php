<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Neo Faraid') - Admin Panel</title>
    <meta name="description" content="Neo Faraid Administration Panel">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        :root {
            --primary-color: #1a5fb4;
            --primary-dark: #0d2d5c;
            --secondary-color: #2d7ad6;
            --accent-color: #ffd700;
            --light-bg: #f5f7fa;
            --text-light: #666;
            --white: #ffffff;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif !important;
        }
        
        body {
            background-color: var(--light-bg);
            color: #333;
            min-height: 100vh;
        }
        
        /* Top Navigation */
        .admin-nav {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
            color: var(--white);
            padding: 1rem 2rem;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: var(--shadow);
        }
        
        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .nav-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--white);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-brand span {
            color: var(--accent-color);
        }
        
        .nav-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }
        
        .nav-link {
            color: var(--white);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: var(--transition);
            position: relative;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .nav-link.active {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .logout-btn {
            background: rgba(255, 255, 255, 0.2);
            color: var(--white);
            border: 1px solid rgba(255, 255, 255, 0.3);
            padding: 0.5rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .logout-btn:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        /* Main Content */
        .main-content {
            margin-top: 80px;
            min-height: calc(100vh - 120px);
            padding: 2rem;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }
        
        /* Footer */
        .admin-footer {
            background: var(--light-bg);
            padding: 2rem;
            text-align: center;
            color: var(--text-light);
            border-top: 1px solid rgba(0, 0, 0, 0.1);
        }
        
        /* Alert Container */
        .alert-container {
            position: fixed;
            top: 100px;
            right: 2rem;
            z-index: 9999;
            max-width: 400px;
        }
        
        .alert-message {
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            box-shadow: var(--shadow);
            animation: slideInRight 0.3s ease;
        }
        
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }
        
        .alert-error {
            background-color: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }
        
        .alert-warning {
            background-color: #fef3c7;
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }
        
        .alert-info {
            background-color: #dbeafe;
            color: #1e40af;
            border-left: 4px solid #3b82f6;
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .admin-nav {
                padding: 1rem;
            }
            
            .nav-container {
                flex-direction: column;
                gap: 1rem;
            }
            
            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
                gap: 0.5rem;
            }
            
            .nav-link {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
            
            .main-content {
                padding: 1rem;
                margin-top: 120px;
            }
            
            .alert-container {
                left: 1rem;
                right: 1rem;
                max-width: none;
            }
        }
        
        /* Button Styles */
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            font-size: 1rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: var(--white);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            color: var(--white);
        }
        
        .btn-secondary:hover {
            background-color: var(--primary-color);
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background-color: #ef4444;
            color: var(--white);
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
            transform: translateY(-2px);
        }
        
        /* Card Styles */
        .card {
            background: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: var(--shadow);
            margin-bottom: 1.5rem;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--light-bg);
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        /* Table Styles */
        .table-container {
            overflow-x: auto;
            background: var(--white);
            border-radius: 8px;
            box-shadow: var(--shadow);
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .data-table th {
            background-color: var(--light-bg);
            padding: 1rem;
            text-align: left;
            font-weight: 600;
            color: var(--primary-dark);
            border-bottom: 2px solid var(--primary-color);
        }
        
        .data-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--light-bg);
        }
        
        .data-table tr:hover {
            background-color: rgba(26, 95, 180, 0.05);
        }
        
        /* Form Styles (Added for consistency) */
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--primary-dark);
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.1);
        }
        
        .form-control.is-invalid {
            border-color: #ef4444;
        }
        
        .invalid-feedback {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid #e0e0e0;
        }
        
        .page-title h1 {
            font-size: 1.75rem;
            color: var(--primary-dark);
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            color: var(--text-light);
        }
        
        /* Action buttons in tables */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
        }
        
        /* Modal styles for FAQ details */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1100;
        }
        
        .modal-content {
            background: var(--white);
            border-radius: 12px;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: modalSlideIn 0.3s ease;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Category badges */
        .category-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 500;
        }
        
        .badge-getting-started {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .badge-calculations {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .badge-security-privacy {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .badge-other {
            background-color: #f3f4f6;
            color: #374151;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Top Navigation -->
    <nav class="admin-nav">
        <div class="nav-container">
            <a href="{{ route('admin.dashboard') }}" class="nav-brand">
                Neo Faraid <span>Admin</span>
            </a>
            
            <div class="nav-links">
                <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                    Manage Users
                </a>
                <a href="{{ route('admin.feedback.index') }}" class="nav-link {{ request()->is('admin/feedback*') ? 'active' : '' }}">
                    Manage Feedback
                </a>
                <a href="{{ route('admin.faq.index') }}" class="nav-link {{ request()->is('admin/faq*') ? 'active' : '' }}">
                    Manage FAQ
                </a>
                <a href="{{ route('admin.calculations.index') }}" class="nav-link {{ request()->is('admin/calculations*') ? 'active' : '' }}">
                    Manage Calculations
                </a>
                <a href="{{ route('dashboard') }}" class="nav-link">
                    User Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Alert Container -->
    <div class="alert-container" id="alert-container">
        @if(session('success'))
            <div class="alert-message alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert-message alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert-message alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>Please fix the following errors:</span>
                <ul style="margin-top: 0.5rem; padding-left: 1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="admin-footer">
        <p>© {{ date('Y') }} Neo Faraid. All rights reserved.</p>
    </footer>

    @stack('scripts')

    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert-message');
            
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 300);
                }, 5000);
            });
            
            // Fade in page
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.3s ease';
            
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 50);
            
            // Button hover effects
            const buttons = document.querySelectorAll('.btn, .nav-link, .logout-btn');
            buttons.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    if (!this.disabled) {
                        this.style.transform = 'translateY(-2px)';
                    }
                });
                
                btn.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
        
        // Show alert function
        function showAlert(message, type = 'success') {
            const alertContainer = document.getElementById('alert-container');
            
            const alert = document.createElement('div');
            alert.className = `alert-message alert-${type}`;
            
            const icons = {
                success: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
                error: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
                warning: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                info: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'
            };
            
            alert.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="20" height="20">
                    ${icons[type] || icons.info}
                </svg>
                <span>${message}</span>
            `;
            
            alertContainer.appendChild(alert);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 300);
            }, 5000);
        }
        
        // Confirm dialog function
        function confirmAction(message, callback) {
            if (confirm(message)) {
                callback();
            }
        }
        
        // FAQ Modal Function
        function showFAQDetails(faqData) {
            const modalHtml = `
                <div class="modal-overlay" onclick="closeFAQModal()">
                    <div class="modal-content" onclick="event.stopPropagation()">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">FAQ Details</h3>
                                <button class="btn btn-secondary btn-sm" onclick="closeFAQModal()">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Close
                                </button>
                            </div>
                            <div style="padding: 1.5rem;">
                                <div style="margin-bottom: 1.5rem;">
                                    <strong style="display: block; margin-bottom: 0.5rem; color: var(--primary-dark);">Question:</strong>
                                    <p style="background: var(--light-bg); padding: 1rem; border-radius: 8px; border-left: 4px solid var(--primary-color);">${faqData.question}</p>
                                </div>
                                
                                <div style="margin-bottom: 1.5rem;">
                                    <strong style="display: block; margin-bottom: 0.5rem; color: var(--primary-dark);">Answer:</strong>
                                    <div style="background: var(--light-bg); padding: 1rem; border-radius: 8px; border-left: 4px solid #10b981; white-space: pre-wrap;">${faqData.answer}</div>
                                </div>
                                
                                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                                    <div>
                                        <strong style="display: block; margin-bottom: 0.5rem; color: var(--primary-dark);">Category:</strong>
                                        <span class="category-badge badge-${faqData.category}">${faqData.categoryLabel}</span>
                                    </div>
                                    <div>
                                        <strong style="display: block; margin-bottom: 0.5rem; color: var(--primary-dark);">Display Order:</strong>
                                        <span style="font-weight: 500;">${faqData.order}</span>
                                    </div>
                                    <div>
                                        <strong style="display: block; margin-bottom: 0.5rem; color: var(--primary-dark);">Created:</strong>
                                        <span>${faqData.created}</span>
                                    </div>
                                </div>
                                
                                <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid var(--light-bg);">
                                    <a href="/admin/faq/${faqData.id}/edit" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="16" height="16" style="margin-right: 0.5rem;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit FAQ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }
        
        function closeFAQModal() {
            const modal = document.querySelector('.modal-overlay');
            if (modal) {
                modal.remove();
            }
        }
        
        // Initialize FAQ show buttons
        function initFAQShowButtons() {
            document.querySelectorAll('.show-faq-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const faqData = {
                        id: this.dataset.faqId,
                        question: this.dataset.question,
                        answer: this.dataset.answer,
                        category: this.dataset.category,
                        categoryLabel: this.dataset.categoryLabel,
                        order: this.dataset.order,
                        created: this.dataset.created
                    };
                    showFAQDetails(faqData);
                });
            });
        }
    </script>
</body>
</html>