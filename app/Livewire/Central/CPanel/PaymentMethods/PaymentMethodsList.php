<?php

namespace App\Livewire\Central\CPanel\PaymentMethods;

use App\Models\PaymentMethod;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class PaymentMethodsList extends Component
{
    use LivewireOperations, WithPagination;

    public ?PaymentMethod $current = null;

    public function setCurrent(?int $id): void
    {
        $this->current = $id ? PaymentMethod::find($id) : null;
    }

    public function deleteAlert(int $id): void
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this Payment Method', 'Yes, delete it!');
    }

    public function delete(): void
    {
        if (!$this->current) {
            $this->popup('error', 'Payment Method not found');
            return;
        }

        $this->current->delete();

        $this->popup('success', 'Payment Method deleted successfully');

        $this->reset('current');
    }

    public function render()
    {
        $paymentMethods = PaymentMethod::query()
            ->orderByDesc('id')
            ->paginate(10)
            ->withPath(route('cpanel.payment-methods.list'));

        return view('livewire.central.cpanel.payment-methods.payment-methods-list', get_defined_vars());
    }
}
