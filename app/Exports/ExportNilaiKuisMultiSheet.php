<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Models\PertemuanKuis;

class ExportNilaiKuisMultiSheet implements WithMultipleSheets
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

        $kuisList = PertemuanKuis::with(['kuis', 'pembelajaran.kelas'])
            ->whereHas('pembelajaran', function ($query) {
                $query->where('guru_id', $this->guruId);
                if ($this->pembelajaranId) {
                    $query->where('id', $this->pembelajaranId);
                }
            })
            ->get();

        foreach ($kuisList as $pertemuanKuis) {
            $kuis = $pertemuanKuis->kuis;
            $pembelajaran = $pertemuanKuis->pembelajaran;
            $kelas = $pembelajaran->kelas;
            $pertemuan = $pertemuanKuis->pertemuan;

            // Pastikan relasi tidak null
            $judul = $kuis->judul ?? 'Tanpa Judul';
            $namaKelas = $kelas->nama_kelas ?? 'Tanpa Kelas';
            $pertemuan = $pertemuan->judul ?? 'Tanpa Judul';

            $judulSheet = substr("{$judul} - {$pertemuan}", 0, 31); // Batas Excel 31 karakter

            $sheets[] = new ExportNilai($pertemuanKuis, $judulSheet);
        }

        return $sheets;
    }
}
