<?php

namespace App\Livewire\Central\CPanel\Plans;

use App\Models\Plan;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cpanel')]
class CpanelPlansList extends Component
{
    use LivewireOperations;

    public array $data = [];

    public $rules = [
        'data.*.name_en' => 'required|string|max:255',
        'data.*.name_ar' => 'required|string|max:255',
        'data.*.price_month' => 'required|numeric|min:0',
        'data.*.price_year' => 'required|numeric|min:0',
        'data.*.active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadData();
    }

    private function loadData(): void
    {
        $plans = Plan::query()->whereIn('slug', ['monthly', 'annual'])->get()->keyBy('slug');

        foreach (['monthly', 'annual'] as $slug) {
            $plan = $plans->get($slug);
            $this->data[$slug] = [
                'name_en' => $plan?->name_en ?? '',
                'name_ar' => $plan?->name_ar ?? '',
                'price_month' => $plan?->price_month ?? 0,
                'price_year' => $plan?->price_year ?? 0,
                'active' => (bool) ($plan?->active ?? true),
            ];
        }

        $this->dispatch('iCheck-load');
    }

    public function save(string $slug)
    {
        if (!isset($this->data[$slug])) {
            return;
        }

        $this->validate([
            'data.'.$slug.'.name_en' => 'required|string|max:255',
            'data.'.$slug.'.name_ar' => 'required|string|max:255',
            'data.'.$slug.'.price_month' => 'required|numeric|min:0',
            'data.'.$slug.'.price_year' => 'required|numeric|min:0',
        ]);

        $row = $this->data[$slug];

        Plan::updateOrCreate(['slug' => $slug], [
            'name' => $row['name_en'],
            'name_en' => $row['name_en'],
            'name_ar' => $row['name_ar'],
            'price_month' => $row['price_month'],
            'price_year' => $row['price_year'],
            'active' => !empty($row['active']),
        ]);

        $this->popup('success', 'Plan saved successfully');
    }

    public function render()
    {
        return view('livewire.central.cpanel.plans.cpanel-plans-list');
    }
}
