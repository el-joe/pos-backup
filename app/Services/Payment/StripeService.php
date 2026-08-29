<?php

namespace App\Services\Payment;

use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Stripe\StripeClient;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeService
{
    protected ?PaymentMethod $gateway;

    public function __construct()
    {
        $this->gateway = PaymentMethod::where('provider', 'stripe')->first();
    }

    public function getCredentials(): array
    {
        return $this->gateway?->credentials ?? [];
    }

    protected function client(): StripeClient
    {
        $secretKey = $this->getCredentials()['secret_key'] ?? null;

        if (!$secretKey) {
            throw new \RuntimeException('Stripe secret key is not configured.');
        }

        return new StripeClient($secretKey);
    }

    public function createCheckoutSession(Subscription $subscription, string $successUrl, string $cancelUrl): string
    {
        $currencyCode = strtolower($subscription->currency?->code ?? 'usd');

        $session = $this->client()->checkout->sessions->create([
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'client_reference_id' => (string) $subscription->id,
            'metadata' => [
                'subscription_id' => $subscription->id,
            ],
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => $currencyCode,
                    'unit_amount' => (int) round($subscription->price * 100),
                    'product_data' => [
                        'name' => 'Subscription #' . $subscription->id,
                    ],
                ],
            ]],
        ]);

        $subscription->update(['status' => 'pending']);

        $this->log('checkout.session.created', 'pending', [
            'subscription_id' => $subscription->id,
            'session_id' => $session->id,
        ]);

        return $session->url;
    }

    public function handleWebhook(Request $request): void
    {
        $webhookSecret = $this->getCredentials()['webhook_secret'] ?? null;
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');

        try {
            $event = $webhookSecret
                ? Webhook::constructEvent($payload, $signature, $webhookSecret)
                : json_decode($payload, false, 512, JSON_THROW_ON_ERROR);
        } catch (SignatureVerificationException|\Throwable $e) {
            $this->log('webhook.invalid_signature', 'failed', ['error' => $e->getMessage()]);
            abort(400, 'Invalid webhook signature');
        }

        $type = $event->type ?? null;
        $object = $event->data->object ?? null;

        if ($type === 'checkout.session.completed') {
            $subscriptionId = $object->metadata->subscription_id ?? $object->client_reference_id ?? null;
            $subscription = $subscriptionId ? Subscription::find($subscriptionId) : null;

            if ($subscription) {
                $subscription->update(['status' => 'paid']);
            }

            $this->log('checkout.session.completed', 'paid', [
                'subscription_id' => $subscriptionId,
                'session_id' => $object->id ?? null,
            ]);
        } elseif ($type === 'invoice.payment_failed') {
            $subscriptionId = $object->metadata->subscription_id ?? null;
            $subscription = $subscriptionId ? Subscription::find($subscriptionId) : null;

            if ($subscription) {
                $subscription->update(['status' => 'failed']);
            }

            $this->log('invoice.payment_failed', 'failed', [
                'subscription_id' => $subscriptionId,
                'invoice_id' => $object->id ?? null,
            ]);
        } else {
            $this->log($type ?? 'unknown', 'ignored', ['event_id' => $event->id ?? null]);
        }
    }

    protected function log(string $event, string $status, array $payload): void
    {
        PaymentLog::create([
            'payment_method_id' => $this->gateway?->id,
            'gateway' => 'stripe',
            'event' => $event,
            'status' => $status,
            'payload' => $payload,
        ]);
    }
}
