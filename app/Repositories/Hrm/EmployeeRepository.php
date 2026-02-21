<?php

namespace App\Repositories\Hrm;

use App\Models\Tenant\Employee;
use App\Repositories\BaseRepository;

class EmployeeRepository extends BaseRepository
{
    public function __construct(Employee $employee)
    {
        $this->setInstance($employee);
    }
}
