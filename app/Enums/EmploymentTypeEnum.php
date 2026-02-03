<?php

namespace App\Enums;

enum EmploymentTypeEnum: string
{
    case FULL_TIME = 'full_time';
    case PART_TIME = 'part_time';
    case CONTRACT = 'contract';
    case TEMPORARY = 'temporary';
    case INTERN = 'intern';
    case FREELANCER = 'freelancer';

    public function label(): string
    {
        return match($this) {
            self::FULL_TIME => 'Full Time',
            self::PART_TIME => 'Part Time',
            self::CONTRACT => 'Contract',
            self::TEMPORARY => 'Temporary',
            self::INTERN => 'Intern',
            self::FREELANCER => 'Freelancer',
        };
    }

    public function labelAr(): string
    {
        return match($this) {
            self::FULL_TIME => 'دوام كامل',
            self::PART_TIME => 'دوام جزئي',
            self::CONTRACT => 'عقد',
            self::TEMPORARY => 'مؤقت',
            self::INTERN => 'متدرب',
            self::FREELANCER => 'مستقل',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::FULL_TIME => 'success',
            self::PART_TIME => 'info',
            self::CONTRACT => 'warning',
            self::TEMPORARY => 'secondary',
            self::INTERN => 'primary',
            self::FREELANCER => 'dark',
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
