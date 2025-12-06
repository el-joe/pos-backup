<?php

namespace App\Livewire\Central\CPanel\Countries;

use App\Models\Country;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class CountriesList extends Component
{
    use LivewireOperations, WithPagination;

    public $id, $country;
    public $current;
    public $data = [];

    public $rules = [
        'name' => 'required|string',
        'code' => 'required|string',
    ];

    function setCurrent($id)
    {
        $this->current = Country::find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
        }

        $this->dispatch('iCheck-load');
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);
        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this Country', 'Yes, delete it!');
    }

    function delete()
    {
        if (!$this->current) {
            $this->popup('error', 'Country not found');
            return;
        }

        $this->current->delete();

        $this->popup('success', 'Country deleted successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    function save()
    {

        if ($this->current) {
            $country = $this->current;
        } else {
            $country = new Country();
        }

        if (!$this->validator()) return;

        $country = $country->fill($this->data);
        $country->save();

        $this->popup('success', 'Country saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        $countries = Country::query()->orderBy('name', 'asc')->paginate(10);
        return view('livewire.central.c-panel.countries.countries-list', get_defined_vars());
    }
}
