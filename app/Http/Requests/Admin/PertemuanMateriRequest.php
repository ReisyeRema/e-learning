<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PertemuanMateriRequest extends FormRequest
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
            'pembelajaran_id' => 'required|exists:pembelajaran,id',
            'pertemuan_id' => 'required|exists:pertemuan,id',
            'materi_id' => 'required|exists:materi,id',
        ];
    }

    public function messages(): array
    {
        return [
            'pembelajaran_id.required' => 'pembelajaran harus dipilih.',
            'pembelajaran_id.exists' => 'pembelajaran yang dipilih tidak valid.',
            'pertemuan_id.required' => 'pertemuan harus dipilih.',
            'pertemuan_id.exists' => 'pertemuan yang dipilih tidak valid.',
            'materi_id.required' => 'materi harus dipilih.',
            'materi_id.exists' => 'materi yang dipilih tidak valid.',
        ];
    }
}
