<?php

namespace App\Http\Controllers\Siswa;

use App\Models\Kuis;
use App\Models\SoalKuis;
use App\Models\HasilKuis;
use App\Models\JawabanKuis;
use Illuminate\Support\Str;
use App\Models\Pembelajaran;
use Illuminate\Http\Request;
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


    // public function kumpulkan(Request $request)
    // {
    //     dd($request->all());
    //     $userId = Auth::id();
    //     $kuisId = $request->kuis_id; // Pastikan ID kuis ada di form (atau bisa diambil dari URL)

    //     $totalSkor = 0;
    //     $totalSoal = 0;

    //     // Menyimpan jawaban untuk setiap soal
    //     foreach ($request->except('_token', 'kuis_id') as $key => $value) {
    //         if (str_starts_with($key, 'soal_')) {
    //             $soalId = str_replace('soal_', '', $key);
    //             $soal = SoalKuis::find($soalId);

    //             $jawabanUser = trim($value);
    //             $isCorrect = false;

    //             if ($soal->type_soal === 'Essay') {
    //                 $isCorrect = strtolower(trim($soal->jawaban_benar)) === strtolower($jawabanUser);
    //             } elseif ($soal->type_soal === 'Objective' || $soal->type_soal === 'TrueFalse') {
    //                 $isCorrect = $soal->jawaban_benar == $jawabanUser;
    //             }

    //             // Simpan jawaban per soal ke dalam jawaban_kuis
    //             JawabanKuis::updateOrCreate(
    //                 [
    //                     'soal_id' => $soalId,
    //                     'siswa_id' => $userId,
    //                 ],
    //                 [
    //                     'jawaban_user' => $jawabanUser,
    //                     'status' => $isCorrect,
    //                 ]
    //             );

    //             // Hitung skor
    //             if ($isCorrect) {
    //                 $totalSkor++;
    //             }

    //             $totalSoal++;
    //         }
    //     }

    //     // Simpan hasil akhir kuis ke dalam tabel hasil_kuis
    //     HasilKuis::updateOrCreate(
    //         [
    //             'kuis_id' => $kuisId,
    //             'siswa_id' => $userId,
    //         ],
    //         [
    //             'skor_total' => $totalSkor,
    //             'is_done' => true,
    //         ]
    //     );

    //     return response()->json(['success' => true, 'message' => 'Jawaban berhasil dikumpulkan!']);
    // }

    public function kumpulkan(Request $request)
    {
        $request->validate([
            'kuis_id' => 'required|exists:kuis,id',
        ]);

        $userId = Auth::id();
        $kuisId = $request->kuis_id;
        $totalSkor = 0;
        $jawabanJSON = [];

        foreach ($request->all() as $key => $jawabanUser) {
            if (Str::startsWith($key, 'soal_')) {
                $soalId = str_replace('soal_', '', $key);
                $soal = SoalKuis::find($soalId);

                if (!$soal || is_null($jawabanUser)) {
                    continue;
                }

                $isCorrect = false;

                if ($soal->type_soal === 'Essay') {
                    $isCorrect = strtolower(trim($soal->jawaban_benar)) === strtolower(trim($jawabanUser));
                } elseif (in_array($soal->type_soal, ['Objective', 'TrueFalse'])) {
                    $isCorrect = $soal->jawaban_benar == $jawabanUser;
                }

                if ($isCorrect) {
                    $totalSkor++;
                }

                $jawabanJSON[$soalId] = [
                    'jawaban' => $jawabanUser,
                    'status_jawaban' => $isCorrect
                ];
            }
        }

        // Simpan ke tabel hasil_kuis
        HasilKuis::updateOrCreate(
            [
                'kuis_id' => $kuisId,
                'siswa_id' => $userId
            ],
            [
                'jawaban_user' => json_encode($jawabanJSON),
                'skor_total' => $totalSkor,
                'is_done' => true
            ]
        );

        return response()->json(['message' => 'Jawaban berhasil dikumpulkan!']);
    }
}
