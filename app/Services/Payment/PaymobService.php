<?php

namespace App\Services\Payment;

use App\Models\PaymentLog;
use App\Models\PaymentMethod;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PaymobService
{
    protected ?PaymentMethod $gateway;
    protected string $baseUrl = 'https://accept.paymob.com';

    public function __construct()
    {
        $this->gateway = PaymentMethod::where('provider', 'paymob')->first();
    }

    public function getCredentials(): array
    {
        return $this->gateway?->credentials ?? [];
    }

    public function authenticate(): string
    {
        $apiKey = $this->getCredentials()['api_key'] ?? null;

        if (!$apiKey) {
            throw new \RuntimeException('Paymob API key is not configured.');
        }

        $response = Http::post($this->baseUrl . '/api/auth/tokens', [
            'api_key' => $apiKey,
        ]);

        if (!$response->successful()) {
            $this->log('authenticate', 'failed', ['response' => $response->json()]);
            throw new \RuntimeException('Paymob authentication failed.');
        }

        return $response->json('token');
    }

    public function createOrder(float $amount, string $currency, array $items): int
    {
        $token = $this->authenticate();

        $response = Http::withToken($token)->post($this->baseUrl . '/api/ecommerce/orders', [
            'auth_token' => $token,
            'delivery_needed' => false,
            'amount_cents' => (int) round($amount * 100),
            'currency' => $currency,
            'items' => $items,
        ]);

        if (!$response->successful()) {
            $this->log('create_order', 'failed', ['response' => $response->json()]);
            throw new \RuntimeException('Unable to create Paymob order.');
        }

        $orderId = $response->json('id');

        $this->log('create_order', 'pending', ['order_id' => $orderId, 'amount' => $amount]);

        return $orderId;
    }

    public function getPaymentKey(int $orderId, float $amount, array $billingData): string
    {
        $credentials = $this->getCredentials();
        $token = $this->authenticate();

        $response = Http::withToken($token)->post($this->baseUrl . '/api/acceptance/payment_keys', [
            'auth_token' => $token,
            'amount_cents' => (int) round($amount * 100),
            'expiration' => 3600,
            'order_id' => $orderId,
            'billing_data' => $billingData,
            'currency' => 'EGP',
            'integration_id' => $credentials['integration_id'] ?? null,
        ]);

        if (!$response->successful()) {
            $this->log('get_payment_key', 'failed', ['response' => $response->json()]);
            throw new \RuntimeException('Unable to obtain Paymob payment key.');
        }

        return $response->json('token');
    }

    public function verifyHmac(array $data, string $hmac): bool
    {
        $hmacSecret = $this->getCredentials()['hmac_secret'] ?? null;

        if (!$hmacSecret) {
            return false;
        }

        $orderedKeys = [
            'amount_cents', 'created_at', 'currency', 'error_occured',
            'has_parent_transaction', 'id', 'integration_id', 'is_3d_secure',
            'is_auth', 'is_capture', 'is_refunded', 'is_standalone_payment',
            'is_voided', 'order', 'owner', 'pending', 'source_data_pan',
            'source_data_sub_type', 'source_data_type', 'success',
        ];

        $concatenated = '';
        foreach ($orderedKeys as $key) {
            $value = $data[$key] ?? '';
            $concatenated .= is_bool($value) ? ($value ? 'true' : 'false') : (string) $value;
        }

        $calculatedHmac = hash_hmac('sha512', $concatenated, $hmacSecret);

        return hash_equals($calculatedHmac, $hmac);
    }

    public function handleCallback(Request $request): void
    {
        $data = $request->input('obj', $request->all());
        $hmac = $request->query('hmac', $request->input('hmac', ''));

        if (!$this->verifyHmac($data, $hmac)) {
            $this->log('callback', 'invalid_hmac', ['data' => $data]);
            abort(400, 'Invalid HMAC signature');
        }

        $subscriptionId = $data['order']['merchant_order_id'] ?? null;
        $subscription = $subscriptionId ? Subscription::find($subscriptionId) : null;
        $success = (bool) ($data['success'] ?? false);

        if ($success) {
            if ($subscription) {
                $subscription->update(['status' => 'paid']);
            }
            $this->log('callback', 'paid', ['subscription_id' => $subscriptionId, 'transaction_id' => $data['id'] ?? null]);
        } else {
            if ($subscription) {
                $subscription->update(['status' => 'failed']);
            }
            $this->log('callback', 'failed', ['subscription_id' => $subscriptionId, 'transaction_id' => $data['id'] ?? null]);
        }
    }

    protected function log(string $event, string $status, array $payload): void
    {
        PaymentLog::create([
            'payment_method_id' => $this->gateway?->id,
            'gateway' => 'paymob',
            'event' => $event,
            'status' => $status,
            'payload' => $payload,
        ]);
    }
}
