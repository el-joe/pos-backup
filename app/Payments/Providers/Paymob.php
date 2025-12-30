<?php

namespace App\Payments\Providers;

use App\Models\PaymentMethod;
use App\Payments\Interfaces\PaymentMethodInterface;
use Illuminate\Support\Facades\Http;

class Paymob implements PaymentMethodInterface
{
    protected $gateway;
    protected $accessToken, $baseUrl;

    public function __construct()
    {
        $className = (new \ReflectionClass($this))->getShortName();
        $this->gateway = PaymentMethod::whereProvider($className)->firstOrFail();

        $this->baseUrl = 'https://accept.paymob.com';
    }

    function getGrandTotalAmount($amount){
        return ($amount + $this->gateway->fixed_fee) / (1 - ($this->gateway->fee_percentage / 100));
    }

    public function pay($data)
    {
        $paymentConfig = $this->gateway->credentials;

        $integrationIds = $paymentConfig['integration_ids'];
        $apiKey = $paymentConfig['api_key'];
        $apiSecret = $paymentConfig['secret'];
        $publicKey = $paymentConfig['public_key'];


        // $createIntentionResponse = $this->createIntention($secret, $integrationIds, $data['amount'], $data['token']);
        // $retriveIntentionResponse = $this->retriveIntention(
        //     $createIntentionResponse['public_key'] ?? '',
        //     $createIntentionResponse['client_secret'] ?? ''
        // );
        return [
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
            ->send('POST', $this->baseUrl . '/v2/checkout/orders/' . $transactionId . '/capture')
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

    function createIntention($secret,$integrationIds,$amount,$token){
        $allData = decodedData($token);
        $companyDetails = $allData['company'] ?? [];
        $arr = [
            // amount_cents must be equal to the sum of the items amounts
            'amount' => $amount,
            'currency' => 'EGP',
            // Enter your integration ID as an Integer, you can list multiple integration IDs as below
            'payment_methods' => [...$integrationIds],
            'items' => [
                [
                    'name' => 'Plan Subscription',
                    'amount' => $amount,
                    'description' => 'Item description',
                    'quantity' => 1,
                ],
            ],
            'billing_data' => [
                'apartment' => 'dumy',
                // First Name, Last Name, Phone number, & Email are mandatory fields within sending the intention request
                'first_name' => $companyDetails['name'],
                'last_name' => 'dumy',
                'street' => 'dumy',
                'building' => 'dumy',
                'phone_number' => $companyDetails['phone'],
                'city' => 'dumy',
                'country' => 'dumy',
                'email' => $companyDetails['email'],
                'floor' => 'dumy',
                'state' => 'dumy',
            ],
            'extras' => [
                'token' => $token
            ],
            // Refer to a unique or special identifier or reference associated with a transaction or order. It can be used for tracking or categorizing specific types of transactions and it returns within the transaction callback under merchant_order_id
            'special_reference' => uniqid() . '-' . now()->timestamp,
            'notification_url' => url('/payment/check'),
            'redirection_url' => url('/payment/check'),
            // Notification and redirection URL are working only with Cards and they overlap the transaction processed and response callbacks sent per Integration ID
        ];
        $response = Http::withHeaders([
            'Authorization' => 'Token '.$secret,
        ])->post($this->baseUrl . '/v1/intention', $arr);

        return $response->json();
    }

    function retriveIntention($publicKey,$clientSecret){
        $arr = [
            'publicKey' => $publicKey,
            'clientSecret' => $clientSecret,
        ];
        $response = Http::get($this->baseUrl . '/unifiedcheckout', $arr);

        return $response->json();
    }
}
