<?php

namespace App\Http\Requests\Siswa;

use Illuminate\Foundation\Http\FormRequest;

class SubmitTugasRequest extends FormRequest
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
            'tugas_id' => 'required|exists:tugas,id',
            'siswa_id' => 'required|exists:users,id',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
            'url' => 'nullable|url',
            'mime_type' => 'nullable|string',
            'file_size' => 'nullable|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'tugas_id.required' => 'Tugas harus dipilih.',
            'tugas_id.exists' => 'Tugas yang dipilih tidak valid.',
            'siswa_id.required' => 'Siswa harus diisi.',
            'siswa_id.exists' => 'Siswa yang dipilih tidak valid.',
            'file_path.file' => 'File yang diunggah harus berupa berkas.',
            'file_path.mimes' => 'Format file harus PDF, DOC, DOCX, JPG, atau PNG.',
            'file_path.max' => 'Ukuran file maksimal adalah 2MB.',
            'url.url' => 'URL yang dimasukkan tidak valid.',
        ];
    }
}
