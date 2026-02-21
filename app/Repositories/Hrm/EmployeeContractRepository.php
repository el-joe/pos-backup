<?php

namespace App\Repositories\Hrm;

use App\Models\Tenant\EmployeeContract;
use App\Repositories\BaseRepository;

class EmployeeContractRepository extends BaseRepository
{
    public function __construct(EmployeeContract $contract)
    {
        $this->setInstance($contract);
    }
}
