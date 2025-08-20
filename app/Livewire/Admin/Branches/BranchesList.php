<?php

namespace App\Livewire\Admin\Branches;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class BranchesList extends Component
{
    public function render()
    {
        return view('livewire.admin.branches.branches-list');
    }
}
