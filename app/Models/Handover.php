<?php

namespace App\Models;

use App\Enums\HandoverType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['program_id', 'created_by', 'type', 'title', 'content'])]
class Handover extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => HandoverType::class,
        ];
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
