<?php

namespace App\Livewire\Central\Site;

use App\Models\Country;
use App\Models\Plan;
use App\Traits\LivewireOperations;
use Livewire\Component;

class CheckoutPage extends Component
{
    public $plan, $period, $slug;

    public $data = [
        'domain_mode'=>'subdomain',
    ];

    public $rules = [
        'data.company_name'=>'required|string|max:255|unique:tenants,id|regex:/^[a-zA-Z0-9_]+$/',
        'data.company_email'=>'required|email|max:255',
        'data.company_phone'=>'required|string|max:50',
        'data.country_id'=>'required|exists:countries,id',
        'data.tax_number'=>'nullable|string|max:100',
        'data.address'=>'nullable|string|max:500',
        'data.admin_name'=>'required|string|max:255',
        'data.admin_email'=>'required|email|max:255',
        'data.admin_phone'=>'nullable|string|max:50',
        'data.admin_password'=>'required|string|min:6',
    ];

    function mount()
    {
        $newPlanSlug = request()->query('plan');
        $data = decodedSlug($newPlanSlug);
        $this->period = $data['period'] ?? 'monthly';
        $this->slug = $slug = $data['slug'] ?? null;
        $this->plan = Plan::whereSlug($slug)->firstOrFail();
    }

    function completeSubscription()
    {
        $this->validate();
        $dataToString = encodedSlug($this->data);
        $this->js("window.location='". '/payment/callback/success?data=' . $dataToString ."'");
    }

    public function render()
    {
        $countries = Country::all();
        return view('livewire.central.site.checkout-page', get_defined_vars());
    }
}
