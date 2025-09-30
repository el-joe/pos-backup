<?php

namespace App\Repositories;

use App\Models\Tenant\Purchase;
use App\Models\Tenant\Sale;

class SellRepository extends BaseRepository
{
    function __construct(Sale $sale)
    {
        $this->setInstance($sale);
    }
}
