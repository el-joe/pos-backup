<?php

namespace App\Enums;

enum ExpenseClaimStatusEnum: string
{
    case PENDING = 'pending';
    case SUBMITTED = 'submitted';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case PAID = 'paid';

    public function label(): string
    {
        return __('general.pages.hrm.statuses.' . $this->value);
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::PENDING, self::SUBMITTED => 'warning',
            self::APPROVED => 'success',
            self::REJECTED => 'danger',
            self::PAID => 'primary',
        };
    }
}
