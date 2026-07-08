<?php

namespace App\Enums;

enum AttendanceSheetStatusEnum: string
{
    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case APPROVED = 'approved';

    public function label(): string
    {
        return __('general.pages.hrm.statuses.' . $this->value);
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::DRAFT => 'secondary',
            self::SUBMITTED => 'warning',
            self::APPROVED => 'success',
        };
    }
}
