@if($type === 'animated')
<div class="fixed inset-0 -z-50 overflow-hidden pointer-events-none" id="animated-background">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-blue-100 to-indigo-50"></div>
    
    <!-- Particles will be added by JavaScript -->
    <div class="particles-container absolute inset-0"></div>
    
    <div class="absolute inset-0 opacity-5" 
         style="background-image: linear-gradient(#1a5fb4 1px, transparent 1px), linear-gradient(to right, #1a5fb4 1px, transparent 1px); background-size: 50px 50px;"></div>
</div>

<script>
    if (!window.backgroundInitialized) {
        window.backgroundInitialized = true;
        
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.particles-container');
            if (!container) return;
            
            // Create 20 particles
            for (let i = 0; i < 20; i++) {
                const particle = document.createElement('div');
                particle.className = 'absolute rounded-full bg-blue-300 opacity-20';
                particle.style.width = (Math.random() * 4 + 2) + 'px';
                particle.style.height = particle.style.width;
                particle.style.left = Math.random() * 100 + '%';
                particle.style.top = Math.random() * 100 + '%';
                
                // Animation
                particle.style.animation = `particleFloat ${Math.random() * 15 + 10}s linear infinite`;
                particle.style.animationDelay = `-${Math.random() * 5}s`;
                
                container.appendChild(particle);
            }
        });
        
        // Add CSS animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes particleFloat {
                0% { transform: translate(0, 0) rotate(0deg); }
                25% { transform: translate(15px, 15px) rotate(90deg); }
                50% { transform: translate(-10px, 5px) rotate(180deg); }
                75% { transform: translate(5px, -15px) rotate(270deg); }
                100% { transform: translate(0, 0) rotate(360deg); }
            }
        `;
        document.head.appendChild(style);
    }
</script>

@elseif($type === 'islamic')
<div class="fixed inset-0 -z-50 overflow-hidden pointer-events-none">
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900"></div>
    <div class="absolute inset-0 opacity-10" 
         style="background-image: radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 1px, transparent 1px); background-size: 100px 100px;"></div>
</div>

@else
<!-- Default -->
<div class="fixed inset-0 -z-50 bg-gradient-to-br from-blue-50 to-indigo-100 pointer-events-none"></div>
@endif