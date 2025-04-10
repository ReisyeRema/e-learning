<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class KuisRequest extends FormRequest
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
            'materi_id' => 'nullable|required_if:kategori,Kuis|exists:materi,id',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|in:Kuis,Ujian Akhir,Ujian Mid',
        ];
        
    }

    public function messages():array
    {
        return [
            'materi_id.required_if' => 'Materi harus dipilih jika kategori adalah Kuis.',
            'materi_id.exists' => 'Materi yang dipilih tidak valid.',
            'judul.required' => 'Judul materi harus diisi!',
            'judul.string' => 'Judul harus berupa teks!',
            'judul.max' => 'Judul tidak boleh lebih dari 255 karakter!',
            'kategori.required' => 'Kategori Kuis wajib diisi.',
            'kategori.in' => 'Kategori Kuis harus salah satu dari: kuis,Ujian Akhir,Ujian Mid.',

        ];

    }
}
