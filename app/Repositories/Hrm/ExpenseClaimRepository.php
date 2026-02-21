<?php

namespace App\Repositories\Hrm;

use App\Models\Tenant\ExpenseClaim;
use App\Repositories\BaseRepository;

class ExpenseClaimRepository extends BaseRepository
{
    public function __construct(ExpenseClaim $claim)
    {
        $this->setInstance($claim);
    }
}
