<?php

namespace App\Repositories;

use App\Models\Tenant\SaleRequest;

class SaleRequestRepository extends BaseRepository
{
    public function __construct(SaleRequest $request)
    {
        $this->setInstance($request);
    }
}
