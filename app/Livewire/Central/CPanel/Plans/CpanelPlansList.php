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
        'data.*.price' => 'required|numeric|min:0',
        'data.*.type' => 'required|in:monthly,yearly',
        'data.*.free_trial_days' => 'nullable|integer|min:0',
        'data.*.active' => 'boolean',
    ];

    public function mount()
    {
        $this->loadData();
    }

    private function loadData(): void
    {
        $plans = Plan::query()->whereIn('slug', ['monthly', 'annual'])->get()->keyBy('slug');

        $defaultTypes = ['monthly' => 'monthly', 'annual' => 'yearly'];

        foreach (['monthly', 'annual'] as $slug) {
            $plan = $plans->get($slug);
            $this->data[$slug] = [
                'name_en' => $plan?->name_en ?? '',
                'name_ar' => $plan?->name_ar ?? '',
                'price' => $plan?->price ?? 0,
                'type' => $plan?->type ?? $defaultTypes[$slug],
                'free_trial_days' => $plan?->free_trial_days ?? 0,
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
            'data.'.$slug.'.price' => 'required|numeric|min:0',
            'data.'.$slug.'.type' => 'required|in:monthly,yearly',
            'data.'.$slug.'.free_trial_days' => 'nullable|integer|min:0',
        ]);

        $row = $this->data[$slug];

        Plan::updateOrCreate(['slug' => $slug], [
            'name' => $row['name_en'],
            'name_en' => $row['name_en'],
            'name_ar' => $row['name_ar'],
            'price' => $row['price'],
            'type' => $row['type'],
            'free_trial_days' => (int) ($row['free_trial_days'] ?? 0),
            'active' => !empty($row['active']),
        ]);

        $this->popup('success', 'Plan saved successfully');
    }

    public function render()
    {
        return view('livewire.central.cpanel.plans.cpanel-plans-list');
    }
}
