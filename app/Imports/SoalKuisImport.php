<?php

namespace App\Imports;

use App\Models\SoalKuis;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SoalKuisImport implements ToCollection, WithHeadingRow
{
    protected $kuisId;

    public function __construct($kuisId)
    {
        $this->kuisId = $kuisId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Lewati jika kolom penting kosong
            if (empty($row['teks_soal']) || empty($row['type_soal'])) {
                continue;
            }

            $pilihanJawaban = null;
            if (!empty($row['pilihan_jawaban'])) {
                $decoded = json_decode($row['pilihan_jawaban'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $pilihanJawaban = $decoded;
                }
            }

            SoalKuis::create([
                'kuis_id'         => $this->kuisId,
                'teks_soal'       => $row['teks_soal'],
                'type_soal'       => $row['type_soal'],
                'pilihan_jawaban' => $pilihanJawaban,
                'jawaban_benar'   => $row['jawaban_benar'] ?? null,
            ]);
        }
    }

    public function headingRow(): int
    {
        return 1; // baris ke-1 adalah header
    }
}
