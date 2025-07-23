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


class ExportAbsensiKelas implements FromArray, WithTitle, WithStyles
{
    protected $pembelajaran;

    public function __construct($pembelajaran)
    {
        $this->pembelajaran = $pembelajaran;
    }

    public function array(): array
    {
        // Ambil semua siswa yang ikut pembelajaran ini
        $siswaList = User::whereHas('enrollments', function ($query) {
            $query->where('pembelajaran_id', $this->pembelajaran->id);
        })->get();

        // Ambil semua absensi untuk pembelajaran ini
        $absensiList = $this->pembelajaran->absensi()->with('detailAbsensi')->orderBy('tanggal')->get();

        // Header
        $header = ['No', 'Nama Siswa', 'NIS'];
        foreach ($absensiList as $absensi) {
            $tanggal = $absensi->tanggal ? $absensi->tanggal->format('d/m') : 'Tanpa Tanggal';
            $header[] = $tanggal;
        }
        // Tambahan kolom total
        $header[] = 'Hadir';
        $header[] = 'Izin';
        $header[] = 'Sakit';
        $header[] = 'Alfa';

        $rows = [];

        foreach ($siswaList as $index => $siswa) {
            $row = [$index + 1, $siswa->name, $siswa->siswa->nis ?? '-'];

            // Hitung total absensi per jenis
            $total = [
                'Hadir' => 0,
                'Izin' => 0,
                'Sakit' => 0,
                'Alfa' => 0,
            ];

            foreach ($absensiList as $absensi) {
                $detail = $absensi->detailAbsensi->firstWhere('siswa_id', $siswa->id);
                $keterangan = $detail->keterangan ?? null;

                // Simbol
                $simbol = match ($keterangan) {
                    'Hadir' => '✔',
                    'Izin'  => 'i',
                    'Sakit' => 's',
                    'Alfa'  => 'a',
                    default => '-',
                };

                $row[] = $simbol;

                if ($keterangan && isset($total[$keterangan])) {
                    $total[$keterangan]++;
                }
            }

            // Tambahkan total ke row
            $row[] = $total['Hadir'];
            $row[] = $total['Izin'];
            $row[] = $total['Sakit'];
            $row[] = $total['Alfa'];

            $rows[] = $row;
        }

        // Judul baris pertama
        $judul = sprintf(
            'Rekapitulasi Absensi %s - Kelas %s - Semester %s - Tahun Ajaran %s',
            $this->pembelajaran->nama_mapel,
            $this->pembelajaran->kelas->nama_kelas,
            $this->pembelajaran->semester,
            $this->pembelajaran->tahunAjaran->nama_tahun
        );

        $guru = $this->pembelajaran->guru->name ?? '-';
        $nuptk = $this->pembelajaran->guru->guru->nuptk ?? '-';

        return array_merge(
            [[$judul]], // Judul
            [['Guru Pengampu: ' . $guru . "          NUPTK:" . $nuptk]],
            [$header],  // Header
            $rows       // Data siswa
        );
    }

    public function title(): string
    {
        return $this->pembelajaran->nama_mapel ?? 'Mapel';
    }

    public function styles(Worksheet $sheet)
    {
        $absensiCount = $this->pembelajaran->absensi()->count();
        $siswaCount = User::whereHas('enrollments', function ($query) {
            $query->where('pembelajaran_id', $this->pembelajaran->id);
        })->count();

        $totalColumns = $absensiCount + 7; // +2 untuk No dan Nama Siswa, +4 kolom total
        $totalRows = $siswaCount + 3;      // Judul + header + siswa

        $endColumn = Coordinate::stringFromColumnIndex($totalColumns);

        // Merge untuk judul
        $sheet->mergeCells("A1:{$endColumn}1"); 
        $sheet->mergeCells("A2:{$endColumn}2"); 
        
        return [
            1 => [ // Judul
                'font' => ['bold' => true, 'size' => 14],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
            2 => [ // Guru
                'font' => ['italic' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            ],
            3 => [ // Header tabel
                'font' => ['bold' => true],
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
