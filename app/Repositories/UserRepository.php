<?php

namespace App\Repositories;

use App\Models\Tenant\User;

class UserRepository extends BaseRepository
{
    function __construct(User $user)
    {
        $this->setInstance($user);
    }
}
