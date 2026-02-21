<?php

namespace App\Repositories\Hrm;

use App\Models\Tenant\Designation;
use App\Repositories\BaseRepository;

class DesignationRepository extends BaseRepository
{
    public function __construct(Designation $designation)
    {
        $this->setInstance($designation);
    }
}
