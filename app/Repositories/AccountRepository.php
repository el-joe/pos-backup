<?php

namespace App\Repositories;

use App\Models\Tenant\Account;

class AccountRepository extends BaseRepository
{
    function __construct(Account $account)
    {
        $this->setInstance($account);
    }
}
