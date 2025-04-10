<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class)->ignore($user->id)],
        ];

        if ($user->hasRole('Guru')) {
            $rules += [
                'nip' => ['required', 'string', 'max:20'],
                'tempat_lahir' => ['required', 'string', 'max:255'],
                'tanggal_lahir' => ['required', 'date'],
                'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
                'alamat' => ['required', 'string', 'max:255'],
                'status' => ['nullable', 'string'],
            ];
        } elseif ($user->hasRole('Siswa')) {
            $rules += [
                // 'nis' => ['required', 'string', 'max:20'],
                // 'kelas_id' => ['required', 'exists:kelas,id'],
                'tempat_lahir' => ['required', 'string', 'max:255'],
                'tanggal_lahir' => ['required', 'date'],
                'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
                'alamat' => ['required', 'string', 'max:255'],
            ];
        }

        return $rules;
    }
}
