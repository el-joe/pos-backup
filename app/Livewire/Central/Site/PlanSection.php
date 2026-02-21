<?php

namespace App\Livewire\Central\Site;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Plan;
use Livewire\Component;

class PlanSection extends Component
{
    public $yearly = false; // false for monthly, true for yearly
    public $period = 'month';

    public $country_id;
    public $currency_id;

    function mount()
    {
        $countryCode = strtoupper(session('country'));
        $currencyCode = strtoupper(session('country'));

        $this->country_id = Country::where('code', $countryCode)->first()?->id;
        $this->currency_id = Currency::where('country_code', $currencyCode)->first()?->id;
    }

    function updatingYearly($value)
    {
        $this->period = $value ? 'year' : 'month';
    }

    public function render()
    {
        $plans = Plan::where('active', true)
            ->with('planFeatures.feature')
            ->orderBy('price_month')->get();

        $currentCurrency = Currency::find($this->currency_id ?? null);

        return view('livewire.central.'. defaultLandingLayout() .'.plan-section',get_defined_vars());
    }
}
