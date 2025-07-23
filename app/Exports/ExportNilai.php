<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportNilai implements FromView, WithStyles, WithTitle
{
    protected $pertemuanKuis;
    protected $sheetTitle;
    protected $jumlahBarisSiswa; // Baris data siswa (tanpa header)
    protected $barisHeaderTabel = 7; // Header tabel mulai baris ke-6

    public function __construct($pertemuanKuis, $sheetTitle)
    {
        $this->pertemuanKuis = $pertemuanKuis;
        $this->sheetTitle = $sheetTitle;
    }

    public function view(): View
    {
        $pembelajaran = $this->pertemuanKuis->pembelajaran;
        $kuis = $this->pertemuanKuis->kuis;
        $pertemuan = $this->pertemuanKuis->pertemuan;


        $enrollments = $pembelajaran->enrollments()->with('siswa')->get();
        $hasilKuis = $kuis->hasilKuis()->get()->keyBy('siswa_id');

        $siswaList = $enrollments->map(function ($enroll) use ($hasilKuis) {
            $siswa = $enroll->siswa;
            $hasil = $hasilKuis->get($siswa->id);
            return [
                'nama' => $siswa->name,
                'nis' => $siswa->siswa->nis,
                'status' => $hasil ? 'Sudah' : 'Belum',
                'skor' => $hasil->skor_total ?? 0,
            ];
        })->sortBy(fn($item) => strtolower($item['nama']))->values(); // urutkan dan reset index
        

        // Simpan jumlah data siswa untuk styling nanti
        $this->jumlahBarisSiswa = $siswaList->count();

        return view('pages.admin.kuis.table', [
            'kuis' => $kuis,
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
