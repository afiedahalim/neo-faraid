<?php

namespace App\View\Components;

use Illuminate\View\Component;

class SimpleBackground extends Component
{
    public $type;
    
    /**
     * Create a new component instance.
     */
    public function __construct($type = 'default')
    {
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.simple-background');
    }
}