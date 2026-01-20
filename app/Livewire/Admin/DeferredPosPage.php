<?php

namespace App\Livewire\Admin;

class DeferredPosPage extends PosPage
{
    public function mount()
    {
        $this->deferredMode = true;
        parent::mount();

        $this->data['is_deferred'] = 1;
    }
}
