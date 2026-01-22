<?php

namespace App\Enums\Tenant;

enum ExpenseTypeEnum : string
{
    case NORMAL = 'normal';
    case PREPAID = 'prepaid';
    case ACCRUED = 'accrued';

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }

    public static function labels(): array
    {
        return [
            self::NORMAL->value => __('general.pages.expenses.types.normal'),
            self::PREPAID->value => __('general.pages.expenses.types.prepaid'),
            self::ACCRUED->value => __('general.pages.expenses.types.accrued'),
        ];
    }

    function label(): string
    {
        return match($this) {
            self::NORMAL => __('general.pages.expenses.types.normal'),
            self::PREPAID => __('general.pages.expenses.types.prepaid'),
            self::ACCRUED => __('general.pages.expenses.types.accrued'),
        };
    }
}
