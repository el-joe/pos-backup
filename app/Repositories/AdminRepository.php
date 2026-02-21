<?php

namespace App\Repositories;

use App\Models\Tenant\Admin;
use App\Models\Tenant\Branch;

class AdminRepository extends BaseRepository
{
    function __construct(Admin $admin)
    {
        $this->setInstance($admin);
    }
}
