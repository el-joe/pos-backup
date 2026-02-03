<?php

namespace App\Enums;

enum PayslipStatusEnum: string
{
    case DRAFT = 'draft';
    case GENERATED = 'generated';
    case SENT = 'sent';
    case PAID = 'paid';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::GENERATED => 'Generated',
            self::SENT => 'Sent',
            self::PAID => 'Paid',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function labelAr(): string
    {
        return match($this) {
            self::DRAFT => 'مسودة',
            self::GENERATED => 'تم الإنشاء',
            self::SENT => 'تم الإرسال',
            self::PAID => 'تم الدفع',
            self::CANCELLED => 'ملغي',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'secondary',
            self::GENERATED => 'info',
            self::SENT => 'warning',
            self::PAID => 'success',
            self::CANCELLED => 'danger',
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
