@extends('layouts.app')

@section('title', 'Contact Us')

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
            Get In Touch <span class="hero-highlight">With Us</span>
        </h1>
        
        <p class="hero-subtitle">
            We're here to help you with your inheritance planning needs
        </p>
    </div>
</header>

<main class="main-content" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
    <!-- Contact Information -->
    <div class="card" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); border-radius: var(--border-radius-lg); padding: 4rem 3rem; margin: 3rem 0; position: relative; overflow: hidden; color: white;">
        <div style="position: absolute; top: 0; right: 0; width: 250px; height: 250px; background: rgba(255,255,255,0.1); border-radius: 50%; transform: translate(30%, -30%);"></div>
        <div style="position: absolute; bottom: 0; left: 0; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%; transform: translate(-30%, 30%);"></div>
        
        <div style="position: relative; z-index: 2; max-width: 800px; margin: 0 auto;">
            <div style="display: flex; align-items: center; gap: 2rem; margin-bottom: 2rem;">
                <div style="background: rgba(255,255,255,0.2); width: 80px; height: 80px; border-radius: var(--border-radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; backdrop-filter: blur(10px); border: 2px solid rgba(255,255,255,0.3);">
                    <svg style="width: 36px; height: 36px; color: var(--accent-color);" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h2 style="font-size: 2.2rem; margin: 0 0 0.5rem 0; font-weight: 700; color: white;">Contact Information</h2>
                    <p style="font-size: 1.2rem; opacity: 0.9; margin: 0; color: white;">We're here to help you with your inheritance planning needs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Details Grid -->
    <div class="grid cols-2" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; margin: 4rem 0;">
        <!-- Phone & Email -->
        <div style="position: relative;">
            <div class="card" style="background: white; border-radius: var(--border-radius-lg); padding: 2.5rem; height: 100%; position: relative; overflow: hidden; box-shadow: var(--shadow-md);">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));"></div>
                
                <div style="display: grid; gap: 1.5rem;">
                    <div class="contact-item" style="display: flex; align-items: flex-start; padding: 1.5rem; background: var(--light-bg); border-radius: var(--border-radius-md); transition: var(--transition);">
                        <div style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); width: 50px; height: 50px; border-radius: var(--border-radius-md); display: flex; align-items: center; justify-content: center; margin-right: 1.5rem; flex-shrink: 0;">
                            <svg style="width: 24px; height: 24px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 style="color: var(--primary-color); margin: 0 0 0.5rem 0; font-size: 1.1rem; font-weight: 600;">Phone</h3>
                            <p style="color: var(--text-primary); margin: 0; font-size: 1.2rem; font-weight: 500;">+60 12-345 6789</p>
                            <p style="color: var(--text-light); margin: 0.25rem 0 0 0; font-size: 0.9rem;">Available: 9 AM - 6 PM (Mon-Fri)</p>
                        </div>
                    </div>
                    
                    <div class="contact-item" style="display: flex; align-items: flex-start; padding: 1.5rem; background: var(--light-bg); border-radius: var(--border-radius-md); transition: var(--transition);">
                        <div style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); width: 50px; height: 50px; border-radius: var(--border-radius-md); display: flex; align-items: center; justify-content: center; margin-right: 1.5rem; flex-shrink: 0;">
                            <svg style="width: 24px; height: 24px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 style="color: var(--primary-color); margin: 0 0 0.5rem 0; font-size: 1.1rem; font-weight: 600;">Email</h3>
                            <p style="color: var(--text-primary); margin: 0; font-size: 1.2rem; font-weight: 500;">info@neofaraid.com</p>
                            <p style="color: var(--text-light); margin: 0.25rem 0 0 0; font-size: 0.9rem;">Response within 24 hours</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Social Media -->
        <div style="position: relative;">
            <div class="card" style="background: white; border-radius: var(--border-radius-lg); padding: 2.5rem; height: 100%; position: relative; overflow: hidden; box-shadow: var(--shadow-md);">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));"></div>
                
                <div style="padding: 1.5rem; background: linear-gradient(135deg, var(--light-bg) 0%, var(--light-border) 100%); border-radius: var(--border-radius-md); color: var(--text-primary);">
                    <h3 style="margin: 0 0 1.5rem 0; font-size: 1.2rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; color: var(--primary-color);">
                        <svg style="width: 24px; height: 24px;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-3 3a4 4 0 001.656 5.656 1 1 0 101.414-1.414 2 2 0 010-2.828l3-3z" clip-rule="evenodd"/>
                        </svg>
                        Connect With Us
                    </h3>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        <a href="#" style="display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); padding: 0.75rem 1.25rem; border-radius: var(--border-radius-sm); text-decoration: none; color: white; transition: var(--transition);">
                            <svg style="width: 20px; height: 20px;" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                            @neofaraid
                        </a>
                        <a href="#" style="display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); padding: 0.75rem 1.25rem; border-radius: var(--border-radius-sm); text-decoration: none; color: white; transition: var(--transition);">
                            <svg style="width: 20px; height: 20px;" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            @neofaraid
                        </a>
                        <a href="#" style="display: inline-flex; align-items: center; gap: 0.5rem; background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); padding: 0.75rem 1.25rem; border-radius: var(--border-radius-sm); text-decoration: none; color: white; transition: var(--transition);">
                            <svg style="width: 20px; height: 20px;" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.213c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                            @neofaraid
                        </a>
                    </div>
                </div>
            </div>
        </div>
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

/* Card hover effects */
.card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg) !important;
    transition: var(--transition);
}

.contact-item:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md) !important;
}

.btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(26, 95, 180, 0.3) !important;
}

.btn:hover::before {
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
    .grid.cols-2 {
        grid-template-columns: 1fr !important;
    }
    
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
    
    .grid.cols-2 {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
    }
    
    .card {
        padding: 1.5rem !important;
    }
    
    .hero-container {
        padding-left: 1rem;
        padding-right: 1rem;
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
    
    .card {
        padding: 1.25rem !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to cards
    const cards = document.querySelectorAll('.card');
    const contactItems = document.querySelectorAll('.contact-item');
    const buttons = document.querySelectorAll('.btn');
    
    // Card hover effect
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Contact item hover effect
    contactItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Button hover effect
    buttons.forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});
</script>
@endsection