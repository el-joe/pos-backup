<?php

namespace App\Payments\Providers;

use App\Models\PaymentMethod;
use App\Payments\Interfaces\PaymentMethodInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Paypal implements PaymentMethodInterface
{
    protected $gateway;
    protected $accessToken, $baseUrl;

    public function __construct()
    {
        $className = (new \ReflectionClass($this))->getShortName();
        $this->gateway = PaymentMethod::whereProvider($className)->firstOrFail();

        $mode = strtolower((string)($this->gateway->credentials['mode'] ?? 'sandbox'));

        $this->baseUrl = $mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    function getGrandTotalAmount($amount){
        return ($amount + $this->gateway->fixed_fee) / (1 - ($this->gateway->fee_percentage / 100));
    }

    public function pay($data)
    {
        $paymentConfig = $this->gateway->credentials;

        $clientId = $paymentConfig['client_id'] ?? null;
        $secret = $paymentConfig['secret'] ?? null;

        if (!$clientId || !$secret) {
            return [
                'status' => 'error',
                'message' => 'PayPal is not configured correctly.',
            ];
        }

        $accessTokenData = $this->createAccessToken($clientId, $secret);

        if (!$this->accessToken) {
            return [
                'status' => 'error',
                'message' => 'Unable to get access token from PayPal',
            ];
        }

        $requestPayload = $this->createPayment(
            $data['return_url'],
            $data['cancel_url'],
            number_format($data['amount'], 2, '.', ''),
            $data['currency']
        );

        if (!$requestPayload) {
            return [
                'status' => 'error',
                'message' => 'Unable to create PayPal order.',
            ];
        }

        return [
            'access_token' => $accessTokenData,
            'payment' => $requestPayload,
        ];
    }

    public function capture($transactionId)
    {
        $paymentConfig = $this->gateway->credentials; // its array contains 'client_id', 'secret', 'mode' etc.

        $accessToken = $this->createAccessToken($paymentConfig['client_id'] ?? null, $paymentConfig['secret'] ?? null);

        if (!$accessToken) {
            return [
                'status' => 'error',
                'message' => 'Unable to get access token from PayPal',
            ];
        }

        $headers = [
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation',
            'PayPal-Request-Id' => uniqid()
        ];

        try {
            $response = Http::withToken($accessToken)
                ->withHeaders($headers)
                ->timeout(30)
                ->send('POST', $this->baseUrl . '/v2/checkout/orders/' . $transactionId . '/capture');
        } catch (\Throwable $e) {
            Log::error('PayPal capture request failed', ['transaction_id' => $transactionId, 'error' => $e->getMessage()]);

            return [
                'status' => 'error',
                'message' => 'Unable to reach PayPal to capture the payment.',
            ];
        }

        if (!$response->successful()) {
            Log::error('PayPal capture failed', ['transaction_id' => $transactionId, 'status' => $response->status(), 'body' => $response->body()]);

            return [
                'status' => 'error',
                'message' => 'PayPal capture failed.',
            ];
        }

        return $response->json();
    }

    public function refund($transactionId)
    {
        $paymentConfig = $this->gateway->credentials; // its array contains 'client_id', 'secret', 'mode' etc.

        // Simulate refunding the transaction via PayPal API
        return [
            'status' => 'success',
            'message' => "Refunded successful via PaypalPayment",
            'transaction_id' => $transactionId,
        ];
    }

    function createAccessToken($clientId, $secret)
    {
        if (!$clientId || !$secret) {
            $this->accessToken = null;
            return null;
        }

        $cacheKey = 'paypal_access_token_' . md5($this->baseUrl . '_' . $clientId);

        // ttl = 8 hours = 28800 seconds
        $accessToken = cache()->driver('file')->remember($cacheKey, 28800, function () use ($clientId, $secret) {
            try {
                $response = Http::asForm()
                    ->withBasicAuth($clientId, $secret)
                    ->timeout(30)
                    ->post($this->baseUrl . '/v1/oauth2/token', [
                        'grant_type' => 'client_credentials',
                    ]);
            } catch (\Throwable $e) {
                Log::error('PayPal auth request failed', ['error' => $e->getMessage()]);
                return null;
            }

            if (!$response->successful()) {
                Log::error('PayPal Auth Failed', ['status' => $response->status(), 'body' => $response->body()]);
                return null;
            }

            return $response->json('access_token');
        });

        // Never cache a failed lookup, otherwise a transient PayPal outage
        // would lock out checkout for the full TTL.
        if (!$accessToken) {
            cache()->driver('file')->forget($cacheKey);
        }

        $this->accessToken = $accessToken ?: null;
        return $this->accessToken;
    }

    function createPayment($returnUrl, $cancelUrl, $amount, $currency)
    {
        $arr = [
            'intent' => 'CAPTURE',
            'payment_source' => [
                'paypal' => [
                    'experience_context' => [
                        'return_url' => $returnUrl,
                        'cancel_url' => $cancelUrl,
                        'user_action' => 'PAY_NOW'
                    ]
                ]
            ],
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $currency,
                        'value' => $amount
                    ]
                ]
            ]
        ];

        try {
            $response = Http::withToken($this->accessToken)
                ->timeout(30)
                ->post($this->baseUrl . '/v2/checkout/orders', $arr);
        } catch (\Throwable $e) {
            Log::error('PayPal create order request failed', ['error' => $e->getMessage()]);
            return null;
        }

        if (!$response->successful()) {
            Log::error('PayPal create order failed', ['status' => $response->status(), 'body' => $response->body()]);
            return null;
        }

        return $response->json();
    }

    public function getApproveUrl(array $payment): ?string
    {
        foreach ($payment['links'] ?? [] as $link) {
            if (in_array($link['rel'] ?? null, ['approve', 'payer-action'], true)) {
                return $link['href'] ?? null;
            }
        }

        return null;
    }
}
