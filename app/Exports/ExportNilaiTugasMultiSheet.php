<?php

namespace App\Exports;

use App\Models\PertemuanTugas;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExportNilaiTugasMultiSheet implements WithMultipleSheets
{
    protected $guruId;
    protected $pembelajaranId;

    public function __construct($guruId, $pembelajaranId = null)
    {
        $this->guruId = $guruId;
        $this->pembelajaranId = $pembelajaranId;
    }

    public function sheets(): array
    {
        $sheets = [];

        $tugasList = PertemuanTugas::with(['tugas', 'pembelajaran.kelas'])
            ->whereHas('pembelajaran', function ($query) {
                $query->where('guru_id', $this->guruId);
                if ($this->pembelajaranId) {
                    $query->where('id', $this->pembelajaranId);
                }
            })
            ->get();

        foreach ($tugasList as $pertemuanTugas) {
            $tugas = $pertemuanTugas->tugas;
            $pembelajaran = $pertemuanTugas->pembelajaran;
            $kelas = $pembelajaran->kelas;

            // Pastikan relasi tidak null
            $judul = $tugas->judul ?? 'Tanpa Judul';
            $namaKelas = $kelas->nama_kelas ?? 'Tanpa Kelas';

            $judulSheet = substr("{$judul} - {$namaKelas}", 0, 31); // Batas Excel 31 karakter

            $sheets[] = new ExportTugas($pertemuanTugas, $judulSheet);
        }

        return $sheets;
    }
}
