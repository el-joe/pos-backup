<?php

namespace App\Livewire\Central\CPanel\Plans;

use App\Enums\ModulesEnum;
use App\Models\Feature;
use App\Models\Plan;
use App\Models\PlanFeature;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class CpanelPlansList extends Component
{
    use LivewireOperations, WithPagination;

    public $current;
    public $id, $plan;
    public $data = [];
    public $rules = [
        'name' => 'required|string|max:255',
        'module_name' => 'required|in:pos,hrm,booking',
        'price_month' => 'required|numeric|min:0',
        'price_year' => 'required|numeric|min:0',
        'plan_features' => 'nullable|array',
        'recommended' => 'boolean',
    ];

    function setCurrent($id)
    {
        $this->current = Plan::with('plan_features')->find($id);

        if ($this->current) {
            $this->data = $this->current->toArray();
        }

        $this->data['module_name'] = $this->data['module_name'] ?? ModulesEnum::POS->value;
        $this->data['plan_features'] = $this->data['plan_features'] ?? [];

        if ($this->current) {
            foreach ($this->current->plan_features as $row) {
                $this->data['plan_features'][$row->feature_id] = [
                    'value' => (int) $row->value,
                    'content' => $row->content,
                ];
            }
        }

        $this->dispatch('iCheck-load');
    }

    function triggerActive($id)
    {
        $plan = Plan::find($id);
        $plan->active = !$plan->active;
        $plan->save();
    }

    function save()
    {
        if ($this->current) {
            $plan = $this->current;
        } else {
            $plan = new Plan();
        }

        if (!$this->validator())
            return;

        $plan = $plan->fill($this->data);
        $plan->save();

        $features = Feature::query()->where('active', true)->orderBy('id')->get();
        foreach ($features as $feature) {
            $rawValue = $this->data['plan_features'][$feature->id]['value'] ?? 0;
            $rawContent = $this->data['plan_features'][$feature->id]['content'] ?? null;

            $value = 0;
            if ($feature->type === 'boolean') {
                $value = (int) ((bool) $rawValue);
            } else {
                $value = is_numeric($rawValue) ? (int) $rawValue : 0;
            }

            PlanFeature::updateOrCreate(
                ['plan_id' => $plan->id, 'feature_id' => $feature->id],
                ['value' => $value, 'content' => is_string($rawContent) ? $rawContent : null]
            );
        }

        $this->popup('success', 'Plan saved successfully');
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this plan', 'Yes, delete it!');
    }

    function delete()
    {
        $this->current->delete();
        $this->popup('success', 'Plan deleted successfully');
    }

    public function render()
    {
        $plans = Plan::orderBy('id', 'desc')->paginate(10);
        $features = Feature::query()->where('active', true)->orderBy('id')->get();
        $modules = ModulesEnum::cases();
        return view('livewire.central.cpanel.plans.cpanel-plans-list', get_defined_vars());
    }
}
