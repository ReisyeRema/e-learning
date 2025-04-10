<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentRequest extends FormRequest
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
            'siswa_id' => 'required|exists:users,id',
            'pembelajaran_id' => 'required|exists:pembelajaran,id',
        ];
        
    }

    public function messages():array
    {
        return [
            'siswa_id.required' => 'Siswa harus dipilih.',
            'siswa_id.exists' => 'Siswa yang dipilih tidak valid.',
            'pembelajaran_id.required' => 'pembelajaran harus dipilih.',
            'pembelajaran_id.exists' => 'pembelajaran yang dipilih tidak valid.',
        ];

    }
}
