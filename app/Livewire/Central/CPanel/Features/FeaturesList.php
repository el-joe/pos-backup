<?php

namespace App\Livewire\Central\CPanel\Features;

use App\Enums\ModulesEnum;
use App\Models\Feature;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class FeaturesList extends Component
{
    use LivewireOperations, WithPagination;

    public $current;
    public $data = [];

    public $rules = [
        'code' => 'required|string|max:255',
        'module_name' => 'required|in:pos,hrm,booking',
        'name_en' => 'required|string|max:255',
        'name_ar' => 'required|string|max:255',
        'type' => 'required|in:boolean,text',
        'active' => 'boolean',
    ];

    public function setCurrent($id)
    {
        $this->current = Feature::find($id);

        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool) ($this->data['active'] ?? false);
        } else {
            $this->reset('data');
            $this->data = [
                'module_name' => ModulesEnum::POS->value,
                'type' => 'boolean',
                'active' => true,
            ];
        }

        $this->dispatch('iCheck-load');
    }

    public function save()
    {
        $feature = $this->current ? $this->current : new Feature();

        $rules = $this->rules;
        $unique = 'unique:features,code';
        if ($this->current?->id) {
            $unique .= ',' . $this->current->id;
        }
        $rules['code'] .= '|' . $unique;

        if (!$this->validator($this->data, $rules)) {
            return;
        }

        $this->data['active'] = !empty($this->data['active']) ? 1 : 0;

        $feature->fill($this->data);
        $feature->save();

        $this->popup('success', __('general.cpanel.features.messages.saved'));

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function deleteAlert($id)
    {
        $this->setCurrent($id);
        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this feature', 'Yes, delete it!');
    }

    public function delete()
    {
        if (!$this->current) {
            $this->popup('error', __('general.cpanel.features.messages.not_found'));
            return;
        }

        $this->current->delete();

        $this->popup('success', __('general.cpanel.features.messages.deleted'));

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        $features = Feature::orderBy('id', 'desc')->paginate(10);
        $modules = ModulesEnum::cases();

        return view('livewire.central.cpanel.features.features-list', get_defined_vars());
    }
}
