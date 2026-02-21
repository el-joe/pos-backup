<?php

namespace App\Repositories\Hrm;

use App\Models\Tenant\PayrollSlip;
use App\Repositories\BaseRepository;

class PayrollSlipRepository extends BaseRepository
{
    public function __construct(PayrollSlip $slip)
    {
        $this->setInstance($slip);
    }
}
