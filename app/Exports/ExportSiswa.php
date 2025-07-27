<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Kelas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportSiswa implements FromView, WithStyles
{
    protected $rowCount;
    protected $kelasId;
    protected $title;

    public function __construct($kelasId = null)
    {
        $this->kelasId = $kelasId;

        // Hitung jumlah data berdasarkan filter kelas
        $query = User::role('Siswa')->with('siswa.kelas');
        if ($this->kelasId) {
            $query->whereHas('siswa.kelas', function ($q) {
                $q->where('id', $this->kelasId);
            });
        }
        $this->rowCount = $query->count() + 2; 

        // Set judul berdasarkan filter kelas
        if ($this->kelasId) {
            $kelas = Kelas::find($this->kelasId);
            $this->title = "Daftar Siswa Kelas " . ($kelas ? $kelas->nama_kelas : "Tidak Ditemukan");
        } else {
            $this->title = "Daftar Semua Siswa";
        }
    }

    public function view(): View
    {
        $query = User::role('Siswa')->with('siswa.kelas');
        if ($this->kelasId) {
            $query->whereHas('siswa.kelas', function ($q) {
                $q->where('id', $this->kelasId);
            });
        }

        $users = $query->get();
        return view('pages.admin.siswa.table', [
            'users' => $users,
            'title' => $this->title,
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        // Atur gaya untuk judul
        $sheet->mergeCells('A1:F1');
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

        // Border tabel dinamis
        $sheet->getStyle("A3:F{$this->rowCount}")->applyFromArray([
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
