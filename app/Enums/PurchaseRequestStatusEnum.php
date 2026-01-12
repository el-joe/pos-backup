<?php

namespace App\Enums;

enum PurchaseRequestStatusEnum: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case CONVERTED = 'converted';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::SUBMITTED => 'Submitted',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::CONVERTED => 'Converted',
            self::CANCELED => 'Canceled',
        };
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::DRAFT => 'secondary',
            self::SUBMITTED => 'info',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            self::CONVERTED => 'primary',
            self::CANCELED => 'dark',
        };
    }
}
