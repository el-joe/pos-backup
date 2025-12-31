<?php

namespace App\Livewire\Central\CPanel\Contacts;

use App\Models\Contact;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class ContactsList extends Component
{
    use LivewireOperations, WithPagination;

    public function render()
    {
        $contacts = Contact::latest()->paginate(10)->withPath(route('cpanel.contacts.list'));
        return view('livewire.central.cpanel.contacts.contacts-list', get_defined_vars());
    }
}
