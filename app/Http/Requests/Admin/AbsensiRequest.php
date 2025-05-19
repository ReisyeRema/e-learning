<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AbsensiRequest extends FormRequest
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
            // 'pertemuan_id'     => ['required', 'exists:pertemuan,id'],
            // 'pembelajaran_id'  => ['required', 'exists:pembelajaran,id'],
            'tanggal'          => ['required', 'date'],
            'jam_mulai'        => ['required', 'date_format:H:i'],
            'jam_selesai'      => ['required', 'date_format:H:i', 'after_or_equal:jam_mulai'],
            'is_multisession'  => ['boolean'],
            'ulangi_pada'      => ['nullable', 'array'],
            'ulangi_pada.*'    => ['in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu'],
            'ulangi_sampai'    => ['nullable', 'date', 'after_or_equal:tanggal'],
            'gunakan_koordinat'  => ['boolean'],
        ];
        
    }


    public function messages(): array
    {
        return [
            // 'pertemuan_id.required' => 'Pertemuan wajib diisi.',
            // 'pertemuan_id.exists' => 'Pertemuan tidak valid.',
            // 'pembelajaran_id.required' => 'Pembelajaran wajib diisi.',
            // 'pembelajaran_id.exists' => 'Pembelajaran tidak valid.',
            'tanggal.required' => 'Tanggal wajib diisi.',
            'tanggal.date' => 'Format tanggal tidak valid.',
            'jam_mulai.required' => 'Jam mulai wajib diisi.',
            'jam_mulai.date_format' => 'Format jam mulai harus HH:MM.',
            'jam_selesai.required' => 'Jam selesai wajib diisi.',
            'jam_selesai.date_format' => 'Format jam selesai harus HH:MM.',
            'jam_selesai.after_or_equal' => 'Jam selesai harus sama atau setelah jam mulai.',
            'ulangi_pada.array' => 'Format ulangi_pada harus berupa array.',
            'ulangi_pada.*.in' => 'Hari dalam ulangi_pada harus berupa nama hari yang valid.',
            'ulangi_sampai.date' => 'Tanggal ulangi_sampai tidak valid.',
            'ulangi_sampai.after_or_equal' => 'Tanggal ulangi_sampai harus setelah atau sama dengan tanggal mulai.',
        ];
    }
}
