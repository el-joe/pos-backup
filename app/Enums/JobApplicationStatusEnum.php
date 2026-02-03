<?php

namespace App\Enums;

enum JobApplicationStatusEnum: string
{
    case APPLIED = 'applied';
    case SCREENING = 'screening';
    case INTERVIEW_SCHEDULED = 'interview_scheduled';
    case INTERVIEWED = 'interviewed';
    case OFFERED = 'offered';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case WITHDRAWN = 'withdrawn';
    case ONBOARDING = 'onboarding';
    case HIRED = 'hired';

    public function label(): string
    {
        return match($this) {
            self::APPLIED => 'Applied',
            self::SCREENING => 'Screening',
            self::INTERVIEW_SCHEDULED => 'Interview Scheduled',
            self::INTERVIEWED => 'Interviewed',
            self::OFFERED => 'Offered',
            self::ACCEPTED => 'Accepted',
            self::REJECTED => 'Rejected',
            self::WITHDRAWN => 'Withdrawn',
            self::ONBOARDING => 'Onboarding',
            self::HIRED => 'Hired',
        };
    }

    public function labelAr(): string
    {
        return match($this) {
            self::APPLIED => 'تم التقديم',
            self::SCREENING => 'فحص أولي',
            self::INTERVIEW_SCHEDULED => 'مقابلة محددة',
            self::INTERVIEWED => 'تمت المقابلة',
            self::OFFERED => 'تم العرض',
            self::ACCEPTED => 'مقبول',
            self::REJECTED => 'مرفوض',
            self::WITHDRAWN => 'منسحب',
            self::ONBOARDING => 'التوظيف',
            self::HIRED => 'تم التعيين',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::APPLIED => 'info',
            self::SCREENING => 'primary',
            self::INTERVIEW_SCHEDULED => 'warning',
            self::INTERVIEWED => 'info',
            self::OFFERED => 'success',
            self::ACCEPTED => 'success',
            self::REJECTED => 'danger',
            self::WITHDRAWN => 'secondary',
            self::ONBOARDING => 'primary',
            self::HIRED => 'success',
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
