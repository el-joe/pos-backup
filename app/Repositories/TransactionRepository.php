<?php

namespace App\Repositories;

use App\Models\Tenant\Branch;
use App\Models\Tenant\Transaction;

class TransactionRepository extends BaseRepository
{
    function __construct(Transaction $transaction)
    {
        $this->setInstance($transaction);
    }
}
