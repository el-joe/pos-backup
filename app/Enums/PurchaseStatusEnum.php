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

    function colorClass() {
        return match($this) {
            self::PENDING => 'warning',
            self::PARTIAL_PAID => 'info',
            self::FULL_PAID => 'success',
            self::REFUNDED => 'primary',
            self::CANCELED => 'danger',
        };
    }
}
