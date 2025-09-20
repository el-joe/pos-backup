<?php

namespace App\Repositories;

use App\Models\Tenant\Branch;
use App\Models\Tenant\Expense;

class ExpenseRepository extends BaseRepository
{
    function __construct(Expense $expense)
    {
        $this->setInstance($expense);
    }
}
