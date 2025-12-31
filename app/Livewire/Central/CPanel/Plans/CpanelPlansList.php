<?php

namespace App\Livewire\Central\CPanel\Plans;

use App\Enums\PlanFeaturesEnum;
use App\Models\Plan;
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
        'price_month' => 'required|numeric|min:0',
        'price_year' => 'required|numeric|min:0',
        'features' => 'required|array',
        'recommended' => 'boolean',
    ];

    function setCurrent($id)
    {
        $this->current = Plan::find($id);

        if ($this->current) {
            $this->data = $this->current->toArray();
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

        $features = collect($this->data['features'])->map(function ($item) {
            $keys = ['status', 'description', 'limit'];
            $newItem = [];
            foreach ($keys as $key) {
                switch ($key) {
                    case 'status':
                        $newItem[$key] = $item[$key];
                        continue 2;
                    case 'description':
                        if (!empty($item[$key] ?? null)) {
                            $newItem[$key] = $item[$key];
                        }
                        continue 2;
                    case 'limit':
                        if (is_numeric($item[$key] ?? null)) {
                            $newItem[$key] = (int) $item[$key];
                        }
                        continue 2;
                    default:
                        continue 2;
                }
            }

            return $newItem;
        })->toArray();

        if (!$this->validator())
            return;

        $plan = $plan->fill(array_merge($this->data, ['features' => $features]));
        $plan->save();

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
        $features = PlanFeaturesEnum::cases();
        return view('livewire.central.cpanel.plans.cpanel-plans-list', get_defined_vars());
    }
}
