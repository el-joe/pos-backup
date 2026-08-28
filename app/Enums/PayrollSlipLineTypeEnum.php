<?php

namespace App\Enums;

enum PayrollSlipLineTypeEnum: string
{
    case EARNING = 'earning';
    case DEDUCTION = 'deduction';
    case BASIC = 'basic';
    case OVERTIME = 'overtime';
    case BONUS = 'bonus';
    case ALLOWANCE = 'allowance';
    case UNPAID_LEAVE_DEDUCTION = 'unpaid_leave_deduction';
    case ABSENCE_DEDUCTION = 'absence_deduction';

    public function label(): string
    {
        return __('general.pages.hrm.types.' . $this->value);
    }
}
