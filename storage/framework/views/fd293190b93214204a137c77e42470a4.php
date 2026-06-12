

<?php $__env->startSection('title', 'Neo Faraid - Digital Islamic Estate Planning'); ?>

<?php $__env->startSection('content'); ?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap');
    
    * {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
</style>

<?php
    // Function to generate animated background - SAFE VERSION with function_exists check
    if (!function_exists('generateAnimatedBackground')) {
        function generateAnimatedBackground($options = []) {
            $defaults = [
                'circles' => 3,
                'shapes' => 4,
                'pattern' => true,
                'animation_speed' => 'normal',
                'floating_shapes' => true
            ];
            
            $options = array_merge($defaults, $options);
            
            $html = '<div class="hero-bg-elements animated-bg animated-speed-' . $options['animation_speed'] . '">';
            
            // Generate circles
            for ($i = 1; $i <= $options['circles']; $i++) {
                $html .= '<div class="bg-circle bg-circle-' . $i . '"></div>';
            }
            
            // Add pattern if enabled
            if ($options['pattern']) {
                $html .= '<div class="bg-pattern"></div>';
            }
            
            // Add floating shapes if enabled
            if ($options['floating_shapes']) {
                $html .= '<div class="floating-shapes">';
                for ($i = 1; $i <= $options['shapes']; $i++) {
                    $html .= '<div class="shape shape-' . $i . '"></div>';
                }
                $html .= '</div>';
            }
            
            $html .= '</div>';
            
            return $html;
        }
    }
?>

<header class="hero-section">
    <!-- Animated background -->
    <?php echo generateAnimatedBackground(['animation_speed' => 'fast']); ?>


    <div class="container hero-container">
        <div class="hero-kicker">
            <div class="kicker-content">
                <?php $__currentLoopData = ['Precise', 'Reliable', 'Shariah-Compliant']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="kicker-item">
                    <svg class="kicker-icon" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    <?php echo e($item); ?>

                </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        
        <h1 class="hero-title">
            Digital Islamic<br>
            <span class="hero-highlight">Estate Planning Made Simple</span>
        </h1>
        
        <p class="hero-subtitle">
            Prepare your estate today. Protect your family's tomorrow.<br>
            <strong>Plan while alive. Execute with peace.</strong>
        </p>
        
        <div class="hero-actions">
            <a href="<?php echo e(route('estate-setup.index')); ?>" class="btn btn-primary">
                <span>Start Estate Planning</span>
                <svg class="btn-icon" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                <div class="btn-hover-effect"></div>
            </a>
            
            <a href="<?php echo e(route('calculator.index')); ?>" class="btn btn-secondary">
                <span>Calculate Faraid</span>
                <svg class="btn-icon" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 2a1 1 0 011 1v1h1a1 1 0 010 2H6v1a1 1 0 01-2 0V6H3a1 1 0 010-2h1V3a1 1 0 011-1zm0 10a1 1 0 011 1v1h1a1 1 0 110 2H6v1a1 1 0 11-2 0v-1H3a1 1 0 110-2h1v-1a1 1 0 011-1zM12 2a1 1 0 01.967.744L14.146 7.2 17.5 9.134a1 1 0 010 1.732l-3.354 1.935-1.18 4.455a1 1 0 01-1.933 0L9.854 12.8 6.5 10.866a1 1 0 010-1.732l3.354-1.935 1.18-4.455A1 1 0 0112 2z" clip-rule="evenodd"/>
                </svg>
                <div class="btn-hover-effect"></div>
            </a>
        </div>
    </div>
</header>

<main class="main-content" id="learn-more">

    <!-- Why Estate Planning Matters -->
    <section class="importance-section">
        <div class="importance-container">
            <h2 class="importance-title">Why Estate Planning Matters</h2>
            <p class="importance-description">
                When a loved one passes away, families often face emotional grief, unpaid debts, missing records, 
                inheritance disputes, and lengthy legal procedures.
            </p>
            <div class="importance-highlight">
                <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.342 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <span>Don't leave your family unprepared.</span>
            </div>
            <p class="importance-cta">Create a complete estate plan today and ensure your wishes are carried out properly.</p>
        </div>
    </section>

    <!-- Two-Phase System Banner -->
    <section class="phase-banner-section">
        <h2 class="section-title">
            <span class="title-text">Two Simple Phases</span>
            <div class="title-line"></div>
        </h2>
        
        <div class="phase-banner">
            <div class="phase-flow">
                <div class="phase phase-1">
                    <div class="phase-icon-wrapper">
                        <svg class="phase-icon" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.52 19c.64-2.2 1.84-3 3.22-3h6.52c1.38 0 2.58.8 3.22 3M12 13V7m-4 6V7m8 6V7M4 4h16a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                        </svg>
                    </div>
                    <div class="phase-content">
                        <span class="phase-label">PHASE 1 — WHILE ALIVE</span>
                        <h3 class="phase-title">Estate Planning</h3>
                        <p class="phase-desc">Create and manage your complete digital estate plan</p>
                    </div>
                </div>
                <div class="phase-arrow">
                    <svg viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </div>
                <div class="phase phase-2">
                    <div class="phase-icon-wrapper">
                        <svg class="phase-icon" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="phase-content">
                        <span class="phase-label">PHASE 2 — AFTER DEATH</span>
                        <h3 class="phase-title">Instant Estate</h3>
                        <p class="phase-desc">Secure verification and automated estate retrieval</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Estate Setup Features (Phase 1 Details) -->
    <section class="estate-setup-section">
        <div class="estate-steps-container">
            <div class="estate-steps-header">
                <div>
                    <h3 class="estate-step-phase-title">Phase 1 — Estate Planning</h3>
                    <p class="estate-intro">Complete your digital estate plan in 6 simple steps. All data is securely stored and will be automatically retrieved when needed.</p>
                </div>
                <a href="<?php echo e(route('estate-setup.index')); ?>" class="btn btn-start-setup">
                    <span>Create Estate Plan</span>
                    <svg class="btn-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
            
            <div class="estate-steps-grid">
                <?php $__currentLoopData = [
                    ['step' => '01', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'title' => 'Personal Information', 'desc' => 'Store your identity and contact details'],
                    ['step' => '02', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'title' => 'Add Heirs', 'desc' => 'Record family members and beneficiaries'],
                    ['step' => '03', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'title' => 'Assets & Properties', 'desc' => 'List houses, land, vehicles, savings, investments, and other assets'],
                    ['step' => '04', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'title' => 'Debts & Liabilities', 'desc' => 'Record loans, obligations, and outstanding liabilities'],
                    ['step' => '05', 'icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z', 'title' => 'Digital Wasiyyah', 'desc' => 'Upload or record your final message and instructions'],
                    ['step' => '06', 'icon' => 'M3 8l7.89 7.89a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z', 'title' => 'Beneficiary Contacts', 'desc' => 'Register email addresses for future notifications']
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="estate-step-card">
                    <div class="step-header">
                        <span class="step-number-badge"><?php echo e($item['step']); ?></span>
                        <div class="step-icon-circle">
                            <svg class="step-icon-svg" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($item['icon']); ?>"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="step-card-title"><?php echo e($item['title']); ?></h4>
                    <p class="step-card-desc"><?php echo e($item['desc']); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    <!-- Phase 2: Instant Estate -->
    <section class="instant-estate-section">
        <div class="instant-estate-container">
            <div class="instant-header">
                <div>
                    <span class="instant-phase-label">Phase 2 — After Death</span>
                    <h2 class="instant-title">Instant Estate</h2>
                    <p class="instant-subtitle">Secure verification and automated estate retrieval</p>
                </div>
                <a href="<?php echo e(route('instant-estate.index')); ?>" class="btn btn-instant">
                    <span>Learn About Instant Estate</span>
                    <svg class="btn-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
            
            <div class="instant-steps-grid">
                <?php $__currentLoopData = [
                    ['step' => '01', 'title' => 'Upload Death Certificate', 'desc' => 'The heir uploads the deceased\'s original death certificate'],
                    ['step' => '02', 'title' => 'OCR Verification', 'desc' => 'The system extracts and validates key information'],
                    ['step' => '03', 'title' => 'Estate Matching', 'desc' => 'The system searches for the deceased\'s estate planning record'],
                    ['step' => '04', 'title' => 'Admin Review', 'desc' => 'Notification requests are reviewed and approved'],
                    ['step' => '05', 'title' => 'Beneficiary Notification', 'desc' => 'Eligible heirs receive secure access notifications'],
                    ['step' => '06', 'title' => 'Estate Distribution', 'desc' => 'View inheritance reports, family tree, and Faraid calculations']
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="instant-step-card">
                    <span class="instant-step-number"><?php echo e($item['step']); ?></span>
                    <h4 class="instant-step-title"><?php echo e($item['title']); ?></h4>
                    <p class="instant-step-desc"><?php echo e($item['desc']); ?></p>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>

    <!-- Everything In One Platform -->
    <section class="platform-section">
        <h2 class="section-title">
            <span class="title-text">Everything In One Platform</span>
            <div class="title-line"></div>
        </h2>
        
        <div class="platform-grid">
            <?php $__currentLoopData = [
                ['icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'title' => 'Estate Planning', 'desc' => 'Prepare your estate while alive'],
                ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Instant Estate', 'desc' => 'Retrieve verified estate information after death'],
                ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Faraid Calculator', 'desc' => 'Calculate inheritance shares instantly'],
                ['icon' => 'M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Family Tree Generator', 'desc' => 'Visualize inheritance distribution clearly'],
                ['icon' => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Debt Management', 'desc' => 'Ensure liabilities are settled before distribution'],
                ['icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z', 'title' => 'Digital Wasiyyah', 'desc' => 'Securely preserve final wishes and messages']
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="platform-card">
                <div class="platform-icon-wrapper">
                    <svg class="platform-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($feature['icon']); ?>"/>
                    </svg>
                </div>
                <h3 class="platform-card-title"><?php echo e($feature['title']); ?></h3>
                <p class="platform-card-desc"><?php echo e($feature['desc']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>

    <!-- Why Choose Neo Faraid -->
    <section class="features-section">
        <h2 class="section-title">
            <span class="title-text">Why Choose Neo Faraid?</span>
            <div class="title-line"></div>
        </h2>
        
        <div class="features-grid">
            <?php $__currentLoopData = [
                ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Accurate', 'desc' => 'Built according to Islamic inheritance principles'],
                ['icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z', 'title' => 'Secure', 'desc' => 'Encrypted storage and controlled access'],
                ['icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z', 'title' => 'Transparent', 'desc' => 'Clear calculations and traceable processes'],
                ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197', 'title' => 'User-Friendly', 'desc' => 'Designed for everyday users'],
                ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Fast', 'desc' => 'Instant inheritance calculations and reports'],
                ['icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Trusted', 'desc' => 'Combines estate planning, inheritance calculation, and digital wasiyyah in one platform']
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="feature-card">
                <div class="feature-top-line"></div>
                <div class="feature-header">
                    <div class="feature-icon-wrapper">
                        <svg class="feature-icon" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($feature['icon']); ?>"></path>
                        </svg>
                    </div>
                    <h3 class="feature-title"><?php echo e($feature['title']); ?></h3>
                </div>
                <p class="feature-description"><?php echo e($feature['desc']); ?></p>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>

    <!-- Faraid Calculator Section -->
    <section class="calculator-section">
        <div class="calculator-bg"></div>
        <div class="calculator-content">
            <div class="calculator-text">
                <span class="calculator-badge">Faraid Calculator</span>
                <h2 class="calculator-title">Calculate inheritance distribution in just <span class="highlight">five simple steps</span></h2>
            </div>
            <div class="calculator-steps">
                <?php $__currentLoopData = [
                    ['num' => '01', 'title' => 'Enter Deceased Information'],
                    ['num' => '02', 'title' => 'Add All Heirs'],
                    ['num' => '03', 'title' => 'Enter Assets & Debts'],
                    ['num' => '04', 'title' => 'Review Details'],
                    ['num' => '05', 'title' => 'View Results & Family Tree']
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="calculator-step">
                    <div class="calculator-step-number"><?php echo e($step['num']); ?></div>
                    <div class="calculator-step-title"><?php echo e($step['title']); ?></div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <div class="text-center">
                <a href="<?php echo e(route('calculator.index')); ?>" class="btn btn-calculator">
                    <span>Start Calculation</span>
                    <svg class="btn-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- About Faraid -->
    <section class="about-section">
        <div class="about-grid">
            <div class="about-card">
                <div class="about-bg"></div>
                <h2 class="about-title">What Is Faraid?</h2>
                <p class="about-description">
                    Faraid is the Islamic system of inheritance distribution based on the Quran and Sunnah.
                </p>
                <p class="about-description">
                    It determines who is entitled to inherit and how much each heir should receive after debts, 
                    funeral expenses, and other obligations have been settled.
                </p>
                <div class="highlight-box">
                    <div class="highlight-content">
                        <svg class="highlight-icon" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        <p class="highlight-text">
                            In simple words, Faraid tells you who inherits and how much they get according to Islamic law.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="facts-card">
                <div class="facts-header">
                    <div class="facts-icon-wrapper">
                        <svg class="facts-icon" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="facts-title">Quick Facts</h3>
                </div>
                <div class="facts-list">
                    <?php $__currentLoopData = [
                        'Based on Quran & Sunnah',
                        'Mathematically Precise',
                        'Protects Heirs\' Rights',
                        'Helps Prevent Family Disputes',
                        'Mandatory for Muslims'
                    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="fact-item">
                        <svg class="fact-icon" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span><?php echo e($fact); ?></span>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Important Notice -->
    <section class="notice-section">
        <div class="notice-card">
            <div class="notice-icon">
                <svg viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="notice-content">
                <h3 class="notice-title">Important Notice</h3>
                <p class="notice-message">
                    Neo Faraid assists with estate planning, inheritance calculations, and digital estate management.
                    All estate retrieval requests involving death certificate verification, inheritance reports, 
                    and digital wasiyyah access are subject to verification and administrator approval.
                </p>
            </div>
        </div>
    </section>

    <!-- Share Section -->
    <section class="share-section">
        <div class="share-gradient-overlay"></div>
        <div class="share-pattern"></div>
        <div class="share-hearts">
            <?php for($i = 1; $i <= 15; $i++): ?>
            <div class="share-heart heart-<?php echo e($i); ?>">🧡</div>
            <?php endfor; ?>
        </div>
        
        <div class="share-container">
            <div class="share-header">
                <div class="share-icon-wrapper">
                    <svg class="share-icon" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
                    </svg>
                </div>
                <h2 class="share-title">Spread the Blessings</h2>
                <p class="share-subtitle">
                    Sharing beneficial knowledge is <span class="highlight">Sadaqah Jariyah</span> - an ongoing charity that continues to benefit people even after you're gone.
                </p>
                <p class="share-message">If Neo Faraid has helped you understand Islamic inheritance better, share it with your family and friends.</p>
            </div>
            
            <div class="share-card">
                <div class="share-form" id="shareForm">
                    <div class="form-group">
                        <label class="form-label">Share via WhatsApp</label>
                        <p class="form-help">Enter your friend's number to send them a helpful message</p>
                        
                        <div class="phone-input-container">
                            <div class="country-code-wrapper">
                                <div class="flag-selector">
                                    <span id="flagIcon" class="country-flag">🇲🇾</span>
                                    <select id="country_code" name="country_code" class="country-select">
                                        <?php $__currentLoopData = [
                                            '+60' => ['🇲🇾', 'Malaysia'],
                                            '+62' => ['🇮🇩', 'Indonesia'],
                                            '+966' => ['🇸🇦', 'Saudi Arabia'],
                                            '+971' => ['🇦🇪', 'UAE'],
                                            '+65' => ['🇸🇬', 'Singapore'],
                                            '+91' => ['🇮🇳', 'India'],
                                            '+44' => ['🇬🇧', 'UK'],
                                            '+1' => ['🇺🇸', 'USA'],
                                            '+93' => ['🇦🇫', 'Afghanistan'],
                                            '+92' => ['🇵🇰', 'Pakistan'],
                                            '+880' => ['🇧🇩', 'Bangladesh']
                                        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($code); ?>" data-flag="<?php echo e($data[0]); ?>"><?php echo e($code); ?> <?php echo e($data[1]); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <svg class="dropdown-arrow" viewBox="0 0 20 20">
                                        <path fill="currentColor" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="phone-input-wrapper">
                                <svg class="phone-icon" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M20 15.5c-1.2 0-2.4-.2-3.6-.6-.35-.1-.75 0-1.05.2l-2.2 2.2c-2.8-1.5-5.2-3.8-6.6-6.6l2.2-2.2c.3-.3.45-.7.35-1.1C8.7 6.4 8.5 5.2 8.5 4c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1 0 9.39 7.61 17 17 17 .55 0 1-.45 1-1v-3.5c0-.55-.45-1-1-1z"/>
                                </svg>
                                <input
                                    type="tel"
                                    id="whatsapp_number"
                                    name="whatsapp_number"
                                    placeholder="Phone number without country code"
                                    value=""
                                    required
                                    class="phone-input"
                                >
                                <div class="input-focus-border"></div>
                            </div>
                        </div>
                        <div id="error-message" class="error-message" style="display: none;"></div>
                    </div>
                    
                    <button type="button" class="btn btn-share" id="shareWhatsAppBtn">
                        <span class="btn-text">
                            <svg class="whatsapp-icon" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006 1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347zm-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.76.982.998-3.675-.236-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.9 6.994c-.004 5.45-4.438 9.88-9.888 9.88zm8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.333.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.333 11.893-11.893 0-3.18-1.24-6.162-3.495-8.411z"/>
                            </svg>
                            Share via WhatsApp
                        </span>
                        <span class="btn-hover-effect">
                            <svg class="arrow-icon" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </span>
                    </button>
                </div>
                
                <div class="alternative-sharing">
                    <p class="alternative-title">Or share directly:</p>
                    <div class="social-buttons">
                        <a href="https://wa.me/?text=<?php echo e(urlencode('Check out Neo Faraid - Digital Islamic Estate Planning: ' . url('/'))); ?>" 
                           target="_blank" 
                           class="social-btn whatsapp-btn">
                            <svg viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006 1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347zm-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.76.982.998-3.675-.236-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.9 6.994c-.004 5.45-4.438 9.88-9.888 9.88zm8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.333.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.333 11.893-11.893 0-3.18-1.24-6.162-3.495-8.411z"/>
                            </svg>
                            <span>WhatsApp</span>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e(urlencode(url('/'))); ?>" 
                           target="_blank" 
                           class="social-btn facebook-btn">
                            <svg viewBox="0 0 24 24">
                                <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>
                            </svg>
                            <span>Facebook</span>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=<?php echo e(urlencode('Check out Neo Faraid - Digital Islamic Estate Planning: ' . url('/'))); ?>" 
                           target="_blank" 
                           class="social-btn twitter-btn">
                            <svg viewBox="0 0 24 24">
                                <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/>
                            </svg>
                            <span>X</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Quranic Quote -->
    <section class="quran-section">
        <div class="quran-bg quran-bg-1"></div>
        <div class="quran-bg quran-bg-2"></div>
        <div class="quran-content">
            <div class="quran-arabic">
                يُوصِيكُمُ اللَّهُ فِي أَوْلَادِكُمْ
            </div>
            <div class="quran-translation">
                "Allah instructs you concerning your children..."
            </div>
            <p>— Surah An-Nisa (4:11)</p>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="cta-section">
        <div class="cta-container">
            <h2 class="cta-title">Ready to Protect Your Family's Future?</h2>
            <p class="cta-subtitle">Create your estate plan today and ensure your loved ones are prepared tomorrow.</p>
            <div class="cta-buttons">
                <a href="<?php echo e(route('estate-setup.index')); ?>" class="btn btn-cta-primary">
                    <span>Start Estate Planning</span>
                    <svg class="btn-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </a>
                <a href="<?php echo e(route('calculator.index')); ?>" class="btn btn-cta-secondary">
                    <span>Calculate Faraid</span>
                    <svg class="btn-icon" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>
</main>

<style>
/* ===== BASE STYLES ===== */
:root {
    --primary-color: #1a5fb4;
    --primary-dark: #0d2d5c;
    --secondary-color: #2d7ad6;
    --accent-color: #ffd700;
    --accent-light: #ffed4e;
    --danger-color: #dc3545;
    --success-color: #25D366;
    --success-dark: #128C7E;
    --whatsapp-green: #25D366;
    --facebook-blue: #1877F2;
    --twitter-blue: #1DA1F2;
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

/* ===== HERO SECTION ===== */
.hero-section {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
    min-height: 85vh;
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
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
    padding: 4rem 2rem;
}

.hero-kicker {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    display: inline-block;
    padding: 0.75rem 1.75rem;
    border-radius: var(--border-radius-xl);
    margin-bottom: 1.5rem;
    border: 1px solid rgba(255,255,255,0.2);
}

.kicker-content {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.kicker-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

.kicker-icon {
    width: 16px;
    height: 16px;
    fill: currentColor;
}

.hero-title {
    font-size: 3.5rem;
    line-height: 1.2;
    margin-bottom: 1.5rem;
    text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
    font-weight: 700;
}

.hero-highlight {
    color: var(--accent-color);
}

.hero-subtitle {
    font-size: 1.3rem;
    max-width: 700px;
    margin-bottom: 2.5rem;
    opacity: 0.95;
    line-height: 1.6;
    font-weight: 400;
}

.hero-actions {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
    margin-bottom: 2rem;
}

.hero-badge {
    background: rgba(255,215,0,0.15);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,215,0,0.3);
    border-radius: var(--border-radius-xl);
    padding: 0.75rem 1.5rem;
    display: inline-flex;
    align-items: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.badge-text {
    color: var(--accent-color);
    font-weight: 600;
}

.badge-subtext {
    color: rgba(255,255,255,0.9);
    font-size: 0.95rem;
}

/* ===== IMPORTANCE SECTION ===== */
.importance-section {
    max-width: 1200px;
    margin: 4rem auto;
    padding: 0 2rem;
}

.importance-container {
    background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    text-align: center;
    border-left: 4px solid var(--danger-color);
}

.importance-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--danger-color);
    margin-bottom: 1rem;
}

.importance-description {
    font-size: 1.1rem;
    color: var(--text-primary);
    max-width: 800px;
    margin: 0 auto 1.5rem;
    line-height: 1.6;
}

.importance-highlight {
    background: rgba(220, 53, 69, 0.1);
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius-xl);
    margin-bottom: 1rem;
    font-weight: 600;
    color: var(--danger-color);
}

.importance-cta {
    font-weight: 600;
    color: var(--danger-color);
    font-size: 1.1rem;
}

/* ===== PHASE BANNER SECTION ===== */
.phase-banner-section {
    max-width: 1200px;
    margin: 3rem auto 2rem;
    padding: 0 2rem;
}

.section-title {
    text-align: center;
    margin-bottom: 3rem;
    position: relative;
}

.title-text {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-size: 2rem;
    font-weight: 700;
    display: inline-block;
}

.title-line {
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 100px;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    border-radius: 2px;
}

.phase-banner {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: var(--border-radius-lg);
    padding: 2rem;
    box-shadow: var(--shadow-md);
    border: 1px solid rgba(26,95,180,0.1);
}

.phase-flow {
    display: flex;
    align-items: center;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.phase {
    display: flex;
    align-items: flex-start;
    gap: 1.5rem;
    flex: 1;
    min-width: 280px;
}

.phase-icon-wrapper {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.phase-icon {
    width: 30px;
    height: 30px;
    color: white;
    stroke: currentColor;
    fill: none;
}

.phase-content {
    flex: 1;
}

.phase-label {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 1px;
    color: var(--primary-color);
    text-transform: uppercase;
}

.phase-title {
    margin: 0.25rem 0 0.5rem;
    color: var(--primary-dark);
    font-weight: 600;
    font-size: 1.2rem;
}

.phase-desc {
    margin: 0;
    color: var(--text-light);
    font-size: 0.9rem;
    line-height: 1.5;
}

.phase-arrow {
    color: var(--primary-color);
    width: 48px;
    height: 48px;
    flex-shrink: 0;
}

.phase-arrow svg {
    width: 100%;
    height: 100%;
    stroke: currentColor;
}

/* ===== ESTATE SETUP SECTION ===== */
.estate-setup-section {
    max-width: 1200px;
    margin: 4rem auto;
    padding: 0 2rem;
}

.estate-steps-container {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
    box-shadow: var(--shadow-md);
}

.estate-steps-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2.5rem;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.estate-step-phase-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.5rem;
}

.estate-intro {
    color: var(--text-primary);
    font-size: 1rem;
    line-height: 1.6;
    max-width: 500px;
    margin: 0;
}

.btn-start-setup {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 1rem 2rem;
    border-radius: var(--border-radius-xl);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    box-shadow: 0 5px 15px rgba(26,95,180,0.3);
}

.btn-start-setup:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(26,95,180,0.4);
}

.estate-steps-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.estate-step-card {
    background: var(--light-bg);
    border-radius: var(--border-radius-md);
    padding: 1.5rem;
    transition: var(--transition);
    border: 1px solid var(--light-border);
}

.estate-step-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-md);
    border-color: var(--primary-color);
}

.step-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
}

.step-number-badge {
    background: var(--primary-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.step-icon-circle {
    width: 40px;
    height: 40px;
    background: rgba(26,95,180,0.1);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
}

.step-icon-svg {
    width: 22px;
    height: 22px;
}

.step-card-title {
    margin: 0 0 0.5rem;
    color: var(--primary-dark);
    font-weight: 600;
    font-size: 1rem;
}

.step-card-desc {
    margin: 0;
    color: var(--text-light);
    font-size: 0.85rem;
    line-height: 1.5;
}

/* ===== INSTANT ESTATE SECTION ===== */
.instant-estate-section {
    max-width: 1200px;
    margin: 4rem auto;
    padding: 0 2rem;
}

.instant-estate-container {
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
    color: var(--white);
}

.instant-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 2.5rem;
    flex-wrap: wrap;
    gap: 1.5rem;
}

.instant-phase-label {
    font-size: 0.75rem;
    font-weight: 700;
    letter-spacing: 1px;
    color: var(--accent-color);
    text-transform: uppercase;
}

.instant-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0.25rem 0 0.5rem;
}

.instant-subtitle {
    font-size: 1rem;
    opacity: 0.9;
}

.btn-instant {
    background: transparent;
    color: var(--accent-color);
    border: 2px solid rgba(255,215,0,0.3);
    padding: 0.875rem 1.75rem;
    border-radius: var(--border-radius-xl);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
}

.btn-instant:hover {
    background: rgba(255,215,0,0.1);
    border-color: var(--accent-color);
    transform: translateY(-3px);
}

.instant-steps-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.instant-step-card {
    background: rgba(255,255,255,0.1);
    backdrop-filter: blur(10px);
    border-radius: var(--border-radius-md);
    padding: 1.5rem;
    transition: var(--transition);
    border: 1px solid rgba(255,255,255,0.2);
}

.instant-step-card:hover {
    transform: translateY(-5px);
    background: rgba(255,255,255,0.15);
}

.instant-step-number {
    display: inline-block;
    background: var(--accent-color);
    color: var(--primary-dark);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.instant-step-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.instant-step-desc {
    font-size: 0.85rem;
    opacity: 0.9;
    line-height: 1.5;
}

/* ===== PLATFORM SECTION ===== */
.platform-section {
    max-width: 1200px;
    margin: 4rem auto;
    padding: 0 2rem;
}

.platform-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
}

.platform-card {
    background: var(--white);
    border-radius: var(--border-radius-md);
    padding: 2rem;
    text-align: center;
    transition: var(--transition);
    border: 1px solid var(--light-border);
    box-shadow: var(--shadow-sm);
}

.platform-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.platform-icon-wrapper {
    width: 70px;
    height: 70px;
    background: var(--primary-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.platform-icon {
    width: 32px;
    height: 32px;
    color: var(--primary-color);
    stroke: currentColor;
    stroke-width: 2;
    fill: none;
}

.platform-card-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--primary-dark);
    margin-bottom: 0.75rem;
}

.platform-card-desc {
    font-size: 0.9rem;
    color: var(--text-light);
    line-height: 1.5;
}

/* ===== FEATURES SECTION ===== */
.features-section {
    max-width: 1200px;
    margin: 4rem auto;
    padding: 0 2rem;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
}

.feature-card {
    background: var(--white);
    border-radius: var(--border-radius-md);
    padding: 2rem 1.5rem;
    box-shadow: var(--shadow-md);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
    height: 100%;
    text-align: center;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.feature-top-line {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.feature-icon-wrapper {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    width: 70px;
    height: 70px;
    border-radius: var(--border-radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.feature-icon {
    width: 32px;
    height: 32px;
    color: var(--white);
    stroke: currentColor;
    fill: none;
    stroke-width: 2;
}

.feature-title {
    color: var(--primary-dark);
    margin: 0 0 0.75rem;
    font-size: 1.1rem;
    font-weight: 600;
    text-align: center;
}

.feature-description {
    color: var(--text-light);
    line-height: 1.6;
    margin: 0;
    font-size: 0.9rem;
    font-weight: 400;
    text-align: center;
}

.feature-card:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-lg);
}

/* ===== CALCULATOR SECTION ===== */
.calculator-section {
    max-width: 1200px;
    margin: 4rem auto;
    padding: 0 2rem;
    position: relative;
}

.calculator-bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(26,95,180,0.05) 0%, rgba(45,122,214,0.05) 100%);
    border-radius: var(--border-radius-lg);
    z-index: 0;
}

.calculator-content {
    position: relative;
    z-index: 1;
    background: var(--white);
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--light-border);
}

.calculator-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--accent-color), var(--accent-light));
    color: var(--primary-dark);
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius-xl);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    margin-bottom: 1rem;
}

.calculator-text {
    text-align: center;
    margin-bottom: 2rem;
}

.calculator-title {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary-dark);
}

.calculator-title .highlight {
    color: var(--primary-color);
}

.calculator-steps {
    display: flex;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.calculator-step {
    flex: 1;
    text-align: center;
    padding: 1rem;
    background: var(--light-bg);
    border-radius: var(--border-radius-md);
    transition: var(--transition);
}

.calculator-step:hover {
    transform: translateY(-3px);
    background: var(--primary-light);
}

.calculator-step-number {
    width: 40px;
    height: 40px;
    background: var(--primary-color);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 0.75rem;
    font-weight: 700;
    font-size: 1rem;
}

.calculator-step-title {
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--text-primary);
}

.btn-calculator {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 1rem 2rem;
    border-radius: var(--border-radius-xl);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    box-shadow: 0 5px 15px rgba(26,95,180,0.3);
}

.btn-calculator:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(26,95,180,0.4);
}

