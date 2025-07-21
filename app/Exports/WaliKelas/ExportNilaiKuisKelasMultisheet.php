<?php

namespace App\Exports\WaliKelas;

use App\Models\Pembelajaran;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportNilaiKuisKelasMultisheet implements WithMultipleSheets
{
    protected $semester;
    protected $kelasId;
    protected $tahunAjaranId;

    public function __construct($semester, $kelasId, $tahunAjaranId)
    {
        $this->semester = $semester;
        $this->kelasId = $kelasId;
        $this->tahunAjaranId = $tahunAjaranId;
    }

    public function sheets(): array
    {
        $sheets = [];

        $pembelajarans = Pembelajaran::with(['kelas', 'guru'])
            ->where('semester', $this->semester)
            ->where('kelas_id', $this->kelasId)
            ->where('tahun_ajaran_id', $this->tahunAjaranId)
            ->get();

        foreach ($pembelajarans as $pembelajaran) {
            $sheets[] = new ExportNilaiKuisKelas($pembelajaran);
        }

        return $sheets;
    }
}
