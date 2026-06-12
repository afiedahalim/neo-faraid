@props(['type', 'intensity', 'color', 'zIndex'])

@php
    $component = new \App\View\Components\AnimatedBackground($type, $intensity, $color, $zIndex);
    $colors = $component->colorClasses();
    $intensityValues = $component->intensityValues();
@endphp

<div 
    {{ $attributes->merge(['class' => "animated-background animated-background-{$type}"]) }}
    data-type="{{ $type }}"
    data-intensity="{{ $intensity }}"
    data-color-scheme="{{ $color }}"
    style="z-index: {{ $zIndex }};"
>
    @switch($type)
        @case('particles')
            <div class="particles-container absolute inset-0 overflow-hidden">
                @for($i = 0; $i < $intensityValues['particles']; $i++)
                    <div class="particle absolute rounded-full bg-gradient-to-r {{ $colors['primary'] }} opacity-20 animate-float"></div>
                @endfor
            </div>
            @break

        @case('gradient')
            <div class="gradient-background absolute inset-0">
                <div class="absolute inset-0 bg-gradient-to-br {{ $colors['primary'] }} opacity-70 animate-gradient-shift"></div>
                <div class="absolute inset-0 bg-gradient-to-tr {{ $colors['secondary'] }} opacity-50 animate-gradient-shift-reverse"></div>
                <div class="absolute inset-0 bg-gradient-to-bl {{ $colors['accent'] }} opacity-30 animate-gradient-shift-delayed"></div>
            </div>
            @break

        @case('grid')
            <div class="grid-background absolute inset-0">
                <div class="absolute inset-0 bg-grid-pattern opacity-{{ $intensity === 'high' ? '20' : ($intensity === 'medium' ? '10' : '5') }}"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent animate-shimmer"></div>
            </div>
            @break

        @case('waves')
            <div class="waves-container absolute inset-0 overflow-hidden">
                <div class="wave wave-1 absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-r {{ $colors['primary'] }} opacity-30 animate-wave"></div>
                <div class="wave wave-2 absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-r {{ $colors['secondary'] }} opacity-25 animate-wave-delayed"></div>
                <div class="wave wave-3 absolute bottom-0 left-0 right-0 h-12 bg-gradient-to-r {{ $colors['accent'] }} opacity-20 animate-wave-slow"></div>
            </div>
            @break

        @case('geometric')
            <div class="geometric-pattern absolute inset-0">
                <div class="shape shape-1 absolute top-10 left-10 w-32 h-32 bg-gradient-to-br {{ $colors['primary'] }} opacity-20 rounded-3xl rotate-45 animate-pulse-slow"></div>
                <div class="shape shape-2 absolute top-1/4 right-10 w-24 h-24 bg-gradient-to-tr {{ $colors['secondary'] }} opacity-25 rounded-2xl -rotate-12 animate-pulse"></div>
                <div class="shape shape-3 absolute bottom-10 left-1/4 w-20 h-20 bg-gradient-to-tl {{ $colors['accent'] }} opacity-30 rounded-full animate-bounce-slow"></div>
                <div class="shape shape-4 absolute bottom-1/3 right-1/4 w-16 h-16 bg-gradient-to-br {{ $colors['primary'] }} opacity-15 rounded-lg rotate-12 animate-spin-slow"></div>
            </div>
            @break

        @case('islamic')
            <div class="islamic-background absolute inset-0">
                <div class="islamic-pattern absolute inset-0 opacity-{{ $intensity === 'high' ? '30' : ($intensity === 'medium' ? '20' : '10' ) }}"></div>
                <div class="islamic-geometric absolute inset-0 opacity-{{ $intensity === 'high' ? '15' : ($intensity === 'medium' ? '10' : '5' ) }}"></div>
                <div class="arabic-calligraphy absolute inset-0 flex items-center justify-center opacity-5">
                    <div class="text-6xl md:text-8xl font-arabic text-white">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيمِ</div>
                </div>
            </div>
            @break

        @default
            <div class="default-background absolute inset-0 bg-gradient-to-br {{ $colors['primary'] }} opacity-20"></div>
    @endswitch
</div>

