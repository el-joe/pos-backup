<?php

namespace App\Enums;

enum SaleStatusEnum : string
{
    case PENDING = 'pending';
    case PARTIAL_PAID = 'partial_paid';
    case FULL_PAID = 'full_paid';
    case REFUNDED = 'refunded';
    case CANCELLED = 'cancelled';

    function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::PARTIAL_PAID => 'Partial Paid',
            self::FULL_PAID => 'Full Paid',
            self::REFUNDED => 'Refunded',
            self::CANCELLED => 'Cancelled',
        };
    }

    function colorClass() {
        return match($this) {
            self::PENDING => 'warning',
            self::PARTIAL_PAID => 'info',
            self::FULL_PAID => 'success',
            self::REFUNDED => 'primary',
            self::CANCELLED => 'danger',
        };
    }
}
