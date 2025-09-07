<?php

namespace App\Repositories;

use App\Models\Tenant\Discount;

class DiscountRepository extends BaseRepository
{
    function __construct(Discount $discount)
    {
        $this->setInstance($discount);
    }
}
