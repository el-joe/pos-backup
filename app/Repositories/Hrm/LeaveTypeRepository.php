<?php

namespace App\Repositories\Hrm;

use App\Models\Tenant\LeaveType;
use App\Repositories\BaseRepository;

class LeaveTypeRepository extends BaseRepository
{
    public function __construct(LeaveType $type)
    {
        $this->setInstance($type);
    }
}
