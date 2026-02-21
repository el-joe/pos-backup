<?php

namespace App\Livewire\Admin\Plans;

use App\Models\Plan;
use Livewire\Component;

class PlansList extends Component
{
    public $yearly = false; // false for monthly, true for yearly
    public $period = 'month';

    function updatingYearly($value)
    {
        $this->period = $value ? 'year' : 'month';
    }
    public function render()
    {
        $plans = Plan::where('active', true)
        ->orderBy('price_month')->get();
        return layoutView('plans.plans-list', get_defined_vars());
    }
}