.text-center {
    text-align: center;
}

/* ===== BUTTONS ===== */
.btn {
    padding: 1rem 2rem;
    font-size: 1rem;
    border-radius: var(--border-radius-xl);
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.btn-primary {
    background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%);
    color: var(--primary-dark);
    box-shadow: var(--shadow-accent);
}

.btn-secondary {
    background: transparent;
    color: var(--white);
    border: 2px solid rgba(255,255,255,0.3);
    font-weight: 500;
}

.btn-icon {
    width: 20px;
    height: 20px;
    transition: transform 0.3s ease;
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

.btn-primary:hover .btn-hover-effect {
    left: 100%;
}

.btn-primary:hover .btn-icon {
    transform: translateX(5px);
}

.btn-secondary:hover {
    border-color: rgba(255,255,255,0.5);
    background: rgba(255,255,255,0.1);
}

/* ===== ABOUT SECTION ===== */
.about-section {
    margin: 4rem auto;
    max-width: 1200px;
    padding: 0 2rem;
}

.about-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 3rem;
}

.about-card {
    background: linear-gradient(135deg, var(--light-bg) 0%, var(--light-border) 100%);
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
    position: relative;
    overflow: hidden;
}

.about-bg {
    position: absolute;
    top: -20px;
    right: -20px;
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 50%;
    opacity: 0.1;
}

