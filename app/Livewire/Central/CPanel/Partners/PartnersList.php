<?php

namespace App\Livewire\Central\CPanel\Partners;

use App\Models\Partner;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class PartnersList extends Component
{
    use LivewireOperations, WithPagination;

    public ?Partner $current = null;

    public function setCurrent(?int $id): void
    {
        $this->current = $id ? Partner::find($id) : null;
    }

    public function deleteAlert(int $id): void
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this Partner', 'Yes, delete it!');
    }

    public function delete(): void
    {
        if (!$this->current) {
            $this->popup('error', 'Partner not found');
            return;
        }

        $this->current->delete();

        $this->popup('success', 'Partner deleted successfully');

        $this->reset('current');
    }

    public function render()
    {
        $partners = Partner::query()
            ->with('country')
            ->orderByDesc('id')
            ->paginate(10)
            ->withPath(route('cpanel.partners.list'));

        return view('livewire.central.cpanel.partners.partners-list', get_defined_vars());
    }
}
