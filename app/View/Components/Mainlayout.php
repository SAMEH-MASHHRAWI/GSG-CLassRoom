<?php

namespace App\View\Components;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Mainlayout extends Component
{
    public $title;
    /**
     * Create a new component instance.
     */
    public function __construct($title,$class='')
    {
        $this->title=$title;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('layouts.main');
    }
}
