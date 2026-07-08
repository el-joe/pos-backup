<?php

namespace App\Enums;

enum AttendanceLogStatusEnum: string
{
    case PRESENT = 'present';
    case ABSENT = 'absent';
    case LATE = 'late';

    public function label(): string
    {
        return __('general.pages.hrm.statuses.' . $this->value);
    }
}
