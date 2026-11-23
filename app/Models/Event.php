<?php

namespace App\Models;

use App\Enums\EventType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['batch_id', 'name', 'type', 'event_date', 'location', 'description'])]
class Event extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => EventType::class,
            'event_date' => 'datetime',
        ];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function childAttendances(): HasMany
    {
        return $this->hasMany(ChildAttendance::class);
    }

    public function requestedSwaps(): HasMany
    {
        return $this->hasMany(ScheduleSwap::class, 'requester_event_id');
    }

    public function targetSwaps(): HasMany
    {
        return $this->hasMany(ScheduleSwap::class, 'target_event_id');
    }

    public function teachingNotes(): HasMany
    {
        return $this->hasMany(TeachingNote::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function documentations(): HasMany
    {
        return $this->hasMany(Documentation::class);
    }
}
