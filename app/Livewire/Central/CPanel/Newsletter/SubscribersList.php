<?php

namespace App\Livewire\Central\CPanel\Newsletter;

use App\Models\NewsletterSubscriber;
use App\Traits\LivewireOperations;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('layouts.cpanel')]
class SubscribersList extends Component
{
    use LivewireOperations, WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    public ?NewsletterSubscriber $current = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function setCurrent(?int $id): void
    {
        $this->current = $id ? NewsletterSubscriber::find($id) : null;
    }

    public function unsubscribeAlert(int $id): void
    {
        $this->setCurrent($id);

        $this->confirm('unsubscribe', 'warning', 'Are you sure?', 'You want to unsubscribe this subscriber', 'Yes, unsubscribe!');
    }

    public function unsubscribe(): void
    {
        if (!$this->current) {
            $this->popup('error', 'Subscriber not found');
            return;
        }

        $this->current->update(['unsubscribed_at' => now()]);

        $this->popup('success', 'Subscriber unsubscribed successfully');

        $this->reset('current');
    }

    public function deleteAlert(int $id): void
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this subscriber', 'Yes, delete it!');
    }

    public function delete(): void
    {
        if (!$this->current) {
            $this->popup('error', 'Subscriber not found');
            return;
        }

        $this->current->delete();

        $this->popup('success', 'Subscriber deleted successfully');

        $this->reset('current');
    }

    public function exportCsv(): StreamedResponse
    {
        $search = $this->search;

        $filename = 'newsletter-subscribers-' . now()->format('Y-m-d-His') . '.csv';

        $callback = function () use ($search) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Email', 'Name', 'Subscribed At', 'Unsubscribed At']);

            NewsletterSubscriber::query()
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('email', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });
                })
                ->orderByDesc('id')
                ->chunk(200, function ($subscribers) use ($handle) {
                    foreach ($subscribers as $subscriber) {
                        fputcsv($handle, [
                            $subscriber->id,
                            $subscriber->email,
                            $subscriber->name,
                            optional($subscriber->subscribed_at)->format('Y-m-d H:i'),
                            optional($subscriber->unsubscribed_at)->format('Y-m-d H:i'),
                        ]);
                    }
                });

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function render()
    {
        $query = NewsletterSubscriber::query()
            ->when($this->search !== '', function ($q) {
                $q->where(function ($inner) {
                    $inner->where('email', 'like', "%{$this->search}%")
                        ->orWhere('name', 'like', "%{$this->search}%");
                });
            })
            ->orderByDesc('id');

        $subscribers = $query->paginate(15)->withPath(route('cpanel.newsletter.subscribers'));

        $stats = [
            'total' => NewsletterSubscriber::count(),
            'active' => NewsletterSubscriber::active()->count(),
            'unsubscribed_this_month' => NewsletterSubscriber::whereNotNull('unsubscribed_at')
                ->whereBetween('unsubscribed_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                ->count(),
        ];

        return view('livewire.central.cpanel.newsletter.subscribers-list', get_defined_vars());
    }
}
