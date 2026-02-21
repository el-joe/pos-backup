<?php

namespace App\Livewire\Employee;

use Livewire\Component;

class Profile extends Component
{
    public function render()
    {
        $employee = employee()->loadMissing(['department', 'designation', 'manager', 'activeContract']);

        return employeeLayoutView('employee.profile', get_defined_vars(), false)
            ->title('My Profile');
    }
}
