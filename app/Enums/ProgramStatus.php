<?php

namespace App\Enums;

enum ProgramStatus: string
{
    case PLANNING = 'planning';
    case ACTIVE = 'active';
    case COMPLETED = 'completed';

    public function label(): string
    {
        return match ($this) {
            self::PLANNING => 'Perencanaan',
            self::ACTIVE => 'Aktif',
            self::COMPLETED => 'Selesai',
        };
    }
}
