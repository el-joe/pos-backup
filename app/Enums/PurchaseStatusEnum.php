<?php

namespace App\Enums;

enum PurchaseStatusEnum : string
{
    // const STATUS = ['requested','pending','received'];
    case REQUESTED = 'requested';
    case PENDING = 'pending';
    case RECEIVED = 'received';

    function label(): string {
        return match($this) {
            PurchaseStatusEnum::REQUESTED => 'Requested',
            PurchaseStatusEnum::PENDING => 'Pending',
            PurchaseStatusEnum::RECEIVED => 'Received',
        };
    }
}
