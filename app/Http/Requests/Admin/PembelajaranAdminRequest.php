<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PembelajaranAdminRequest extends FormRequest
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
            'nama_mapel' => 'required|string',
            'guru_id' => 'required|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'kurikulum_id' => 'required|exists:kurikulum,id',
            'semester' => 'required|in:Genap,Ganjil',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // Max 10MB
        ];
    }

    /**
     * Pesan kesalahan dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'nama_mapel.required' => 'Nama Mapel harus diisi',
            'guru_id.required' => 'Guru harus dipilih.',
            'guru_id.exists' => 'Guru yang dipilih tidak valid.',
            'kelas_id.required' => 'Kelas harus dipilih.',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid.',
            'tahun_ajaran_id.required' => 'Tahun Ajaran harus dipilih.',
            'tahun_ajaran_id.exists' => 'Tahun Ajaran yang dipilih tidak valid.',
            'kurikulum_id.required' => 'Kurikulum harus dipilih.',
            'kurikulum_id.exists' => 'Kurikulum yang dipilih tidak valid.',
            'semester.required' => 'Jenis kelamin wajib diisi.',
            'semester.in' => 'Jenis kelamin harus salah satu dari: Genap, Ganjil.',
            'cover.image' => 'File harus berupa gambar.',
            'cover.mimes' => 'Format gambar harus jpeg, png, jpg, atau gif.',
            'cover.max' => 'Ukuran gambar maksimal 10 MB.',
        ];
    }
}
