<?php

namespace App\Payments\Interfaces;

interface PaymentMethodInterface
{
    public function pay($data);

    public function callback($transactionId);

    public function refund($transactionId);
}
