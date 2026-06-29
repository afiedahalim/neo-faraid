@extends('layouts.app')

@section('title', 'FAQ')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
    
    * {
        font-family: 'Poppins', sans-serif !important;
    }
    
    :root {
        --primary-color: #1a5fb4;
        --primary-dark: #0d2d5c;
        --secondary-color: #2d7ad6;
        --accent-color: #ffd700;
        --accent-light: #ffed4e;
        --light-bg: #f8f9fa;
        --light-border: #e9ecef;
        --text-primary: #495057;
        --text-light: #6c757d;
        --white: #ffffff;
        --shadow-sm: 0 2px 8px rgba(0,0,0,0.08);
        --shadow-md: 0 10px 30px rgba(0,0,0,0.08);
        --shadow-lg: 0 20px 40px rgba(0,0,0,0.12);
        --border-radius-sm: 12px;
        --border-radius-md: 15px;
        --border-radius-lg: 20px;
        --border-radius-xl: 50px;
        --transition: all 0.3s ease;
    }
</style>

<header class="dashboard-header">
    <!-- Animated background using CSS only -->
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
        <div class="hero-kicker">
            <div class="kicker-content">
                @foreach(['Precise', 'Reliable', 'Shariah-Compliant'] as $item)
                <span class="kicker-item">
                    <svg class="kicker-icon" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                    {{ $item }}
                </span>
                @endforeach
            </div>
        </div>
        
        <h1 class="hero-title">
            Frequently <span class="hero-highlight">Asked Questions</span>
        </h1>
        
        <p class="hero-subtitle">
            Clear answers for common questions about Neo Faraid
        </p>
    </div>
</header>

<main class="main-content" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
    <!-- Search Bar -->
    <div class="card search-section">
        <div class="search-decoration"></div>
        
        <div class="search-header">
            <h2>
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                </svg>
                Search for Answers
            </h2>
            <p>
                Can't find what you're looking for? Search through our comprehensive FAQ database.
            </p>
        </div>
        
        <div class="search-input-container">
            <input type="text" id="faq-search" placeholder="Type your question here...">
            <div class="search-icon">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- FAQ Categories with Tabs -->
    <div class="faq-categories-section">
        <h2>
            <span>Browse by Category</span>
            <div class="title-decoration"></div>
        </h2>
        
        <div class="category-buttons">
            @php
                $categories = [
                    ['id' => 'all', 'name' => 'All Questions'],
                    ['id' => 'gettingStarted', 'name' => 'Getting Started'],
                    ['id' => 'calculations', 'name' => 'Calculations'],
                    ['id' => 'securityPrivacy', 'name' => 'Security & Privacy'],
                    ['id' => 'others', 'name' => 'Other']
                ];
            @endphp
            
            @foreach($categories as $index => $category)
                <button class="category-btn {{ $index === 0 ? 'active' : '' }}" 
                        data-category="{{ $category['id'] }}">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        @if($category['id'] === 'all')
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                        @elseif($category['id'] === 'gettingStarted')
                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                        @elseif($category['id'] === 'calculations')
                        <path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/>
                        @elseif($category['id'] === 'securityPrivacy')
                        <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        @else
                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        @endif
                    </svg>
                    {{ $category['name'] }}
                </button>
            @endforeach
        </div>
    </div>

    <!-- FAQ Accordion -->
    <div id="faq-accordion">
        @forelse($faqs as $faq)
            @php
                // Map category to display label and icon
                $categoryMap = [
                    'gettingStarted' => ['label' => 'Getting Started', 'icon' => 'M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z'],
                    'calculations'   => ['label' => 'Calculations',   'icon' => 'M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z'],
                    'securityPrivacy'=> ['label' => 'Security & Privacy', 'icon' => 'M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z'],
                    'others'         => ['label' => 'Other',          'icon' => 'M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z'],
                ];
                $catInfo = $categoryMap[$faq->category] ?? $categoryMap['others'];
                $badgeClass = match($faq->category) {
                    'gettingStarted' => 'category-getting-started',
                    'calculations'   => 'category-calculations',
                    'securityPrivacy'=> 'category-security-privacy',
                    default          => 'category-other',
                };
            @endphp
            <div class="faq-item" data-category="{{ $faq->category }}">
                <div class="card">
                    <button class="faq-question" type="button">
                        <div class="faq-header">
                            <div class="faq-icon-container">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="{{ $catInfo['icon'] }}" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="faq-title-section">
                                <h3>{{ $faq->question }}</h3>
                                <span class="category-badge {{ $badgeClass }}">
                                    {{ $catInfo['label'] }}
                                </span>
                            </div>
                        </div>
                        <svg class="faq-toggle-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div class="faq-answer">
                        <div class="faq-answer-content">
                            <p>{{ $faq->answer }}</p>
                            <div class="faq-footer">
                                <div class="faq-meta">
                                    <span>
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                        </svg>
                                        2 min read
                                    </span>
                                    <span>
                                        <svg fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Verified answer
                                    </span>
                                </div>
                                @if($faq->category == 'calculations')
                                <a href="{{ route('calculator.index') }}" class="calculator-link">
                                    Try in calculator
                                    <svg fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center" style="padding: 3rem 0; color: var(--text-light);">No FAQs available at the moment.</p>
        @endforelse

        <!-- Pagination -->
        <div class="pagination-wrapper" style="margin-top: 2rem;">
            {{ $faqs->links() }}
        </div>
    </div>
