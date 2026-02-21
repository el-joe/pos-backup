<?php

namespace App\Livewire\Central\CPanel\Plans;

use App\Models\Plan;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class PlanDiscountsPage extends Component
{
    use LivewireOperations, WithPagination;

    public ?int $editingPlanId = null;
    public array $data = [];

    public function edit(int $planId): void
    {
        $plan = Plan::query()->findOrFail($planId);
        $this->editingPlanId = $plan->id;
        $this->data = [
            'is_three_month_trial' => (bool) ($plan->three_months_free ?? false),
        ];
    }

    public function save(): void
    {
        if (!$this->editingPlanId) {
            $this->popup('error', 'No plan selected');
            return;
        }

        $this->validate([
            'data.is_three_month_trial' => ['required', 'boolean'],
        ]);

        Plan::query()->whereKey($this->editingPlanId)->update([
            'three_months_free' => !empty($this->data['is_three_month_trial']),
        ]);

        $this->popup('success', 'Plan discount settings saved');
    }

    public function render()
    {
        $plans = Plan::query()->orderBy('module_name')->orderBy('name')->paginate(12)->withPath(route('cpanel.plans.list'));

        return view('livewire.central.cpanel.plans.plan-discounts-page', get_defined_vars());
    }
}
