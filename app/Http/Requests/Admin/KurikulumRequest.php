<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class KurikulumRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan untuk membuat request ini.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Aturan validasi yang berlaku untuk request ini.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama_kurikulum' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Pesan kesalahan validasi dalam bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama_kurikulum.required' => 'Nama kurikulum wajib diisi.',
            'nama_kurikulum.string' => 'Nama kurikulum harus berupa teks.',
            'nama_kurikulum.max' => 'Nama kurikulum tidak boleh lebih dari 255 karakter.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'icon.image' => 'Ikon harus berupa file gambar.',
            'icon.mimes' => 'Ikon harus berformat: jpeg, png, jpg, atau gif.',
            'icon.max' => 'Ukuran ikon tidak boleh lebih dari 2MB.',
        ];
    }
}
