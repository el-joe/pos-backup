<?php

namespace App\Payments\Interfaces;

interface PaymentMethodInterface
{
    public function pay($data);

    public function capture($transactionId);

    public function refund($transactionId);
}
