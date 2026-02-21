<?php

namespace App\Livewire\Central\CPanel\Pages;

use App\Models\Page;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class PagesList extends Component
{
    use LivewireOperations, WithPagination;

    public ?Page $current = null;

    public function setCurrent(?int $id): void
    {
        $this->current = $id ? Page::find($id) : null;
    }

    public function deleteAlert(int $id): void
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this Page', 'Yes, delete it!');
    }

    public function delete(): void
    {
        if (!$this->current) {
            $this->popup('error', 'Page not found');
            return;
        }

        $this->current->delete();

        $this->popup('success', 'Page deleted successfully');

        $this->reset('current');
    }

    public function render()
    {
        $pages = Page::orderByDesc('id')
            ->paginate(10)
            ->withPath(route('cpanel.pages.list'));

        return view('livewire.central.cpanel.pages.pages-list', get_defined_vars());
    }
}
