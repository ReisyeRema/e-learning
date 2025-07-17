<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class WaliKelasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'guru_id' => 'required|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ];
    }

    public function messages(): array
    {
        return [
            'guru_id.required' => 'Guru harus dipilih.',
            'guru_id.exists' => 'Guru yang dipilih tidak valid.',
            'kelas_id.required' => 'Kelas harus dipilih.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',
            'tahun_ajaran_id.required' => 'Tahun Ajaran harus dipilih.',
            'tahun_ajaran_id.exists' => 'Tahun Ajaran yang dipilih tidak valid.',
        ];
    }
}
