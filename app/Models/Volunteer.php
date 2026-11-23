<?php

namespace App\Models;

use App\Enums\VolunteerStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'nim', 'name', 'major', 'generation', 'phone', 'status'])]
class Volunteer extends Model
{
    use HasFactory, SoftDeletes;

    protected function casts(): array
    {
        return [
            'status' => VolunteerStatus::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function batches(): BelongsToMany
    {
        return $this->belongsToMany(Batch::class, 'batch_volunteer')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function mentorAssignments(): HasMany
    {
        return $this->hasMany(MentorAssignment::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function requestedSwaps(): HasMany
    {
        return $this->hasMany(ScheduleSwap::class, 'requester_volunteer_id');
    }

    public function targetSwaps(): HasMany
    {
        return $this->hasMany(ScheduleSwap::class, 'target_volunteer_id');
    }

    public function teachingNotes(): HasMany
    {
        return $this->hasMany(TeachingNote::class);
    }

    public function givenEvaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'evaluator_volunteer_id');
    }

    public function receivedEvaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class, 'evaluated_volunteer_id');
    }
}
