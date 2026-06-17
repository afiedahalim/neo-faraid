<?php $__env->startSection('title', 'Edit Profile'); ?>

<?php $__env->startSection('content'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    :root {
        --primary-color: #1a5fb4;
        --primary-dark: #0d2d5c;
        --secondary-color: #2d7ad6;
        --accent-color: #ffd700;
        --accent-light: #ffed4e;
        --danger-color: #dc3545;
        --success-color: #25D366;
        --success-dark: #128C7E;
        --light-bg: #f8f9fa;
        --light-border: #e9ecef;
        --text-primary: #495057;
        --text-light: #6c757d;
        --white: #ffffff;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
        --shadow-md: 0 10px 30px rgba(0,0,0,0.08);
        --shadow-lg: 0 20px 40px rgba(0,0,0,0.12);
        --shadow-accent: 0 10px 25px rgba(255, 215, 0, 0.3);
        --border-radius-sm: 12px;
        --border-radius-md: 15px;
        --border-radius-lg: 20px;
        --border-radius-xl: 50px;
        --transition: all 0.3s ease;
    }
    
    * {
        font-family: 'Poppins', sans-serif !important;
    }
    
    .dashboard-header {
        min-height: 60vh !important;
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
        display: flex;
        align-items: center;
        color: var(--white);
    }
    
    .hero-bg-elements {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
    }
    
    .animated-bg .bg-circle-1 {
        position: absolute;
        top: 10%;
        right: 5%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(255,215,0,0.1) 0%, transparent 70%);
    }
    
    .animated-bg .bg-circle-2 {
        position: absolute;
        bottom: 10%;
        left: 5%;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(26, 95, 180, 0.15) 0%, transparent 70%);
    }
    
    .animated-bg .bg-circle-3 {
        position: absolute;
        bottom: 20%;
        right: 15%;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(255, 215, 0, 0.15) 0%, transparent 70%);
        animation: pulse 4s ease-in-out infinite;
    }
    
    .animated-bg .bg-pattern {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('data:image/svg+xml,<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><circle cx="2" cy="2" r="1" fill="rgba(255,255,255,0.05)"/></svg>');
        opacity: 0.5;
    }
    
    .floating-shapes {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        pointer-events: none;
        z-index: 1;
    }
    
    .shape {
        position: absolute;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        animation-duration: 6s;
        animation-timing-function: ease-in-out;
        animation-iteration-count: infinite;
    }
    
    .shape-1 {
        width: 40px;
        height: 40px;
        top: 20%;
        left: 10%;
        animation-name: float-1;
    }
    
    .shape-2 {
        width: 25px;
        height: 25px;
        top: 60%;
        left: 85%;
        animation-name: float-2;
        animation-delay: 1s;
    }
    
    .shape-3 {
        width: 35px;
        height: 35px;
        top: 75%;
        left: 15%;
        animation-name: float-3;
        animation-delay: 0.5s;
    }
    
    .shape-4 {
        width: 20px;
        height: 20px;
        top: 30%;
        left: 70%;
        animation-name: float-4;
        animation-delay: 1.5s;
    }
    
    .animated-speed-fast .shape {
        animation-duration: 4s !important;
    }
    
    .animated-speed-fast .bg-circle-3 {
        animation-duration: 3s !important;
    }
    
    .hero-container {
        position: relative;
        z-index: 2;
        padding-top: 3rem;
        padding-bottom: 3rem;
        max-width: 1200px;
        margin: 0 auto;
        width: 100%;
        padding-left: 2rem;
        padding-right: 2rem;
    }
    
    .hero-title {
        font-size: 3rem;
        line-height: 1.2;
        margin-bottom: 1rem;
        text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
        font-weight: 700;
    }
    
    .hero-highlight {
        color: var(--accent-color);
    }
    
    .hero-subtitle {
        font-size: 1.2rem;
        max-width: 600px;
        margin-bottom: 2.5rem;
        opacity: 0.95;
        line-height: 1.6;
        font-weight: 400;
    }
    
    .profile-badges {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
    }
    
    .profile-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255,255,255,0.1);
        padding: 0.75rem 1.5rem;
        border-radius: var(--border-radius-xl);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
        font-weight: 500;
    }
    
    .profile-badge.account-settings {
        background: rgba(26, 95, 180, 0.2);
        border-color: rgba(26, 95, 180, 0.3);
    }
    
    .profile-badge.profile-management {
        background: rgba(255, 215, 0, 0.2);
        border-color: rgba(255, 215, 0, 0.3);
    }
    
    .profile-icon {
        width: 16px;
        height: 16px;
        fill: currentColor;
    }
    
    .dashboard-section {
        margin: 2rem auto;
        max-width: 1200px;
        padding: 0 2rem;
    }
    
    .profile-form-container {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .profile-form-card {
        background: var(--white);
        border-radius: var(--border-radius-lg);
        padding: 3rem;
        box-shadow: var(--shadow-md);
        position: relative;
        overflow: hidden;
    }
    
    .form-top-line {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 5px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    }
    
    .form-title {
        color: var(--primary-color);
        margin-bottom: 2rem;
        font-size: 1.8rem;
        font-weight: 600;
    }
    
    .info-note {
        background: #e8f1fd;
        border-left: 4px solid var(--primary-color);
        padding: 12px 15px;
        border-radius: var(--border-radius-sm);
        margin-bottom: 25px;
        font-size: 13px;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .info-note svg {
        flex-shrink: 0;
    }
    
    .form-group {
        margin-bottom: 1.75rem;
    }
    
    .form-label {
        display: block;
        font-weight: 500;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }
    
    .form-input {
        width: 100%;
        padding: 1rem 1.25rem;
        border: 2px solid var(--light-border);
        border-radius: var(--border-radius-sm);
        font-size: 1rem;
        transition: var(--transition);
        background: var(--white);
    }
    
    .form-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.1);
        outline: none;
    }
    
    .form-input.readonly-field {
        background: var(--light-bg);
        cursor: not-allowed;
        color: var(--text-light);
    }
    
    textarea.form-input {
        resize: vertical;
        min-height: 100px;
    }
    
    .form-input.is-invalid {
        border-color: var(--danger-color);
    }
    
    .error-message {
        color: var(--danger-color);
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
    
    .form-hint {
        color: var(--text-light);
        font-size: 0.8rem;
        margin-top: 0.25rem;
    }
    
    .grid-2 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }
    
    .form-actions {
        display: flex;
        gap: 1.5rem;
        margin-top: 3rem;
    }
    
    .btn-primary {
        flex: 1;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
        color: var(--primary-dark);
        border: none;
        border-radius: var(--border-radius-xl);
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }
    
    .btn-primary span {
        position: relative;
        z-index: 2;
    }
    
    .btn-hover-effect {
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.6s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-accent);
    }
    
    .btn-primary:hover .btn-hover-effect {
        left: 100%;
    }
    
    .btn-primary:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
    
    .btn-secondary {
        flex: 1;
        padding: 1rem 2rem;
        background: transparent;
        color: var(--primary-color);
        text-align: center;
        border-radius: var(--border-radius-xl);
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        border: 2px solid var(--primary-color);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-secondary:hover {
        background: var(--primary-color);
        color: var(--white);
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    
    .alert-message {
        padding: 1rem 1.5rem;
        border-radius: var(--border-radius-sm);
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideDown 0.3s ease;
    }
    
    .alert-success {
        background: linear-gradient(135deg, rgba(37, 211, 102, 0.1) 0%, rgba(18, 140, 126, 0.1) 100%);
        border: 1px solid rgba(37, 211, 102, 0.3);
        color: var(--success-dark);
    }
    
    .alert-error {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.1) 0%, rgba(185, 28, 28, 0.1) 100%);
        border: 1px solid rgba(220, 53, 69, 0.3);
        color: var(--danger-color);
    }
    
    @keyframes float-1 {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        33% { transform: translateY(-15px) rotate(120deg); }
        66% { transform: translateY(8px) rotate(240deg); }
    }
    
    @keyframes float-2 {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        33% { transform: translateY(-20px) rotate(90deg); }
        66% { transform: translateY(10px) rotate(180deg); }
    }
    
    @keyframes float-3 {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        33% { transform: translateY(-12px) rotate(60deg); }
        66% { transform: translateY(6px) rotate(120deg); }
    }
    
    @keyframes float-4 {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        33% { transform: translateY(-18px) rotate(150deg); }
        66% { transform: translateY(9px) rotate(300deg); }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 0.7; transform: scale(1); }
        50% { opacity: 1; transform: scale(1.1); }
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
        display: inline-block;
    }
    
    @media (max-width: 768px) {
        .dashboard-section {
            padding: 0 1rem !important;
        }
        
        .profile-form-card {
            padding: 2rem 1.5rem !important;
        }
        
        .grid-2 {
            grid-template-columns: 1fr !important;
        }
        
        .form-actions {
            flex-direction: column !important;
        }
        
        .btn-primary,
        .btn-secondary {
            width: 100% !important;
        }
        
        .hero-title {
            font-size: 2.5rem !important;
        }
        
        .hero-subtitle {
            font-size: 1.1rem !important;
        }
        
        .profile-badges {
            gap: 0.5rem;
        }
        
        .shape {
            display: none !important;
        }
        
        .bg-circle-3 {
            display: none !important;
        }
    }
    
    @media (max-width: 480px) {
        .hero-title {
            font-size: 2rem !important;
        }
        
        .profile-form-card {
            padding: 1.5rem 1rem !important;
        }
        
        .form-title {
            font-size: 1.5rem !important;
        }
        
        .profile-badges {
            flex-direction: column;
        }
        
        .profile-badge {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<header class="hero-section dashboard-header">
    <div class="hero-bg-elements animated-bg animated-speed-fast">
        <div class="bg-circle bg-circle-1"></div>
        <div class="bg-circle bg-circle-2"></div>
        <div class="bg-circle bg-circle-3"></div>
        <div class="bg-pattern"></div>
        <div class="floating-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
            <div class="shape shape-4"></div>
        </div>
    </div>

    <div class="container hero-container">
        <h1 class="hero-title">
            Edit <span class="hero-highlight">Profile</span>
        </h1>
        
        <p class="hero-subtitle">
            Update your contact information and preferences
        </p>
        
        <div class="profile-badges">
            <span class="profile-badge account-settings">
                <svg class="profile-icon" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
                Account Settings
            </span>
            
            <span class="profile-badge profile-management">
                <svg class="profile-icon" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Profile Management
            </span>
        </div>
    </div>
</header>

<main class="main-content">
    <div class="dashboard-section">
        <div class="profile-form-container">
            <?php if(session('success')): ?>
                <div class="alert-message alert-success">
                    <svg class="profile-icon" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="alert-message alert-error">
                    <svg class="profile-icon" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <ul style="margin: 0; padding-left: 20px;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <div class="profile-form-card">
                <div class="form-top-line"></div>
                
                <h2 class="form-title">
                    Personal Information
                </h2>

                <div class="info-note">
                    <svg width="20" height="20" fill="none" stroke="#1a5fb4" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>Personal identification information (Name, NRIC, Date of Birth, Gender) cannot be changed. Please contact support if corrections are needed.</span>
                </div>
                
                <form method="POST" action="<?php echo e(route('profile.update')); ?>" id="profileForm">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" id="name" class="form-input readonly-field" 
                                   value="<?php echo e($user->name); ?>" readonly disabled>
                        </div>

                        <div class="form-group">
                            <label for="nric" class="form-label">NRIC/Passport Number</label>
                            <input type="text" id="nric" class="form-input readonly-field" 
                                   value="<?php echo e($user->formatted_nric); ?>" readonly disabled>
                        </div>
                    </div>

                    <div class="grid-2">
                        <div class="form-group">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="text" id="dob" class="form-input readonly-field" 
                                   value="<?php echo e($user->date_of_birth ? $user->date_of_birth->format('d/m/Y') : '-'); ?>" 
                                   readonly disabled>
                        </div>

                        <div class="form-group">
                            <label for="gender" class="form-label">Gender</label>
                            <input type="text" id="gender" class="form-input readonly-field" 
                                   value="<?php echo e($user->gender == 'male' ? 'Male' : ($user->gender == 'female' ? 'Female' : '-')); ?>" 
                                   readonly disabled>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" class="form-input readonly-field" 
                               value="<?php echo e($user->email); ?>" readonly disabled>
                    </div>

                    <div class="form-group">
                        <label for="contact_phone" class="form-label">
                            Contact Phone <span style="color: var(--danger-color);">*</span>
                        </label>
                        <input type="tel" name="contact_phone" id="contact_phone" 
                               class="form-input <?php $__errorArgs = ['contact_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('contact_phone', $user->contact_phone)); ?>" 
                               required placeholder="012-3456789">
                        <div class="form-hint"></div>
                        <?php $__errorArgs = ['contact_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error-message"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label for="address" class="form-label">
                            Residential Address <span style="color: var(--danger-color);">*</span>
                        </label>
                        <textarea name="address" id="address" rows="3"
                                  class="form-input <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  required placeholder="Enter your full residential address"><?php echo e(old('address', $user->address)); ?></textarea>
                        <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="error-message"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary" id="submitBtn">
                            <span>Save Changes</span>
                            <div class="btn-hover-effect"></div>
                        </button>
                        <a href="<?php echo e(route('home')); ?>" class="btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Phone number formatting with auto dash
    const phoneInput = document.getElementById('contact_phone');
    if (phoneInput) {
        // Format the initial value if it doesn't have a dash
        let initialValue = phoneInput.value.replace(/[^0-9]/g, '');
        if (initialValue.length >= 3 && initialValue.length <= 10) {
            if (initialValue.startsWith('01')) {
                initialValue = initialValue.substring(0, 3) + '-' + initialValue.substring(3);
            } else if (initialValue.startsWith('0') && initialValue.length >= 3) {
                initialValue = initialValue.substring(0, 3) + '-' + initialValue.substring(3);
            }
            phoneInput.value = initialValue;
        }
        
        // Format on input
        phoneInput.addEventListener('input', function(e) {
            let val = this.value.replace(/[^0-9]/g, '');
            if (val.length >= 3 && val.length <= 10) {
                // Format as 012-3456789
                if (val.startsWith('01')) {
                    val = val.substring(0, 3) + '-' + val.substring(3);
                } else if (val.startsWith('0') && val.length >= 3) {
                    val = val.substring(0, 3) + '-' + val.substring(3);
                }
            }
            if (val.length > 10) {
                val = val.substring(0, 10);
            }
            this.value = val;
        });
        
        // Prevent user from typing dash or other invalid characters manually
        phoneInput.addEventListener('keydown', function(e) {
            if (e.key === '-' || e.key === 'e' || e.key === 'E' || e.key === '+' || e.key === '.') {
                e.preventDefault();
            }
        });
    }
    
    // Form submission - prevent double submit
    const form = document.getElementById('profileForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.querySelector('span').innerHTML = 
                '<svg class="animate-spin" viewBox="0 0 20 20" width="18" height="18" style="margin-right: 8px; vertical-align: middle;">' +
                '<path d="M10 3v2a5 5 0 00-5 5H3a7 7 0 017-7z" fill="currentColor"/>' +
                '</svg> Saving...';
        });
    }
    
    // Auto-hide alert messages after 5 seconds
    const alertMessage = document.querySelector('.alert-message');
    if (alertMessage) {
        setTimeout(() => {
            alertMessage.style.opacity = '0';
            alertMessage.style.transform = 'translateY(-10px)';
            alertMessage.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            setTimeout(() => {
                if (alertMessage.parentNode) {
                    alertMessage.remove();
                }
            }, 300);
        }, 5000);
    }
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\neo-faraid\resources\views/profile/edit.blade.php ENDPATH**/ ?>