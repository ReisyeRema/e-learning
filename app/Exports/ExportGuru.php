<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportGuru implements FromView, WithStyles
{
    protected $rowCount;

    public function __construct()
    {
        // Hitung jumlah data untuk menentukan jumlah baris
        $this->rowCount = User::role('Guru')->with('guru')->count() + 2; // +2 untuk header dan jarak
    }

    public function view(): View
    {
        $users = User::role('Guru')->with('guru')->get();
        return view('pages.admin.guru.table', [
            'users' => $users,
            'title' => 'Daftar Guru'
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Atur gaya untuk judul (baris pertama)
        $sheet->mergeCells('A1:E1'); // Menggabungkan sel A1 sampai E1 untuk judul
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Tentukan range dinamis untuk border tabel
        $lastRow = $this->rowCount; // Total baris (header + data + jarak)
        $sheet->getStyle("A3:E{$lastRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ]);

        return [];
    }
}
