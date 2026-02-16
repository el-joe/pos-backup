<?php

namespace App\Enums;

enum CheckDirectionEnum: string
{
    case RECEIVED = 'received';
    case ISSUED = 'issued';

    public function label(): string
    {
        return match ($this) {
            self::RECEIVED => 'Received',
            self::ISSUED => 'Issued',
        };
    }
}
