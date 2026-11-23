<?php

namespace App\Enums;

enum EvaluationType: string
{
    case INDIVIDUAL = 'individual';
    case GROUP = 'group';

    public function label(): string
    {
        return match ($this) {
            self::INDIVIDUAL => 'Individu',
            self::GROUP => 'Kelompok',
        };
    }
}
