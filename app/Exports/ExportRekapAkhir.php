<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Absensi;
use App\Models\HasilKuis;
use App\Models\SubmitTugas;
use App\Models\Pembelajaran;
use App\Models\DetailAbsensi;
use App\Models\PertemuanKuis;
use App\Models\PertemuanTugas;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;

class ExportRekapAkhir implements FromCollection, WithHeadings, WithStyles, WithTitle, WithEvents, WithCustomStartCell
{
    protected $pembelajaran_id;
    protected $nama_kelas;
    protected $tahun_ajaran;
    protected $guru;
    protected $semester;


    public function __construct($pembelajaran_id, $nama_kelas, $tahun_ajaran, $guru, $semester)
    {
        $this->pembelajaran_id = $pembelajaran_id;
        $this->nama_kelas = $nama_kelas;
        $this->tahun_ajaran = $tahun_ajaran;
        $this->guru = $guru;
        $this->semester = $semester;
    }

    public function collection()
    {
        $tugasIds = PertemuanTugas::where('pembelajaran_id', $this->pembelajaran_id)->pluck('tugas_id');
        $kuisMap = PertemuanKuis::where('pembelajaran_id', $this->pembelajaran_id)
            ->with('kuis')->get()->groupBy('kuis.kategori');

        $kuisIds = [
            'Kuis' => $kuisMap->get('Kuis')?->pluck('kuis_id') ?? collect(),
            'Ujian Mid' => $kuisMap->get('Ujian Mid')?->pluck('kuis_id') ?? collect(),
            'Ujian Akhir' => $kuisMap->get('Ujian Akhir')?->pluck('kuis_id') ?? collect(),
        ];

        $absensiIds = Absensi::where('pembelajaran_id', $this->pembelajaran_id)->pluck('id');
        $siswaList = User::whereHas('roles', fn($q) => $q->where('name', 'siswa'))->get();

        $data = $siswaList->map(function ($siswa) use ($tugasIds, $kuisIds, $absensiIds) {
            $tugas = SubmitTugas::where('siswa_id', $siswa->id)->whereIn('tugas_id', $tugasIds)->pluck('skor')->filter();
            $kuis = HasilKuis::where('siswa_id', $siswa->id)->whereIn('kuis_id', $kuisIds['Kuis'])->pluck('skor_total')->filter();
            $mid = HasilKuis::where('siswa_id', $siswa->id)->whereIn('kuis_id', $kuisIds['Ujian Mid'])->pluck('skor_total')->filter();
            $uas = HasilKuis::where('siswa_id', $siswa->id)->whereIn('kuis_id', $kuisIds['Ujian Akhir'])->pluck('skor_total')->filter();

            $avgTugas = $tugas->count() ? $tugas->avg() : 0;
            $avgKuis = $kuis->count() ? $kuis->avg() : 0;
            $avgMid = $mid->count() ? $mid->avg() : 0;
            $avgUas = $uas->count() ? $uas->avg() : 0;

            $hadir = DetailAbsensi::where('siswa_id', $siswa->id)->whereIn('absensi_id', $absensiIds)->where('keterangan', 'Hadir')->count();
            $totalAbsensi = DetailAbsensi::where('siswa_id', $siswa->id)->whereIn('absensi_id', $absensiIds)->count();
            $persenHadir = $totalAbsensi > 0 ? ($hadir / $totalAbsensi) * 100 : 0;

            $nilaiAkhir = (
                ($avgTugas * 0.20) +
                ($avgKuis * 0.20) +
                ($avgMid * 0.25) +
                ($avgUas * 0.25) +
                ($persenHadir * 0.10)
            );

            return [
                'NIS' => $siswa->siswa->nis ?? '-', // pastikan kolom nis ada di table users
                'Nama' => $siswa->name,
                'Tugas (20%)' => round($avgTugas, 2),
                'Kuis (20%)' => round($avgKuis, 2),
                'Ujian Mid (25%)' => round($avgMid, 2),
                'Ujian Akhir (25%)' => round($avgUas, 2),
                'Absensi (10%)' => round($persenHadir, 2),
                'Nilai Akhir' => round($nilaiAkhir, 2),
            ];
        });

        return new Collection($data);
    }

    public function headings(): array
    {
        return [
            'NIS',
            'Nama',
            'Tugas (20%)',
            'Kuis (20%)',
            'Ujian Mid (25%)',
            'Ujian Akhir (25%)',
            'Absensi (10%)',
            'Nilai Akhir',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Judul (A1:H1)
            1 => ['font' => ['bold' => true, 'size' => 14]],
            // Header tabel (baris ke-3 karena start cell di A3)
            3 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Rekap Nilai';
    }

    public function startCell(): string
    {
        return 'A3'; // Data mulai dari baris ke-3 (baris 1 untuk judul, 2 untuk kosong/spasi)
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $judul = "Rekapitulasi Nilai Akhir Kelas {$this->nama_kelas} TA {$this->tahun_ajaran} Semester {$this->semester}";
                $sheet->mergeCells('A1:H1');
                $sheet->setCellValue('A1', $judul);

                // Data guru
                $namaGuru = $this->guru->name ?? '-';
                $nip = $this->guru->guru->nip ?? '-';
                $nuptk = $this->guru->guru->nuptk ?? '-';

                // Tulis info guru di bawah judul
                $event->sheet->setCellValue('A2', "Nama Guru: $namaGuru");
                $event->sheet->setCellValue('D2', "NIP: $nip");
                $event->sheet->setCellValue('F2', "NUPTK: $nuptk");

                // Merge dan styling
                $event->sheet->mergeCells('A2:C2');
                $event->sheet->mergeCells('D2:E2');
                $event->sheet->mergeCells('F2:H2');

                $event->sheet->getDelegate()->getStyle('A2:H2')->applyFromArray([
                    'font' => ['italic' => true, 'size' => 11],
                ]);


                // Styling judul
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // Header table styling (baris 3 karena startCell = A3)
                $sheet->getStyle('A3:H3')->applyFromArray([
                    'font' => ['bold' => true],
                ]);

                // Auto-size kolom
                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }

                // Border untuk seluruh tabel
                $jumlahBaris = $sheet->getHighestRow();
                $sheet->getStyle("A3:H{$jumlahBaris}")->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);
            },
        ];
    }
}
