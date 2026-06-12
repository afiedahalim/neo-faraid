@extends('layouts.app')

@section('title', 'About Us')

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
            About <span class="hero-highlight">Neo Faraid</span>
        </h1>
        
        <p class="hero-subtitle">
            Revolutionizing Islamic inheritance planning through innovative technology
        </p>
    </div>
</header>

<main class="main-content" id="learn-more" style="max-width: 1200px; margin: 0 auto; padding: 0 2rem;">
    <!-- Hero Introduction -->
    <div class="card" style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); border-radius: var(--border-radius-lg); padding: 4rem 3rem; margin: 3rem 0; position: relative; overflow: hidden; color: white;">
        <div style="position: absolute; top: 0; right: 0; width: 250px; height: 250px; background: rgba(255,255,255,0.1); border-radius: 50%; transform: translate(30%, -30%);"></div>
        <div style="position: absolute; bottom: 0; left: 0; width: 200px; height: 200px; background: rgba(255,255,255,0.05); border-radius: 50%; transform: translate(-30%, 30%);"></div>
        
        <div style="position: relative; z-index: 2; max-width: 800px; margin: 0 auto;">
            <div style="display: flex; align-items: center; gap: 2rem; margin-bottom: 2rem;">
                <div style="background: rgba(255,255,255,0.2); width: 80px; height: 80px; border-radius: var(--border-radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0; backdrop-filter: blur(10px); border: 2px solid rgba(255,255,255,0.3);">
                    <svg style="width: 36px; height: 36px; color: var(--accent-color);" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <h2 style="font-size: 2.2rem; margin: 0 0 0.5rem 0; font-weight: 700; color: white;">Welcome to Neo Faraid</h2>
                    <p style="font-size: 1.2rem; opacity: 0.9; margin: 0; color: white;">Where Tradition Meets Technology</p>
                </div>
            </div>
            
            <p style="font-size: 1.15rem; line-height: 1.8; margin-bottom: 2rem; opacity: 0.95; color: white;">
                Neo Faraid is a groundbreaking digital platform that bridges the gap between traditional Islamic inheritance principles and modern technology. We're dedicated to making Faraid calculations accessible, accurate, and understandable for everyone—from individuals and families to students and professionals.
            </p>
            
            <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap;">
                @foreach(['100% Shariah-Compliant', 'Expert-Verified Calculations', 'User-Friendly Interface'] as $item)
                <div style="display: flex; align-items: center; gap: 0.5rem; color: white;">
                    <svg style="width: 20px; height: 20px; color: var(--accent-color);" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $item }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Our Story -->
    <div class="card" style="background: white; border-radius: var(--border-radius-lg); padding: 3rem; margin: 4rem 0; position: relative; overflow: hidden; box-shadow: var(--shadow-md);">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));"></div>
        
        <div class="grid cols-2" style="display: grid; grid-template-columns: 1fr 2fr; gap: 3rem; align-items: center;">
            <div>
                <div style="background: linear-gradient(135deg, var(--light-bg) 0%, var(--light-border) 100%); padding: 2rem; border-radius: var(--border-radius-md); text-align: center;">
                    <div style="font-size: 4rem; color: var(--primary-color); margin-bottom: 1rem;">📜</div>
                    <h3 style="color: var(--primary-color); margin: 0 0 0.5rem 0; font-size: 1.5rem; font-weight: 600;">Our Journey</h3>
                    <p style="color: var(--text-light); margin: 0; font-size: 0.95rem;">Established 2023</p>
                </div>
            </div>
            
            <div>
                <h2 style="border: none; padding-bottom: 0; margin-bottom: 1.5rem; color: var(--primary-color); display: flex; align-items: center; gap: 0.75rem; font-size: 1.8rem; font-weight: 600;">
                    <svg style="width: 28px; height: 28px;" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                    </svg>
                    Our Story
                </h2>
                
                <p style="font-size: 1.1rem; line-height: 1.8; color: var(--text-primary); margin-bottom: 1.5rem;">
                    Neo Faraid was born from a simple yet powerful observation: while Islamic inheritance laws are precise and comprehensive, they can be complex and intimidating for many people. We saw families struggling with manual calculations, students grappling with theoretical concepts, and professionals spending hours on intricate inheritance cases.
                </p>
                
                <p style="font-size: 1.1rem; line-height: 1.8; color: var(--text-primary);">
                    Our mission became clear: to create a digital platform that combines the accuracy of traditional Islamic scholarship with the convenience of modern technology. Today, we're proud to serve thousands of users worldwide, helping them navigate inheritance planning with confidence and clarity.
                </p>
                
                <div style="display: flex; gap: 1.5rem; margin-top: 2rem;">
                    @foreach(['10K+', '99.8%', '24/7'] as $stat)
                    <div style="text-align: center; flex: 1;">
                        <div style="font-size: 2.5rem; font-weight: 700; color: var(--primary-color); margin-bottom: 0.5rem;">{{ $stat }}</div>
                        <div style="color: var(--text-light); font-size: 0.9rem;">
                            @if($stat == '10K+') Users Worldwide
                            @elseif($stat == '99.8%') Accuracy Rate
                            @else Available
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Vision & Mission Grid -->
    <div class="grid cols-2" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 2rem; margin: 4rem 0;">
        <!-- Vision Card -->
        <div style="position: relative;">
            <div class="card" style="background: white; border-radius: var(--border-radius-lg); padding: 2.5rem; height: 100%; position: relative; overflow: hidden; box-shadow: var(--shadow-md); transition: var(--transition);">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));"></div>
                
                <div style="display: flex; align-items: center; margin-bottom: 2rem;">
                    <div style="background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%); width: 60px; height: 60px; border-radius: var(--border-radius-md); display: flex; align-items: center; justify-content: center; margin-right: 1.5rem; flex-shrink: 0;">
                        <svg style="width: 28px; height: 28px; color: var(--primary-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </div>
                    <h2 style="border: none; padding-bottom: 0; margin: 0; color: var(--primary-color); font-size: 1.5rem; font-weight: 600;">Our Vision</h2>
                </div>
                
                <div style="background: linear-gradient(135deg, rgba(255, 215, 0, 0.05) 0%, rgba(255, 237, 78, 0.05) 100%); padding: 1.5rem; border-radius: var(--border-radius-sm); border-left: 4px solid var(--accent-color);">
                    <p style="font-size: 1.2rem; line-height: 1.6; color: var(--text-primary); margin: 0; font-weight: 500;">
                        To become the world's most trusted digital platform for Islamic inheritance planning, empowering communities through accessible, accurate, and modern Faraid solutions.
                    </p>
                </div>
                
                <div style="margin-top: 2rem;">
                    <h4 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 1.1rem; font-weight: 600;">Key Focus Areas:</h4>
                    <div style="display: grid; gap: 1rem;">
                        @foreach(['Global accessibility for Muslim communities worldwide', 'Educational empowerment through technology', 'Digital transformation of Islamic financial planning'] as $item)
                        <div style="display: flex; align-items: flex-start; gap: 1rem; padding: 1rem; background: linear-gradient(135deg, rgba(26, 95, 180, 0.03) 0%, rgba(45, 122, 214, 0.03) 100%); border-radius: var(--border-radius-sm);">
                            <div style="background: var(--primary-color); width: 24px; height: 24px; border-radius: var(--border-radius-sm); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg style="width: 12px; height: 12px; color: white;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span style="color: var(--text-primary); line-height: 1.5; font-size: 0.95rem;">{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mission Card -->
        <div style="position: relative;">
            <div class="card" style="background: white; border-radius: var(--border-radius-lg); padding: 2.5rem; height: 100%; position: relative; overflow: hidden; box-shadow: var(--shadow-md); transition: var(--transition);">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));"></div>
                
                <div style="display: flex; align-items: center; margin-bottom: 2rem;">
                    <div style="background: linear-gradient(135deg, var(--accent-color) 0%, var(--accent-light) 100%); width: 60px; height: 60px; border-radius: var(--border-radius-md); display: flex; align-items: center; justify-content: center; margin-right: 1.5rem; flex-shrink: 0;">
                        <svg style="width: 28px; height: 28px; color: var(--primary-color);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h2 style="border: none; padding-bottom: 0; margin: 0; color: var(--primary-color); font-size: 1.5rem; font-weight: 600;">Our Mission</h2>
                </div>
                
                <div style="background: linear-gradient(135deg, rgba(255, 215, 0, 0.05) 0%, rgba(255, 237, 78, 0.05) 100%); padding: 1.5rem; border-radius: var(--border-radius-sm); border-left: 4px solid var(--accent-color);">
                    <p style="font-size: 1.2rem; line-height: 1.6; color: var(--text-primary); margin: 0; font-weight: 500;">
                        To simplify Islamic inheritance for everyone by combining authentic scholarship with innovative technology, ensuring accuracy, accessibility, and understanding.
                    </p>
                </div>
                
                <div style="margin-top: 2rem;">
                    <h4 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 1.1rem; font-weight: 600;">Our Commitments:</h4>
                    <div style="display: grid; gap: 1rem;">
                        @foreach(['Provide accurate, Shariah-compliant calculations', 'Make complex inheritance rules accessible to all', 'Support education and informed decision-making'] as $item)
                        <div style="display: flex; align-items: flex-start; gap: 1rem; padding: 1rem; background: linear-gradient(135deg, rgba(26, 95, 180, 0.03) 0%, rgba(45, 122, 214, 0.03) 100%); border-radius: var(--border-radius-sm);">
                            <div style="background: var(--primary-color); width: 24px; height: 24px; border-radius: var(--border-radius-sm); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <svg style="width: 12px; height: 12px; color: white;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <span style="color: var(--text-primary); line-height: 1.5; font-size: 0.95rem;">{{ $item }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Core Values -->
    <div style="margin: 4rem 0;">
        <h2 style="text-align: center; margin-bottom: 3rem; color: var(--primary-color); font-size: 1.8rem; font-weight: 600;">
            <span style="display: inline-flex; align-items: center; gap: 0.75rem;">
                <svg style="width: 28px; height: 28px;" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Our Core Values
            </span>
        </h2>
        
        <div class="grid cols-3" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
            @foreach([
                ['title' => 'Accuracy First', 'icon' => 'M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z', 'desc' => 'Every calculation is meticulously verified against authentic Islamic sources and scholarly consensus to ensure complete Shariah compliance.'],
                ['title' => 'Simplicity & Clarity', 'icon' => 'M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z', 'desc' => 'We transform complex inheritance rules into intuitive, easy-to-understand results with clear explanations and visual representations.'],
                ['title' => 'Trust & Integrity', 'icon' => 'M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z', 'desc' => 'We operate with complete transparency, maintaining the highest standards of data security and ethical conduct in all our operations.']
            ] as $value)
            <div class="value-card" style="background: white; border-radius: var(--border-radius-lg); padding: 2.5rem; text-align: center; box-shadow: var(--shadow-md); transition: var(--transition); position: relative; overflow: hidden;">
                <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));"></div>
                <div style="background: linear-gradient(135deg, rgba(26, 95, 180, 0.1) 0%, rgba(45, 122, 214, 0.1) 100%); width: 80px; height: 80px; border-radius: var(--border-radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                    <svg style="width: 36px; height: 36px; color: var(--primary-color);" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="{{ $value['icon'] }}" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 style="color: var(--primary-color); margin: 0 0 1rem 0; font-size: 1.4rem; font-weight: 600;">{{ $value['title'] }}</h3>
                <p style="color: var(--text-primary); line-height: 1.6; margin: 0; font-size: 0.95rem;">
                    {{ $value['desc'] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Technology Section -->
    <div class="card" style="background: linear-gradient(135deg, var(--light-bg) 0%, var(--light-border) 100%); border-radius: var(--border-radius-lg); padding: 3rem; margin: 4rem 0; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));"></div>
        
        <div style="display: flex; align-items: center; gap: 2rem; margin-bottom: 3rem;">
            <div style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); width: 60px; height: 60px; border-radius: var(--border-radius-md); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <svg style="width: 28px; height: 28px; color: white;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                </svg>
            </div>
            <div>
                <h2 style="border: none; padding-bottom: 0; margin: 0; color: var(--primary-color); font-size: 1.8rem; font-weight: 600;">Our Technology</h2>
                <p style="color: var(--text-light); margin: 0.5rem 0 0 0; font-size: 1rem;">Innovative solutions built on solid foundations</p>
            </div>
        </div>
        
        <div class="grid cols-3" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
            @foreach([
                ['title' => 'Advanced Algorithms', 'icon' => 'M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z', 'desc' => 'Proprietary algorithms that accurately handle complex inheritance scenarios and edge cases.'],
                ['title' => 'Visual Analytics', 'icon' => 'M12 1.586l-4 4v12.828l4-4V1.586zM3.707 3.293A1 1 0 002 4v10a1 1 0 00.293.707L6 18.414V5.586L3.707 3.293zM17.707 5.293L14 1.586v12.828l2.293 2.293A1 1 0 0018 16V6a1 1 0 00-.293-.707z', 'desc' => 'Interactive family trees and graphical representations for better understanding of inheritance distribution.'],
                ['title' => 'Bank-Level Security', 'icon' => 'M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z', 'desc' => 'Enterprise-grade encryption and security protocols to protect your sensitive financial information.']
            ] as $tech)
            <div style="background: white; padding: 2rem; border-radius: var(--border-radius-md); box-shadow: var(--shadow-sm);">
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); width: 40px; height: 40px; border-radius: var(--border-radius-sm); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <svg style="width: 20px; height: 20px; color: white;" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="{{ $tech['icon'] }}" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h4 style="color: var(--primary-color); margin: 0; font-size: 1.1rem; font-weight: 600;">{{ $tech['title'] }}</h4>
                </div>
                <p style="color: var(--text-primary); margin: 0; font-size: 0.9rem; line-height: 1.6;">
                    {{ $tech['desc'] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Team/Advisors Section -->
    <div class="card" style="background: white; border-radius: var(--border-radius-lg); padding: 3rem; margin: 4rem 0; position: relative; overflow: hidden; box-shadow: var(--shadow-md);">
        <div style="position: absolute; top: 0; left: 0; width: 100%; height: 4px; background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));"></div>
        
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2 style="border: none; padding-bottom: 0; margin-bottom: 1rem; color: var(--primary-color); font-size: 1.8rem; font-weight: 600;">
                <span style="display: inline-flex; align-items: center; gap: 0.75rem;">
                    Scholarly Advisors
                </span>
            </h2>
            <p style="color: var(--text-light); max-width: 600px; margin: 0 auto; font-size: 1.1rem; line-height: 1.6;">
                Our work is guided by respected Islamic scholars and financial experts to ensure authenticity and accuracy.
            </p>
        </div>
        
        <div class="grid cols-3" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem;">
            @foreach([
                ['initial' => 'S', 'title' => 'Shariah Compliance', 'desc' => 'Verified by certified Islamic scholars'],
                ['initial' => 'T', 'title' => 'Technical Excellence', 'desc' => 'Developed by technology experts'],
                ['initial' => 'E', 'title' => 'Educational Support', 'desc' => 'Guided by academic advisors']
            ] as $advisor)
            <div style="text-align: center; padding: 2rem; background: linear-gradient(135deg, var(--light-bg) 0%, var(--light-border) 100%); border-radius: var(--border-radius-md);">
                <div style="width: 80px; height: 80px; background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); border-radius: 50%; margin: 0 auto 1.5rem; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: 600;">
                    {{ $advisor['initial'] }}
                </div>
                <h4 style="color: var(--primary-color); margin: 0 0 0.5rem 0; font-size: 1.1rem; font-weight: 600;">{{ $advisor['title'] }}</h4>
                <p style="color: var(--text-primary); margin: 0; font-size: 0.9rem;">{{ $advisor['desc'] }}</p>
            </div>
            @endforeach
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

/* ===== BUTTONS ===== */
.btn {
    padding: 1rem 2.5rem;
    font-size: 1.1rem;
    border-radius: var(--border-radius-xl);
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
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
    box-shadow: 0 10px 25px rgba(255, 215, 0, 0.3);
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

.btn-secondary:hover .btn-icon {
    transform: translateY(2px);
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

/* Card hover effects */
.card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg) !important;
    transition: var(--transition);
}

.value-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg) !important;
}

.btn:hover {
    transform: translateY(-2px);
}

.btn-primary:hover {
    box-shadow: 0 15px 35px rgba(255, 215, 0, 0.4) !important;
}

.btn-secondary:hover {
    box-shadow: 0 5px 15px rgba(255, 255, 255, 0.2) !important;
}

/* Responsive styles */
@media (max-width: 1200px) {
    .grid.cols-3 {
        grid-template-columns: repeat(2, 1fr) !important;
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
    
    .hero-actions {
        flex-direction: column;
        align-items: stretch;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
    
    .grid.cols-3,
    .grid.cols-2 {
        grid-template-columns: 1fr !important;
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
    
    .grid.cols-3,
    .grid.cols-2 {
        grid-template-columns: 1fr !important;
    }
    
    .card {
        padding: 1.25rem !important;
    }
}

/* Smooth scrolling for anchor links */
html {
    scroll-behavior: smooth;
    scroll-padding-top: 5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to value cards
    const valueCards = document.querySelectorAll('.value-card');
    
    valueCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
    
    // Add animation to cards on scroll
    const cards = document.querySelectorAll('.card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1
    });
    
    cards.forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(card);
    });
    
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

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            }
        });
    });
});
</script>
@endsection