.about-title {
    color: var(--primary-color);
    margin-bottom: 1.5rem;
    font-size: 1.8rem;
    font-weight: 600;
}

.about-description {
    font-size: 1rem;
    line-height: 1.8;
    margin-bottom: 1rem;
    color: var(--text-primary);
    font-weight: 400;
}

.highlight-box {
    background: linear-gradient(135deg, rgba(26, 95, 180, 0.1) 0%, rgba(45, 122, 214, 0.1) 100%);
    padding: 1.5rem;
    border-radius: var(--border-radius-sm);
    border-left: 4px solid var(--primary-color);
    margin-top: 1.5rem;
}

.highlight-content {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.highlight-icon {
    width: 24px;
    height: 24px;
    color: var(--primary-color);
    fill: currentColor;
    flex-shrink: 0;
    margin-top: 0.25rem;
}

.highlight-text {
    font-weight: 600;
    color: var(--primary-color);
    margin: 0;
    font-size: 1rem;
}

.facts-card {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
    color: var(--white);
    position: relative;
    overflow: hidden;
}

.facts-header {
    display: flex;
    align-items: center;
    margin-bottom: 2rem;
}

.facts-icon-wrapper {
    width: 50px;
    height: 50px;
    background: rgba(255,255,255,0.2);
    border-radius: var(--border-radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
    flex-shrink: 0;
}

.facts-icon {
    width: 24px;
    height: 24px;
    color: var(--white);
    stroke: currentColor;
    fill: none;
    stroke-width: 2;
}

.facts-title {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
}

.facts-list {
    display: grid;
    gap: 1rem;
}

.fact-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: rgba(255,255,255,0.1);
    border-radius: var(--border-radius-sm);
    transition: var(--transition);
}

.fact-icon {
    width: 20px;
    height: 20px;
    color: var(--accent-color);
    fill: currentColor;
    margin-right: 1rem;
    flex-shrink: 0;
}

.fact-item span {
    font-weight: 500;
    font-size: 0.9rem;
}

.fact-item:hover {
    background: rgba(255,255,255,0.15);
    transform: translateX(5px);
}

/* ===== NOTICE SECTION ===== */
.notice-section {
    max-width: 1200px;
    margin: 4rem auto;
    padding: 0 2rem;
}

.notice-card {
    background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
    border-radius: var(--border-radius-lg);
    padding: 1.5rem;
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    border-left: 4px solid var(--warning-color);
}

.notice-icon {
    flex-shrink: 0;
    width: 32px;
    height: 32px;
    color: #b76e00;
}

.notice-content {
    flex: 1;
}

.notice-title {
    font-size: 1rem;
    font-weight: 700;
    color: #b76e00;
    margin-bottom: 0.5rem;
}

.notice-message {
    font-size: 0.9rem;
    color: var(--text-primary);
    line-height: 1.6;
    margin: 0;
}

/* ===== SHARE SECTION ===== */
.share-section {
    position: relative;
    padding: 6rem 2rem;
    margin: 5rem auto;
    overflow: hidden;
    max-width: 1200px;
    border-radius: 30px;
}

.share-gradient-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, 
        rgba(37, 211, 102, 0.05) 0%,
        rgba(45, 122, 214, 0.08) 25%,
        rgba(255, 215, 0, 0.06) 50%,
        rgba(26, 95, 180, 0.05) 75%,
        rgba(128, 0, 128, 0.04) 100%
    );
    z-index: 1;
}

