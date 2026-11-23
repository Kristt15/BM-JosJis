<?php

namespace App\Models;

use App\Enums\EvaluationType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['event_id', 'evaluator_volunteer_id', 'evaluated_volunteer_id', 'type', 'content'])]
class Evaluation extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => EvaluationType::class,
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(Volunteer::class, 'evaluator_volunteer_id');
    }

    public function evaluated(): BelongsTo
    {
        return $this->belongsTo(Volunteer::class, 'evaluated_volunteer_id');
    }
}
