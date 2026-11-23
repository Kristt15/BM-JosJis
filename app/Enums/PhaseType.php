<?php

namespace App\Enums;

enum PhaseType: string
{
    case TEACHING = 'teaching';
    case CLOSING = 'closing';
    case SPECIAL = 'special';

    public function label(): string
    {
        return match ($this) {
            self::TEACHING => 'Fase Mengajar',
            self::CLOSING => 'Fase Penutupan',
            self::SPECIAL => 'Fase Khusus',
        };
    }
}
