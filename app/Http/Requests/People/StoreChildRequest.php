<?php

namespace App\Http\Requests\People;

use Illuminate\Foundation\Http\FormRequest;

class StoreChildRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'nickname'    => ['nullable', 'string', 'max:100'],
            'birth_date'  => ['nullable', 'date', 'before:today'],
            'gender'      => ['nullable', 'in:male,female'],
            'school_class'=> ['required', 'string', 'max:50'],
            'school'      => ['nullable', 'string', 'max:255'],
            'address'     => ['required', 'string'],
            'notes'       => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'         => 'Nama Anak',
            'nickname'     => 'Nama Panggilan',
            'birth_date'   => 'Tanggal Lahir',
            'gender'       => 'Jenis Kelamin',
            'school_class' => 'Kelas',
            'school'       => 'Sekolah',
            'address'      => 'Alamat',
            'notes'        => 'Catatan',
        ];
    }
}
