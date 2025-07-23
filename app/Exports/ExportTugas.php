<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportTugas implements FromView, WithStyles, WithTitle
{
    protected $pertemuanTugas;
    protected $sheetTitle;
    protected $jumlahBarisSiswa; // Baris data siswa (tanpa header)
    protected $barisHeaderTabel = 7; // Header tabel mulai baris ke-6

    public function __construct($pertemuanTugas, $sheetTitle)
    {
        $this->pertemuanTugas = $pertemuanTugas;
        $this->sheetTitle = $sheetTitle;
    }

    public function view(): View
    {
        $pembelajaran = $this->pertemuanTugas->pembelajaran;
        $tugas = $this->pertemuanTugas->tugas;
        $pertemuan = $this->pertemuanTugas->pertemuan;

        $enrollments = $pembelajaran->enrollments()->with('siswa')->get();
        $submitTugas = $tugas->submitTugas()->get()->keyBy('siswa_id');

        $siswaList = $enrollments->map(function ($enroll) use ($submitTugas) {
            $siswa = $enroll->siswa;
            $hasil = $submitTugas->get($siswa->id);
            return [
                'nama' => $siswa->name,
                'nis' => $siswa->siswa->nis,
                'status' => $hasil ? 'Sudah' : 'Belum',
                'skor' => $hasil->skor ?? 0,
            ];
        })->sortBy(fn($item) => strtolower($item['nama']))->values();

        // Simpan jumlah data siswa untuk styling nanti
        $this->jumlahBarisSiswa = $siswaList->count();

        return view('pages.admin.tugas.table', [
            'tugas' => $tugas,
            'pembelajaran' => $pembelajaran,
            'siswaList' => $siswaList,
            'pertemuan' => $pertemuan,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Merge & style judul (baris 1)
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Border mulai dari header tabel (baris 6) sampai akhir data siswa
        $startRow = $this->barisHeaderTabel;
        $endRow = $startRow + $this->jumlahBarisSiswa;

        $sheet->getStyle("A{$startRow}:E{$endRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        return [];
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }
}
