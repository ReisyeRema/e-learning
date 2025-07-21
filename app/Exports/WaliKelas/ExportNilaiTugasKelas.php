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
        $header = ['No', 'Nama Siswa'];
        foreach ($tugasList as $tugas) {
            $header[] = $tugas->judul ?? 'Tugas ' . $tugas->id;
        }
        $header[] = 'Rata-rata';

        // Body + kumpulkan nilai per kolom untuk hitung rata-rata kolom
        $rows = [];
        $nilaiKolom = array_fill(0, count($tugasList) + 1, []); // index 0..n-1: tugas, index n: rata-rata per siswa

        foreach ($siswaList as $index => $siswa) {
            $row = [$index + 1, $siswa->name];
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
        $rataRataRow = ['Rata-rata', ''];
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

        // Gabungkan semua
        return array_merge(
            [[$judul]],     // Baris 1
            [$header],      // Baris 2: header
            $rows,          // Baris 3-n: data siswa
            [$rataRataRow]  // Baris terakhir: rata-rata per kolom
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

        $totalColumns = $tugasCount + 3; // +1 No, +1 Nama Siswa, +1 Rata-rata
        $totalRows = $siswaCount + 3;    // +1 Judul, +1 Header, +N siswa, +1 rata-rata

        $endColumn = Coordinate::stringFromColumnIndex($totalColumns);

        // Merge cell judul
        $sheet->mergeCells("A1:{$endColumn}1");

        return [
            // Baris 1: Judul
            1 => [
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            // Baris 2: Header tabel
            2 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EEEDEB'],
                ],
            ],
            // Baris rata-rata (baris terakhir)
            $totalRows => [
                'font' => ['italic' => true, 'bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'EEEDEB'],
                ],
            ],
            // Border semua sel tabel (header sampai rata-rata)
            "A2:{$endColumn}{$totalRows}" => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }
}
