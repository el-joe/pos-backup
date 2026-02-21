<?php

namespace App\Repositories;

use App\Models\Tenant\Purchase;

class PurchaseRepository extends BaseRepository
{
    function __construct(Purchase $purchase)
    {
        $this->setInstance($purchase);
    }
}
