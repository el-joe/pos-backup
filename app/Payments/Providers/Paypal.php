<?php

namespace App\Payments\Providers;

use App\Models\PaymentMethod;
use App\Payments\Interfaces\PaymentMethodInterface;

class Paypal implements PaymentMethodInterface
{
    protected $gateway;
    protected $accessToken;

    public function __construct()
    {
        $className = (new \ReflectionClass($this))->getShortName();
        $this->gateway = PaymentMethod::whereProvider($className)->firstOrFail();
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

        $this->accessToken = $accessTokenData['access_token'] ?? null;
        $this->gateway->credentials = [
            ... ($this->gateway->credentials ?? []),
            'access_token' => $this->accessToken,
        ];

        $this->gateway->save();

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
            'auth' => $accessTokenData,
            'payment' => $requestPayload
        ];
    }

    public function callback($transactionId)
    {
        $paymentConfig = $this->gateway->credentials; // its array contains 'client_id', 'secret', 'mode' etc.

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v2/checkout/orders/'. $transactionId .'/capture',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Prefer: return=representation',
            'PayPal-Request-Id: ' . uniqid(),
            'Authorization: Bearer ' . $paymentConfig['access_token']
        ),
        ));

        $response = curl_exec($curl);

        return json_decode($response, true);
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
        $basicToken = base64_encode($clientId . ':' . $secret);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v1/oauth2/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=client_credentials',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded',
                'Authorization: Basic ' . $basicToken
            ),
        ));

        $response = curl_exec($curl);

        return json_decode($response, true);
    }

    function createPayment($returnUrl, $cancelUrl, $amount, $currency){
        $curl = curl_init();

        $arr = [
            'intent' => 'CAPTURE',
            'payment_source' => [
                'paypal' => [
                    'experience_context' => [
                        "payment_method_preference" =>  "UNRESTRICTED",
                        "landing_page" => "LOGIN",
                        "shipping_preference" => "NO_SHIPPING",
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
            ],
            'payment_method' => [
                'payee_preferred' => 'UNRESTRICTED'
            ]
        ];

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-m.sandbox.paypal.com/v2/checkout/orders',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($arr),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Prefer: return=representation',
                'PayPal-Request-Id: ' . uniqid(),
                'Authorization: Bearer ' . $this->accessToken
            ),
        ));

        $response = curl_exec($curl);

        return json_decode($response, true);
    }
}
