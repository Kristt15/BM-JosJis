<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case PRESENT = 'present';
    case ABSENT = 'absent';
    case LATE = 'late';
    case EXCUSED = 'excused';

    public function label(): string
    {
        return match ($this) {
            self::PRESENT => 'Hadir',
            self::ABSENT => 'Tidak Hadir',
            self::LATE => 'Terlambat',
            self::EXCUSED => 'Izin',
        };
    }
}
