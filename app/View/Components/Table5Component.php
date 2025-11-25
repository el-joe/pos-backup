<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Table5Component extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $columns, public $headers, public $rows = [],public $totals = null) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.table5-component');
    }
}
