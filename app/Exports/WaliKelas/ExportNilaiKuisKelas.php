<?php

namespace App\Exports\WaliKelas;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class ExportNilaiKuisKelas implements FromArray, WithTitle, WithStyles
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

        $kuisList = $this->pembelajaran->pertemuanKuis->pluck('kuis')->filter();

        // Pisahkan kuis berdasarkan kategori
        $kuisBiasa = $kuisList->filter(function ($kuis) {
            return !in_array($kuis->kategori, ['Ujian Mid', 'Ujian Akhir']);
        })->values();

        $kuisUjian = $kuisList->filter(function ($kuis) {
            return in_array($kuis->kategori, ['Ujian Mid', 'Ujian Akhir']);
        })->values();

        // Header
        $header = ['No', 'Nama Siswa', 'NIS'];
        foreach ($kuisBiasa as $kuis) {
            $header[] = $kuis->judul ?? 'Kuis ' . $kuis->id;
        }
        $header[] = 'Rata-rata';
        foreach ($kuisUjian as $kuis) {
            $header[] = $kuis->judul ?? 'Ujian ' . $kuis->id;
        }

        // Body dan nilai kolom
        $rows = [];
        $nilaiKolom = array_fill(0, $kuisBiasa->count() + 1, []); 

        foreach ($siswaList as $index => $siswa) {
            $row = [$index + 1, $siswa->name, $siswa->siswa->nis ?? '-'];
            $nilaiSiswa = [];

            // Nilai kuis biasa (dihitung ke rata-rata)
            foreach ($kuisBiasa as $i => $kuis) {
                $skor = $kuis->HasilKuis()
                    ->where('siswa_id', $siswa->id)
                    ->first()
                    ->skor_total ?? '-';

                $row[] = $skor;

                if (is_numeric($skor)) {
                    $nilaiSiswa[] = $skor;
                    $nilaiKolom[$i][] = $skor;
                }
            }

            // Hitung rata-rata
            $rataRata = count($nilaiSiswa) > 0 ? round(array_sum($nilaiSiswa) / count($nilaiSiswa), 2) : '-';
            $row[] = $rataRata;
            if (is_numeric($rataRata)) {
                $nilaiKolom[$kuisBiasa->count()][] = $rataRata;
            }

            // Nilai ujian
            foreach ($kuisUjian as $kuis) {
                $skor = $kuis->HasilKuis()
                    ->where('siswa_id', $siswa->id)
                    ->first()
                    ->skor_total ?? '-';
                $row[] = $skor;
            }

            $rows[] = $row;
        }

        // Baris rata-rata kolom (hanya untuk kuis biasa + rata-rata siswa)
        $rataRataRow = ['Rata-rata', '',''];
        foreach ($nilaiKolom as $nilai) {
            $rataRataRow[] = count($nilai) > 0 ? round(array_sum($nilai) / count($nilai), 2) : '-';
        }

        // Tambahkan kolom kosong untuk ujian karena tidak dihitung rata-rata kolom
        foreach ($kuisUjian as $kuis) {
            $nilaiUjian = [];

            foreach ($siswaList as $siswa) {
                $skor = $kuis->HasilKuis()
                    ->where('siswa_id', $siswa->id)
                    ->first()
                    ->skor_total ?? null;

                if (is_numeric($skor)) {
                    $nilaiUjian[] = $skor;
                }
            }

            $rataUjian = count($nilaiUjian) > 0 ? round(array_sum($nilaiUjian) / count($nilaiUjian), 2) : '-';
            $rataRataRow[] = $rataUjian;
        }


        // Judul dinamis
        $judul = sprintf(
            'Rekapitulasi Nilai Kuis Dan Ujian %s Kelas %s Tahun Ajaran %s',
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
        $kuisList = $this->pembelajaran->pertemuanKuis->pluck('kuis')->filter();

        $kuisBiasaCount = $kuisList->filter(function ($kuis) {
            return !in_array($kuis->kategori, ['Ujian Mid', 'Ujian Akhir']);
        })->count();

        $ujianCount = $kuisList->filter(function ($kuis) {
            return in_array($kuis->kategori, ['Ujian Mid', 'Ujian Akhir']);
        })->count();

        $siswaCount = User::whereHas('enrollments', function ($query) {
            $query->where('pembelajaran_id', $this->pembelajaran->id);
        })->count();

        $totalColumns = 3 + $kuisBiasaCount + 1 + $ujianCount; 
        $totalRows = $siswaCount + 4;

        $endColumn = Coordinate::stringFromColumnIndex($totalColumns);

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
