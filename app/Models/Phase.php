<?php

namespace App\Models;

use App\Enums\PhaseType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['program_id', 'name', 'type', 'sequence', 'start_date', 'end_date'])]
class Phase extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => PhaseType::class,
            'sequence' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }
}
