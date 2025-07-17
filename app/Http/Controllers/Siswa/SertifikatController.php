<?php

namespace App\Http\Controllers\Siswa;

use setasign\Fpdi\Fpdi;
use App\Models\HasilKuis;
use App\Models\Enrollments;
use App\Models\SubmitTugas;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Google\Service\Eventarc\Enrollment;

class SertifikatController extends Controller
{
    public function process(Request $request)
    {
        // Ambil user yang sedang login
        $user = Auth::user();
        $nama = $user->name;

        // Ambil data siswa yang terkait
        $siswa = $user->siswa;
        $kelas = $siswa && $siswa->kelas ? $siswa->kelas->nama_kelas : 'Kelas Tidak Diketahui';

        // Ambil pembelajaran yang sudah di-approve oleh guru
        $kelasId = $siswa && $siswa->kelas ? $siswa->kelas->id : null;

        $enrollments = Enrollments::where('siswa_id', $user->id)
            ->where('status', 'approved')
            ->whereHas('pembelajaran', function ($query) use ($kelasId) {
                $query->where('kelas_id', $kelasId);
            })
            ->with('pembelajaran')
            ->get();

        // Ambil daftar mata pelajaran dari pembelajaran yang sudah di-approve
        $nilai = $enrollments->map(function ($enrollment) use ($user) {
            $pembelajaran = $enrollment->pembelajaran;

            // Ambil semua tugas dari pembelajaran ini
            $tugasIds = $pembelajaran->pertemuanTugas->pluck('tugas_id')->toArray();
            $nilaiTugas = SubmitTugas::whereIn('tugas_id', $tugasIds)
                ->where('siswa_id', $user->id)
                ->whereNotNull('skor')
                ->pluck('skor')
                ->toArray();

            // Ambil semua kuis dari pembelajaran ini
            $kuisIds = $pembelajaran->pertemuanKuis->pluck('kuis_id')->toArray();
            $nilaiKuis = HasilKuis::whereIn('kuis_id', $kuisIds)
                ->where('siswa_id', $user->id)
                ->whereNotNull('skor_total')
                ->pluck('skor_total')
                ->toArray();

            // Gabungkan semua nilai dan hitung rata-rata
            $allScores = array_merge($nilaiTugas, $nilaiKuis);
            $avgScore = count($allScores) > 0 ? round(array_sum($allScores) / count($allScores)) : 0;

            return [
                'mapel' => $pembelajaran->nama_mapel,
                'nilai' => $avgScore,
            ];
        })->toArray();


        $outputfile = public_path('dcc.pdf');
        $this->fillPDF(public_path('master/dcc.pdf'), $outputfile, $nama, $kelas, $nilai);

        return response()->make(file_get_contents($outputfile), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="dcc.pdf"'
        ]);
    }


    public function fillPDF($file, $outputfile, $nama, $kelas, $nilai)
    {
        $fpdi = new FPDI;
        $pageCount = $fpdi->setSourceFile($file);

        for ($i = 1; $i <= $pageCount; $i++) {
            $template = $fpdi->importPage($i);
            $size = $fpdi->getTemplateSize($template);
            $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
            $fpdi->useTemplate($template);

            if ($i == 1) {

                // Set font & warna
                $fpdi->SetFont("times", "", 38);
                $fpdi->SetTextColor(25, 26, 25);

                // Nama siswa di tengah
                $textWidth = $fpdi->GetStringWidth($nama);
                $xNama = ($size['width'] - $textWidth) / 2;
                $yNama = 100;
                $fpdi->Text($xNama, $yNama, $nama);

                // Garis bawah sesuai panjang nama
                $fpdi->SetDrawColor(50, 50, 50);
                $fpdi->SetLineWidth(0.5);
                $fpdi->Line($xNama, $yNama + 2, $xNama + $textWidth, $yNama + 2);

                // Nama kelas
                $fpdi->SetFont("times", "I", 16);
                $xKelas = ($size['width'] - $fpdi->GetStringWidth($kelas)) / 2;
                $fpdi->Text($xKelas, $yNama + 8, $kelas);
            } else {
                // Halaman kedua -> Tabel nilai
                $fpdi->SetFont("times", "B", 16);
                $fpdi->SetTextColor(0, 0, 0);

                // Nama kelas
                $fpdi->SetFont("times", "", 25);
                $fpdi->Text(130, 67, $kelas);

                // Tabel posisional
                $startX = 50;
                $startY = 75;
                $col1Width = 160;
                $col2Width = 35;
                $rowHeight = 10;

                // Header
                $fpdi->SetFillColor(50, 50, 50); // abu-abu sedang
                $fpdi->SetTextColor(255, 255, 255);
                $fpdi->Rect($startX, $startY, $col1Width, $rowHeight, 'F');
                $fpdi->Rect($startX + $col1Width, $startY, $col2Width, $rowHeight, 'F');
                $fpdi->SetFont("times", "B", 12);
                $fpdi->Text($startX + 3, $startY + 7, "Mata Pelajaran");
                $fpdi->Text($startX + $col1Width + 5, $startY + 7, "Nilai");

                // Reset warna teks
                $fpdi->SetFont("times", "", 12);
                $fpdi->SetTextColor(0, 0, 0);

                // Isi tabel
                foreach ($nilai as $index => $n) {
                    $y = $startY + (($index + 1) * $rowHeight);
                    $isEven = $index % 2 === 0;
                    $fpdi->SetFillColor($isEven ? 245 : 255, $isEven ? 245 : 255, $isEven ? 245 : 255);
                    $fpdi->Rect($startX, $y, $col1Width, $rowHeight, 'F');
                    $fpdi->Rect($startX + $col1Width, $y, $col2Width, $rowHeight, 'F');
                    $fpdi->Text($startX + 3, $y + 7, $n['mapel']);
                    $fpdi->Text($startX + $col1Width + 8, $y + 7, (string) $n['nilai']);
                }

                break;
            }
        }

        return $fpdi->Output($outputfile, 'F');
    }
}
