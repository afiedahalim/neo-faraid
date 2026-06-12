<?php

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