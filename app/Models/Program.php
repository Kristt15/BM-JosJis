<?php

namespace App\Models;

use App\Enums\ProgramStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'year', 'status', 'description', 'start_date', 'end_date'])]
class Program extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'year' => 'integer',
            'status' => ProgramStatus::class,
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function phases(): HasMany
    {
        return $this->hasMany(Phase::class);
    }

    public function handovers(): HasMany
    {
        return $this->hasMany(Handover::class);
    }
}
