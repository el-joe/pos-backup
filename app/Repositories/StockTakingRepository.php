<?php

namespace App\Repositories;

use App\Models\Tenant\StockTaking;

class StockTakingRepository extends BaseRepository
{
    function __construct(StockTaking $stockTaking)
    {
        $this->setInstance($stockTaking);
    }
}
