<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Statistics extends Component
{
    public function render()
    {
        return layoutView('statistics')->title(__('general.titles.statistics'));
    }
}
