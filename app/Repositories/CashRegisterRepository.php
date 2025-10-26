<?php

namespace App\Repositories;

use App\Models\Tenant\CashRegister;

class CashRegisterRepository extends BaseRepository
{
    function __construct(CashRegister $cashRegister)
    {
        $this->setInstance($cashRegister);
    }
}
