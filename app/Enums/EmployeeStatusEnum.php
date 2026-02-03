<?php

namespace App\Enums;

enum EmployeeStatusEnum: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case ON_LEAVE = 'on_leave';
    case PROBATION = 'probation';
    case SUSPENDED = 'suspended';
    case TERMINATED = 'terminated';
    case RESIGNED = 'resigned';

    public function label(): string
    {
        return match($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::ON_LEAVE => 'On Leave',
            self::PROBATION => 'Probation',
            self::SUSPENDED => 'Suspended',
            self::TERMINATED => 'Terminated',
            self::RESIGNED => 'Resigned',
        };
    }

    public function labelAr(): string
    {
        return match($this) {
            self::ACTIVE => 'نشط',
            self::INACTIVE => 'غير نشط',
            self::ON_LEAVE => 'في إجازة',
            self::PROBATION => 'تحت الاختبار',
            self::SUSPENDED => 'موقوف',
            self::TERMINATED => 'منتهي الخدمة',
            self::RESIGNED => 'مستقيل',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ACTIVE => 'success',
            self::INACTIVE => 'secondary',
            self::ON_LEAVE => 'warning',
            self::PROBATION => 'info',
            self::SUSPENDED => 'danger',
            self::TERMINATED => 'dark',
            self::RESIGNED => 'secondary',
        };
    }

    public static function toArray(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
            'label_ar' => $case->labelAr(),
        ], self::cases());
    }
}
