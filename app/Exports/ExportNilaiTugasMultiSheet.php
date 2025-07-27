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
            $pertemuan = $pertemuanTugas->pertemuan;

            // Pastikan relasi tidak null
            $judul = $tugas->judul ?? 'Tanpa Judul';
            $namaKelas = $kelas->nama_kelas ?? 'Tanpa Kelas';
            $pertemuan = $pertemuan->judul ?? 'Tanpa Judul';

            $judulSheet = substr("{$judul} - {$pertemuan}", 0, 31); 

            $sheets[] = new ExportTugas($pertemuanTugas, $judulSheet);
        }

        return $sheets;
    }
}
