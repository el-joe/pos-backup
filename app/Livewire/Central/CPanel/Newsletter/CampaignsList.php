<?php

namespace App\Livewire\Central\CPanel\Newsletter;

use App\Models\EmailCampaign;
use App\Traits\LivewireOperations;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.cpanel')]
class CampaignsList extends Component
{
    use LivewireOperations, WithPagination;

    public ?EmailCampaign $current = null;

    public function setCurrent(?int $id): void
    {
        $this->current = $id ? EmailCampaign::find($id) : null;
    }

    public function deleteAlert(int $id): void
    {
        $this->setCurrent($id);

        $this->confirm('delete', 'warning', 'Are you sure?', 'You want to delete this campaign', 'Yes, delete it!');
    }

    public function delete(): void
    {
        if (!$this->current) {
            $this->popup('error', 'Campaign not found');
            return;
        }

        $this->current->delete();

        $this->popup('success', 'Campaign deleted successfully');

        $this->reset('current');
    }

    public function duplicate(int $id): void
    {
        $campaign = EmailCampaign::find($id);

        if (!$campaign) {
            $this->popup('error', 'Campaign not found');
            return;
        }

        $clone = $campaign->replicate();
        $clone->subject_en = $campaign->subject_en . ' (Copy)';
        $clone->status = 'draft';
        $clone->sent_count = 0;
        $clone->sent_at = null;
        $clone->scheduled_at = null;
        $clone->save();

        $this->popup('success', 'Campaign duplicated successfully');

        $this->redirect(route('cpanel.newsletter.campaigns.edit', ['id' => $clone->id]));
    }

    public function render()
    {
        $campaigns = EmailCampaign::orderByDesc('id')->paginate(10)->withPath(route('cpanel.newsletter.campaigns'));

        return view('livewire.central.cpanel.newsletter.campaigns-list', get_defined_vars());
    }
}
