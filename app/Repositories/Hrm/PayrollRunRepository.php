<?php

namespace App\Repositories\Hrm;

use App\Models\Tenant\PayrollRun;
use App\Repositories\BaseRepository;

class PayrollRunRepository extends BaseRepository
{
    public function __construct(PayrollRun $run)
    {
        $this->setInstance($run);
    }
}
