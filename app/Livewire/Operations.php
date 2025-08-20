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

    #[On('open-modal')]
    function openModal($selector) {
        $this->dispatch('openModal',$selector);
    }

    public function render()
    {
        return view('livewire.operations');
    }
}
