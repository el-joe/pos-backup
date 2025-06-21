<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Operations extends Component
{
    #[On('alert-message')]
    function alert($params) {
        $this->dispatch('alert',$params);
    }

    #[On('modal-dismiss')]
    function modalDismiss() {
        $this->dispatch('closeModal');
    }

    public function render()
    {
        return view('livewire.operations');
    }
}
