<?php

namespace App\Livewire\Central\CPanel\Languages;

use App\Models\Language;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class LanguagesList extends Component
{
    use LivewireOperations, WithPagination;

    public $id, $language;
    public $current;
    public $data = [];

    public $rules = [
        'name' => 'required',
        'code' => 'required',
    ];

    function setCurrent($id)
    {
        $this->current = Language::find($id);
        if ($this->current) {
            $this->data = $this->current->toArray();
            $this->data['active'] = (bool)$this->data['active'];
        }

        $this->dispatch('iCheck-load');
    }

    function deleteAlert($id)
    {
        $this->setCurrent($id);
        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this language', 'Yes, delete it!');
    }

    function delete()
    {
        if (!$this->current) {
            $this->popup('error', 'Language not found');
            return;
        }

        $this->current->delete();

        $this->popup('success', 'Language deleted successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    function save()
    {

        if ($this->current) {
            $language = $this->current;
        } else {
            $language = new Language();
        }

        if (!$this->validator()) return;

        $this->data['active'] = !empty($this->data['active']) ? 1 : 0;

        $language = $language->fill($this->data);
        $language->save();

        $this->popup('success', 'Language saved successfully');

        $this->dismiss();

        $this->reset('current', 'data');
    }

    public function render()
    {
        $languages = Language::paginate(10)->withPath(route('cpanel.languages.list'));

        return view('livewire.central.cpanel.languages.languages-list', get_defined_vars());
    }
}
