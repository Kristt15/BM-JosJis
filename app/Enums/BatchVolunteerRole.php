<?php

namespace App\Enums;

enum BatchVolunteerRole: string
{
    case MENTOR = 'mentor';
    case ASSISTANT = 'assistant';

    public function label(): string
    {
        return match ($this) {
            self::MENTOR => 'Mentor',
            self::ASSISTANT => 'Asisten',
        };
    }
}
