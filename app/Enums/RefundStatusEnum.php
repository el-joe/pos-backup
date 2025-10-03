<?php

namespace App\Enums;

enum RefundStatusEnum : string
{
    case NO_REFUND = 'no_refund';
    case PARTIAL_REFUND = 'partial_refund';
    case FULL_REFUND = 'full_refund';

    public function label(): string
    {
        return match($this) {
            self::NO_REFUND => 'No Refund',
            self::PARTIAL_REFUND => 'Partial Refund',
            self::FULL_REFUND => 'Full Refund',
        };
    }

    public function colorClass(): string
    {
        return match($this) {
            self::NO_REFUND => 'info',
            self::PARTIAL_REFUND => 'warning',
            self::FULL_REFUND => 'danger',
        };
    }
}
