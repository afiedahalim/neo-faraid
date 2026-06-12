<?php

namespace App\View\Components;

use Illuminate\View\Component;

class AnimatedBackground extends Component
{
    public $type;
    public $intensity;
    public $color;
    public $zIndex;
    
    /**
     * Available background types
     */
    const TYPES = [
        'particles' => 'particles',
        'gradient' => 'gradient',
        'grid' => 'grid',
        'waves' => 'waves',
        'geometric' => 'geometric',
        'islamic' => 'islamic'
    ];
    
    /**
     * Available color schemes
     */
    const COLORS = [
        'blue' => 'blue',
        'gold' => 'gold',
        'green' => 'green',
        'purple' => 'purple',
        'monochrome' => 'monochrome'
    ];

    /**
     * Create a new component instance.
     *
     * @param string $type
     * @param string $intensity
     * @param string $color
     * @param string $zIndex
     * @return void
     */
    public function __construct(
        $type = 'particles',
        $intensity = 'medium',
        $color = 'blue',
        $zIndex = '-10'
    ) {
        $this->type = in_array($type, self::TYPES) ? $type : 'particles';
        $this->intensity = in_array($intensity, ['low', 'medium', 'high']) ? $intensity : 'medium';
        $this->color = in_array($color, self::COLORS) ? $color : 'blue';
        $this->zIndex = $zIndex;
    }

    /**
     * Get the color scheme classes
     */
    public function colorClasses()
    {
        $schemes = [
            'blue' => [
                'primary' => 'from-blue-400 to-indigo-600',
                'secondary' => 'from-cyan-400 to-blue-500',
                'accent' => 'from-violet-400 to-purple-500'
            ],
            'gold' => [
                'primary' => 'from-yellow-400 to-amber-600',
                'secondary' => 'from-orange-300 to-yellow-500',
                'accent' => 'from-amber-400 to-yellow-500'
            ],
            'green' => [
                'primary' => 'from-emerald-400 to-green-600',
                'secondary' => 'from-teal-300 to-emerald-500',
                'accent' => 'from-green-400 to-emerald-500'
            ],
            'purple' => [
                'primary' => 'from-purple-400 to-violet-600',
                'secondary' => 'from-fuchsia-300 to-purple-500',
                'accent' => 'from-violet-400 to-purple-500'
            ],
            'monochrome' => [
                'primary' => 'from-gray-300 to-gray-600',
                'secondary' => 'from-gray-200 to-gray-500',
                'accent' => 'from-gray-400 to-gray-500'
            ]
        ];

        return $schemes[$this->color] ?? $schemes['blue'];
    }

    /**
     * Get the intensity values
     */
    public function intensityValues()
    {
        $values = [
            'low' => ['particles' => 20, 'speed' => 'slow', 'opacity' => '0.1'],
            'medium' => ['particles' => 50, 'speed' => 'medium', 'opacity' => '0.2'],
            'high' => ['particles' => 100, 'speed' => 'fast', 'opacity' => '0.3']
        ];

        return $values[$this->intensity] ?? $values['medium'];
    }

    /**
     * Get Islamic pattern styles
     */
    public function islamicPatternStyles()
    {
        return '
        .islamic-pattern {
            background-image: 
                radial-gradient(circle at 25% 25%, rgba(255,255,255,0.1) 2px, transparent 2px),
                radial-gradient(circle at 75% 75%, rgba(255,255,255,0.1) 2px, transparent 2px);
            background-size: 60px 60px;
            background-position: 0 0, 30px 30px;
        }
        
        .islamic-geometric {
            background-image: 
                repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,0.05) 10px, rgba(255,255,255,0.05) 20px),
                repeating-linear-gradient(-45deg, transparent, transparent 10px, rgba(255,255,255,0.03) 10px, rgba(255,255,255,0.03) 20px);
        }
        ';
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.animated-background');
    }
}