</main>

<!-- Keep all original styles (the entire <style> block) - unchanged -->
<style>
/* ===== DASHBOARD HEADER ===== */
.dashboard-header {
    min-height: 60vh;
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

/* Animated Background Styles */
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

/* Floating Shapes Animation */
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

/* Animation Speed Classes */
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
    font-family: 'Poppins', sans-serif;
}

.kicker-icon {
    width: 16px;
    height: 16px;
    fill: currentColor;
}

.hero-title {
    font-size: 3rem;
    line-height: 1.2;
    margin-bottom: 1.5rem;
    text-shadow: 2px 2px 8px rgba(0,0,0,0.3);
    font-family: 'Poppins', sans-serif;
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
    font-family: 'Poppins', sans-serif;
    font-weight: 400;
}

/* Main Content Styles */
.main-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
}

/* Search Section */
.search-section {
    background: linear-gradient(135deg, var(--light-bg) 0%, var(--light-border) 100%);
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
    margin: 3rem 0;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow-md);
}

.search-decoration {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.search-header {
    text-align: center;
    margin-bottom: 1.5rem;
}

.search-header h2 {
    color: var(--primary-color);
    font-size: 1.75rem;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    font-weight: 600;
}

.search-header h2 svg {
    width: 28px;
    height: 28px;
}

.search-header p {
    color: var(--text-light);
    max-width: 500px;
    margin: 0 auto;
    font-size: 1.1rem;
    font-weight: 500;
}

.search-input-container {
    position: relative;
    max-width: 600px;
    margin: 0 auto;
}

#faq-search {
    width: 100%;
    padding: 1.25rem 1.25rem 1.25rem 3.5rem;
    border: 2px solid var(--light-border);
    border-radius: var(--border-radius-md);
    font-size: 1.1rem;
    transition: var(--transition);
    font-family: 'Poppins', sans-serif !important;
}

#faq-search:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.1);
    outline: none;
}

.search-icon {
    position: absolute;
    left: 1.25rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary-color);
}

.search-icon svg {
    width: 22px;
    height: 22px;
}

/* FAQ Categories Section */
.faq-categories-section {
    margin: 3rem 0 4rem 0;
    background: white;
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
    border: 1px solid var(--light-border);
    box-shadow: var(--shadow-sm);
}

.faq-categories-section h2 {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 2rem;
    text-align: center;
    position: relative;
    font-size: 1.8rem;
}

.title-decoration {
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 60px;
    height: 3px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    border-radius: 2px;
}

.category-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 3rem;
    flex-wrap: wrap;
}

.category-btn {
    padding: 0.75rem 1.5rem;
    background: white;
    color: var(--primary-color);
    border: 2px solid var(--light-border);
    border-radius: var(--border-radius-xl);
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-family: 'Poppins', sans-serif !important;
}

.category-btn.active {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    border: none;
    box-shadow: 0 5px 15px rgba(26, 95, 180, 0.3);
}

.category-btn:hover:not(.active) {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(26, 95, 180, 0.2);
}

.category-btn svg {
    width: 18px;
    height: 18px;
}

/* FAQ Accordion */
#faq-accordion {
    margin: 0 0 4rem 0;
}

.faq-item {
    margin-bottom: 1rem;
}

.faq-item .card {
    border-radius: var(--border-radius-md);
    padding: 0;
    overflow: hidden;
    background: white;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    border: 1px solid var(--light-border);
}

.faq-item .card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.faq-question {
    width: 100%;
    text-align: left;
    background: none;
    border: none;
    padding: 1.75rem;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: var(--transition);
    font-family: 'Poppins', sans-serif !important;
}

.faq-question:hover {
    background: linear-gradient(135deg, rgba(26, 95, 180, 0.05) 0%, rgba(45, 122, 214, 0.05) 100%);
}

