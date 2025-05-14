<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class DetailAbsensiRequest extends FormRequest
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
            'absensi_id' => 'required|exists:absensi,id',
            'siswa_id' => 'required|exists:users,id',
            'keterangan' => 'nullable|in:Hadir,Izin,Sakit,Alfa',
        ];
    }


    public function messages(): array
    {
        return [
            'absensi_id.required' => 'Absensi wajib diisi.',
            'absensi_id.exists' => 'Absensi tidak valid.',
            'siswa_id.required' => 'Enrollment siswa wajib diisi.',
            'siswa_id.exists' => 'Enrollment tidak valid.',
            'keterangan.in' => 'Keterangan harus salah satu dari: Hadir, Izin, Sakit, Alfa.',
        ];
    }
}
