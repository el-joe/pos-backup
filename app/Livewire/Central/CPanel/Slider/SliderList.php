<?php

namespace App\Livewire\Central\CPanel\Slider;

use App\Models\Slider;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.cpanel')]
class SliderList extends Component
{
    use LivewireOperations;

    public function render()
    {
        $sliders = Slider::orderBy('number', 'asc')->get();

        return view('livewire.central.cpanel.slider.slider-list');
    }
}
