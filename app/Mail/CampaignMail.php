<?php

namespace App\Mail;

use App\Models\EmailCampaign;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;

class CampaignMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public EmailCampaign $campaign,
        public string $locale = 'en',
        public ?string $recipientEmail = null
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subjectField = "subject_{$this->locale}";
        $subject = $this->campaign->{$subjectField} ?: $this->campaign->subject_en;

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $bodyField = "body_{$this->locale}";
        $body = $this->campaign->{$bodyField} ?: $this->campaign->body_en;

        $unsubscribeUrl = null;
        if ($this->recipientEmail) {
            $token = urlencode(Crypt::encryptString($this->recipientEmail));
            $unsubscribeUrl = route('newsletter.unsubscribe', ['token' => $token]);
        }

        return new Content(
            view: 'emails.campaign',
            with: [
                'body' => $body,
                'unsubscribeUrl' => $unsubscribeUrl,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
