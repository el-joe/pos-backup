<?php

namespace App\Livewire\Central\CPanel\Partners;

use App\Models\PartnerCommission;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class PartnerCommissionsList extends Component
{
    use LivewireOperations, WithPagination;

    public ?PartnerCommission $current = null;

    public function setCurrent(?int $id): void
    {
        $this->current = $id ? PartnerCommission::find($id) : null;
    }

    public function markCollectedAlert(int $id): void
    {
        $this->setCurrent($id);

        $this->confirm('markCollected', 'warning', 'Are you sure?', 'You want to mark this commission as collected', 'Yes, mark collected!');
    }

    public function markCollected(): void
    {
        if (!$this->current) {
            $this->popup('error', 'Commission not found');
            return;
        }

        if ($this->current->collected_at) {
            $this->popup('warning', 'Commission already collected');
            return;
        }

        $this->current->update([
            'collected_at' => now(),
            'status' => 'collected',
        ]);

        $this->popup('success', 'Commission marked as collected');

        $this->reset('current');
    }

    public function deleteAlert(int $id): void
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this Commission', 'Yes, delete it!');
    }

    public function delete(): void
    {
        if (!$this->current) {
            $this->popup('error', 'Commission not found');
            return;
        }

        $this->current->delete();

        $this->popup('success', 'Commission deleted successfully');

        $this->reset('current');
    }

    public function render()
    {
        $commissions = PartnerCommission::query()
            ->with(['partner', 'currency', 'subscription'])
            ->orderByDesc('id')
            ->paginate(10)
            ->withPath(route('cpanel.partner-commissions.list'));

        return view('livewire.central.cpanel.partners.partner-commissions-list', get_defined_vars());
    }
}
