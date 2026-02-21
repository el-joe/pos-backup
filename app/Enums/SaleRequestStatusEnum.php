<?php

namespace App\Enums;

enum SaleRequestStatusEnum: string
{
    case DRAFT = 'draft';
    case SENT = 'sent';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case EXPIRED = 'expired';
    case CONVERTED = 'converted';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::SENT => 'Sent',
            self::ACCEPTED => 'Accepted',
            self::REJECTED => 'Rejected',
            self::EXPIRED => 'Expired',
            self::CONVERTED => 'Converted',
            self::CANCELED => 'Canceled',
        };
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::DRAFT => 'secondary',
            self::SENT => 'info',
            self::ACCEPTED => 'success',
            self::REJECTED => 'danger',
            self::EXPIRED => 'warning',
            self::CONVERTED => 'primary',
            self::CANCELED => 'dark',
        };
    }
}
