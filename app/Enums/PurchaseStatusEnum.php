<?php

namespace App\Enums;

enum PurchaseStatusEnum : string
{
    case PENDING = 'pending';
    case PARTIAL_PAID = 'partial_paid';
    case FULL_PAID = 'full_paid';
    case REFUNDED = 'refunded';
    case CANCELED = 'canceled';

    function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::PARTIAL_PAID => 'Partial Paid',
            self::FULL_PAID => 'Full Paid',
            self::REFUNDED => 'Refunded',
            self::CANCELED => 'Canceled',
        };
    }
}