.share-pattern {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-image: 
        radial-gradient(circle at 20% 80%, rgba(37, 211, 102, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(26, 95, 180, 0.1) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(255, 215, 0, 0.1) 0%, transparent 50%);
    z-index: 1;
}

.share-hearts {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 1;
    overflow: hidden;
}

.share-heart {
    position: absolute;
    font-size: 24px;
    opacity: 0.2;
    animation: floatHeart 8s linear infinite;
}

.share-heart.heart-1 { left: 10%; top: 20%; animation-delay: -3s; animation-duration: 8s; font-size: 28px; opacity: 0.12; }
.share-heart.heart-2 { left: 85%; top: 30%; animation-delay: -5s; animation-duration: 12s; font-size: 32px; opacity: 0.08; }
.share-heart.heart-3 { left: 45%; top: 70%; animation-delay: -2s; animation-duration: 9s; font-size: 20px; opacity: 0.15; }
.share-heart.heart-4 { left: 70%; top: 50%; animation-delay: -6s; animation-duration: 11s; font-size: 24px; opacity: 0.1; }
.share-heart.heart-5 { left: 25%; top: 40%; animation-delay: -4s; animation-duration: 7s; font-size: 36px; opacity: 0.06; }

.share-container {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
}

.share-header {
    text-align: center;
    margin-bottom: 3rem;
}

.share-icon-wrapper {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--success-color), var(--success-dark));
    border-radius: 50%;
    margin-bottom: 1.5rem;
    box-shadow: 0 10px 30px rgba(37, 211, 102, 0.3);
    animation: pulse 2s ease-in-out infinite;
}

