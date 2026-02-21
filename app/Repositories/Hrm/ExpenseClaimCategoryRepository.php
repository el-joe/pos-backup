<?php

namespace App\Repositories\Hrm;

use App\Models\Tenant\ExpenseClaimCategory;
use App\Repositories\BaseRepository;

class ExpenseClaimCategoryRepository extends BaseRepository
{
    public function __construct(ExpenseClaimCategory $category)
    {
        $this->setInstance($category);
    }
}
