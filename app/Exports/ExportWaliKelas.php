<?php

namespace App\Exports;

use App\Models\User;
use App\Models\WaliKelas;
use App\Models\TahunAjaran;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class ExportWaliKelas implements FromView, WithStyles
{
    protected $rowCount;
    protected $tahunAjaranId;
    protected $namaTahun;

    public function __construct($tahunAjaranId = null)
    {
        $this->tahunAjaranId = $tahunAjaranId;

        // Hitung total data
        $this->rowCount = WaliKelas::when($tahunAjaranId, function ($query) {
            $query->where('tahun_ajaran_id', $this->tahunAjaranId);
        })->count() + 2;

        // Ambil nama tahun ajaran
        $tahun = TahunAjaran::find($tahunAjaranId);
        $this->namaTahun = $tahun ? $tahun->nama_tahun : 'Semua Tahun Ajaran';
    }

    public function view(): View
    {
        $users = User::role('Wali Kelas')->whereHas('waliKelas', function ($query) {
            if ($this->tahunAjaranId) {
                $query->where('tahun_ajaran_id', $this->tahunAjaranId);
            }
        })->with(['waliKelas' => function ($query) {
            if ($this->tahunAjaranId) {
                $query->where('tahun_ajaran_id', $this->tahunAjaranId);
            }
        }])->get();
        

        return view('pages.admin.waliKelas.table', [
            'users' => $users,
            'title' => 'Daftar Wali Kelas - Tahun Ajaran ' . $this->namaTahun,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:E1');
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

        $lastRow = $this->rowCount;
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
