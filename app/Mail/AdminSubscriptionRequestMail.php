<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminSubscriptionRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public $requestModel, public string $title) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->title,
        );
    }

    public function content(): Content
    {
        $model = $this->requestModel;

        return new Content(
            view: 'emails.admin_subscription_request',
            with: [
                'title' => $this->title,
                'id' => $model->id,
                'tenant' => $model->tenant?->name ?? $model->tenant_id,
                'amount' => number_format((float) ($model->price ?? $model->amount ?? 0), 2),
                'plan' => $model->plan?->name ?? null,
                'paymentMethod' => $model->manual
                    ? ($model->paymentMethod?->name . ' (Manual)')
                    : ($model->pay_from_balance ?? false ? 'Wallet Balance' : ($model->paymentMethod?->name ?? 'N/A')),
                'convertedAmount' => $model->converted_amount
                    ? number_format((float) $model->converted_amount, 2) . ' ' . $model->currency_code
                    : null,
                'createdAt' => carbon($model->created_at)->format('d M, Y H:i A'),
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
