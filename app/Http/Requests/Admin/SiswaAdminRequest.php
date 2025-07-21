<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SiswaAdminRequest extends FormRequest
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
        $siswa = $this->route('siswa');
        $siswaId = $siswa ? $siswa->id : null;
        $userId = $siswa ? $siswa->user_id : null;

        return [
            // Validasi untuk user
            'username' => 'required|string|max:50|unique:users,username,' . $userId,
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:users,email,' . $userId,
            'password' => $this->isMethod('post') ? 'required|string|min:8|confirmed' : 'nullable|string|min:8|confirmed',
            'password_confirmation' => $this->isMethod('post') ? 'required_with:password' : 'nullable',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Validasi untuk siswa
            'nis' => 'nullable|string|max:10|regex:/^\d{1,10}$/|unique:siswa,nis,' . $siswaId,
            'tempat_lahir' => 'required|string|max:100',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-Laki,Perempuan',
            'alamat' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
        ];
    }


    public function messages(): array
    {
        return [
            // Pesan error untuk user
            'username.required' => 'Username wajib diisi.',
            'username.string' => 'Username harus berupa teks.',
            'username.max' => 'Username maksimal 50 karakter.',
            'username.unique' => 'Username sudah digunakan.',
            'name.required' => 'Nama wajib diisi.',
            'name.string' => 'Nama harus berupa teks.',
            'name.max' => 'Nama maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.max' => 'Email maksimal 100 karakter.',
            'email.unique' => 'Email sudah digunakan.',
            'password.required' => 'Password wajib diisi.',
            'password.string' => 'Password harus berupa teks.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required_with' => 'Konfirmasi password wajib diisi jika password diisi.',
            'foto.image' => 'Foto harus berupa gambar.',
            'foto.mimes' => 'Foto hanya boleh memiliki format: jpg, jpeg, png.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',

            // Pesan error untuk siswa
            'user_id.exists' => 'ID pengguna tidak valid.',
            'kelas_id.exists' => 'ID pengguna tidak valid.',
            'nis.max' => 'NIS maksimal 10 digit.',
            'nis.regex' => 'NIS hanya boleh terdiri dari angka.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'tempat_lahir.string' => 'Tempat lahir harus berupa teks.',
            'tempat_lahir.max' => 'Tempat lahir maksimal 100 karakter.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib diisi.',
            'jenis_kelamin.in' => 'Jenis kelamin harus salah satu dari: Laki-Laki, Perempuan.',
            'alamat.required' => 'Alamat wajib diisi.',
            'alamat.string' => 'Alamat harus berupa teks.',
            'alamat.max' => 'Alamat maksimal 255 karakter.',
        ];
    }
}
