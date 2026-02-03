<?php

namespace App\Enums;

enum PerformanceRatingEnum: string
{
    case OUTSTANDING = 'outstanding';
    case EXCEEDS_EXPECTATIONS = 'exceeds_expectations';
    case MEETS_EXPECTATIONS = 'meets_expectations';
    case NEEDS_IMPROVEMENT = 'needs_improvement';
    case UNSATISFACTORY = 'unsatisfactory';

    public function label(): string
    {
        return match($this) {
            self::OUTSTANDING => 'Outstanding',
            self::EXCEEDS_EXPECTATIONS => 'Exceeds Expectations',
            self::MEETS_EXPECTATIONS => 'Meets Expectations',
            self::NEEDS_IMPROVEMENT => 'Needs Improvement',
            self::UNSATISFACTORY => 'Unsatisfactory',
        };
    }

    public function labelAr(): string
    {
        return match($this) {
            self::OUTSTANDING => 'متميز',
            self::EXCEEDS_EXPECTATIONS => 'يتجاوز التوقعات',
            self::MEETS_EXPECTATIONS => 'يلبي التوقعات',
            self::NEEDS_IMPROVEMENT => 'يحتاج تحسين',
            self::UNSATISFACTORY => 'غير مرضي',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::OUTSTANDING => 'success',
            self::EXCEEDS_EXPECTATIONS => 'primary',
            self::MEETS_EXPECTATIONS => 'info',
            self::NEEDS_IMPROVEMENT => 'warning',
            self::UNSATISFACTORY => 'danger',
        };
    }

    public function score(): int
    {
        return match($this) {
            self::OUTSTANDING => 5,
            self::EXCEEDS_EXPECTATIONS => 4,
            self::MEETS_EXPECTATIONS => 3,
            self::NEEDS_IMPROVEMENT => 2,
            self::UNSATISFACTORY => 1,
        };
    }

    public static function toArray(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
            'label_ar' => $case->labelAr(),
            'score' => $case->score(),
        ], self::cases());
    }
}
