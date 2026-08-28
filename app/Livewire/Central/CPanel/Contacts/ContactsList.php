<?php

namespace App\Livewire\Central\CPanel\Contacts;

use App\Models\Contact;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class ContactsList extends Component
{
    use LivewireOperations, WithPagination;

    public array $selectedIds = [];
    public bool $selectAll = false;
    public string $search = '';
    public string $filter = 'all';
    public ?int $viewingId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilter(): void
    {
        $this->resetPage();
    }

    public function updatedSelectAll(bool $value): void
    {
        $this->selectedIds = $value ? Contact::pluck('id')->toArray() : [];
    }

    public function markRead(): void
    {
        Contact::whereIn('id', $this->selectedIds)->update(['read_at' => now()]);
        $this->selectedIds = [];
        $this->selectAll = false;
        $this->popup('success', 'Marked as read');
    }

    public function markUnread(): void
    {
        Contact::whereIn('id', $this->selectedIds)->update(['read_at' => null]);
        $this->selectedIds = [];
        $this->selectAll = false;
        $this->popup('success', 'Marked as unread');
    }

    public function deleteSelected(): void
    {
        if (empty($this->selectedIds)) {
            $this->popup('error', 'No contacts selected');
            return;
        }

        Contact::whereIn('id', $this->selectedIds)->delete();
        $this->selectedIds = [];
        $this->selectAll = false;
        $this->popup('success', 'Deleted successfully');
    }

    public function delete(int $id): void
    {
        Contact::find($id)?->delete();
        $this->selectedIds = array_values(array_diff($this->selectedIds, [$id]));
        $this->popup('success', 'Deleted successfully');
    }

    public function view(int $id): void
    {
        $this->viewingId = $id;
        Contact::find($id)?->markAsRead();
    }

    public function markUnreadSingle(int $id): void
    {
        Contact::find($id)?->markAsUnread();
    }

    public function render()
    {
        $query = Contact::query()
            ->when($this->search, fn ($q, $v) => $q->where('name', 'LIKE', "%{$v}%")->orWhere('email', 'LIKE', "%{$v}%"))
            ->when($this->filter === 'read', fn ($q) => $q->read())
            ->when($this->filter === 'unread', fn ($q) => $q->unread())
            ->latest();

        $contacts = $query->paginate(20)->withPath(route('cpanel.contacts.list'));

        $stats = [
            'total' => Contact::count(),
            'unread' => Contact::unread()->count(),
        ];

        return view('livewire.central.cpanel.contacts.contacts-list', get_defined_vars());
    }
}
