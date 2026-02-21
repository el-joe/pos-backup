<?php

namespace App\Enums;

enum CheckStatusEnum: string
{
    case UNDER_COLLECTION = 'under_collection';
    case COLLECTED = 'collected';
    case BOUNCED = 'bounced';

    case ISSUED = 'issued';
    case CLEARED = 'cleared';

    public function label(): string
    {
        return match ($this) {
            self::UNDER_COLLECTION => 'Under Collection',
            self::COLLECTED => 'Collected',
            self::BOUNCED => 'Bounced',
            self::ISSUED => 'Issued',
            self::CLEARED => 'Cleared',
        };
    }
}
