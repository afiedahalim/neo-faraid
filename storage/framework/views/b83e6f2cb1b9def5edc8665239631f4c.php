<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', config('app.name', 'Neo Faraid')); ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Vite CSS -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    
    <!-- Global Styles -->
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Figtree', sans-serif; 
            min-height: 100vh;
            position: relative;
            background: #f5f7fa;
        }
        .content { position: relative; z-index: 10; }
        
        /* Notification Styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 9999;
            animation: slideIn 0.3s ease;
            max-width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .notification.success {
            background: linear-gradient(135deg, #25D366 0%, #20c997 100%);
        }
        
        .notification.error {
            background: linear-gradient(135deg, #dc3545 0%, #ee5a52 100%);
        }
        
        .notification.warning {
            background: linear-gradient(135deg, #ffc107 0%, #ffd700 100%);
            color: #333;
        }
        
        .notification.info {
            background: linear-gradient(135deg, #0d6efd 0%, #17a2b8 100%);
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Loading Spinner */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        .loading.dark {
            border: 3px solid rgba(0,0,0,.1);
            border-top-color: #333;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 99999;
            flex-direction: column;
            gap: 1rem;
        }
        
        .loading-overlay .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Background Component -->
    <?php if (isset($component)) { $__componentOriginal12fca4b8cacd5a88b1d214496f366925 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal12fca4b8cacd5a88b1d214496f366925 = $attributes; } ?>
<?php $component = App\View\Components\SimpleBackground::resolve(['type' => 'animated'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('simple-background'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(App\View\Components\SimpleBackground::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal12fca4b8cacd5a88b1d214496f366925)): ?>
<?php $attributes = $__attributesOriginal12fca4b8cacd5a88b1d214496f366925; ?>
<?php unset($__attributesOriginal12fca4b8cacd5a88b1d214496f366925); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal12fca4b8cacd5a88b1d214496f366925)): ?>
<?php $component = $__componentOriginal12fca4b8cacd5a88b1d214496f366925; ?>
<?php unset($__componentOriginal12fca4b8cacd5a88b1d214496f366925); ?>
<?php endif; ?>
    
    <!-- Flash Messages -->
    <?php if(session('success')): ?>
    <div class="notification success">
        <i class="fas fa-check-circle mr-2"></i>
        <?php echo e(session('success')); ?>

    </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
    <div class="notification error">
        <i class="fas fa-exclamation-circle mr-2"></i>
        <?php echo e(session('error')); ?>

    </div>
    <?php endif; ?>
    
    <?php if(session('warning')): ?>
    <div class="notification warning">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        <?php echo e(session('warning')); ?>

    </div>
    <?php endif; ?>
    
    <?php if(session('info')): ?>
    <div class="notification info">
        <i class="fas fa-info-circle mr-2"></i>
        <?php echo e(session('info')); ?>

    </div>
    <?php endif; ?>
    
    <!-- Loading Overlay (hidden by default) -->
    <div id="globalLoadingOverlay" class="loading-overlay" style="display: none;">
        <div class="spinner"></div>
        <p class="text-gray-600">Loading...</p>
    </div>

    <div class="content">
        <!-- Navigation -->
        <?php echo $__env->make('partials.navigation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        
        <!-- Main Content -->
        <main class="min-h-screen">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
        
        <!-- Footer -->
        <?php echo $__env->make('partials.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
    
    <!-- Vite JS -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/js/app.js']); ?>
    
    <!-- Global JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Auto-hide notifications after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide notifications
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                setTimeout(() => {
                    notification.style.transition = 'all 0.3s ease';
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }, 5000);
            });
            
            // Add CSRF token to all AJAX requests
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            if (token) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': token
                    }
                });
                
                // Also set for fetch API
                window.csrfToken = token;
            }
            
            // Loading button functionality
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('btn-loading') || 
                    e.target.closest('.btn-loading')) {
                    const btn = e.target.classList.contains('btn-loading') 
                        ? e.target 
                        : e.target.closest('.btn-loading');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = '<div class="loading"></div>';
                    btn.disabled = true;
                    
                    // Re-enable button after form submission or AJAX completion
                    // For forms, this should be handled by the form's submit event
                    if (btn.type === 'submit' || btn.form) {
                        // Let the form submission handle it
                        return;
                    }
                    
                    // For buttons that trigger AJAX
                    btn.addEventListener('ajax:complete', function() {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    });
                }
            });
        });
        
        // Global loading overlay functions
        window.showGlobalLoading = function(message = 'Loading...') {
            const overlay = document.getElementById('globalLoadingOverlay');
            const messageEl = overlay.querySelector('p');
            messageEl.textContent = message;
            overlay.style.display = 'flex';
        };
        
        window.hideGlobalLoading = function() {
            const overlay = document.getElementById('globalLoadingOverlay');
            overlay.style.display = 'none';
        };
        
        // Form submission with loading
        document.addEventListener('submit', function(e) {
            const form = e.target;
            const submitBtn = form.querySelector('button[type="submit"]');
            
            if (submitBtn && !submitBtn.hasAttribute('data-no-loading')) {
                submitBtn.disabled = true;
                if (!submitBtn.querySelector('.loading')) {
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<div class="loading"></div>';
                    submitBtn.setAttribute('data-original-text', originalText);
                }
                
                // Re-enable button after form submission (for AJAX forms)
                form.addEventListener('ajax:complete', function() {
                    const originalText = submitBtn.getAttribute('data-original-text');
                    submitBtn.innerHTML = originalText || 'Submit';
                    submitBtn.disabled = false;
                });
            }
        });
        
        // Handle AJAX errors globally
        $(document).ajaxError(function(event, jqXHR, settings, error) {
            console.error('AJAX Error:', error);
            
            // Create error notification
            const errorMsg = jqXHR.responseJSON?.message || 
                           jqXHR.responseText || 
                           'An error occurred. Please try again.';
            
            const notification = document.createElement('div');
            notification.className = 'notification error';
            notification.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i>${errorMsg}`;
            document.body.appendChild(notification);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                notification.style.transition = 'all 0.3s ease';
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 5000);
            
            // Hide loading overlay
            hideGlobalLoading();
        });
        
        // Success notification helper
        window.showSuccess = function(message) {
            const notification = document.createElement('div');
            notification.className = 'notification success';
            notification.innerHTML = `<i class="fas fa-check-circle mr-2"></i>${message}`;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.transition = 'all 0.3s ease';
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 5000);
        };
        
        // Error notification helper
        window.showError = function(message) {
            const notification = document.createElement('div');
            notification.className = 'notification error';
            notification.innerHTML = `<i class="fas fa-exclamation-circle mr-2"></i>${message}`;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.transition = 'all 0.3s ease';
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 5000);
        };
    </script>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\laragon\www\neo-faraid\resources\views/layouts/app.blade.php ENDPATH**/ ?>