<?php

namespace App\Repositories;

use App\Models\Tenant\Brand;

class BrandRepository extends BaseRepository
{
    function __construct(Brand $brand)
    {
        $this->setInstance($brand);
    }
}
