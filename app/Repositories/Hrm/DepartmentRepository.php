<?php

namespace App\Repositories\Hrm;

use App\Models\Tenant\Department;
use App\Repositories\BaseRepository;

class DepartmentRepository extends BaseRepository
{
    public function __construct(Department $department)
    {
        $this->setInstance($department);
    }
}
