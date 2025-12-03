<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminRegisterRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(public $registerRequest){}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admin Register Request Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_register_request',
            with: [
                'id' => $this->registerRequest->id,
                'name' => $this->registerRequest->data['company']['name'],
                'email' => $this->registerRequest->data['company']['email'],
                'phone' => $this->registerRequest->data['company']['phone'],
                'country_id' => $this->registerRequest->data['company']['country_id'],
                'domain' => $this->registerRequest->data['company']['domain'],
                'created_at' => carbon($this->registerRequest->created_at)->format('d M, Y H:i A'),
            ],
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
