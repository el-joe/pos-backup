<?php

namespace App\Payments\Providers;

use App\Models\PaymentMethod;
use App\Payments\Interfaces\PaymentMethodInterface;

class Paypal implements PaymentMethodInterface
{
    protected $gateway;

    public function __construct()
    {
        $className = (new \ReflectionClass($this))->getShortName();
        $this->gateway = PaymentMethod::whereProvider($className)->firstOrFail();
    }

    public function pay($data)
    {
        $paymentConfig = $this->gateway->credentials; // its array contains 'client_id', 'secret', 'mode' etc.

        // lets imagine we are processing payment via PayPal API and got a response

        $details = [
            'amount' => $data['amount'],
            'metadata' => $data
        ];

        $transactionId = base64_encode(json_encode($details));
        $details['transaction_id'] = $transactionId;

        $details['redirect_url'] = route('payment.callback', ['q' => $transactionId]);

        return $details;
    }

    public function callback($transactionId)
    {
        $paymentConfig = $this->gateway->credentials; // its array contains 'client_id', 'secret', 'mode' etc.

        $details = json_decode(base64_decode($transactionId), true);
        // Simulate fetching transaction details from PayPal
        return [
            'status' => 'success',
            'message' => 'Payment successful via PaypalPayment',
            'transaction_id' => $transactionId,
            'metadata' => $details
        ];
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
}
