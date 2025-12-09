<?php

namespace App\Livewire\Central\CPanel\Plans;

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
        'name'         => 'required|string|max:255',
        'price_month'  => 'required|numeric|min:0',
        'price_year'   => 'required|numeric|min:0',
        'features' => 'required|array',
        'recommended'  => 'boolean',
    ];

    function setCurrent($id)
    {
        $this->current = Plan::find($id);

        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['features'] = json_encode($this->data['features'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
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

        $this->data['features'] = json_decode($this->data['features'], true);

        if (!$this->validator()) return;

        $plan = $plan->fill($this->data);
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

        return view('livewire.central.cpanel.plans.cpanel-plans-list', get_defined_vars());
    }
}
