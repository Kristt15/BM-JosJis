<?php

namespace App\Enums;

enum VolunteerStatus: string
{
    case ACTIVE = 'active';
    case ALUMNI = 'alumni';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Aktif',
            self::ALUMNI => 'Alumni',
        };
    }
}
