<?php

namespace App\Livewire\Central\CPanel\Faqs;

use App\Models\Faq;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class FaqsList extends Component
{
    use LivewireOperations, WithPagination;

    public ?Faq $current = null;

    public function setCurrent(?int $id): void
    {
        $this->current = $id ? Faq::find($id) : null;
    }

    public function deleteAlert(int $id): void
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this FAQ', 'Yes, delete it!');
    }

    public function delete(): void
    {
        if (!$this->current) {
            $this->popup('error', 'FAQ not found');
            return;
        }

        $this->current->delete();

        $this->popup('success', 'FAQ deleted successfully');

        $this->reset('current');
    }

    public function render()
    {
        $faqs = Faq::orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(10);

        $faqs->withPath(route('cpanel.faqs.list'));

        return view('livewire.central.cpanel.faqs.faqs-list', get_defined_vars());
    }
}
