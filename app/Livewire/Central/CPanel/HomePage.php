<?php

namespace App\Livewire\Central\CPanel;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cpanel')]
class HomePage extends Component
{
    public function render()
    {
        return view('livewire.central.cpanel.home-page');
    }
}