.share-icon {
    width: 40px;
    height: 40px;
    color: white;
}

.share-title {
    color: var(--primary-color);
    font-size: 2.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
}

.share-subtitle {
    font-size: 1.1rem;
    color: var(--text-primary);
    max-width: 600px;
    margin: 0 auto;
    line-height: 1.6;
}

.share-subtitle .highlight {
    color: var(--success-dark);
    font-weight: 600;
    background: rgba(37, 211, 102, 0.1);
    padding: 2px 8px;
    border-radius: 4px;
}

.share-message {
    font-size: 1rem;
    color: var(--text-light);
    margin-top: 1rem;
}

.share-card {
    background: var(--white);
    border-radius: 25px;
    padding: 3rem;
    box-shadow: 
        0 20px 60px rgba(0,0,0,0.1),
        0 0 0 1px rgba(255,255,255,0.8);
    position: relative;
    overflow: hidden;
}

.share-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 5px;
    background: linear-gradient(90deg, 
        var(--success-color),
        var(--primary-color),
        var(--accent-color)
    );
}

/* Form Styles */
.form-group {
    margin-bottom: 2rem;
}

.form-label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
    font-size: 1rem;
}

.form-help {
    color: var(--text-light);
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
}

.phone-input-container {
    display: flex;
    gap: 10px;
    background: var(--light-bg);
    border-radius: 12px;
    padding: 5px;
    border: 2px solid #e0e0e0;
    transition: var(--transition);
}

