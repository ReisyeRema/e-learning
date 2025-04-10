<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SoalKuisRequest extends FormRequest
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
            'kuis_id' => 'required|exists:kuis,id',
            'teks_soal' => 'required|string|max:255',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'type_soal' => ['required', Rule::in(['Objective', 'Essay', 'TrueFalse'])],
            'pilihan_jawaban' => [
                'nullable',
                'array', // Ubah dari 'json' ke 'array'
                function ($attribute, $value, $fail) {
                    if ($this->type_soal === 'Objective' && empty($value)) {
                        $fail('Pilihan jawaban wajib diisi untuk soal Objective.');
                    } elseif ($this->type_soal !== 'Objective' && !empty($value)) {
                        $fail('Pilihan jawaban hanya boleh diisi untuk soal Objective.');
                    }
                }
            ],

            'jawaban_benar' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if ($this->type_soal === 'Objective' && !in_array($value, ['A', 'B', 'C', 'D'])) {
                        $fail('Jawaban benar harus berupa A, B, C, atau D untuk soal Objective.');
                    } elseif ($this->type_soal === 'TrueFalse' && !in_array(strtolower($value), ['true', 'false'])) {
                        $fail('Jawaban benar harus "true" atau "false" untuk soal TrueFalse.');
                    }
                }
            ],
            'skor' => 'nullable|integer|min:0'
        ];
    }

    public function messages(): array
    {
        return [
            'kuis_id.required' => 'Kuis harus dipilih.',
            'kuis_id.exists' => 'Kuis yang dipilih tidak valid.',
            'teks_soal.required' => 'Teks soal tidak boleh kosong.',
            'teks_soal.string' => 'Teks soal harus berupa teks.',
            'teks_soal.max' => 'Teks soal tidak boleh lebih dari 255 karakter.',
            'gambar.image' => 'Gambar harus berupa gambar.',
            'gambar.mimes' => 'Gambar hanya boleh memiliki format: jpg, jpeg, png.',
            'gambar.max' => 'Ukuran Gambar maksimal 2 MB.',
            'type_soal.required' => 'Tipe soal harus dipilih.',
            'type_soal.in' => 'Tipe soal harus salah satu dari: Objective, Essay, atau TrueFalse.',
            'pilihan_jawaban.json' => 'Pilihan jawaban harus dalam format JSON yang valid.',
            'jawaban_benar.required' => 'Jawaban benar tidak boleh kosong.',
            'jawaban_benar.string' => 'Jawaban benar harus berupa teks.',
            'skor.integer' => 'Skor harus berupa angka.',
            'skor.min' => 'Skor tidak boleh kurang dari 0.'
        ];
    }
}
