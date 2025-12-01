<?php

namespace App\Livewire\Central\Site;

use App\Models\Plan;
use Livewire\Component;

class PlanSection extends Component
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

        return view('livewire.central.site.plan-section',get_defined_vars());
    }
}
