<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isPengurus(): bool
    {
        return $this->role === UserRole::PENGURUS;
    }

    public function isVolunteer(): bool
    {
        return $this->role === UserRole::VOLUNTEER;
    }

    public function volunteer(): HasOne
    {
        return $this->hasOne(Volunteer::class);
    }

    public function approvedSwaps(): HasMany
    {
        return $this->hasMany(ScheduleSwap::class, 'approved_by');
    }

    public function createdHandovers(): HasMany
    {
        return $this->hasMany(Handover::class, 'created_by');
    }

    public function uploadedDocumentations(): HasMany
    {
        return $this->hasMany(Documentation::class, 'uploaded_by');
    }
}
