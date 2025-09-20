<?php

namespace App\Repositories;

use App\Models\Tenant\Branch;
use App\Models\Tenant\Stock;

class StockRepository extends BaseRepository
{
    function __construct(Stock $stock)
    {
        $this->setInstance($stock);
    }
}
