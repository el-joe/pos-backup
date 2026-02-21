<?php

namespace App\Livewire\Central\CPanel\Partners;

use App\Models\Country;
use App\Models\Partner;
use App\Traits\LivewireOperations;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cpanel')]
class PartnerForm extends Component
{
    use LivewireOperations;

    public ?Partner $partner = null;
    public array $data = [];

    public function mount(?int $id = null): void
    {
        $this->partner = $id ? Partner::findOrFail($id) : null;

        if ($this->partner) {
            $this->data = [
                'name' => (string) ($this->partner->name ?? ''),
                'email' => (string) ($this->partner->email ?? ''),
                'phone' => (string) ($this->partner->phone ?? ''),
                'country_id' => $this->partner->country_id,
                'address' => (string) ($this->partner->address ?? ''),
                'commission_rate' => (string) ($this->partner->commission_rate ?? '0.00'),
            ];
        } else {
            $this->data = [
                'name' => '',
                'email' => '',
                'phone' => '',
                'country_id' => null,
                'address' => '',
                'commission_rate' => '0.00',
            ];
        }
    }

    public function save(): void
    {
        $partnerId = $this->partner?->id;

        $this->validate([
            'data.name' => ['required', 'string', 'max:255'],
            'data.email' => [
                'required',
                'email',
                Rule::unique('central.partners', 'email')->ignore($partnerId),
            ],
            'data.phone' => ['nullable', 'string', 'max:50'],
            'data.country_id' => ['nullable', 'exists:central.countries,id'],
            'data.address' => ['nullable', 'string'],
            'data.commission_rate' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $partner = $this->partner ?: new Partner();

        $partner->fill($this->data);
        $partner->save();

        $this->partner = $partner;

        $this->popup('success', 'Partner saved successfully');

        $this->redirect(route('cpanel.partners.edit', ['id' => $partner->id]), navigate: true);
    }

    public function render()
    {
        $countries = Country::query()->orderBy('name')->get();

        return view('livewire.central.cpanel.partners.partner-form', get_defined_vars());
    }
}
