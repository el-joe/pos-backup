<?php

namespace App\Repositories;

use App\Models\Tenant\Branch;

class BranchRepository extends BaseRepository
{
    function __construct(Branch $branch)
    {
        $this->setInstance($branch);
    }
}
