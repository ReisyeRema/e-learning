<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\DetailAbsensi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithTitle;


class ExportAbsensi implements FromView, WithStyles, WithTitle
{
    protected $absensi;
    protected $sheetTitle;
    protected $jumlahKolomPertemuan; 
    protected $jumlahBarisSiswa; 
    protected $barisHeaderTabel = 6; 

    public function __construct($pembelajaranId)
    {
        $this->sheetTitle = 'Laporan Absensi';
        $this->absensi = Absensi::with('pembelajaran.enrollments.siswa')
            ->where('pembelajaran_id', $pembelajaranId)
            ->latest('tanggal')
            ->first();

        if (!$this->absensi) {
            abort(404, 'Data absensi tidak ditemukan untuk pembelajaran ini.');
        }
    }


    public function view(): View
    {
        $pembelajaran = $this->absensi->pembelajaran;

        // Ambil semua absensi (pertemuan) berdasarkan pembelajaran
        $pertemuanList = Absensi::where('pembelajaran_id', $pembelajaran->id)->orderBy('tanggal')->get();

        // Ambil semua siswa yang enroll
        $enrollments = $pembelajaran->enrollments()->with('siswa')->where('status', 'approved')->get();

        // Ambil detail absensi berdasarkan pertemuan
        $detailAbsensi = DetailAbsensi::whereIn('absensi_id', $pertemuanList->pluck('id'))->get();

        // Mapping ke siswa
        $siswaList = $enrollments->map(function ($enroll) use ($detailAbsensi, $pertemuanList) {
            $siswa = $enroll->siswa;
            $data = [];

            $rekap = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alfa' => 0];

            foreach ($pertemuanList as $pertemuan) {
                $absensi = $detailAbsensi->firstWhere(function ($item) use ($siswa, $pertemuan) {
                    return $item->siswa_id == $siswa->id && $item->absensi_id == $pertemuan->id;
                });

                $keterangan = $absensi->keterangan ?? '';
                $data['pertemuan'][] = $keterangan;

                if (isset($rekap[$keterangan])) {
                    $rekap[$keterangan]++;
                }
            }

            return [
                'nama' => $siswa->name,
                'nis' => $siswa->siswa->nis,
                'pertemuan' => $data['pertemuan'],
                'rekap' => $rekap,
            ];
        });

        $this->jumlahBarisSiswa = $siswaList->count();
        $this->jumlahKolomPertemuan = $pertemuanList->count();

        return view('pages.admin.detailAbsensi.table', [
            'pembelajaran' => $pembelajaran,
            'pertemuanList' => $pertemuanList,
            'siswaList' => $siswaList,
        ]);
    }


    public function styles(Worksheet $sheet)
    {
        // Merge & style judul
        $sheet->mergeCells('A1:D1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ]);

        // Hitung kolom total: 2 (No + Nama) + n pertemuan + 4 (Hadir, Izin, Sakit, Alfa)
        $totalKolom = 3 + $this->jumlahKolomPertemuan + 4;
        $endColLetter = $this->getExcelColumnName($totalKolom);

        // Baris awal dan akhir data
        $startRow = $this->barisHeaderTabel;
        $dataStartRow = $startRow + 3;
        $endRow = $dataStartRow + $this->jumlahBarisSiswa - 1;

        // Terapkan border ke seluruh tabel
        $sheet->getStyle("A{$startRow}:{$endColLetter}{$endRow}")->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        return [];
    }

    // Fungsi bantu: konversi angka ke huruf kolom Excel (misal 1 => A, 28 => AB)
    private function getExcelColumnName($number)
    {
        $columnName = '';
        while ($number > 0) {
            $mod = ($number - 1) % 26;
            $columnName = chr(65 + $mod) . $columnName;
            $number = (int)(($number - $mod) / 26);
        }
        return $columnName;
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }
}
