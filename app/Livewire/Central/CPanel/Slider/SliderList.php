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

    public $current;
    public $data = [];
    public $rules = [
        'title' => 'required',
        'number' => 'nullable',
        'image' => 'nullable|mimes:jpg,jpeg,png,gif,webp|max:2048',
    ];

    function setCurrent($id)
    {
        $this->current = Slider::find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
            $this->data['image_path'] = $this->current->image_path;
        }

        $this->dispatch('iCheck-load');
    }

    function save()
    {
        if ($this->current) {
            $slider = $this->current;
        } else {
            $slider = new Slider();
        }

        if (!$this->validator()) return;

        $this->data['active'] = !empty($this->data['active']) ? 1 : 0;

        $slider = $slider->fill($this->data);
        $slider->save();

        if ($this->data['image'] ?? false) {
            $slider->image()->delete();
            $slider->image()->create([
                'path' => $this->data['image'],
                'key' => 'image',
            ]);
        }

        $this->popup('success', 'Slider saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        $sliders = Slider::orderBy('number', 'asc')->get();

        return view('livewire.central.cpanel.slider.slider-list', get_defined_vars());
    }
}