.phone-input-container:focus-within {
    border-color: var(--success-color);
    box-shadow: 0 0 0 3px rgba(37, 211, 102, 0.1);
}

.country-code-wrapper {
    min-width: 140px;
}

.flag-selector {
    position: relative;
    height: 100%;
}

.flag-selector .country-flag {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
    z-index: 2;
}

.flag-selector .country-select {
    width: 100%;
    padding: 15px 15px 15px 50px;
    border: none;
    background: var(--white);
    border-radius: 8px;
    font-size: 14px;
    color: var(--text-primary);
    cursor: pointer;
    appearance: none;
    font-weight: 400;
}

.flag-selector .dropdown-arrow {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    width: 16px;
    height: 16px;
    color: var(--text-light);
    pointer-events: none;
}

.phone-input-wrapper {
    flex: 1;
    position: relative;
}

.phone-input-wrapper .phone-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    width: 20px;
    height: 20px;
    color: var(--text-light);
    z-index: 2;
}

.phone-input {
    width: 100%;
    padding: 15px 15px 15px 45px;
    border: none;
    background: var(--white);
    border-radius: 8px;
    font-size: 16px;
    color: var(--text-primary);
    outline: none;
    font-weight: 400;
}

.phone-input::placeholder {
    color: var(--text-light);
}

