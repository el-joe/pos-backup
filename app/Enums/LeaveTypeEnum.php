<?php

namespace App\Enums;

enum LeaveTypeEnum: string
{
    case ANNUAL = 'annual';
    case SICK = 'sick';
    case CASUAL = 'casual';
    case MATERNITY = 'maternity';
    case PATERNITY = 'paternity';
    case UNPAID = 'unpaid';
    case EMERGENCY = 'emergency';
    case COMPENSATORY = 'compensatory';
    case STUDY = 'study';
    case HAJJ = 'hajj';

    public function label(): string
    {
        return match($this) {
            self::ANNUAL => 'Annual Leave',
            self::SICK => 'Sick Leave',
            self::CASUAL => 'Casual Leave',
            self::MATERNITY => 'Maternity Leave',
            self::PATERNITY => 'Paternity Leave',
            self::UNPAID => 'Unpaid Leave',
            self::EMERGENCY => 'Emergency Leave',
            self::COMPENSATORY => 'Compensatory Leave',
            self::STUDY => 'Study Leave',
            self::HAJJ => 'Hajj Leave',
        };
    }

    public function labelAr(): string
    {
        return match($this) {
            self::ANNUAL => 'إجازة سنوية',
            self::SICK => 'إجازة مرضية',
            self::CASUAL => 'إجازة عارضة',
            self::MATERNITY => 'إجازة أمومة',
            self::PATERNITY => 'إجازة أبوة',
            self::UNPAID => 'إجازة بدون راتب',
            self::EMERGENCY => 'إجازة طارئة',
            self::COMPENSATORY => 'إجازة تعويضية',
            self::STUDY => 'إجازة دراسية',
            self::HAJJ => 'إجازة حج',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::ANNUAL => 'primary',
            self::SICK => 'warning',
            self::CASUAL => 'info',
            self::MATERNITY => 'success',
            self::PATERNITY => 'success',
            self::UNPAID => 'danger',
            self::EMERGENCY => 'danger',
            self::COMPENSATORY => 'secondary',
            self::STUDY => 'info',
            self::HAJJ => 'primary',
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
