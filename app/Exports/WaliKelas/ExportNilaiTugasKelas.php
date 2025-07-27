<?php

namespace App\Exports\WaliKelas;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class ExportNilaiTugasKelas implements FromArray, WithTitle, WithStyles
{
    protected $pembelajaran;

    public function __construct($pembelajaran)
    {
        $this->pembelajaran = $pembelajaran;
    }

    public function array(): array
    {
        $siswaList = User::whereHas('enrollments', function ($query) {
            $query->where('pembelajaran_id', $this->pembelajaran->id);
        })->get();

        $tugasList = $this->pembelajaran->pertemuanTugas->pluck('tugas')->filter();

        // Header tabel
        $header = ['No', 'Nama Siswa', 'NIS'];
        foreach ($tugasList as $tugas) {
            $header[] = $tugas->judul ?? 'Tugas ' . $tugas->id;
        }
        $header[] = 'Rata-rata';

        // Body + kumpulkan nilai per kolom untuk hitung rata-rata kolom
        $rows = [];
        $nilaiKolom = array_fill(0, count($tugasList) + 1, []); 

        foreach ($siswaList as $index => $siswa) {
            $row = [$index + 1, $siswa->name, $siswa->siswa->nis ?? '-'];
            $nilaiSiswa = [];

            foreach ($tugasList as $tugasIndex => $tugas) {
                $skor = $tugas->submitTugas()
                    ->where('siswa_id', $siswa->id)
                    ->first()
                    ->skor ?? '-';

                $row[] = $skor;

                if (is_numeric($skor)) {
                    $nilaiSiswa[] = $skor;
                    $nilaiKolom[$tugasIndex][] = $skor;
                }
            }

            $rataRata = count($nilaiSiswa) > 0 ? round(array_sum($nilaiSiswa) / count($nilaiSiswa), 2) : '-';
            $row[] = $rataRata;

            if (is_numeric($rataRata)) {
                $nilaiKolom[count($tugasList)][] = $rataRata;
            }

            $rows[] = $row;
        }

        // Baris rata-rata kolom
        $rataRataRow = ['Rata-rata', '', ''];
        foreach ($nilaiKolom as $nilai) {
            $rataRataRow[] = count($nilai) > 0 ? round(array_sum($nilai) / count($nilai), 2) : '-';
        }

        // Judul dinamis di baris 1
        $judul = sprintf(
            'Rekapitulasi Nilai Tugas %s Kelas %s Tahun Ajaran %s',
            $this->pembelajaran->nama_mapel,
            $this->pembelajaran->kelas->nama_kelas,
            $this->pembelajaran->tahunAjaran->nama_tahun
        );

        $guru = $this->pembelajaran->guru->name ?? '-';
        $nuptk = $this->pembelajaran->guru->guru->nuptk ?? '-';
        $guruRow = ['Guru Pengampu: ' . $guru . "          NUPTK:" . $nuptk];


        // Gabungkan semua
        return array_merge(
            [[$judul]],     
            [$guruRow],
            [$header],      
            $rows,          
            [$rataRataRow]  
        );
    }

    public function title(): string
    {
        return $this->pembelajaran->nama_mapel;
    }

    public function styles(Worksheet $sheet)
    {
        $tugasCount = count($this->pembelajaran->pertemuanTugas->pluck('tugas')->filter());
        $siswaCount = User::whereHas('enrollments', function ($query) {
            $query->where('pembelajaran_id', $this->pembelajaran->id);
        })->count();

        $totalColumns = $tugasCount + 4; 
        $totalRows = $siswaCount + 4;    

        $endColumn = Coordinate::stringFromColumnIndex($totalColumns);

        // Merge judul (baris 1) dan nama guru (baris 2)
        $sheet->mergeCells("A1:{$endColumn}1");
        $sheet->mergeCells("A2:{$endColumn}2");

        return [
            1 => [ // Judul
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            2 => [ // Nama Guru
                'font' => ['italic' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ],
            3 => [ // Header
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EEEDEB'],
                ],
            ],
            $totalRows => [ // Baris rata-rata
                'font' => ['italic' => true, 'bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EEEDEB'],
                ],
            ],
            "A3:{$endColumn}{$totalRows}" => [ 
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }
}
