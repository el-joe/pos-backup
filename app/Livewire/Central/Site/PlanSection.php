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
        $countryCode = old('data.country_id') ?? strtoupper(session('country'));
        $currencyCode = old('data.currency_id') ?? strtoupper(session('country'));

        $this->country_id = Country::where((old('data.country_id') != null ? 'id' : 'code'), $countryCode)->first()?->id;
        $this->currency_id = Currency::where((old('data.currency_id') != null ? 'id' : 'country_code'), $currencyCode)->first()?->id;
    }

    function updatingYearly($value)
    {
        $this->period = $value ? 'year' : 'month';
    }

    public function render()
    {
        $plans = Plan::where('active', true)
            ->orderBy('price_month')->get();

        $currentCurrency = Currency::find($this->data['currency_id'] ?? null);

        return view('livewire.central.site.plan-section',get_defined_vars());
    }
}
