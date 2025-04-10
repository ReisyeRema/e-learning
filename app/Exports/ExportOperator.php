<?php

namespace App\Exports;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportOperator implements FromView, WithStyles
{
    protected $rowCount;

    public function __construct()
    {
        // Hitung jumlah baris data dengan filter roles "Super Admin" dan "Admin"
        $this->rowCount = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Super Admin', 'Admin']);
        })->count() + 2; // +2 untuk header dan judul
    }


    public function view(): View
    {
        // Ambil data pengguna berdasarkan roles
        $users = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['Super Admin', 'Admin']);
        })->with('roles')->get();

        return view('pages.user.table', [
            'users' => $users,
            'title' => 'Daftar Super Admin dan Admin'
        ]);
    }


    public function styles(Worksheet $sheet)
    {
        // Format untuk judul
        $sheet->mergeCells('A1:F1'); // Menggabungkan sel A1 sampai F1 untuk judul
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

        // Tambahkan border pada tabel
        $lastRow = $this->rowCount;
        $sheet->getStyle("A3:F{$lastRow}")->applyFromArray([
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
