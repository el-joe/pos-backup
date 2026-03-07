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
            self::PENDING => __('general.pages.stock-transfers.statuses.pending'),
            self::IN_TRANSIT => __('general.pages.stock-transfers.statuses.in_transit'),
            self::COMPLETED => __('general.pages.stock-transfers.statuses.completed'),
            self::CANCELLED => __('general.pages.stock-transfers.statuses.cancelled'),
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
