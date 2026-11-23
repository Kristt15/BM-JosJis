<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'nickname', 'birth_date', 'gender', 'grade', 'school', 'address', 'notes'])]
class Child extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'children';

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function batches(): BelongsToMany
    {
        return $this->belongsToMany(Batch::class, 'batch_child')
            ->withTimestamps();
    }

    public function mentorAssignments(): HasMany
    {
        return $this->hasMany(MentorAssignment::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(ChildAttendance::class);
    }
}
