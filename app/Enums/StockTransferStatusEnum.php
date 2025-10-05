<?php

namespace App\Enums;

enum StockTransferStatusEnum : string
{
    case PENDING = 'pending';
    case IN_TRANSIT = 'in_transit';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';

    function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::IN_TRANSIT => 'In Transit',
            self::COMPLETED => 'Completed',
            self::CANCELLED => 'Cancelled',
        };
    }

    function colorClass(): string
    {
        return match($this) {
            self::PENDING => 'badge-warning',
            self::IN_TRANSIT => 'badge-info',
            self::COMPLETED => 'badge-success',
            self::CANCELLED => 'badge-danger',
        };
    }
}
