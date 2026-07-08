<?php

namespace App\Enums;

enum AttendanceLogSourceEnum: string
{
    case EMPLOYEE = 'employee';
    case MANUAL = 'manual';
    case WEB = 'web';
    case MOBILE = 'mobile';
    case DEVICE = 'device';
    case BIOMETRIC = 'biometric';
    case SYSTEM = 'system';
    case API = 'api';

    public function label(): string
    {
        return __('general.pages.hrm.sources.' . $this->value);
    }
}
