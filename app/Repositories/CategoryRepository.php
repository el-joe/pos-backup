<?php

namespace App\Repositories;

use App\Models\Tenant\Category as TenantCategory;

class CategoryRepository extends BaseRepository
{
    function __construct(TenantCategory $category)
    {
        $this->setInstance($category);
    }
}
