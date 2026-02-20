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
            'discount_percent' => (float) ($plan->discount_percent ?? 0),
            'multi_system_discount_percent' => (float) ($plan->multi_system_discount_percent ?? 0),
            'free_trial_months' => (int) ($plan->free_trial_months ?? 0),
        ];
    }

    public function save(): void
    {
        if (!$this->editingPlanId) {
            $this->popup('error', 'No plan selected');
            return;
        }

        $this->validate([
            'data.discount_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'data.multi_system_discount_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'data.free_trial_months' => ['required', 'integer', 'min:0', 'max:24'],
        ]);

        Plan::query()->whereKey($this->editingPlanId)->update([
            'discount_percent' => (float) $this->data['discount_percent'],
            'multi_system_discount_percent' => (float) $this->data['multi_system_discount_percent'],
            'free_trial_months' => (int) $this->data['free_trial_months'],
        ]);

        $this->popup('success', 'Plan discount settings saved');
    }

    public function render()
    {
        $plans = Plan::query()->orderBy('module_name')->orderBy('name')->paginate(12)->withPath(route('cpanel.plans.discounts'));

        return view('livewire.central.cpanel.plans.plan-discounts-page', get_defined_vars());
    }
}
