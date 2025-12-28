<?php

namespace App\Payments\Providers;

use App\Models\PaymentMethod;
use App\Payments\Interfaces\PaymentMethodInterface;
use Illuminate\Support\Facades\Http;

class Paypal implements PaymentMethodInterface
{
    protected $gateway;
    protected $accessToken, $baseUrl;

    public function __construct()
    {
        $className = (new \ReflectionClass($this))->getShortName();
        $this->gateway = PaymentMethod::whereProvider($className)->firstOrFail();

        $this->baseUrl = false
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    function getGrandTotalAmount($amount){
        return ($amount + $this->gateway->fixed_fee) / (1 - ($this->gateway->fee_percentage / 100));
    }

    public function pay($data)
    {
        $paymentConfig = $this->gateway->credentials;

        $clientId = $paymentConfig['client_id'];
        $secret = $paymentConfig['secret'];

        $accessTokenData = $this->createAccessToken($clientId, $secret);

        // $this->gateway->credentials = [
        //     ... ($this->gateway->credentials ?? []),
        //     'access_token' => $this->accessToken,
        // ];

        // $this->gateway->save();

        if(!$this->accessToken){
            return [
                'status' => 'error',
                'message' => 'Unable to get access token from PayPal'
            ];
        }

        $requestPayload = $this->createPayment(
            $data['return_url'],
            $data['cancel_url'],
            number_format($data['amount'], 2, '.', ''),
            $data['currency']
        );

        return [
            'access_token' => $accessTokenData,
            'payment' => $requestPayload
        ];
    }

    public function capture($transactionId)
    {
        $paymentConfig = $this->gateway->credentials; // its array contains 'client_id', 'secret', 'mode' etc.

        $accessToken = $this->createAccessToken($paymentConfig['client_id'], $paymentConfig['secret']);

        $headers = [
            'Content-Type' => 'application/json',
            'Prefer' => 'return=representation',
            'PayPal-Request-Id' => uniqid()
        ];

        return Http::withToken($accessToken)
            ->withHeaders($headers)
            ->post($this->baseUrl . '/v2/checkout/orders/' . $transactionId . '/capture', [])
            ->json();
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
        // ttl = 8 hours = 28800 seconds
        $accessToken = cache()->driver('file')->remember('paypal_access_token_'.$clientId, 28800, function() use ($clientId, $secret) {

            $response = Http::asForm()
                ->withBasicAuth(
                    $clientId,
                    $secret
                )
                ->post($this->baseUrl . '/v1/oauth2/token', [
                    'grant_type' => 'client_credentials'
                ]);

            if (!$response->successful()) {
                throw new \Exception('PayPal Auth Failed');
            }

            return $response->json('access_token') ?? null;
        });


        $this->accessToken = $accessToken ?? null;
        return $accessToken;
    }

    function createPayment($returnUrl, $cancelUrl, $amount, $currency){
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

        $response = Http::withToken($this->accessToken)
            ->post($this->baseUrl . '/v2/checkout/orders', $arr);

        return $response->json();
    }
}