.input-focus-border {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--success-color);
    transition: width 0.3s ease;
}

.phone-input:focus + .input-focus-border {
    width: 100%;
}

.error-message {
    color: var(--danger-color);
    font-size: 0.875rem;
    margin-top: 0.5rem;
    font-weight: 500;
}

.btn-share {
    background: linear-gradient(135deg, var(--success-color), var(--success-dark));
    color: var(--white);
    width: 100%;
    justify-content: center;
    padding: 1.2rem 2.5rem;
    font-size: 1rem;
    border-radius: 12px;
    margin-top: 1.5rem;
    overflow: hidden;
    position: relative;
}

.btn-share .btn-text {
    display: flex;
    align-items: center;
    gap: 10px;
    z-index: 2;
    position: relative;
}

.btn-share .whatsapp-icon {
    width: 24px;
    height: 24px;
    fill: currentColor;
}

.btn-share .btn-hover-effect {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    width: 24px;
    height: 24px;
    opacity: 0;
    transition: all 0.3s ease;
    z-index: 2;
}

.btn-share:hover .btn-hover-effect {
    opacity: 1;
    right: 30px;
}

.btn-share .arrow-icon {
    width: 100%;
    height: 100%;
    color: white;
    stroke-width: 2;
}

.btn-share::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.6s ease;
}

.btn-share:hover::before {
    left: 100%;
}

/* Alternative Sharing */
.alternative-sharing {
    margin-top: 3rem;
    padding-top: 2rem;
    border-top: 1px solid #e9ecef;
}

.alternative-title {
    text-align: center;
    color: var(--text-light);
    margin-bottom: 1.5rem;
}

.social-buttons {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}

.social-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 0.875rem;
    border-radius: 10px;
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
}

.social-btn svg {
    width: 20px;
    height: 20px;
}

.whatsapp-btn {
    background: rgba(37, 211, 102, 0.1);
    color: var(--success-dark);
    border: 1px solid rgba(37, 211, 102, 0.3);
}

.whatsapp-btn:hover {
    background: rgba(37, 211, 102, 0.2);
    transform: translateY(-2px);
}

.facebook-btn {
    background: rgba(24, 119, 242, 0.1);
    color: var(--facebook-blue);
    border: 1px solid rgba(24, 119, 242, 0.3);
}

.facebook-btn:hover {
    background: rgba(24, 119, 242, 0.2);
    transform: translateY(-2px);
}

.twitter-btn {
    background: rgba(29, 161, 242, 0.1);
    color: var(--twitter-blue);
    border: 1px solid rgba(29, 161, 242, 0.3);
}

.twitter-btn:hover {
    background: rgba(29, 161, 242, 0.2);
    transform: translateY(-2px);
}

/* ===== QURAN SECTION ===== */
.quran-section {
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary-color) 100%);
    color: var(--white);
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    text-align: center;
    margin: 4rem auto;
    position: relative;
    overflow: hidden;
    max-width: 1200px;
}

.quran-bg-1 {
    position: absolute;
    top: 0;
    right: 0;
    width: 150px;
    height: 150px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    transform: translate(30%, -30%);
}

.quran-bg-2 {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
    transform: translate(-30%, 30%);
}

.quran-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
    margin: 0 auto;
}

.quran-arabic {
    font-family: 'Traditional Arabic', 'Amiri', serif;
    font-size: 2.5rem;
    margin-bottom: 1.5rem;
    line-height: 1.4;
    text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
}

.quran-translation {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 1rem;
    font-style: italic;
    line-height: 1.6;
}

/* ===== CTA SECTION ===== */
.cta-section {
    max-width: 1200px;
    margin: 4rem auto;
    padding: 0 2rem;
}

.cta-container {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    text-align: center;
    color: var(--white);
}

.cta-title {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.cta-subtitle {
    font-size: 1.1rem;
    opacity: 0.95;
    margin-bottom: 2rem;
}

.cta-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-cta-primary {
    background: var(--accent-color);
    color: var(--primary-dark);
    padding: 1rem 2rem;
    border-radius: var(--border-radius-xl);
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
}

.btn-cta-primary:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-accent);
}

.btn-cta-secondary {
    background: transparent;
    color: var(--white);
    border: 2px solid rgba(255,255,255,0.3);
    padding: 1rem 2rem;
    border-radius: var(--border-radius-xl);
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
}

.btn-cta-secondary:hover {
    border-color: var(--white);
    background: rgba(255,255,255,0.1);
    transform: translateY(-3px);
}

/* ===== ANIMATIONS ===== */
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

@keyframes floatHeart {
    0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
    10% { opacity: 0.2; }
    90% { opacity: 0.2; }
    100% { transform: translateY(-100px) rotate(720deg); opacity: 0; }
}

/* ===== RESPONSIVE STYLES ===== */
@media (max-width: 1200px) {
    .features-grid,
    .platform-grid,
    .estate-steps-grid,
    .instant-steps-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 992px) {
    .about-grid {
        grid-template-columns: 1fr !important;
    }
    
    .phase-flow {
        flex-direction: column;
        text-align: center;
    }
    
    .phase {
        text-align: left;
    }
    
    .phase-arrow {
        transform: rotate(90deg);
    }
    
    .platform-grid,
    .features-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 768px) {
    .hero-section {
        min-height: 70vh;
    }
    
    .hero-title {
        font-size: 2.5rem !important;
    }
    
    .hero-subtitle {
        font-size: 1.1rem !important;
    }
    
    .hero-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .features-grid,
    .platform-grid,
    .estate-steps-grid,
    .instant-steps-grid {
        grid-template-columns: 1fr !important;
    }
    
    .estate-steps-header,
    .instant-header {
        flex-direction: column;
        text-align: center;
    }
    
    .calculator-steps {
        flex-direction: column;
    }
    
    .calculator-title {
        font-size: 1.5rem;
    }
    
    .share-title {
        font-size: 2rem !important;
    }
    
    .share-card {
        padding: 2rem;
    }
    
    .cta-title {
        font-size: 1.5rem;
    }
    
    .cta-buttons {
        flex-direction: column;
    }
    
    .phone-input-container {
        flex-direction: column;
    }
    
    .country-code-wrapper {
        width: 100% !important;
    }
    
    .flag-selector {
        width: 100%;
    }
    
    .social-buttons {
        grid-template-columns: 1fr;
    }
    
    .shape {
        display: none !important;
    }
    
    .bg-circle-3 {
        display: none !important;
    }
    
    .importance-container {
        padding: 2rem;
    }
    
    .about-card,
    .facts-card {
        padding: 1.5rem !important;
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: 2rem !important;
    }
    
    .kicker-content {
        justify-content: center;
    }
    
    .share-title {
        font-size: 1.7rem !important;
    }
    
    .calculator-title {
        font-size: 1.3rem;
    }
    
    .importance-title {
        font-size: 1.5rem;
    }
    
    .section-title .title-text {
        font-size: 1.5rem;
    }
    
    .phase-title {
        font-size: 1rem;
    }
    
    .quran-arabic {
        font-size: 1.8rem !important;
    }
}

