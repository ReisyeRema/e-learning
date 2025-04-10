<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class MateriRequest extends FormRequest
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
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file_path' => 'nullable|file|max:10240', 
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul materi harus diisi!',
            'judul.string' => 'Judul harus berupa teks!',
            'judul.max' => 'Judul tidak boleh lebih dari 255 karakter!',
            
            'deskripsi.string' => 'Deskripsi harus berupa teks!',

            'file_path.file' => 'File yang diunggah tidak valid!',
            'file_path.max' => 'Ukuran file tidak boleh lebih dari 10MB!',
        ];
    }
}
