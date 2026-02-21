<?php

namespace App\Repositories;

use App\Models\Tenant\StockTransfer;

class StockTransferRepository extends BaseRepository
{
    function __construct(StockTransfer $stock)
    {
        $this->setInstance($stock);
    }

}
