<?php

namespace App\Models;

use App\Enums\DocumentationType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['event_id', 'uploaded_by', 'type', 'file_path', 'caption'])]
class Documentation extends Model
{
    use HasFactory;

    protected $table = 'documentations';

    protected function casts(): array
    {
        return [
            'type' => DocumentationType::class,
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
