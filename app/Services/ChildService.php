<?php

namespace App\Services;

use App\Models\Child;
use Illuminate\Pagination\LengthAwarePaginator;

class ChildService
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return Child::with('batches')
            ->when(
                isset($filters['search']) && $filters['search'] !== '',
                fn($q) => $q->where(function ($query) use ($filters) {
                    $query->where('name', 'like', "%{$filters['search']}%")
                          ->orWhere('grade', 'like', "%{$filters['search']}%");
                })
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();
    }

    public function create(array $data): Child
    {
        return Child::create([
            'name'       => $data['name'],
            'nickname'   => $data['nickname'] ?? null,
            'birth_date' => $data['birth_date'] ?? null,
            'gender'     => $data['gender'] ?? null,
            'grade'      => $data['school_class'],
            'school'     => $data['school'] ?? null,
            'address'    => $data['address'],
            'notes'      => $data['notes'] ?? null,
        ]);
    }

    public function update(Child $child, array $data): Child
    {
        $child->update([
            'name'       => $data['name'],
            'nickname'   => $data['nickname'] ?? null,
            'birth_date' => $data['birth_date'] ?? null,
            'gender'     => $data['gender'] ?? null,
            'grade'      => $data['school_class'],
            'school'     => $data['school'] ?? null,
            'address'    => $data['address'],
            'notes'      => $data['notes'] ?? null,
        ]);

        return $child->fresh();
    }

    public function delete(Child $child): bool
    {
        return (bool) $child->delete();
    }
}
