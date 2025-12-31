<?php

namespace App\Payments\Services;

use App\Payments\Interfaces\PaymentMethodInterface;

class PaymentService
{
    protected PaymentMethodInterface $gateway;

    public function __construct(PaymentMethodInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    public function pay($data)
    {
        return $this->gateway->pay($data);
    }

    public function capture($transactionId)
    {
        return $this->gateway->capture($transactionId);
    }
}
