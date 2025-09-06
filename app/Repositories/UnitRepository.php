<?php

namespace App\Repositories;

use App\Models\Tenant\Unit;

class UnitRepository extends BaseRepository
{
    function __construct(Unit $unit)
    {
        $this->setInstance($unit);
    }
}
