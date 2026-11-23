<?php

namespace App\Enums;

enum HandoverType: string
{
    case KETUA = 'ketua';
    case SEKRETARIS = 'sekretaris';
    case KOORDINATOR = 'koordinator';

    public function label(): string
    {
        return match ($this) {
            self::KETUA => 'Ketua',
            self::SEKRETARIS => 'Sekretaris',
            self::KOORDINATOR => 'Koordinator',
        };
    }
}
