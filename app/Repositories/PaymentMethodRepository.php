<?php

namespace App\Repositories;

use App\Models\Tenant\Discount;
use App\Models\Tenant\ExpenseCategory;
use App\Models\Tenant\PaymentMethod;

class PaymentMethodRepository extends BaseRepository
{
    function __construct(PaymentMethod $paymentMethod)
    {
        $this->setInstance($paymentMethod);
    }
}
