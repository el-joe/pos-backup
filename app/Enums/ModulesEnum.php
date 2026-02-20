<?php

namespace App\Enums;

enum ModulesEnum: string
{
    case POS = 'pos';
    case HRM = 'hrm';
    case BOOKING = 'booking';

    function label(): string
    {
        return match ($this) {
            self::POS => app()->getLocale() === 'ar' ? 'نقطة البيع' : 'POS System',
            self::HRM => app()->getLocale() === 'ar' ? 'نظام إدارة الموارد البشرية' : 'HRM System',
            self::BOOKING => app()->getLocale() === 'ar' ? 'نظام الحجز' : 'Booking System',
        };
    }
}
