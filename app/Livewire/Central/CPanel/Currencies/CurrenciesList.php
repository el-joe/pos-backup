<?php

namespace App\Livewire\Central\CPanel\Currencies;

use App\Models\Currency;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class CurrenciesList extends Component
{
    use LivewireOperations, WithPagination;

    public $id, $currency;
    public $current;
    public $data =[];

    public $rules = [
        'name' => 'required',
        'code' => 'required',
        'symbol' => 'required',
        'conversion_rate' => 'required',
    ];

    function setCurrent($id) {
        $this->current = Currency::find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
        }

        $this->dispatch('iCheck-load');
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this currency!', 'Yes, delete it!');
    }

    function delete() {
        if (!$this->current) {
            $this->popup('error', 'Currency not found');
            return;
        }

        $this->current->delete();

        $this->popup('success', 'Currency deleted successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    function save() {
        if ($this->current) {
            $currency = $this->current;
        } else {
            $currency = new Currency();
        }

        if (!$this->validator()) return;

        $currency = $currency->fill($this->data);
        $currency->save();

        $this->popup('success', 'Currency saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        $currencies = Currency::paginate(10);

        return view('livewire.central.cpanel.currencies.currencies-list', get_defined_vars());
    }
}
