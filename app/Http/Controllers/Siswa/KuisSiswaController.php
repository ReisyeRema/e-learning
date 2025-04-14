<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Kuis;
use App\Models\SoalKuis;
use App\Models\HasilKuis;
use App\Models\JawabanKuis;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
use App\Models\ProfilSekolah;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class KuisSiswaController extends Controller
{
    public function action($mapel, $kelas, $tahunAjaran, $judulKuis)
    {
        // Cari pembelajaran berdasarkan URL slug
        $pembelajaran = Pembelajaran::whereRaw("LOWER(REPLACE(nama_mapel, ' ', '-')) = ?", [$mapel])
            ->whereHas('kelas', function ($query) use ($kelas) {
                $query->whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelas]);
            })
            ->whereHas('tahunAjaran', function ($query) use ($tahunAjaran) {
                $query->whereRaw("REPLACE(nama_tahun, '/', '-') = ?", [$tahunAjaran]);
            })
            ->firstOrFail();

        $kuis = Kuis::with('soalKuis')
            ->where('judul', str_replace('-', ' ', $judulKuis))
            ->firstOrFail();

        return view('pages.siswa.kuis.action', [
            'pembelajaran' => $pembelajaran,
            'kuis' => $kuis,
            'soalKuis' => $kuis->soalKuis,
        ]);
    }

    public function kumpulkan(Request $request)
    {
        $request->validate([
            'kuis_id' => 'required|exists:kuis,id',
        ]);

        $userId = Auth::id();
        $kuisId = $request->kuis_id;
        $jumlahBenar = 0;
        $jumlahSoal = 0;
        $jawabanJSON = [];

        foreach ($request->all() as $key => $jawabanUser) {
            if (Str::startsWith($key, 'soal_')) {
                $soalId = str_replace('soal_', '', $key);
                $soal = SoalKuis::find($soalId);

                if (!$soal || is_null($jawabanUser)) {
                    continue;
                }

                $jumlahSoal++; // hitung total soal yang dijawab
                $isCorrect = false;

                if ($soal->type_soal === 'Essay') {
                    $isCorrect = strtolower(trim($soal->jawaban_benar)) === strtolower(trim($jawabanUser));
                } elseif (in_array($soal->type_soal, ['Objective', 'TrueFalse'])) {
                    $isCorrect = $soal->jawaban_benar == $jawabanUser;
                }

                if ($isCorrect) {
                    $jumlahBenar++;
                }

                $jawabanJSON[$soalId] = [
                    'jawaban' => $jawabanUser,
                    'status_jawaban' => $isCorrect
                ];
            }
        }

        // Hitung skor sebagai persentase
        $skorTotal = $jumlahSoal > 0 ? round(($jumlahBenar / $jumlahSoal) * 100) : 0;

        // Simpan ke tabel hasil_kuis
        HasilKuis::updateOrCreate(
            [
                'kuis_id' => $kuisId,
                'siswa_id' => $userId
            ],
            [
                'jawaban_user' => json_encode($jawabanJSON),
                'skor_total' => $skorTotal,
                'is_done' => true
            ]
        );

        return redirect()->route('list-kuis.index')->with('success', 'Jawaban berhasil dikumpulkan!');
    }


    public function index()
    {
        $siswaId = Auth::id();

        $kuisList = Kuis::whereHas('pertemuanKuis.pembelajaran.enrollments', function ($query) use ($siswaId) {
            $query->where('siswa_id', $siswaId);
        })
            ->with(['pertemuanKuis.pembelajaran' => function ($query) {
                $query->withCount('enrollments')
                    ->withCount('pertemuanMateri');
            }, 'hasilKuis' => function ($query) use ($siswaId) {
                $query->where('siswa_id', $siswaId);
            }])
            ->get()
            ->sortByDesc(function ($tugas) {
                return optional($tugas->pertemuanKuis->sortByDesc('created_at')->first())->created_at;
            });

        $profileSekolah = ProfilSekolah::first();

        return view('pages.siswa.kuis.index', compact('kuisList', 'profileSekolah'));
    }
}