.faq-item.active .faq-question {
    background: linear-gradient(135deg, rgba(26, 95, 180, 0.1) 0%, rgba(45, 122, 214, 0.1) 100%);
}

.faq-header {
    display: flex;
    align-items: flex-start;
    gap: 1.25rem;
}

.faq-icon-container {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    width: 48px;
    height: 48px;
    border-radius: var(--border-radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.faq-icon-container svg {
    width: 24px;
    height: 24px;
    color: white;
}

.faq-title-section h3 {
    color: var(--primary-color);
    margin: 0 0 0.25rem 0;
    font-size: 1.2rem;
    font-weight: 600;
    text-align: left;
}

.category-badge {
    background: linear-gradient(135deg, rgba(26, 95, 180, 0.1) 0%, rgba(45, 122, 214, 0.1) 100%);
    color: var(--primary-color);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
    display: inline-block;
}
/* Additional category badge colors */
.category-getting-started {
    background: linear-gradient(135deg, rgba(0, 123, 255, 0.1) 0%, rgba(0, 86, 179, 0.1) 100%);
    color: #0069d9;
    border: 1px solid rgba(0, 123, 255, 0.2);
}
.category-calculations {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1) 0%, rgba(33, 136, 56, 0.1) 100%);
    color: #28a745;
    border: 1px solid rgba(40, 167, 69, 0.2);
}
.category-security-privacy {
    background: linear-gradient(135deg, rgba(255, 193, 7, 0.1) 0%, rgba(224, 168, 0, 0.1) 100%);
    color: #ffc107;
    border: 1px solid rgba(255, 193, 7, 0.2);
}
.category-other {
    background: linear-gradient(135deg, rgba(108, 117, 125, 0.1) 0%, rgba(90, 98, 104, 0.1) 100%);
    color: #6c757d;
    border: 1px solid rgba(108, 117, 125, 0.2);
}

.faq-toggle-icon {
    width: 24px;
    height: 24px;
    color: var(--primary-color);
    transition: transform 0.3s ease;
    flex-shrink: 0;
}

.faq-item.active .faq-toggle-icon {
    transform: rotate(180deg);
}

.faq-answer {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.faq-answer-content {
    padding: 0 1.75rem 1.75rem 5.75rem;
    border-top: 1px solid var(--light-border);
}

.faq-answer-content p {
    color: var(--text-primary);
    line-height: 1.8;
    margin: 0 0 1.5rem 0;
    font-size: 1.05rem;
    font-weight: 500;
}

.faq-footer {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 1rem;
}

.faq-meta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.faq-meta span {
    color: var(--text-light);
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    font-weight: 500;
}

.faq-meta span svg {
    width: 16px;
    height: 16px;
}

.faq-meta span:first-child svg {
    color: var(--primary-color);
}

.faq-meta span:last-child svg {
    color: #28a745;
}

.calculator-link {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.calculator-link svg {
    width: 16px;
    height: 16px;
}

/* Contact CTA */
.contact-cta {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border-radius: var(--border-radius-lg);
    padding: 3rem;
    color: white;
    margin: 4rem 0;
    position: relative;
    overflow: hidden;
}

.cta-decoration {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,0.1);
}

.cta-dec-1 {
    top: 0;
    right: 0;
    width: 150px;
    height: 150px;
    transform: translate(30%, -30%);
}

.cta-dec-2 {
    bottom: 0;
    left: 0;
    width: 100px;
    height: 100px;
    background: rgba(255,255,255,0.05);
    transform: translate(-30%, 30%);
}

.cta-content {
    position: relative;
    z-index: 2;
    text-align: center;
}

.cta-content h2 {
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1rem;
    color: white;
}

.cta-content h2 svg {
    width: 32px;
    height: 32px;
    color: var(--accent-color);
}

.cta-content p {
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto 2rem;
    font-size: 1.1rem;
    color: white;
    font-weight: 500;
}

.cta-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}

.btn {
    padding: 1rem 2rem;
    border-radius: var(--border-radius-xl);
    font-weight: 600;
    font-size: 1.1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: var(--transition);
    text-decoration: none;
    cursor: pointer;
    border: none;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(26, 95, 180, 0.3) !important;
}

