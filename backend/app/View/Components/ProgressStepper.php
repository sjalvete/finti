<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProgressStepper extends Component
{
    public int $step;

    public function __construct(int $step = 0)
    {
        $this->step = max(0, min(3, $step));
    }

    public function render(): View|Closure|string
    {
        return view('components.progress-stepper');
    }
}
