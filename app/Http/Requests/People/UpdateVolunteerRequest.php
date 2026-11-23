<?php

namespace App\Http\Requests\People;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVolunteerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $volunteer = $this->route('volunteer');
        $userId    = $volunteer?->user_id;

        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'nim'      => ['required', 'string', Rule::unique('volunteers', 'nim')->ignore($volunteer?->id)],
            'jurusan'  => ['required', 'string', 'max:255'],
            'angkatan' => ['required', 'integer', 'min:2010', 'max:2030'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'bio'      => ['nullable', 'string'],
            'status'   => ['required', 'in:active,alumni'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => 'Nama Lengkap',
            'email'    => 'Email',
            'nim'      => 'NIM',
            'jurusan'  => 'Jurusan',
            'angkatan' => 'Angkatan',
            'phone'    => 'Nomor Telepon',
            'bio'      => 'Bio',
            'status'   => 'Status',
        ];
    }
}
