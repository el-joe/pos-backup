<?php

namespace App\Enums;

enum PayrollSlipLineTypeEnum: string
{
    case EARNING = 'earning';
    case DEDUCTION = 'deduction';

    public function label(): string
    {
        return __('general.pages.hrm.types.' . $this->value);
    }
}
