<?php

namespace App\Enums;

enum EventType: string
{
    case REGULAR = 'regular';
    case SPECIAL = 'special';
    case AKSI = 'aksi';

    public function label(): string
    {
        return match ($this) {
            self::REGULAR => 'Pertemuan Rutin',
            self::SPECIAL => 'Kegiatan Khusus',
            self::AKSI => 'BM AKSI',
        };
    }
}
