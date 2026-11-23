<?php

namespace App\Enums;

enum DocumentationType: string
{
    case PHOTO = 'photo';
    case VIDEO = 'video';

    public function label(): string
    {
        return match ($this) {
            self::PHOTO => 'Foto',
            self::VIDEO => 'Video',
        };
    }
}