/* ===== SMOOTH SCROLLING ===== */
html {
    scroll-behavior: smooth;
    scroll-padding-top: 2rem;
}

/* Loading state */
.btn-share.loading {
    opacity: 0.8;
    cursor: not-allowed;
}

.btn-share.loading .btn-text {
    opacity: 0.8;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    console.log('Home page loaded - WhatsApp sharing functionality enabled');
    
    // Country code selection
    const countrySelect = document.getElementById('country_code');
    const flagIcon = document.getElementById('flagIcon');
    const phoneInput = document.getElementById('whatsapp_number');
    const errorMessage = document.getElementById('error-message');
    const shareBtn = document.getElementById('shareWhatsAppBtn');

    function updateFlag() {
        const selectedOption = countrySelect.options[countrySelect.selectedIndex];
        const flag = selectedOption.dataset.flag;
        flagIcon.textContent = flag || '🏳️';
    }

    if (countrySelect) {
        countrySelect.addEventListener('change', updateFlag);
        updateFlag();
    }
    
    // Validate phone number input
    if (phoneInput) {
        phoneInput.addEventListener('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
            if (errorMessage) {
                errorMessage.style.display = 'none';
                errorMessage.textContent = '';
            }
        });
    }

    // WhatsApp sharing function
    function shareViaWhatsApp() {
        const countryCode = countrySelect.value;
        const phoneNumber = phoneInput.value.trim();
        
        // Validation
        if (!phoneNumber) {
            showError('Please enter a phone number');
            phoneInput.focus();
            return;
        }
        
        if (!/^[0-9]+$/.test(phoneNumber)) {
            showError('Please enter a valid phone number (digits only)');
            phoneInput.focus();
            return;
        }
        
        // Check if number is too short
        if (phoneNumber.length < 7) {
            showError('Phone number seems too short. Please check and try again.');
            phoneInput.focus();
            return;
        }
        
        // Remove leading zeros if any
        const cleanedNumber = phoneNumber.replace(/^0+/, '');
        
        // Combine country code and number
        const fullNumber = countryCode + cleanedNumber;
        
        // WhatsApp message text
        const message = `Hi! I found this amazing platform for Islamic inheritance planning - Neo Faraid. It helps with estate planning, Faraid calculations, and digital wasiyyah. Check it out: ${window.location.origin}`;
        
        // Encode the message for URL
        const encodedMessage = encodeURIComponent(message);
        
        // Construct WhatsApp URL
        const whatsappURL = `https://wa.me/${fullNumber}?text=${encodedMessage}`;
        
        // Show loading state
        shareBtn.classList.add('loading');
        shareBtn.disabled = true;
        const originalText = shareBtn.querySelector('.btn-text').innerHTML;
        shareBtn.querySelector('.btn-text').innerHTML = `
            <svg class="whatsapp-icon" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006 1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347zm-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.76.982.998-3.675-.236-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.9 6.994c-.004 5.45-4.438 9.88-9.888 9.88zm8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.333.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.333 11.893-11.893 0-3.18-1.24-6.162-3.495-8.411z"/>
            </svg>
            Sending...
        `;
        
        // Open WhatsApp in a new tab
        setTimeout(() => {
            window.open(whatsappURL, '_blank');
            
            // Reset button after 2 seconds
            setTimeout(() => {
                shareBtn.classList.remove('loading');
                shareBtn.disabled = false;
                shareBtn.querySelector('.btn-text').innerHTML = originalText;
                
                // Clear the form after successful share
                phoneInput.value = '';
            }, 2000);
        }, 500);
    }

    function showError(message) {
        if (errorMessage) {
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
            phoneInput.classList.add('error');
            
            // Remove error class after 3 seconds
            setTimeout(() => {
                phoneInput.classList.remove('error');
            }, 3000);
        }
    }

    // Event listener for share button
    if (shareBtn) {
        shareBtn.addEventListener('click', shareViaWhatsApp);
    }

    // Also allow Enter key in phone input
    if (phoneInput) {
        phoneInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                shareViaWhatsApp();
            }
        });
    }

    // Debug: Check if elements exist
    console.log('Share button exists:', !!shareBtn);
    console.log('Phone input exists:', !!phoneInput);
    console.log('Country select exists:', !!countrySelect);

    // Button hover effects
    const primaryBtn = document.querySelector('.btn-primary');
    if (primaryBtn) {
        primaryBtn.addEventListener('mouseenter', function() {
            const arrow = this.querySelector('.btn-icon');
            if (arrow) {
                arrow.style.transform = 'translateX(5px)';
            }
        });

        primaryBtn.addEventListener('mouseleave', function() {
            const arrow = this.querySelector('.btn-icon');
            if (arrow) {
                arrow.style.transform = 'translateX(0)';
            }
        });
    }

    // Feature card hover effects
    const featureCards = document.querySelectorAll('.feature-card, .estate-step-card, .platform-card, .instant-step-card');
    featureCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.boxShadow = 'var(--shadow-md)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'var(--shadow-sm)';
        });
    });

    // Process step hover effects
    const calculatorSteps = document.querySelectorAll('.calculator-step');
    calculatorSteps.forEach(step => {
        step.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });

        step.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Social button hover effects
    const socialBtns = document.querySelectorAll('.social-btn');
    socialBtns.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
        });

        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = 'none';
        });
    });

    // Create floating hearts animation
    function animateHearts() {
        const hearts = document.querySelectorAll('.share-heart');
        hearts.forEach(heart => {
            // Reset animation
            heart.style.animation = 'none';
            setTimeout(() => {
                heart.style.animation = '';
            }, 10);
        });
    }

    // Animate hearts on page load
    setTimeout(animateHearts, 500);
});
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\neo-faraid\resources\views/home.blade.php ENDPATH**/ ?>