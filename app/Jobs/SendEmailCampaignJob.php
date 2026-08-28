<?php

namespace App\Jobs;

use App\Mail\CampaignMail;
use App\Models\EmailCampaign;
use App\Models\NewsletterSubscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendEmailCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public int $campaignId) {}

    public function handle(): void
    {
        $campaign = EmailCampaign::find($this->campaignId);

        if (!$campaign) {
            return;
        }

        $campaign->update(['status' => 'sending']);

        try {
            $recipients = $this->resolveRecipients($campaign);
            $sentCount = 0;

            foreach ($recipients->chunk(50) as $chunk) {
                foreach ($chunk as $email) {
                    Mail::to($email)->queue(new CampaignMail($campaign, 'en', $email));
                    $sentCount++;
                }
            }

            $campaign->update([
                'status' => 'sent',
                'sent_count' => $sentCount,
                'sent_at' => now(),
            ]);
        } catch (Throwable $exception) {
            $campaign->update(['status' => 'failed']);

            Log::error('Email campaign sending failed', [
                'campaign_id' => $campaign->id,
                'error' => $exception->getMessage(),
            ]);

            throw $exception;
        }
    }

    /**
     * Resolve the list of recipient emails for the campaign.
     *
     * Never emails unsubscribed users regardless of the recipient_type label.
     */
    protected function resolveRecipients(EmailCampaign $campaign): \Illuminate\Support\Collection
    {
        if ($campaign->recipient_type === 'manual') {
            $lines = preg_split('/[\r\n,]+/', (string) $campaign->manual_emails) ?: [];

            return collect($lines)
                ->map(fn ($email) => trim($email))
                ->filter(fn ($email) => $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL))
                ->unique()
                ->values();
        }

        // 'all' and 'active_only' both resolve to active (non-unsubscribed) subscribers only.
        return NewsletterSubscriber::active()->pluck('email');
    }
}
