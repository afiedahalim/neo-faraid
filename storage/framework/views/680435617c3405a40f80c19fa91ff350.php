<?php if($type === 'animated'): ?>
<div class="fixed inset-0 -z-50 overflow-hidden pointer-events-none">
    <!-- Animated gradient background -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-blue-100 to-indigo-50"></div>
    
    <!-- Animated blobs -->
    <div class="absolute inset-0">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob"></div>
        <div class="absolute top-1/3 right-1/4 w-64 h-64 bg-purple-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-2000"></div>
        <div class="absolute bottom-1/4 left-1/2 w-64 h-64 bg-pink-200 rounded-full mix-blend-multiply filter blur-xl opacity-30 animate-blob animation-delay-4000"></div>
    </div>
    
    <!-- Enhanced Grid Background -->
    <div class="absolute inset-0">
        <!-- Main grid lines -->
        <div class="absolute inset-0 opacity-10"
             style="background-image: 
                 linear-gradient(to right, #1a5fb4 1px, transparent 1px),
                 linear-gradient(to bottom, #1a5fb4 1px, transparent 1px);
                 background-size: 60px 60px;
                 background-position: -1px -1px;"></div>
        
        <!-- Subtle secondary grid (larger) -->
        <div class="absolute inset-0 opacity-5"
             style="background-image: 
                 linear-gradient(to right, #1a5fb4 2px, transparent 2px),
                 linear-gradient(to bottom, #1a5fb4 2px, transparent 2px);
                 background-size: 240px 240px;
                 background-position: -2px -2px;"></div>
        
        <!-- Grid dots at intersections -->
        <div class="absolute inset-0 opacity-15"
             style="background-image: 
                 radial-gradient(circle 2px at 60px 60px, #1a5fb4 2px, transparent 2px);
                 background-size: 60px 60px;"></div>
    </div>
</div>

<style>
    @keyframes blob {
        0% {
            transform: translate(0px, 0px) scale(1);
        }
        33% {
            transform: translate(30px, -50px) scale(1.1);
        }
        66% {
            transform: translate(-20px, 20px) scale(0.9);
        }
        100% {
            transform: translate(0px, 0px) scale(1);
        }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
</style>

<?php elseif($type === 'islamic'): ?>
<div class="fixed inset-0 -z-50 overflow-hidden pointer-events-none">
    <!-- Islamic gradient background -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900"></div>
    
    <!-- Islamic Geometric Grid Pattern -->
    <div class="absolute inset-0">
        <!-- Primary Islamic geometric grid -->
        <div class="absolute inset-0 opacity-20"
             style="background-image: 
                 linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                 linear-gradient(0deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                 linear-gradient(45deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                 linear-gradient(-45deg, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
                 background-size: 80px 80px;
                 background-position: -1px -1px;"></div>
        
        <!-- Star pattern overlay -->
        <div class="absolute inset-0 opacity-15"
             style="background-image: 
                 radial-gradient(circle at 40px 40px, rgba(255, 215, 0, 0.3) 3px, transparent 3px),
                 radial-gradient(circle at 120px 40px, rgba(255, 215, 0, 0.3) 3px, transparent 3px),
                 radial-gradient(circle at 40px 120px, rgba(255, 215, 0, 0.3) 3px, transparent 3px),
                 radial-gradient(circle at 120px 120px, rgba(255, 215, 0, 0.3) 3px, transparent 3px);
                 background-size: 160px 160px;"></div>
        
        <!-- Octagonal pattern -->
        <div class="absolute inset-0 opacity-10"
             style="background-image: 
                 url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><polygon points="50,0 93.3,25 93.3,75 50,100 6.7,75 6.7,25" fill="none" stroke="rgba(255,215,0,0.2)" stroke-width="0.5"/></svg>');
                 background-size: 120px 120px;"></div>
    </div>
    
    <!-- Gold accent stars -->
    <div class="absolute inset-0">
        <?php for($i = 0; $i < 12; $i++): ?>
            <div class="absolute w-1 h-1 bg-yellow-300 rounded-full opacity-30"
                 style="
                     top: <?php echo e(rand(5, 95)); ?>%;
                     left: <?php echo e(rand(5, 95)); ?>%;
                     animation: twinkle <?php echo e(rand(3, 8)); ?>s ease-in-out infinite;
                     animation-delay: <?php echo e($i * 0.3); ?>s;
                     box-shadow: 0 0 10px 2px rgba(255, 215, 0, 0.5);
                 "></div>
        <?php endfor; ?>
        
        <!-- Larger golden dots at grid intersections -->
        <?php for($i = 0; $i < 5; $i++): ?>
            <div class="absolute w-3 h-3 bg-gradient-to-br from-yellow-300 to-yellow-500 rounded-full opacity-20"
                 style="
                     top: <?php echo e(($i + 1) * 20); ?>%;
                     left: <?php echo e(($i * 15 + 10)); ?>%;
                     animation: pulse <?php echo e(rand(4, 7)); ?>s ease-in-out infinite;
                     animation-delay: <?php echo e($i * 1); ?>s;
                 "></div>
        <?php endfor; ?>
    </div>
</div>

<style>
    @keyframes twinkle {
        0%, 100% { opacity: 0.1; transform: scale(1); box-shadow: 0 0 5px 1px rgba(255, 215, 0, 0.3); }
        50% { opacity: 0.8; transform: scale(1.3); box-shadow: 0 0 15px 4px rgba(255, 215, 0, 0.7); }
    }
    @keyframes pulse {
        0%, 100% { opacity: 0.1; transform: scale(1); }
        50% { opacity: 0.4; transform: scale(1.2); }
    }
</style>

<?php elseif($type === 'grid'): ?>
<!-- Pure Grid Background -->
<div class="fixed inset-0 -z-50 overflow-hidden pointer-events-none">
    <!-- Light gradient base -->
    <div class="absolute inset-0 bg-gradient-to-br from-gray-50 to-gray-100"></div>
    
    <!-- Detailed Grid System -->
    <div class="absolute inset-0">
        <!-- Very fine grid (thin lines) -->
        <div class="absolute inset-0 opacity-10"
             style="background-image: 
                 linear-gradient(90deg, rgba(0, 0, 0, 0.05) 0.5px, transparent 0.5px),
                 linear-gradient(0deg, rgba(0, 0, 0, 0.05) 0.5px, transparent 0.5px);
                 background-size: 20px 20px;"></div>
        
        <!-- Main grid (medium lines) -->
        <div class="absolute inset-0 opacity-15"
             style="background-image: 
                 linear-gradient(90deg, rgba(0, 0, 0, 0.1) 1px, transparent 1px),
                 linear-gradient(0deg, rgba(0, 0, 0, 0.1) 1px, transparent 1px);
                 background-size: 100px 100px;"></div>
        
        <!-- Large grid (thick lines) -->
        <div class="absolute inset-0 opacity-20"
             style="background-image: 
                 linear-gradient(90deg, rgba(59, 130, 246, 0.2) 2px, transparent 2px),
                 linear-gradient(0deg, rgba(59, 130, 246, 0.2) 2px, transparent 2px);
                 background-size: 200px 200px;"></div>
        
        <!-- Intersection dots -->
        <div class="absolute inset-0 opacity-30"
             style="background-image: 
                 radial-gradient(circle 3px at 100px 100px, rgba(59, 130, 246, 0.3) 3px, transparent 3px);
                 background-size: 100px 100px;"></div>
    </div>
    
    <!-- Animated grid highlights -->
    <div class="absolute inset-0 opacity-40">
        <?php for($i = 0; $i < 6; $i++): ?>
            <div class="absolute h-full w-1 bg-blue-400 opacity-0 animate-highlight"
                 style="
                     left: <?php echo e(($i + 1) * 16.666); ?>%;
                     animation-delay: <?php echo e($i * 0.5); ?>s;
                 "></div>
            <div class="absolute w-full h-1 bg-blue-400 opacity-0 animate-highlight-vertical"
                 style="
                     top: <?php echo e(($i + 1) * 16.666); ?>%;
                     animation-delay: <?php echo e($i * 0.5 + 0.2); ?>s;
                 "></div>
        <?php endfor; ?>
    </div>
</div>

<style>
    @keyframes highlight {
        0%, 100% { opacity: 0; transform: scaleY(1); }
        50% { opacity: 0.4; transform: scaleY(1.1); }
    }
    @keyframes highlight-vertical {
        0%, 100% { opacity: 0; transform: scaleX(1); }
        50% { opacity: 0.4; transform: scaleX(1.1); }
    }
    .animate-highlight {
        animation: highlight 8s ease-in-out infinite;
    }
    .animate-highlight-vertical {
        animation: highlight-vertical 8s ease-in-out infinite;
    }
</style>

<?php else: ?>
<!-- Default with Enhanced Grid Background -->
<div class="fixed inset-0 -z-50 overflow-hidden pointer-events-none">
    <!-- Gradient base -->
    <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-100"></div>
    
    <!-- Default grid -->
    <div class="absolute inset-0 opacity-10"
         style="background-image: 
             linear-gradient(to right, rgba(0, 0, 0, 0.1) 1px, transparent 1px),
             linear-gradient(to bottom, rgba(0, 0, 0, 0.1) 1px, transparent 1px);
             background-size: 40px 40px;"></div>
    
    <!-- Subtle diagonal pattern -->
    <div class="absolute inset-0 opacity-5"
         style="background-image: 
             linear-gradient(45deg, rgba(0, 0, 0, 0.05) 25%, transparent 25%, transparent 75%, rgba(0, 0, 0, 0.05) 75%, rgba(0, 0, 0, 0.05)),
             linear-gradient(45deg, rgba(0, 0, 0, 0.05) 25%, transparent 25%, transparent 75%, rgba(0, 0, 0, 0.05) 75%, rgba(0, 0, 0, 0.05));
             background-size: 20px 20px;
             background-position: 0 0, 10px 10px;"></div>
</div>
<?php endif; ?><?php /**PATH C:\laragon\www\neo-faraid\resources\views/components/simple-background.blade.php ENDPATH**/ ?>