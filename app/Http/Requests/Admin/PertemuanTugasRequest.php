<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PertemuanTugasRequest extends FormRequest
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
            'tugas_id' => 'required|exists:tugas,id',
            'deadline' => 'nullable|date|after:now'
        ];
    }

    public function messages(): array
    {
        return [
            'pembelajaran_id.required' => 'pembelajaran harus dipilih.',
            'pembelajaran_id.exists' => 'pembelajaran yang dipilih tidak valid.',
            'pertemuan_id.required' => 'pertemuan harus dipilih.',
            'pertemuan_id.exists' => 'pertemuan yang dipilih tidak valid.',
            'tugas_id.required' => 'tugas harus dipilih.',
            'tugas_id.exists' => 'tugas yang dipilih tidak valid.',
            'deadline.after' => 'Deadline harus lebih dari waktu sekarang.'
        ];
    }
}
