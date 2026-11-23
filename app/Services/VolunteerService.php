<?php

namespace App\Services;

use App\Enums\UserRole;
use App\Enums\VolunteerStatus;
use App\Models\User;
use App\Models\Volunteer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VolunteerService
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return Volunteer::with('user')
            ->when(
                isset($filters['status']) && $filters['status'] !== '',
                fn($q) => $q->where('status', $filters['status'])
            )
            ->when(
                isset($filters['search']) && $filters['search'] !== '',
                fn($q) => $q->where(function ($query) use ($filters) {
                    $query->where('name', 'like', "%{$filters['search']}%")
                          ->orWhere('nim', 'like', "%{$filters['search']}%")
                          ->orWhere('major', 'like', "%{$filters['search']}%");
                })
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function create(array $data): Volunteer
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
                'role'     => UserRole::VOLUNTEER,
            ]);

            return Volunteer::create([
                'user_id'    => $user->id,
                'nim'        => $data['nim'],
                'name'       => $data['name'],
                'major'      => $data['jurusan'],
                'generation' => $data['angkatan'],
                'phone'      => $data['phone'] ?? null,
                'status'     => VolunteerStatus::ACTIVE,
            ]);
        });
    }

    public function update(Volunteer $volunteer, array $data): Volunteer
    {
        return DB::transaction(function () use ($volunteer, $data) {
            // Update user
            $volunteer->user->update([
                'name'  => $data['name'],
                'email' => $data['email'],
            ]);

            // Update volunteer profile
            $volunteer->update([
                'name'       => $data['name'],
                'nim'        => $data['nim'],
                'major'      => $data['jurusan'],
                'generation' => $data['angkatan'],
                'phone'      => $data['phone'] ?? null,
                'status'     => VolunteerStatus::from($data['status']),
            ]);

            return $volunteer->fresh();
        });
    }

    public function delete(Volunteer $volunteer): bool
    {
        return (bool) $volunteer->delete();
    }

    public function setAlumni(Volunteer $volunteer): Volunteer
    {
        $volunteer->update(['status' => VolunteerStatus::ALUMNI]);
        return $volunteer->fresh();
    }
}
