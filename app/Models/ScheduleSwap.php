<?php

namespace App\Models;

use App\Enums\SwapStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['requester_volunteer_id', 'target_volunteer_id', 'requester_event_id', 'target_event_id', 'approved_by', 'reason', 'status'])]
class ScheduleSwap extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => SwapStatus::class,
        ];
    }

    public function requesterVolunteer(): BelongsTo
    {
        return $this->belongsTo(Volunteer::class, 'requester_volunteer_id');
    }

    public function targetVolunteer(): BelongsTo
    {
        return $this->belongsTo(Volunteer::class, 'target_volunteer_id');
    }

    public function requesterEvent(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'requester_event_id');
    }

    public function targetEvent(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'target_event_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
