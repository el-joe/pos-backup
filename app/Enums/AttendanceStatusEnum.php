<?php

namespace App\Enums;

enum AttendanceStatusEnum: string
{
    case PRESENT = 'present';
    case ABSENT = 'absent';
    case LATE = 'late';
    case HALF_DAY = 'half_day';
    case ON_LEAVE = 'on_leave';
    case HOLIDAY = 'holiday';
    case WEEKEND = 'weekend';

    public function label(): string
    {
        return match($this) {
            self::PRESENT => 'Present',
            self::ABSENT => 'Absent',
            self::LATE => 'Late',
            self::HALF_DAY => 'Half Day',
            self::ON_LEAVE => 'On Leave',
            self::HOLIDAY => 'Holiday',
            self::WEEKEND => 'Weekend',
        };
    }

    public function labelAr(): string
    {
        return match($this) {
            self::PRESENT => 'حضور',
            self::ABSENT => 'غياب',
            self::LATE => 'تأخير',
            self::HALF_DAY => 'نصف يوم',
            self::ON_LEAVE => 'في إجازة',
            self::HOLIDAY => 'عطلة رسمية',
            self::WEEKEND => 'نهاية الأسبوع',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PRESENT => 'success',
            self::ABSENT => 'danger',
            self::LATE => 'warning',
            self::HALF_DAY => 'info',
            self::ON_LEAVE => 'primary',
            self::HOLIDAY => 'secondary',
            self::WEEKEND => 'secondary',
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
