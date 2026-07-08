<?php

namespace App\Enums;

enum PayrollRunStatusEnum: string
{
    case DRAFT = 'draft';
    case APPROVED = 'approved';
    case PAID = 'paid';

    public function label(): string
    {
        return __('general.pages.hrm.statuses.' . $this->value);
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::DRAFT => 'secondary',
            self::APPROVED => 'success',
            self::PAID => 'primary',
        };
    }
}
