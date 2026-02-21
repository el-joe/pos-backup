<?php

namespace App\Repositories\Hrm;

use App\Models\Tenant\LeaveRequest;
use App\Repositories\BaseRepository;

class LeaveRequestRepository extends BaseRepository
{
    public function __construct(LeaveRequest $request)
    {
        $this->setInstance($request);
    }
}
