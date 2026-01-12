<?php

namespace App\Repositories;

use App\Models\Tenant\PurchaseRequest;

class PurchaseRequestRepository extends BaseRepository
{
    public function __construct(PurchaseRequest $request)
    {
        $this->setInstance($request);
    }
}
