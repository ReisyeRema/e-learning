<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PertemuanKuisRequest extends FormRequest
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
            'kuis_id' => 'required|exists:kuis,id',
            'deadline' => 'nullable|date|after:now',
            'token' => 'nullable|string|unique:pertemuan_kuis,token',
        ];
    }

    public function messages(): array
    {
        return [
            'pembelajaran_id.required' => 'pembelajaran harus dipilih.',
            'pembelajaran_id.exists' => 'pembelajaran yang dipilih tidak valid.',
            'pertemuan_id.required' => 'pertemuan harus dipilih.',
            'pertemuan_id.exists' => 'pertemuan yang dipilih tidak valid.',
            'kuis_id.required' => 'kuis harus dipilih.',
            'kuis_id.exists' => 'kuis yang dipilih tidak valid.',
            'deadline.after' => 'Deadline harus lebih dari waktu sekarang.',
        ];
    }
}
