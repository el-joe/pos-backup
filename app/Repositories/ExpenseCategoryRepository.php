<?php

namespace App\Repositories;

use App\Models\Tenant\Discount;
use App\Models\Tenant\ExpenseCategory;

class ExpenseCategoryRepository extends BaseRepository
{
    function __construct(ExpenseCategory $expenseCategory)
    {
        $this->setInstance($expenseCategory);
    }
}
