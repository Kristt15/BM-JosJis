<?php

namespace App\Http\Requests\People;

use Illuminate\Foundation\Http\FormRequest;

class StoreVolunteerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
            'nim'      => ['required', 'string', 'unique:volunteers,nim'],
            'jurusan'  => ['required', 'string', 'max:255'],
            'angkatan' => ['required', 'integer', 'min:2010', 'max:2030'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'bio'      => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'     => 'Nama Lengkap',
            'email'    => 'Email',
            'password' => 'Password',
            'nim'      => 'NIM',
            'jurusan'  => 'Jurusan',
            'angkatan' => 'Angkatan',
            'phone'    => 'Nomor Telepon',
            'bio'      => 'Bio',
        ];
    }
}
