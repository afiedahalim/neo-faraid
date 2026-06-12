@extends('layouts.app')

@section('title', 'Feedback')

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
        --success: #28a745;
        --danger: #dc3545;
        --warning: #fd7e14;
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
            User <span class="hero-highlight">Feedback</span> & Reviews
        </h1>
        
        <p class="hero-subtitle">
            See what our community says about Neo Faraid. Share your experience too!
        </p>
    </div>
</header>

<main class="main-content" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
    <!-- Success & Error Messages -->
    @if(session('success'))
    <div class="alert alert-success">
        <svg class="alert-icon" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span class="alert-content">{{ session('success') }}</span>
        <button type="button" class="alert-close">×</button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger">
        <svg class="alert-icon" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
        </svg>
        <span class="alert-content">{{ session('error') }}</span>
        <button type="button" class="alert-close">×</button>
    </div>
    @endif

    <!-- Search Section -->
    <div class="card search-section">
        <div class="search-decoration"></div>
        
        <div class="search-header">
            <h2>
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                </svg>
                Search Feedback
            </h2>
            <p>
                Looking for specific feedback? Search through user reviews and ratings.
            </p>
        </div>
        
        <div class="search-input-container">
            <input type="text" id="feedback-search" placeholder="Search by name, rating, or feedback...">
            <div class="search-icon">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 20 20" fill="#ffffff">
                        <path fill-rule="evenodd" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-value">{{ $stats['average_rating'] ?? '0.0' }}</div>
                <div class="stat-label">Average Rating</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 20 20" fill="#ffffff">
                        <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-value">{{ $stats['total_reviews'] ?? 0 }}</div>
                <div class="stat-label">Total Reviews</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <svg viewBox="0 0 20 20" fill="#ffffff">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="stat-value">{{ $stats['satisfaction_rate'] ?? 0 }}%</div>
                <div class="stat-label">Satisfaction Rate</div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <h2>
            <span>Filter by Rating</span>
            <div class="title-decoration"></div>
        </h2>
        
        <div class="filter-buttons">
            <button class="filter-btn active" data-category="all">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                </svg>
                All Reviews
            </button>
            
            <button class="filter-btn" data-category="recent">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
                Recent (7 days)
            </button>
            
            <button class="filter-btn btn-top-three" data-category="top-three">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                </svg>
                Top 3 Reviews
            </button>
            
            @for($i = 5; $i >= 1; $i--)
            <button class="filter-btn" data-category="{{ $i }}-star">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" clip-rule="evenodd"/>
                </svg>
                {{ $i }} Star{{ $i > 1 ? 's' : '' }}
            </button>
            @endfor
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-section">
        <div class="section-header">
            <h2>
                <span>What Our Users Say</span>
                <div class="title-decoration"></div>
            </h2>
            <div class="reviews-count">{{ $feedback->total() ?? 0 }} reviews</div>
        </div>
        
        @if($feedback->count() > 0)
        <div class="reviews-grid" id="feedback-container">
            @php
                $topFeedback = $feedback->sortByDesc(function($item) {
                    return [$item->rating ?? 0, $item->created_at->timestamp];
                })->take(3);
                
                $allFeedback = collect($feedback->items());
            @endphp
            
            @foreach($allFeedback as $item)
            <div class="review-card" 
                 data-rating="{{ $item->rating }}" 
                 data-date="{{ $item->created_at->timestamp }}" 
                 data-top-three="{{ $topFeedback->contains('id', $item->id) ? 'true' : 'false' }}">
                
                @if($topFeedback->contains('id', $item->id))
                <div class="top-badge">
                    <svg viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                    </svg>
                    TOP 3
                </div>
                @endif
                
                <div class="review-header">
                    <div class="user-avatar">
                        {{ $item->avatar_initials ?? '??' }}
                    </div>
                    <div class="user-info">
                        <h4>{{ $item->name ?? 'Anonymous User' }}</h4>
                        <small>{{ $item->email ?? '' }}</small>
                    </div>
                </div>
                
                <div class="stars">
                    @php $rating = $item->rating ?? 0; @endphp
                    @for($i = 1; $i <= 5; $i++)
                    <svg viewBox="0 0 20 20" style="color: {{ $i <= $rating ? '#ffc107' : '#dee2e6' }};">
                        <path fill="currentColor" fill-rule="evenodd" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" clip-rule="evenodd"/>
                    </svg>
                    @endfor
                </div>
                
                <p class="review-content">
                    "{{ Str::limit($item->message ?? '', 150) }}"
                </p>
                
                <div class="review-footer">
                    <div class="review-date">
                        <svg viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                        {{ $item->created_at->format('M d, Y') }}
                    </div>
                    @if($item->created_at->diffInDays() < 7)
                    <div class="new-badge">NEW</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($feedback->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Showing {{ $feedback->firstItem() }} to {{ $feedback->lastItem() }} of {{ $feedback->total() }} reviews
            </div>
            
            <div class="pagination">
                @if ($feedback->onFirstPage())
                <span class="page-link disabled">< Previous</span>
                @else
                <a href="{{ $feedback->previousPageUrl() }}" class="page-link">< Previous</a>
                @endif
                
                @foreach ($feedback->getUrlRange(1, $feedback->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="page-link {{ $page == $feedback->currentPage() ? 'active' : '' }}">
                    {{ $page }}
                </a>
                @endforeach
                
                @if ($feedback->hasMorePages())
                <a href="{{ $feedback->nextPageUrl() }}" class="page-link">Next ></a>
                @else
                <span class="page-link disabled">Next ></span>
                @endif
            </div>
            
            <div class="per-page-select">
                <span>Per page:</span>
                <select id="perPageSelect">
                    <option value="9" {{ request('per_page', 9) == 9 ? 'selected' : '' }}>9</option>
                    <option value="18" {{ request('per_page', 9) == 18 ? 'selected' : '' }}>18</option>
                    <option value="27" {{ request('per_page', 9) == 27 ? 'selected' : '' }}>27</option>
                </select>
            </div>
        </div>
        @endif
        @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">💬</div>
            <h3 class="empty-title">No feedback yet</h3>
            <p class="empty-message">Be the first to share your experience with Neo Faraid!</p>
            @auth
            <a href="#feedback-form" class="btn btn-primary">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                </svg>
                Share Your Experience
            </a>
            @else
            <a href="{{ route('login') }}" class="btn btn-primary">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Login to Submit Feedback
            </a>
            @endauth
        </div>
        @endif
    </div>

    <!-- Feedback Form Section -->
    <div class="feedback-form-section" id="feedback-form">
        <h2>
            <span>Share Your Experience</span>
            <div class="title-decoration"></div>
        </h2>
        
        @auth
        <form id="feedbackForm" action="{{ route('feedback.store') }}" method="POST">
            @csrf
            
            <!-- Rating -->
            <div class="form-group">
                <label class="form-label">
                    <span class="required">*</span> Rating
                </label>
                <div class="star-rating">
                    @for($i = 1; $i <= 5; $i++)
                    <span class="star" data-value="{{ $i }}">★</span>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating" value="{{ old('rating', 5) }}">
                @error('rating')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Message -->
            <div class="form-group">
                <label for="message" class="form-label">
                    <span class="required">*</span> Your Feedback
                </label>
                <div class="textarea-wrapper">
                    <textarea name="message" 
                              id="message" 
                              placeholder="Share your experience with Neo Faraid..." 
                              required>{{ old('message') }}</textarea>
                    <div class="textarea-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 13V5a2 2 0 00-2-2H4a2 2 0 00-2 2v8a2 2 0 002 2h3l3 3 3-3h3a2 2 0 002-2zM5 7a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm1 3a1 1 0 100 2h3a1 1 0 100-2H6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <div class="char-counter">
                    <small>Minimum 10 characters</small>
                    <small id="charCount">0/500</small>
                </div>
                @error('message')
                <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            
            <!-- Submit Button -->
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    Submit
                </button>
            </div>
        </form>
        @else
        <!-- Login Prompt -->
        <div class="login-prompt">
            <div class="login-icon">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                </svg>
            </div>
            <h3>Login to Submit Feedback</h3>
            <p>Please login to share your experience with Neo Faraid.</p>
            <a href="{{ route('login') }}" class="btn btn-primary">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
                Login Now
            </a>
        </div>
        @endauth
    </div>
</main>

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

/* Alerts */
.alert {
    background: var(--white);
    border-radius: var(--border-radius-md);
    padding: 1.5rem;
    margin: 2rem 0;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: var(--shadow-sm);
    border-left: 4px solid transparent;
}

.alert-success {
    border-left-color: var(--success);
    background: linear-gradient(135deg, #f6fff9 0%, #e8f7ec 100%);
}

.alert-danger {
    border-left-color: var(--danger);
    background: linear-gradient(135deg, #fff6f6 0%, #f8e8e8 100%);
}

.alert-icon {
    width: 24px;
    height: 24px;
    flex-shrink: 0;
}

.alert-content {
    flex: 1;
    font-weight: 500;
    color: var(--text-primary);
}

.alert-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--text-light);
    cursor: pointer;
    padding: 0;
    line-height: 1;
    transition: var(--transition);
}

.alert-close:hover {
    color: var(--primary-color);
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

#feedback-search {
    width: 100%;
    padding: 1.25rem 1.25rem 1.25rem 3.5rem;
    border: 2px solid var(--light-border);
    border-radius: var(--border-radius-md);
    font-size: 1.1rem;
    transition: var(--transition);
    font-family: 'Poppins', sans-serif !important;
}

#feedback-search:focus {
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

/* Stats Section */
.stats-section {
    margin: 3rem 0;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    background: var(--white);
    border-radius: var(--border-radius-md);
    padding: 1.5rem;
    text-align: center;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--light-border);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.stat-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.stat-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    border-radius: var(--border-radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
}

.stat-icon svg {
    width: 24px;
    height: 24px;
    color: var(--white);
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-color);
    margin-bottom: 0.25rem;
    line-height: 1;
}

.stat-label {
    color: var(--text-light);
    font-weight: 500;
}

/* Filter Section */
.filter-section {
    margin: 3rem 0 4rem 0;
    background: white;
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
    border: 1px solid var(--light-border);
    box-shadow: var(--shadow-sm);
}

.filter-section h2 {
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

.filter-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-btn {
    padding: 0.75rem 1.5rem;
    background: white;
    color: var(--primary-color);
    border: 2px solid var(--light-border);
    border-radius: var(--border-radius-xl);
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    font-family: 'Poppins', sans-serif !important;
}

.filter-btn.active {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: white;
    border: none;
    box-shadow: 0 5px 15px rgba(26, 95, 180, 0.3);
}

.filter-btn:hover:not(.active) {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(26, 95, 180, 0.2);
}

.filter-btn svg {
    width: 18px;
    height: 18px;
}

.btn-top-three {
    background: linear-gradient(135deg, var(--accent-color) 0%, #ffb347 100%) !important;
    color: #000 !important;
    font-weight: 700 !important;
    border: none !important;
}

.btn-top-three:hover {
    box-shadow: 0 5px 15px rgba(255, 215, 0, 0.3) !important;
}

/* Reviews Section */
.reviews-section {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
    margin: 3rem 0 4rem 0;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--light-border);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.section-header h2 {
    color: var(--primary-color);
    font-weight: 600;
    position: relative;
    font-size: 1.8rem;
    margin: 0;
}

.reviews-count {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: var(--white);
    padding: 0.5rem 1.5rem;
    border-radius: var(--border-radius-xl);
    font-weight: 600;
    font-size: 1rem;
    box-shadow: 0 5px 15px rgba(26, 95, 180, 0.3);
}

.reviews-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.review-card {
    background: var(--white);
    border-radius: var(--border-radius-md);
    padding: 1.5rem;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--light-border);
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.review-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.top-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: linear-gradient(135deg, var(--accent-color) 0%, #ffb347 100%);
    color: #000;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    z-index: 1;
}

.top-badge svg {
    width: 12px;
    height: 12px;
}

.review-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.rating {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.stars {
    display: flex;
    gap: 2px;
}

.stars svg {
    width: 18px;
    height: 18px;
}

.rating-value {
    color: var(--primary-color);
    font-weight: 700;
    font-size: 1.1rem;
}

.review-content {
    color: var(--text-primary);
    line-height: 1.6;
    margin-bottom: 1.5rem;
    font-style: italic;
    font-weight: 500;
}

.review-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid var(--light-border);
}

.review-date {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-light);
    font-size: 0.85rem;
    font-weight: 500;
}

.review-date svg {
    width: 14px;
    height: 14px;
}

.new-badge {
    background: linear-gradient(135deg, var(--success) 0%, #20c997 100%);
    color: var(--white);
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    border-radius: var(--border-radius-md);
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border: 2px dashed var(--light-border);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.empty-title {
    color: var(--primary-color);
    margin-bottom: 0.5rem;
    font-weight: 600;
    font-size: 1.5rem;
}

.empty-message {
    color: var(--text-light);
    margin-bottom: 2rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
    font-weight: 500;
}

/* Pagination */
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 2rem;
    border-top: 1px solid var(--light-border);
    flex-wrap: wrap;
    gap: 1rem;
}

.pagination-info {
    color: var(--text-light);
    font-weight: 500;
}

.pagination {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.page-link {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius-sm);
    border: 2px solid var(--light-border);
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    transition: var(--transition);
    background: var(--white);
}

.page-link:hover {
    background: var(--light-bg);
    border-color: var(--primary-color);
}

.page-link.active {
    background: var(--primary-color);
    color: var(--white);
    border-color: var(--primary-color);
}

.page-link.disabled {
    color: var(--text-light);
    border-color: var(--light-border);
    cursor: not-allowed;
    opacity: 0.7;
}

.per-page-select {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
}

.per-page-select select {
    padding: 0.5rem;
    border-radius: var(--border-radius-sm);
    border: 2px solid var(--light-border);
    background: var(--white);
    color: var(--text-primary);
    font-weight: 500;
    font-family: 'Poppins', sans-serif !important;
}

/* Feedback Form Section */
.feedback-form-section {
    background: var(--white);
    border-radius: var(--border-radius-lg);
    padding: 2.5rem;
    margin: 3rem 0 4rem 0;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--light-border);
    position: relative;
    overflow: hidden;
}

.feedback-form-section h2 {
    color: var(--primary-color);
    font-weight: 600;
    margin-bottom: 2rem;
    text-align: center;
    position: relative;
    font-size: 1.8rem;
}

.feedback-form-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
}

.form-group {
    margin-bottom: 2rem;
}

.form-label {
    display: block;
    color: var(--text-primary);
    font-weight: 600;
    margin-bottom: 0.5rem;
    font-family: 'Poppins', sans-serif !important;
}

.required {
    color: var(--danger);
}

.star-rating {
    display: flex;
    gap: 0.5rem;
    margin-bottom: 0.5rem;
}

.star {
    font-size: 2.5rem;
    color: #dee2e6;
    cursor: pointer;
    transition: var(--transition);
    line-height: 1;
    font-family: 'Poppins', sans-serif !important;
}

.star:hover,
.star.active {
    color: #ffc107;
    transform: scale(1.1);
}

.textarea-wrapper {
    position: relative;
}

.textarea-wrapper textarea {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    border: 2px solid var(--light-border);
    border-radius: var(--border-radius-md);
    font-size: 1rem;
    min-height: 150px;
    resize: vertical;
    transition: var(--transition);
    font-family: 'Poppins', sans-serif !important;
}

.textarea-wrapper textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(26, 95, 180, 0.1);
}

.textarea-icon {
    position: absolute;
    left: 1rem;
    top: 1rem;
    color: var(--primary-color);
}

.textarea-icon svg {
    width: 20px;
    height: 20px;
}

.char-counter {
    display: flex;
    justify-content: space-between;
    margin-top: 0.5rem;
    color: var(--text-light);
    font-size: 0.85rem;
    font-weight: 500;
}

.error-message {
    color: var(--danger);
    font-size: 0.85rem;
    margin-top: 0.5rem;
    font-weight: 500;
}

.form-actions {
    text-align: right;
}

.btn {
    padding: 1rem 2rem;
    border-radius: var(--border-radius-xl);
    font-weight: 600;
    font-size: 1rem;
    border: none;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    font-family: 'Poppins', sans-serif !important;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
    color: var(--white);
    box-shadow: 0 5px 15px rgba(26, 95, 180, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(26, 95, 180, 0.4);
}

.btn svg {
    width: 20px;
    height: 20px;
}

/* Login Prompt */
.login-prompt {
    text-align: center;
    padding: 3rem 2rem;
}

.login-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--light-bg) 0%, var(--light-border) 100%);
    border-radius: var(--border-radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.login-icon svg {
    width: 40px;
    height: 40px;
    color: var(--primary-color);
}

.login-prompt h3 {
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-weight: 600;
    font-size: 1.5rem;
}

.login-prompt p {
    color: var(--text-light);
    margin-bottom: 2rem;
    max-width: 400px;
    margin-left: auto;
    margin-right: auto;
    font-weight: 500;
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

@media (max-width: 1024px) {
    .reviews-grid {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
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
    .filter-section,
    .reviews-section,
    .feedback-form-section {
        padding: 1.5rem !important;
    }
    
    .filter-buttons {
        justify-content: center;
    }
    
    .filter-btn {
        width: 100%;
        min-width: 140px;
        justify-content: center;
    }
    
    .reviews-grid {
        grid-template-columns: 1fr;
    }
    
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .section-header {
        flex-direction: column;
        text-align: center;
    }
    
    .filter-section h2,
    .reviews-section h2,
    .feedback-form-section h2 {
        justify-content: center;
        text-align: center;
    }
    
    .pagination-wrapper {
        flex-direction: column;
        text-align: center;
    }
    
    .btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
    
    #feedback-search {
        padding: 1rem 1rem 1rem 3rem !important;
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
    
    .kicker-content {
        justify-content: center;
    }
    
    .search-section,
    .filter-section,
    .reviews-section,
    .feedback-form-section {
        padding: 1.25rem !important;
    }
    
    .filter-buttons {
        justify-content: center;
    }
    
    .pagination {
        justify-content: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const closeBtn = alert.querySelector('.alert-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }
        
        setTimeout(() => {
            if (alert && document.body.contains(alert)) {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    });
    
    // Star rating
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('rating');
    
    function updateStars(value) {
        if (!ratingInput) return;
        
        ratingInput.value = value;
        
        stars.forEach((star, index) => {
            if (index < value) {
                star.classList.add('active');
            } else {
                star.classList.remove('active');
            }
        });
    }
    
    stars.forEach(star => {
        star.addEventListener('click', () => {
            const value = parseInt(star.dataset.value);
            updateStars(value);
        });
        
        star.addEventListener('mouseover', () => {
            const value = parseInt(star.dataset.value);
            stars.forEach((s, index) => {
                s.style.color = index < value ? '#ffc107' : '#dee2e6';
            });
        });
        
        star.addEventListener('mouseout', () => {
            const currentValue = parseInt(ratingInput.value) || 5;
            stars.forEach((s, index) => {
                s.style.color = index < currentValue ? '#ffc107' : '#dee2e6';
            });
        });
    });
    
    // Initialize stars
    if (ratingInput) {
        updateStars(parseInt(ratingInput.value) || 5);
    }
    
    // Character counter
    const messageTextarea = document.getElementById('message');
    const charCount = document.getElementById('charCount');
    
    if (messageTextarea && charCount) {
        function updateCharCount() {
            const length = messageTextarea.value.length;
            charCount.textContent = `${length}/500`;
            
            if (length > 500) {
                charCount.style.color = 'var(--danger)';
            } else if (length < 10) {
                charCount.style.color = 'var(--warning)';
            } else {
                charCount.style.color = 'var(--text-light)';
            }
        }
        
        messageTextarea.addEventListener('input', updateCharCount);
        updateCharCount();
    }
    
    // Search functionality
    const searchInput = document.getElementById('feedback-search');
    const reviewCards = document.querySelectorAll('.review-card');
    const filterButtons = document.querySelectorAll('.filter-btn');
    
    function filterReviews() {
        const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
        const activeFilter = document.querySelector('.filter-btn.active')?.dataset.category || 'all';
        
        reviewCards.forEach(card => {
            const name = card.querySelector('.user-info h4')?.textContent.toLowerCase() || '';
            const content = card.querySelector('.review-content')?.textContent.toLowerCase() || '';
            const rating = card.dataset.rating || '';
            const date = parseInt(card.dataset.date) * 1000;
            const isTopThree = card.dataset.topThree === 'true';
            
            let matchesSearch = true;
            let matchesFilter = true;
            
            // Search filter
            if (searchTerm) {
                matchesSearch = name.includes(searchTerm) || 
                               content.includes(searchTerm) ||
                               rating.includes(searchTerm);
            }
            
            // Category filter
            switch (activeFilter) {
                case 'all':
                    matchesFilter = true;
                    break;
                case 'recent':
                    const oneWeekAgo = Date.now() - (7 * 24 * 60 * 60 * 1000);
                    matchesFilter = date > oneWeekAgo;
                    break;
                case 'top-three':
                    matchesFilter = isTopThree;
                    break;
                default:
                    if (activeFilter.includes('star')) {
                        const starRating = parseInt(activeFilter.charAt(0));
                        matchesFilter = parseInt(rating) === starRating;
                    }
            }
            
            card.style.display = (matchesSearch && matchesFilter) ? 'block' : 'none';
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('input', filterReviews);
    }
    
    // Filter buttons
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            filterReviews();
        });
    });
    
    // Form submission
    const feedbackForm = document.getElementById('feedbackForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (feedbackForm && submitBtn) {
        feedbackForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Basic validation
            const message = document.getElementById('message')?.value.trim() || '';
            const rating = ratingInput?.value || '5';
            
            if (!message || message.length < 10) {
                alert('Please enter at least 10 characters for your feedback.');
                return false;
            }
            
            if (message.length > 500) {
                alert('Feedback message must be less than 500 characters.');
                return false;
            }
            
            submitBtn.disabled = true;
            submitBtn.innerHTML = 'Submitting...';
            
            // Submit form
            setTimeout(() => {
                feedbackForm.submit();
            }, 1000);
        });
    }
    
    // Per page select
    const perPageSelect = document.getElementById('perPageSelect');
    if (perPageSelect) {
        perPageSelect.addEventListener('change', function() {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', this.value);
            window.location.href = url.toString();
        });
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href !== '#') {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });
    
    // Button hover effects
    const buttons = document.querySelectorAll('.btn, .filter-btn');
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(-2px)';
            }
        });
        
        btn.addEventListener('mouseleave', function() {
            if (!this.classList.contains('active')) {
                this.style.transform = 'translateY(0)';
            }
        });
    });
});
</script>
@endsection