<?php

namespace App\Livewire\Central\Site;

use App\Models\Country;
use App\Models\Currency;
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
        'data.company_name'=>'required|string|max:255|unique:tenants,id|regex:/^[a-zA-Z0-9_ ]+$/',
        'data.company_email'=>'required|email|max:255',
        'data.company_phone'=>'required|string|max:50',
        'data.domain_mode'=>'required|in:subdomain,domain',
        'data.final_domain'=>'required|string|max:255|unique:domains,domain',
        'data.country_id'=>'required|exists:countries,id',
        'data.currency_id'=>'required|exists:currencies,id',
        'data.tax_number'=>'nullable|string|max:100',
        'data.address'=>'nullable|string|max:500',
        'data.admin_name'=>'required|string|max:255',
        'data.admin_email'=>'required|email|max:255',
        'data.admin_phone'=>'nullable|string|max:50',
        'data.admin_password'=>'required|string|min:6',
    ];

    // function updateDomain()
    // {
    //     // Keep only characters that match /^[a-zA-Z0-9_]+$/
    //     $raw = $this->data['subdomain'] ?? '';
    //     $this->updatingDataSubdomain($raw);
    // }

    function updatingDataSubdomain($value){
        $clean = preg_replace('/[^a-z0-9_]/', '', $value);
        $clean = strtolower(trim($clean));
        $clean = substr($clean, 0, 100);
        $this->data['final_domain'] = $clean ? ($clean . '.' . ($_SERVER['HTTP_HOST'] ?? '')) : '';
    }

    function updatingDataDomain($value)
    {
        // if domain mode is domain, set final_domain to domain and make sure domain is valid url
        $domain = $value ?? '';
        $domain = trim($domain);
        // prepend scheme if missing for validation
        $testUrl = (preg_match('/^https?:\/\//i', $domain) ? $domain : 'http://' . $domain);
        if (filter_var($testUrl, FILTER_VALIDATE_URL)) {
            $this->data['final_domain'] = $domain;
        } else {
            $this->data['final_domain'] = '';
        }
    }

    function updatingDataDomainMode($value)
    {
        if ($value === 'subdomain') {
            $this->updatingDataSubdomain($this->data['subdomain'] ?? '');
        } else {
            $this->updatingDataDomain($this->data['domain'] ?? '');
        }
    }

    function mount()
    {
        $newPlanSlug = request()->query('plan');
        $data = decodedSlug($newPlanSlug);
        $this->period = $data['period'] ?? 'month';
        $this->slug = $slug = $data['slug'] ?? null;
        $this->plan = Plan::whereSlug($slug)->firstOrFail();
    }

    function completeSubscription()
    {
        $this->validate();
        $dataToString = encodedSlug($this->data + [
            'plan_id' => $this->plan?->id,
            'period' => $this->period
        ]);
        // TODO : when plan price is free , we should goto success page directly without payment gateway
        // else proceed to payment gateway
        $this->js("window.location='". '/payment/callback/success?data=' . $dataToString ."'");
    }

    public function render()
    {
        $countries = Country::orderBy('name')->get();
        $currencies = Currency::orderBy('name')->get();
        return view('livewire.central.site.checkout-page', get_defined_vars());
    }
}
