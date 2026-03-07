<?php

namespace App\Enums;

enum EmployeeStatusEnum: string
{
    case ACTIVE = 'active';
    case SUSPENDED = 'suspended';
    case TERMINATED = 'terminated';

    public function label(): string
    {
        return __('general.pages.hrm.statuses.' . $this->value);
    }

    public function colorClass(): string
    {
        return match ($this) {
            self::ACTIVE => 'success',
            self::SUSPENDED => 'warning',
            self::TERMINATED => 'danger',
        };
    }
}