<style>
    .animated-background {
        position: fixed;
        inset: 0;
        pointer-events: none;
        user-select: none;
    }

    /* Particle Animation */
    .particle {
        animation-duration: calc(var(--duration, 20s) * {{ $intensityValues['speed'] === 'fast' ? 0.5 : ($intensityValues['speed'] === 'slow' ? 2 : 1) }});
        animation-delay: calc(var(--delay, 0s) * {{ $i ?? 1 }});
        width: {{ rand(2, 8) }}px;
        height: {{ rand(2, 8) }}px;
        top: {{ rand(0, 100) }}%;
        left: {{ rand(0, 100) }}%;
        opacity: {{ rand(10, 30) / 100 }};
    }

    @keyframes float {
        0%, 100% {
            transform: translate(0, 0) rotate(0deg);
        }
        25% {
            transform: translate({{ rand(20, 50) }}px, {{ rand(20, 50) }}px) rotate(90deg);
        }
        50% {
            transform: translate({{ rand(-50, 50) }}px, {{ rand(-50, 50) }}px) rotate(180deg);
        }
        75% {
            transform: translate({{ rand(-20, -50) }}px, {{ rand(-20, -50) }}px) rotate(270deg);
        }
    }

    .animate-float {
        animation: float linear infinite;
    }

    /* Gradient Animation */
    @keyframes gradient-shift {
        0%, 100% {
            transform: translate(0, 0) scale(1);
        }
        50% {
            transform: translate({{ rand(1, 3) }}%, {{ rand(1, 3) }}%) scale(1.1);
        }
    }

    @keyframes gradient-shift-reverse {
        0%, 100% {
            transform: translate(0, 0) scale(1);
        }
        50% {
            transform: translate({{ rand(-3, -1) }}%, {{ rand(-3, -1) }}%) scale(0.9);
        }
    }

    @keyframes gradient-shift-delayed {
        0%, 100% {
            transform: translate({{ rand(-2, 2) }}%, {{ rand(-2, 2) }}%) scale(1);
        }
        50% {
            transform: translate({{ rand(-1, 1) }}%, {{ rand(-1, 1) }}%) scale(1.05);
        }
    }

    .animate-gradient-shift {
        animation: gradient-shift {{ $intensityValues['speed'] === 'fast' ? '15s' : ($intensityValues['speed'] === 'slow' ? '25s' : '20s') }} ease-in-out infinite;
    }

    .animate-gradient-shift-reverse {
        animation: gradient-shift-reverse {{ $intensityValues['speed'] === 'fast' ? '18s' : ($intensityValues['speed'] === 'slow' ? '28s' : '22s') }} ease-in-out infinite;
    }

    .animate-gradient-shift-delayed {
        animation: gradient-shift-delayed {{ $intensityValues['speed'] === 'fast' ? '20s' : ($intensityValues['speed'] === 'slow' ? '30s' : '25s') }} ease-in-out infinite;
    }

    /* Grid Pattern */
    .bg-grid-pattern {
        background-image: 
            linear-gradient(to right, rgba(255,255,255,0.1) 1px, transparent 1px),
            linear-gradient(to bottom, rgba(255,255,255,0.1) 1px, transparent 1px);
        background-size: 50px 50px;
    }

    @keyframes shimmer {
        0% {
            transform: translateX(-100%);
        }
        100% {
            transform: translateX(100%);
        }
    }

    .animate-shimmer {
        animation: shimmer {{ $intensityValues['speed'] === 'fast' ? '3s' : ($intensityValues['speed'] === 'slow' ? '6s' : '4s') }} linear infinite;
    }

    /* Wave Animation */
    @keyframes wave {
        0% {
            transform: translateX(0) scaleY(1);
        }
        50% {
            transform: translateX(-25%) scaleY(0.8);
        }
        100% {
            transform: translateX(-50%) scaleY(1);
        }
    }

    .animate-wave {
        animation: wave {{ $intensityValues['speed'] === 'fast' ? '10s' : ($intensityValues['speed'] === 'slow' ? '20s' : '15s') }} ease-in-out infinite;
    }

    .animate-wave-delayed {
        animation: wave {{ $intensityValues['speed'] === 'fast' ? '12s' : ($intensityValues['speed'] === 'slow' ? '22s' : '17s') }} ease-in-out infinite;
        animation-delay: 1s;
    }

    .animate-wave-slow {
        animation: wave {{ $intensityValues['speed'] === 'fast' ? '15s' : ($intensityValues['speed'] === 'slow' => '25s' : '20s') }} ease-in-out infinite;
        animation-delay: 2s;
    }

    /* Pulse Animations */
    @keyframes pulse-slow {
        0%, 100% {
            opacity: 0.2;
            transform: scale(1) rotate(45deg);
        }
        50% {
            opacity: 0.4;
            transform: scale(1.1) rotate(45deg);
        }
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 0.25;
            transform: scale(1) rotate(-12deg);
        }
        50% {
            opacity: 0.5;
            transform: scale(1.2) rotate(-12deg);
        }
    }

    .animate-pulse-slow {
        animation: pulse-slow {{ $intensityValues['speed'] === 'fast' ? '4s' : ($intensityValues['speed'] === 'slow' ? '8s' : '6s') }} ease-in-out infinite;
    }

    .animate-pulse {
        animation: pulse {{ $intensityValues['speed'] === 'fast' ? '3s' : ($intensityValues['speed'] === 'slow' ? '6s' : '4s') }} ease-in-out infinite;
        animation-delay: 0.5s;
    }

    /* Bounce Animation */
    @keyframes bounce-slow {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-20px);
        }
    }

    .animate-bounce-slow {
        animation: bounce-slow {{ $intensityValues['speed'] === 'fast' ? '5s' : ($intensityValues['speed'] === 'slow' ? '10s' : '7s') }} ease-in-out infinite;
    }

    /* Spin Animation */
    @keyframes spin-slow {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    .animate-spin-slow {
        animation: spin-slow {{ $intensityValues['speed'] === 'fast' ? '20s' : ($intensityValues['speed'] === 'slow' => '40s' : '30s') }} linear infinite;
    }

    /* Font for Arabic calligraphy */
    .font-arabic {
        font-family: 'Amiri', serif;
        font-weight: 700;
    }

    /* Islamic pattern styles */
    {!! $component->islamicPatternStyles() !!}
</style>