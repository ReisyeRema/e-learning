<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TugasRequest extends FormRequest
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
            'materi_id' => 'required|exists:materi,id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,ppt,pptx|max:5120', // Maksimal 5MB
        ];
    }
    
    public function messages(): array
    {
        return [
            'materi_id.required' => 'Materi harus dipilih.',
            'materi_id.exists' => 'Materi yang dipilih tidak valid.',
            'judul.required' => 'Judul tugas harus diisi.',
            'judul.string' => 'Judul tugas harus berupa teks.',
            'judul.max' => 'Judul tugas tidak boleh lebih dari 255 karakter.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'file.file' => 'Berkas yang diunggah harus berupa file.',
            'file.mimes' => 'Format file tidak valid. Hanya diperbolehkan: pdf, doc, docx, ppt, pptx.',
            'file.max' => 'Ukuran file tidak boleh lebih dari 5MB.',
        ];
    }
}
