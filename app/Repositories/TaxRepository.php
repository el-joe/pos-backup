<?php

namespace App\Repositories;

use App\Models\Tenant\Tax;

class TaxRepository extends BaseRepository
{
    function __construct(Tax $tax)
    {
        $this->setInstance($tax);
    }
}
