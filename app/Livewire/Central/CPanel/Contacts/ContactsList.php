<?php

namespace App\Livewire\Central\CPanel\Contacts;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cpanel')]
class ContactsList extends Component
{
    public function render()
    {
        return view('livewire.central.c-panel.contacts.contacts-list');
    }
}
