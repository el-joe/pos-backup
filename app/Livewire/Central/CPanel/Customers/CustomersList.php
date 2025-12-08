<?php

namespace App\Livewire\Central\CPanel\Customers;

use App\Models\Tenant;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cpanel')]
class CustomersList extends Component
{
    public function render()
    {
        $customers = Tenant::with('domains')->paginate(10);

        return view('livewire.central.cpanel.customers.customers-list', get_defined_vars());
    }
}