.btn-primary {
    background: white;
    color: var(--primary-color);
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.btn-secondary {
    background: rgba(255,255,255,0.2);
    color: white;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
}

.btn svg {
    width: 20px;
    height: 20px;
}

/* Homepage Link */
.homepage-link {
    display: flex;
    justify-content: center;
    margin: 4rem 0 2rem 0;
}

.btn-home {
    padding: 1rem 2.5rem;
    border-radius: var(--border-radius-xl);
    font-weight: 600;
    font-size: 1.1rem;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    box-shadow: 0 10px 25px rgba(26, 95, 180, 0.4);
    text-decoration: none;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.btn-home svg {
    width: 20px;
    height: 20px;
    transition: transform 0.3s ease;
    position: relative;
    z-index: 2;
}

.btn-home span {
    position: relative;
    z-index: 2;
}

.btn-hover-effect {
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.6s ease;
    z-index: 1;
}

.btn-home:hover .btn-hover-effect {
    left: 100%;
}

/* Animations */
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

/* Responsive styles */
@media (max-width: 1200px) {
    .hero-container {
        padding-left: 2rem;
        padding-right: 2rem;
    }
}

@media (max-width: 768px) {
    .dashboard-header {
        min-height: 50vh;
    }
    
    .hero-title {
        font-size: 2.5rem !important;
    }
    
    .hero-subtitle {
        font-size: 1.1rem !important;
    }
    
    .main-content {
        padding: 0 1rem;
    }
    
    .search-section,
    .faq-categories-section,
    .contact-cta {
        padding: 1.5rem !important;
    }
    
    .category-btn {
        width: 100%;
        justify-content: center;
    }
    
    .faq-question {
        padding: 1.25rem !important;
    }
    
    .faq-header {
        gap: 0.75rem !important;
    }
    
    .faq-toggle-icon {
        width: 20px !important;
        height: 20px !important;
    }
    
    .faq-answer-content {
        padding: 0 1.25rem 1.25rem 4.5rem !important;
    }
    
    .btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
    
    #faq-search {
        padding: 1rem 1rem 1rem 3rem !important;
    }
    
    /* Hide floating shapes on mobile for performance */
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
    
    .kicker-content {
        justify-content: center;
    }
    
    .search-section,
    .faq-categories-section,
    .contact-cta {
        padding: 1.25rem !important;
    }
    
    .faq-answer-content {
        padding: 0 1rem 1rem 1rem !important;
    }
    
    .faq-icon-container {
        width: 40px !important;
        height: 40px !important;
    }
    
    .faq-icon-container svg {
        width: 18px !important;
        height: 18px !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ Accordion
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const item = this.closest('.faq-item');
            const answer = item.querySelector('.faq-answer');
            const icon = item.querySelector('.faq-toggle-icon');
            
            // Toggle current item
            item.classList.toggle('active');
            
            if (item.classList.contains('active')) {
                answer.style.maxHeight = answer.scrollHeight + 'px';
                icon.style.transform = 'rotate(180deg)';
            } else {
                answer.style.maxHeight = null;
                icon.style.transform = null;
            }
        });
    });
    
    // Category Filtering
    const categoryBtns = document.querySelectorAll('.category-btn');
    const faqItems = document.querySelectorAll('.faq-item');
    
    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active button
            categoryBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const category = this.dataset.category;
            
            // Filter FAQ items
            faqItems.forEach(item => {
                if (category === 'all' || item.dataset.category === category) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
    
    // FAQ Search
    const searchInput = document.getElementById('faq-search');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        // Reset to all category when searching
        categoryBtns.forEach(b => b.classList.remove('active'));
        categoryBtns[0].classList.add('active');
        
        faqItems.forEach(item => {
            const question = item.querySelector('h3').textContent.toLowerCase();
            const answer = item.querySelector('.faq-answer-content p').textContent.toLowerCase();
            
            if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                item.style.display = 'block';
                
                // Auto-open if search term found
                if (searchTerm && !item.classList.contains('active')) {
                    item.querySelector('.faq-question').click();
                }
            } else {
                item.style.display = 'none';
            }
        });
    });
    
    // Auto-open first FAQ
    if (faqQuestions.length > 0) {
        faqQuestions[0].click();
    }
    
    // Add hover effects
    const cards = document.querySelectorAll('.card');
    const buttons = document.querySelectorAll('.btn');
    const categoryButtons = document.querySelectorAll('.category-btn');
    
    // Button hover effect
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Category button hover effect
    categoryButtons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 5px 15px rgba(26, 95, 180, 0.2)';
            }
        });
        
        btn.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'none';
            }
        });
    });
    
    // FAQ question hover effect
    faqQuestions.forEach(question => {
        question.addEventListener('mouseenter', function() {
            if (!this.closest('.faq-item').classList.contains('active')) {
                this.style.background = 'linear-gradient(135deg, rgba(26, 95, 180, 0.05) 0%, rgba(45, 122, 214, 0.05) 100%)';
            }
        });
        
        question.addEventListener('mouseleave', function() {
            if (!this.closest('.faq-item').classList.contains('active')) {
                this.style.background = 'none';
            }
        });
    });
});
</script>
@endsection